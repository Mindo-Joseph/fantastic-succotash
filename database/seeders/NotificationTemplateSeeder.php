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
                'tags' => '', 
                'content' => 'Thanks for your Order'
            ],
            [
                'label' => 'Order Status Update',
                'subject' => 'Verify Mail',
                'tags' => '', 
                'content' => 'Your Order status has been updated'
            ],
            [
                'label' =>'Refund Status Update',
                'subject' => 'Reset Password Notification',
                'tags' => '',
                'content' => 'Your Order status has been updated'
            ]
        ];
        NotificationTemplate::truncate();
        foreach ($create_array as $key => $array) {
            NotificationTemplate::create(['label' => $array['label'], 'slug' => Str::slug($array['label'], "-"),'content' => $array['content'], 'subject' => $array['subject'], 'tags' => $array['tags']]);
        }
    }
}
