<?php

namespace App\Http\Controllers\Admin\Client;

use App\Http\Controllers\Controller;
use Iluminate\Http\Request;
use Ixudra\Curl\Facades\Curl;

class TriggerAction extends Controller
{
    // send client (site) data to update his configuration.
    // @param integer $id
    // @return array()
    public function updateConfiguration($id)
    {
        $site = \App\Site::find($id);
        if (!$site)
            return [
                'type' => 'error',
                'message' => "Site id: $id not exist enymore.",
            ];

        $siteInstance = new \App\Http\Controllers\Admin\Site();
        $conf = $siteInstance->getSiteConfiguration($id);

        $response = Curl::to($site->url)
            ->withData([
                'route'  => 'api',
                'key'    => $site->token,
                'method' => 'updateSiteConfiguration',
                'data'   => $conf,
            ])
            ->post();

        $response = json_decode($response, true);
        if (!$response)
            return [
                'type' => 'error',
                'message' => 'Client site not respond, check Website Url and client site availability in browser.',
            ];

        // if success update isConnected
        if ($response['success']) {
            $site->isConnect = 1;
            $site->save();
        }

        return [
            'type' => $response['success'] ? 'success' : 'error',
            'message' => $response['message'],
        ];
    }

    // send client (site) his archive big for store.
    // @param integer $id
    // @return array()
    public function updateArchiveBig($id)
    {
        $site = \App\Site::find($id);
        if (!$site)
            return [
                'type' => 'error',
                'message' => "Site id: $id not exist enymore.",
            ];

        $archiveBigInstance = new \App\Http\Controllers\Admin\ArchiveBig();
        $archive = $archiveBigInstance->getFullArchiveBig($id);

        $response = Curl::to($site->url)
            ->withData([
                'route'  => 'api',
                'key'    => $site->token,
                'method' => 'updateArchiveBig',
                'data'   => $archive,
            ])
            ->post();

        $response = json_decode($response, true);
        if (!$response)
            return [
                'type' => 'error',
                'message' => 'Client site not respond, check Website Url and client site availability in browser.',
            ];

        return [
            'type' => $response['success'] ? 'success' : 'error',
            'message' => $response['message'],
        ];
    }

    // send client (site) his archive home for store
    // @param integer $id
    // @return array()
    public function updateArchiveHome($id)
    {
        $site = \App\Site::find($id);
        if (!$site)
            return [
                'type' => 'error',
                'message' => "Site id: $id not exist enymore.",
            ];

        $archiveHomeInstance = new \App\Http\Controllers\Admin\ArchiveHome();
        $archive = $archiveHomeInstance->getFullArchiveHome($id);

        $response = Curl::to($site->url)
            ->withData([
                'route'   => 'api',
                'key'     => $site->token,
                'method'  => 'updateArchiveHome',
                'data'    => $archive,
            ])
            ->post();

        $response = json_decode($response, true);
        if (!$response)
            return [
                'type' => 'error',
                'message' => 'Client site not respond, check Website Url and client site availability in browser.',
            ];

        return [
            'type' => $response['success'] ? 'success' : 'error',
            'message' => $response['message'],
        ];
    }
}
