<?php namespace App\Console\Commands;

use Nathanmac\Utilities\Parser\Facades\Parser;

class ImportNewEvents extends CronCommand
{
    protected $name = 'events:import-new';
    protected $description = 'This will import new events that not started yet without prediction';

    protected $imported = 0;
    protected $alreadyExists = 0;

    private $predictions;


    public function fire()
    {
        $cron = $this->startCron();
        $info = [
            'imported'      => 0,
            'alreadyExists' => 0,
            'message'       => []
        ];

        $xml = file_get_contents(env('LINK_PORTAL_NEW_EVENTS'));

        if (!$xml) {
            $info['error'] = true;
            $this->stopCron($cron, $info);
            return true;
        }

        $c = Parser::xml($xml);

        foreach (\App\Prediction::all() as $pred)
            $this->predictions[$pred->identifier] = true;

        foreach ($c['match'] as $k => $match) {

            $m = [
                'id' => $match['id'],
                'country' => $match['tournament_country'],
                'countryCode' => $match['tournament_country_code'],
                'league' => $match['tournament_title'],
                'leagueId' => $match['tournament_id'],
                'homeTeam' => $match['home_team_name'],
                'homeTeamId' => $match['home_team_id'],
                'awayTeam' => $match['away_team_name'],
                'awayTeamId' => $match['away_team_id'],
                'result' => '',
                'eventDate' => $match['utc_date'],
            ];

            if (\App\Match::where('id', $m['id'])->where('leagueId', $m['leagueId'])->count()) {
                // odds
                if (!empty($match['odds']))
                    $this->insertOdds($m['id'], $m['leagueId'], $match['odds']);

                // associationteam country
                if ($m['countryCode']) {
                    $this->createIfNotExistsTeamCountry($m['countryCode'], $m['homeTeamId']);
                    $this->createIfNotExistsTeamCountry($m['countryCode'], $m['homeTeamId']);

                    if ($m['leagueId'])
                        $this->createIfNotExistsLeagueCountry($m['countryCode'], $m['leagueId']);
                }

                $this->alreadyExists++;
                continue;
            }

            // store country name and code if not exists
            if(!\App\Country::where('code', $m['countryCode'])->count()) {

                if (!$m['countryCode']) {
                    $info['message'][] = "Missing country code for matchId: " . $m['id'] . " on leagueId: "  . $m['leagueId'];
                    continue;
                }

                \App\Country::create([
                    'code' => $m['countryCode'],
                    'name' => $m['country']
                ]);
            }

            // store league if not exist
            if(!\App\League::find($m['leagueId'])) {
                \App\League::create([
                    'id' => $m['leagueId'],
                    'name' => $m['league']
                ]);
            }

            $this->createIfNotExistsLeagueCountry($m['countryCode'], $m['leagueId']);

            // store homeTeam if not exists
            if(!\App\Team::find($m['homeTeamId'])) {
                \App\Team::create([
                    'id' => $m['homeTeamId'],
                    'name' => $m['homeTeam'],
                ]);
            }

            $this->createIfNotExistsTeamCountry($m['countryCode'], $m['homeTeamId']);

            // store awayTeam if not exists
            if(!\App\Team::find($m['awayTeamId'])) {
                \App\Team::create([
                    'id' => $m['awayTeamId'],
                    'name' => $m['awayTeam'],
                ]);
            }

            $this->createIfNotExistsTeamCountry($m['countryCode'], $m['awayTeamId']);

            $homeTeamAlias = $this->getAlias($m['homeTeamId']);
            if ($homeTeamAlias != null)
                $m['homeTeam'] = $homeTeamAlias;

            $awayTeamAlias = $this->getAlias($m['awayTeamId']);
            if ($awayTeamAlias != null)
                $m['awayTeam'] = $awayTeamAlias;

            // store new match
            \App\Match::create($m);

            // odds
            if (!empty($match['odds'])) {
                $this->insertOdds($m['id'], $m['leagueId'], $match['odds']);
            }

            $this->imported++;
        }

        $info['imported'] = $this->imported;
        $info['alreadyExists'] = $this->alreadyExists;

        $this->info(json_encode($info));
        $this->stopCron($cron, $info);
        return true;
    }

    private function createIfNotExistsLeagueCountry($countryCode, $leagueId)
    {
        $lc = \App\Models\League\Country::where('leagueId', $leagueId)
            ->count();

        if (! $lc)
            \App\Models\League\Country::create([
                'countryCode' => $countryCode,
                'leagueId'    => $leagueId,
            ]);
    }

    private function getAlias($teamId)
    {
        $alias = \App\Models\Team\Alias::where('teamId', $teamId)
            ->first();

        if (!$alias)
            return null;

        return $alias->alias;
    }

    // @param string $countryCode
    // @param int $teamId
    private function createIfNotExistsTeamCountry($countryCode, $teamId)
    {
        if (!\App\Models\Team\Country::where('countryCode', $countryCode)->where('teamId', $teamId)->count())
            \App\Models\Team\Country::create([
                'countryCode' => $countryCode,
                'teamId' => $teamId,
            ]);
    }

    // TODO till now get odd for ah and over/under
    private function insertOdds($matchId, $leagueId, $odds)
    {
        $predictionId = null;
        $toInsert = [];

        foreach ($odds['odd'] as $odd) {

            $predictionId = null;

            // over / under
            if ($odd['type'] == 'total') {
                $predictionId = strtolower($odd['element']) . '_' . $odd['typekey'];
            }

            // ah
            if ($odd['type'] == 'asian_handicap') {
                $predictionId = ($odd['element'] == 'Home') ? 'team1-ah_' : 'team2-ah_';

                if ($odd['typekey'][0] == '-' || $odd['typekey'] == '0')
                    $predictionId .= trim($odd['typekey']);
                else
                    $predictionId .= '+' . trim($odd['typekey']);
            }

            //g/g
            if ($odd['type'] == 'goal_nogoal') {
                $predictionId = $odd['element'] == 'Yes' ? 'bothToScore' : 'noGoal';
            }

            //1x2 HO -> homeTeam | AO -> awayTeam | DO -> equal
            if ($odd['type'] == '3W') {

                if ($odd['element'] == 'HO')
                    $predictionId = 'team1';
                elseif ($odd['element'] == 'AO')
                    $predictionId = 'team2';
                else
                    $predictionId = 'equal';
            }

            // continue if odd not exists in out database
            if (! isset($this->predictions[$predictionId]))
               continue;

            $oddExists = \App\Models\Events\Odd::where('matchId', $matchId)
                ->where('leagueId', $leagueId)
                ->where('predictionId', $predictionId)
                ->count();

            // continue if odd already exists
            if ($oddExists)
                continue;

            \App\Models\Events\Odd::create([
                'matchId' => $matchId,
                'leagueId' => $leagueId,
                'predictionId' => $predictionId,
                'odd' => number_format((float) $odd['value'], 2, '.', ''),
            ]);
        }
    }
}
