<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Archive extends Controller
{

    // publish events in archive
    // @param array $ids (distributionId)
    //  - mark events publish in distribution
    //  - send events in archive
    // @return array()
    public function publish(Request $r)
    {
        $ids = $r->input('ids');

        $alreadyPublish = 0;
        $inserted = 0;
        $notHaveResultOrStatus = 0;
        $sameTipNotPublished = 0;

        if (!$ids)
            return [
                "type" => "error",
                "message" => "No events provided!",
            ];

        foreach ($ids as $id) {
            $distribution = \App\Distribution::where('id', $id)->first();

            // TODO check if distributed event exists

            if ($distribution->isPublish) {
                $alreadyPublish++;
                continue;
            }

            // noTip not have results and status
            if (!$distribution->isNoTip) {
                if (!$distribution->result || !$distribution->statusId) {
                    $notHaveResultOrStatus++;
                    continue;
                }
            }

            // for no tip set eventDate = systemDate
            if ($distribution->isNoTip)
                $distribution->eventDate = $distribution->systemDate;

            // set publish
            $distribution->isPublish = 1;

            // update in distribution
            $distribution->update();

            // transform in array
            $distribution = json_decode(json_encode($distribution), true);

            // check if event was already published on another package with same tip
            if (\App\ArchiveBig::where('eventId', $distribution['eventId'])
                ->where('tableIdentifier', $distribution['tableIdentifier'])
                ->where('systemDate', $distribution['systemDate'])
                ->where('tipIdentifier', $distribution['tipIdentifier'])->count())
            {
                $sameTipNotPublished++;
                continue;
            }

            // remove id and set distributionId
            $distribution['distributionId'] = $distribution['id'];
            unset($distribution['id']);

            // Insert event in archive_home
            $archiveHome = new \App\Http\Controllers\Admin\ArchiveHome();
            $archiveHome->incrementOrder($distribution['siteId'], $distribution['tableIdentifier']);
            \App\ArchiveHome::create($distribution);

            \App\ArchiveBig::create($distribution);
            $inserted++;
        }

        $message = '';
        if ($alreadyPublish)
            $message .= "$alreadyPublish events already published to archive\r\n";
        if ($inserted)
            $message .= "$inserted events was published to archive\r\n";
        if ($notHaveResultOrStatus)
            $message .= "$notHaveResultOrStatus was NOT published becouse they not have result or status\r\n";
        if ($sameTipNotPublished)
            $message .= "$sameTipNotPublished was NOT inserted in archive becouse they are already published in other packages with same tip\r\n";

        return [
            "type" => "success",
            "message" => $message
        ];
    }

    public function update() {}

    public function destroy() {}

}
