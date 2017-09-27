<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Site extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index()
    {
        return \App\Site::all();
    }

    /*
     * return object
     */
    public function get($id) {
        return \App\Site::find($id);
    }

    public function store(Request $r) {

        // TODO: check if new name is valid
        $name = $r->input('name');

        // Site name must be unique
        $site = \App\Site::where('name', $name)->first();
        if ($site !== null) {
            return response()->json([
                "type" => "error",
                "message" => "This site already exists!"
            ]);
        }

        $site = \App\Site::create([
            "name" => $name,
            "email" => $r->input('email'),
            "url" => $r->input('url'),
            "dateFormat" => $r->input('dateFormat'),
            "smtpHost" => $r->input('smtpHost'),
            "smtpPort" => $r->input('smtpPort'),
            "smtpUser" => $r->input('smtpUser'),
            "smtpPassword" => $r->input('smtpPassword'),
            "smtpEncryption" => $r->input('smtpEncryption'),
            "imapHost" => $r->input('imapHost'),
            "imapPort" => $r->input('imapPort'),
            "imapUser" => $r->input('imapUser'),
            "imapPassword" => $r->input('imapPassword'),
            "imapEncryption" => $r->input('imapEncryption'),
            "token" => md5(microtime() . rand(0, 1000)),
        ]);

        return response()->json([
            "type" => "success",
            "message" => "New site was added with success!",
            "data" => \App\Site::where('name', $name)->first(),
        ]);
    }

    public function update(Request $r, $id) {
        $site = \App\Site::find($id);

        // Site not exists retur status not exists
        if ($site === null) {
            return response()->json([
                "type" => "error",
                "message" => "Site with id: $id not exists"
            ]);
        }

        $where = [
            ['name', '=', $r->input('name')],
            ['id', '!=', $id],
        ];
        if (\App\Site::where($where)->count())
            return response()->json([
                "type" => "error",
                "message" => "Site with name: " . $r->input('name') . " already exists!",
            ]);

        $site->name = $r->input('name');
        $site->email = $r->input('email');
        $site->url = $r->input('url');
        $site->dateFormat = $r->input('dateFormat');
        $site->smtpHost = $r->input('smtpHost');
        $site->smtpPort = $r->input('smtpPort');
        $site->smtpUser = $r->input('smtpUser');
        $site->smtpPassword = $r->input('smtpPassword');
        $site->smtpEncryption = $r->input('smtpEncryption');
        $site->imapHost = $r->input('imapHost');
        $site->imapPort = $r->input('imapPort');
        $site->imapUser = $r->input('imapUser');
        $site->imapPassword = $r->input('imapPassword');
        $site->imapEncryption = $r->input('imapEncryption');
        $site->email = $r->input('email');
        $site->save();

        return response()->json([
            "type" => "success",
            "message" => "General site information was updated with success!"
        ]);
    }

    /*
     * @return array()
     */
    public function destroy($id) {

        $site = \App\Site::find($id);

        // Site not exists retur status not exists
        if ($site === null) {
            return response()->json([
                "type" => "error",
                "message" => "Site with id: $id not exists"
            ]);
        }

        // delete site predictions
        \App\SitePrediction::where('siteId', $id)->delete();

        // result class and status
        \App\SiteResultStatus::where('siteId', $id)->delete();

        // association with packages
        \App\SitePackage::where('siteId', $id)->delete();

        // packages and package associated predictions
        $packages = \App\Package::where('siteId', $id)->get()->toArray();
        foreach ($packages as $p) {

            // associated predictions
            \App\PackagePrediction::where('packageId', $p['id'])->delete();

            // delete pacakge
            \App\Package::find($p['id'])->delete();
        }

        $site->delete();
        return response()->json([
            "type" => "success",
            "message" => "Site with id: $id was deleted with success!"
        ]);
    }

    /*
     * @return array()
     */
    public function getIdsAndNames()
    {
        return \App\Site::select('id', 'name')->get();
    }

    /*
     * @return array()
     */
    public function getAvailableTables($siteId)
    {
        // will get this relation for packages model
        // each package has siteId
        return \App\Package::select('tableIdentifier')->distinct()->where('siteId', $siteId)->get();
    }

}