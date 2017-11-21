<?php

namespace App\Http\Controllers\Admin\Client;

use App\Http\Controllers\Controller;
use Iluminate\Http\Request;
use Ixudra\Curl\Facades\Curl;

class TriggerAction extends Controller
{
    // send client (site) request for update his configuration.
    // route for client is hardcore in controller
    //    - /client/client/get-configuration/$clientId
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

        $response = Curl::to($site->url)
            ->withData([
                'route' => 'api',
                'key' => $site->token,
                'method' => 'updateSiteConfiguration',
                'url' => env('APP_HOST') . '/client/get-configuration/' . $id,
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

    // send client (site) request to update his arvhive big
    // route for client is hardcore in controller
    //    - /client/update-archive-big/$clientId
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

        $response = Curl::to($site->url)
            ->withData([
                'route' => 'api',
                'key' => $site->token,
                'method' => 'updateArchiveBig',
                'url' => env('APP_HOST') . '/client/update-archive-big/' . $id,
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

    // send client (site) request to update his arvhive home
    // route for client is hardcore in controller
    //    - /client/update-archive-home/$clientId
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

        $response = Curl::to($site->url)
            ->withData([
                'route' => 'api',
                'key' => $site->token,
                'method' => 'updateArchiveHome',
                'url' => env('APP_HOST') . '/client/update-archive-home/' . $id,
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
