<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NotificationTemplate;
use Illuminate\Support\Str;

class NotificationTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $create_array = [
            [
                'label' =>'New Order',
                'subject' =>'New Vendor Signup',
                'tags' => '{order_id}, {customer_name}', 
                'content' => '<tbody> <tr> <td> <div style="margin-bottom: 20px;"> <h4 style="margin-bottom: 5px;">Thanks for your Order</h4></div> </td> </tr></tbody>'
            ],
            [
                'label' => 'Order Status Update',
                'subject' => 'Verify Mail',
                'tags' => '{order_id}, {customer_name}', 
                'content' => '<tbody> <tr> <td> <div style="margin-bottom: 20px;"> <h4 style="margin-bottom: 5px;">Your Order status has been updated</h4></div> </td> </tr></tbody>'
            ],
            [
                'label' =>'Refund Status Update',
                'subject' => 'Reset Password Notification',
                'tags' => '{order_id}, {customer_name}',
                'content' => '<tbody> <tr> <td> <div style="margin-bottom: 20px;"> <h4 style="margin-bottom: 5px;">Your Order status has been updated</h4></div> </td> </tr></tbody>'
            ]
        ];
        NotificationTemplate::truncate();
        foreach ($create_array as $key => $array) {
            NotificationTemplate::create(['label' => $array['label'], 'slug' => Str::slug($array['label'], "-"),'content' => $array['content'], 'subject' => $array['subject'], 'tags' => $array['tags']]);
        }
    }
}
