<?php

namespace \App\Http\Controllers\Admin\Client;

use Iluminate\Http\Request;
use Ixudra\Curl\Facades\Curl;

class Request extends Controller
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
}
