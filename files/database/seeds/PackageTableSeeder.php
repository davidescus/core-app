<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Package;

class PackageTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sites = [
            1 => [
                'normal_1' => [
                    'name' => '3 Tips',
                    'identifier' => 'normal_1',
                    'tipIdentifier' => 'tip_1',
                    'tableIdentifier' => 'table_1',
                    'vipFlag' => '',
                    'isVip'           => false,
                    'subscription' => 3,
                    'tipsPerDay'      => 2,
                ],
                'normal_2' => [
                    'name' => '30 Tips',
                    'identifier' => 'normal_2',
                    'tipIdentifier' => 'tip_1',
                    'tableIdentifier' => 'table_1',
                    'vipFlag' => '',
                    'isVip'           => false,
                    'subscription' => 30,
                    'tipsPerDay'      => 4,
                ],
                'vip_1' => [
                    'name' => '2 Vip Tips',
                    'identifier' => 'vip_1',
                    'tipIdentifier' => 'tip_2',
                    'tableIdentifier' => 'table_2',
                    'vipFlag' => ' !VIP!',
                    'isVip'           => true,
                    'subscription' => 2,
                    'tipsPerDay'      => 3,
                ],
            ],
            2 => [
                'normal_1' => [
                    'name' => '10 Tips',
                    'identifier' => 'normal_1',
                    'tipIdentifier' => 'tip_1',
                    'tableIdentifier' => 'table_1',
                    'vipFlag' => '',
                    'isVip'           => false,
                    'subscription' => 10,
                    'tipsPerDay'      => 1,
                ],
                'normal_2' => [
                    'name' => '45 Tips',
                    'identifier' => 'normal_2',
                    'tipIdentifier' => 'tip_1',
                    'tableIdentifier' => 'table_1',
                    'vipFlag' => '',
                    'isVip'           => false,
                    'subscription' => 45,
                    'tipsPerDay'      => 5,
                ],
            ],
            3 => [
                'normal_1' => [
                    'name' => 'Eugene Hendrix',
                    'identifier' => 'normal_1',
                    'tipIdentifier' => 'tip_1',
                    'tableIdentifier' => 'table_1',
                    'vipFlag' => '',
                    'isVip'           => false,
                    'subscription' => 30,
                    'tipsPerDay'      => 2,
                ],
                'normal_2' => [
                    'name' => 'Marcus Spiros',
                    'identifier' => 'normal_2',
                    'tipIdentifier' => 'tip_2',
                    'tableIdentifier' => 'table_2',
                    'vipFlag' => '',
                    'isVip'           => false,
                    'subscription' => 30,
                    'tipsPerDay'      => 1,
                ],
                'normal_3' => [
                    'name' => 'Quentin Whatt',
                    'identifier' => 'normal_3',
                    'tipIdentifier' => 'tip_3',
                    'tableIdentifier' => 'table_3',
                    'vipFlag' => '',
                    'isVip'           => false,
                    'subscription' => 30,
                    'tipsPerDay'      => 2,
                ],
            ],
            4 => [
                'normal_1' => [
                    'name' => '1 Month accesss',
                    'identifier' => 'normal_1',
                    'tipIdentifier' => 'tip_1',
                    'tableIdentifier' => 'table_1',
                    'vipFlag' => '',
                    'isVip'           => false,
                    'subscription' => 30,
                    'tipsPerDay'      => 5,
                ],
            ],
            5 => [
                'normal_1' => [
                    'name' => '3 Tips',
                    'identifier' => 'normal_1',
                    'tipIdentifier' => 'tip_1',
                    'tableIdentifier' => 'table_1',
                    'vipFlag' => '',
                    'isVip'           => false,
                    'subscription' => 3,
                    'tipsPerDay'      => 2,
                ],
                'vip_1' => [
                    'name' => '1 VIP TIP',
                    'identifier' => 'vip_1',
                    'tipIdentifier' => 'tip_1',
                    'tableIdentifier' => 'table_1',
                    'vipFlag' => '- Vip Tip -',
                    'isVip'           => true,
                    'subscription' => 1,
                    'tipsPerDay'      => 1,
                ],
            ],
        ];

        foreach ($sites as $siteId => $site) {
            foreach ($site as $packeIdentifier => $pack) {
                Package::firstOrCreate([
                    'siteId'          => $siteId,
                    'name'            => $pack['name'],
                    'identifier'      => $pack['identifier'],
                    'tipIdentifier'   => $pack['tipIdentifier'],
                    'tableIdentifier' => $pack['tableIdentifier'],
                    'vipFlag'         => $pack['vipFlag'],
                    'isVip'           => $pack['isVip'],
                    'subscription'    => $pack['subscription'],
                    'tipsPerDay'      => $pack['tipsPerDay'],
                ]);
            }
        }
    }
}
