<?php namespace App\Console\Commands;

use Nathanmac\Utilities\Parser\Facades\Parser;

class SetResultAndStauts extends CronCommand
{
    protected $name = 'events:set-result-status';
    protected $description = 'This will will get result from portal and evaluate stausId';

    public function fire()
    {
        $cron = $this->startCron();
        $info = [
            'processed' => 0,
            'notFound'  => 0,
            'message'   => []
        ];

        $matches = \App\Match::where('result', '')
            ->where('eventDate', '>' , gmdate('Y-m-d H:i:s', time() + (105 * 60)))
            ->get();

        foreach ($matches as $match) {
            echo $match->matchId;
        }

        /* $xml = file_get_contents(env('LINK_PORTAL_NEW_EVENTS')); */

        /* if (!$xml) { */
        /*     $info['error'] = true; */
        /*     $this->stopCron($cron, $info); */
        /*     return true; */
        /* } */

        /* $c = Parser::xml($xml); */

        /* foreach ($c['match'] as $k => $match) { */

        /* } */

        /* $info['imported'] = $this->imported; */
        /* $info['alreadyExists'] = $this->alreadyExists; */

        $this->info(json_encode($info));
        $this->stopCron($cron, $info);
        return true;
    }
}

