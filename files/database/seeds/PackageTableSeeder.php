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
                    'tipIdentifier' => 'tip_1',
                    'tableIdentifier' => 'table_1',
                    'isVip'           => false,
                    'tipsPerDay'      => 2,
                ],
                'normal_2' => [
                    'name' => '30 Tips',
                    'tipIdentifier' => 'tip_1',
                    'tableIdentifier' => 'table_1',
                    'isVip'           => false,
                    'tipsPerDay'      => 4,
                ],
                'vip_1' => [
                    'name' => '2 Vip Tips',
                    'tipIdentifier' => 'tip_2',
                    'tableIdentifier' => 'table_2',
                    'isVip'           => true,
                    'tipsPerDay'      => 3,
                ],
            ],
            2 => [
                'normal_1' => [
                    'name' => '10 Tips',
                    'tipIdentifier' => 'tip_1',
                    'tableIdentifier' => 'table_1',
                    'isVip'           => false,
                    'tipsPerDay'      => 1,
                ],
                'normal_2' => [
                    'name' => '45 Tips',
                    'tipIdentifier' => 'tip_1',
                    'tableIdentifier' => 'table_1',
                    'isVip'           => false,
                    'tipsPerDay'      => 5,
                ],
            ],
            3 => [
                'normal_1' => [
                    'name' => 'Eugene Hendrix',
                    'tipIdentifier' => 'tip_1',
                    'tableIdentifier' => 'table_1',
                    'isVip'           => false,
                    'tipsPerDay'      => 2,
                ],
                'normal_2' => [
                    'name' => 'Marcus Spiros',
                    'tipIdentifier' => 'tip_2',
                    'tableIdentifier' => 'table_2',
                    'isVip'           => false,
                    'tipsPerDay'      => 1,
                ],
                'normal_3' => [
                    'name' => 'Quentin Whatt',
                    'tipIdentifier' => 'tip_3',
                    'tableIdentifier' => 'table_3',
                    'isVip'           => false,
                    'tipsPerDay'      => 2,
                ],
            ],
            4 => [
                'normal_1' => [
                    'name' => '1 Month accesss',
                    'tipIdentifier' => 'tip_1',
                    'tableIdentifier' => 'table_1',
                    'isVip'           => false,
                    'tipsPerDay'      => 5,
                ],
            ],
            5 => [
                'normal_1' => [
                    'name' => '3 Tips',
                    'tipIdentifier' => 'tip_1',
                    'tableIdentifier' => 'table_1',
                    'isVip'           => false,
                    'tipsPerDay'      => 2,
                ],
                'vip_1' => [
                    'name' => '1 VIP TIP',
                    'tipIdentifier' => 'tip_1',
                    'tableIdentifier' => 'table_1',
                    'isVip'           => true,
                    'tipsPerDay'      => 1,
                ],
            ],
        ];

        foreach ($sites as $siteId => $site) {
            foreach ($site as $packeIdentifier => $pack) {
                Package::firstOrCreate([
                    'siteId'          => $siteId,
                    'name'            => $pack['name'],
                    'tipIdentifier'   => $pack['tipIdentifier'],
                    'tableIdentifier' => $pack['tableIdentifier'],
                    'isVip'           => $pack['isVip'],
                ]);
            }
        }
    }
}
