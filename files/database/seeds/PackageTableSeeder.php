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
                    'isRecurring'      => false,
                    'subscriptionType' => 'tips',
                    'subscription' => 3,
                    'tipsPerDay'      => 2,
                    'fromName'   => 'From name',
                    'subject' => 'subject',
                    'template' => '<p><font face="Times New Roman">Welcome {{email}}</font></p><p><font face="Times New Roman">{{date}} &nbsp;{{coountry}}: {{league}}<br>{{homeTeam}} - {{awayTeam}}</font></p><p><font face="Times New Roman">Bet: {{prediction}}</font></p><p><font face="Times New Roman">Have a good day!<br>\'\'\'\' ```` """ ----------- ___ \\\\\\?????////////</font></p>',
                ],
                'normal_2' => [
                    'name' => '30 Tips',
                    'identifier' => 'normal_2',
                    'tipIdentifier' => 'tip_1',
                    'tableIdentifier' => 'table_1',
                    'vipFlag' => '',
                    'isVip'           => false,
                    'isRecurring'      => false,
                    'subscriptionType' => 'tips',
                    'subscription' => 30,
                    'tipsPerDay'      => 4,
                    'subject' => 'subject',
                    'template' => '<p><font face="Times New Roman">Welcome {{email}}</font></p><p><font face="Times New Roman">{{date}} &nbsp;{{coountry}}: {{league}}<br>{{homeTeam}} - {{awayTeam}}</font></p><p><font face="Times New Roman">Bet: {{prediction}}</font></p><p><font face="Times New Roman">Have a good day!<br>\'\'\'\' ```` """ ----------- ___ \\\\\\?????////////</font></p>',
                ],
                'vip_1' => [
                    'name' => '2 Vip Tips',
                    'identifier' => 'vip_1',
                    'tipIdentifier' => 'tip_2',
                    'tableIdentifier' => 'table_2',
                    'vipFlag' => ' !VIP!',
                    'isVip'           => true,
                    'isRecurring'      => false,
                    'subscriptionType' => 'tips',
                    'subscription' => 2,
                    'tipsPerDay'      => 3,
                    'fromName'   => 'From Name',
                    'subject' => 'subject',
                    'template' => '<p><font face="Times New Roman">Welcome {{email}}</font></p><p><font face="Times New Roman">{{date}} &nbsp;{{coountry}}: {{league}}<br>{{homeTeam}} - {{awayTeam}}</font></p><p><font face="Times New Roman">Bet: {{prediction}}</font></p><p><font face="Times New Roman">Have a good day!<br>\'\'\'\' ```` """ ----------- ___ \\\\\\?????////////</font></p>',
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
                    'isRecurring'      => false,
                    'subscriptionType' => 'tips',
                    'subscription' => 10,
                    'tipsPerDay'      => 1,
                    'fromName'   => 'From Name',
                    'subject' => 'subject',
                    'template' => '<p><font face="Times New Roman">Welcome {{email}}</font></p><p><font face="Times New Roman">{{date}} &nbsp;{{coountry}}: {{league}}<br>{{homeTeam}} - {{awayTeam}}</font></p><p><font face="Times New Roman">Bet: {{prediction}}</font></p><p><font face="Times New Roman">Have a good day!<br>\'\'\'\' ```` """ ----------- ___ \\\\\\?????////////</font></p>',
                ],
                'normal_2' => [
                    'name' => '45 Tips',
                    'identifier' => 'normal_2',
                    'tipIdentifier' => 'tip_1',
                    'tableIdentifier' => 'table_1',
                    'vipFlag' => '',
                    'isVip'           => false,
                    'isRecurring'      => false,
                    'subscriptionType' => 'tips',
                    'subscription' => 45,
                    'tipsPerDay'      => 5,
                    'fromName'   => 'From Name',
                    'subject' => 'subject',
                    'template' => '<p><font face="Times New Roman">Welcome {{email}}</font></p><p><font face="Times New Roman">{{date}} &nbsp;{{coountry}}: {{league}}<br>{{homeTeam}} - {{awayTeam}}</font></p><p><font face="Times New Roman">Bet: {{prediction}}</font></p><p><font face="Times New Roman">Have a good day!<br>\'\'\'\' ```` """ ----------- ___ \\\\\\?????////////</font></p>',
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
                    'isRecurring'      => true,
                    'subscriptionType' => 'days',
                    'subscription' => 30,
                    'tipsPerDay'      => 2,
                    'subject' => 'subject',
                    'fromName'   => 'From Name',
                    'template' => '<p><font face="Times New Roman">Welcome {{email}}</font></p><p><font face="Times New Roman">{{date}} &nbsp;{{coountry}}: {{league}}<br>{{homeTeam}} - {{awayTeam}}</font></p><p><font face="Times New Roman">Bet: {{prediction}}</font></p><p><font face="Times New Roman">Have a good day!<br>\'\'\'\' ```` """ ----------- ___ \\\\\\?????////////</font></p>',
                ],
                'normal_2' => [
                    'name' => 'Marcus Spiros',
                    'identifier' => 'normal_2',
                    'tipIdentifier' => 'tip_2',
                    'tableIdentifier' => 'table_2',
                    'vipFlag' => '',
                    'isVip'           => false,
                    'isRecurring'      => true,
                    'subscriptionType' => 'days',
                    'subscription' => 30,
                    'tipsPerDay'      => 1,
                    'fromName'   => 'From Name',
                    'subject' => 'subject',
                    'template' => '<p><font face="Times New Roman">Welcome {{email}}</font></p><p><font face="Times New Roman">{{date}} &nbsp;{{coountry}}: {{league}}<br>{{homeTeam}} - {{awayTeam}}</font></p><p><font face="Times New Roman">Bet: {{prediction}}</font></p><p><font face="Times New Roman">Have a good day!<br>\'\'\'\' ```` """ ----------- ___ \\\\\\?????////////</font></p>',
                ],
                'normal_3' => [
                    'name' => 'Quentin Whatt',
                    'identifier' => 'normal_3',
                    'tipIdentifier' => 'tip_3',
                    'tableIdentifier' => 'table_3',
                    'vipFlag' => '',
                    'isVip'           => false,
                    'isRecurring'      => true,
                    'subscriptionType' => 'days',
                    'subscription' => 30,
                    'tipsPerDay'      => 2,
                    'fromName'   => 'From Name',
                    'subject' => 'subject',
                    'template' => '<p><font face="Times New Roman">Welcome {{email}}</font></p><p><font face="Times New Roman">{{date}} &nbsp;{{coountry}}: {{league}}<br>{{homeTeam}} - {{awayTeam}}</font></p><p><font face="Times New Roman">Bet: {{prediction}}</font></p><p><font face="Times New Roman">Have a good day!<br>\'\'\'\' ```` """ ----------- ___ \\\\\\?????////////</font></p>',
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
                    'isRecurring'      => true,
                    'subscriptionType' => 'days',
                    'subscription' => 30,
                    'tipsPerDay'      => 5,
                    'fromName'   => 'From Name',
                    'subject' => 'subject',
                    'template' => '<p><font face="Times New Roman">Welcome {{email}}</font></p><p><font face="Times New Roman">{{date}} &nbsp;{{coountry}}: {{league}}<br>{{homeTeam}} - {{awayTeam}}</font></p><p><font face="Times New Roman">Bet: {{prediction}}</font></p><p><font face="Times New Roman">Have a good day!<br>\'\'\'\' ```` """ ----------- ___ \\\\\\?????////////</font></p>',
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
                    'isRecurring'      => false,
                    'subscriptionType' => 'tips',
                    'subscription' => 3,
                    'tipsPerDay'      => 2,
                    'fromName'   => 'From Name',
                    'subject' => 'subject',
                    'template' => '<p><font face="Times New Roman">Welcome {{email}}</font></p><p><font face="Times New Roman">{{date}} &nbsp;{{coountry}}: {{league}}<br>{{homeTeam}} - {{awayTeam}}</font></p><p><font face="Times New Roman">Bet: {{prediction}}</font></p><p><font face="Times New Roman">Have a good day!<br>\'\'\'\' ```` """ ----------- ___ \\\\\\?????////////</font></p>',
                ],
                'vip_1' => [
                    'name' => '1 VIP TIP',
                    'identifier' => 'vip_1',
                    'tipIdentifier' => 'tip_1',
                    'tableIdentifier' => 'table_1',
                    'vipFlag' => '- Vip Tip -',
                    'isVip'           => true,
                    'isRecurring'      => false,
                    'subscriptionType' => 'tips',
                    'subscription' => 1,
                    'tipsPerDay'      => 1,
                    'fromName'   => 'From Name',
                    'subject' => 'subject',
                    'template' => '<p><font face="Times New Roman">Welcome {{email}}</font></p><p><font face="Times New Roman">{{date}} &nbsp;{{coountry}}: {{league}}<br>{{homeTeam}} - {{awayTeam}}</font></p><p><font face="Times New Roman">Bet: {{prediction}}</font></p><p><font face="Times New Roman">Have a good day!<br>\'\'\'\' ```` """ ----------- ___ \\\\\\?????////////</font></p>',
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
                    'isRecurring'           => $pack['isRecurring'],
                    'subscriptionType' => $pack['subscriptionType'],
                    'subscription'    => $pack['subscription'],
                    'tipsPerDay'      => $pack['tipsPerDay'],
                    'subject'         => $pack['subject'],
                    'fromName'        => $pack['fromName'],
                    'template'        => $pack['template'],
                ]);
            }
        }
    }
}
