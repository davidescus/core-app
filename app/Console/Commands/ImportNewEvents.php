<?php namespace App\Console\Commands;

use Nathanmac\Utilities\Parser\Facades\Parser;

class ImportNewEvents extends CronCommand
{
    protected $name = 'events:import-new';
    protected $description = 'This will import new events that not started yet without prediction';

    protected $imported = 0;
    protected $alreadyExists = 0;


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

            // store homeTeam if not exists
            if(!\App\Team::find($m['homeTeamId'])) {
                \App\Team::create([
                    'id' => $m['homeTeamId'],
                    'name' => $m['homeTeam'],
                ]);
            }

            // store awayTeam if not exists
            if(!\App\Team::find($m['awayTeamId'])) {
                \App\Team::create([
                    'id' => $m['awayTeamId'],
                    'name' => $m['awayTeam'],
                ]);
            }

            // store new match
            \App\Match::create($m);

            $this->imported++;
        }

        $info['imported'] = $this->imported;
        $info['alreadyExists'] = $this->alreadyExists;

        $this->info(json_encode($info));
        $this->stopCron($cron, $info);
        return true;
    }
}