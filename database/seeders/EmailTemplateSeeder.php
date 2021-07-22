<?php

namespace Database\Seeders;
use Illuminate\Support\Str;
use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        $create_array = [
            [
                'label' =>'New Vendor Signup',
                'tags' => '{vendor_name}, {title}, {description}, {email}, {phone_no}, {address},{website}', 
                'content' => '<tbody><tr><td><div style="margin-bottom: 20px;"><h4 style="margin-bottom: 5px;">Name</h4><p>{vendor_name}</p></div><div style="margin-bottom: 20px;"><h4 style="margin-bottom: 5px;">Title</h4><p>{title}</p></div><div style="margin-bottom: 20px;"><h4 style="margin-bottom: 5px;">Description</h4><p>{description}</p></div><div style="margin-bottom: 20px;"><h4 style="margin-bottom: 5px;">Email</h4><p>{email}</p></div><div style="margin-bottom: 20px;"><h4 style="margin-bottom: 5px;">Phone Number</h4><p>{phone_no}</p></div><div style="margin-bottom: 20px;"><h4 style="margin-bottom: 5px;">Address</h4><address style="font-style: normal;"><p style="width: 300px;">{address}</p></address></div><div style="margin-bottom: 20px;"><h4 style="margin-bottom: 5px;">Website</h4><a style="color: #8142ff;" href="{website}" target="_blank"><b>{website}</b></a></div></td></tr></tbody>'
            ], 
            [
                'label' => 'Verify Mail',
                'tags' => '{customer_name}, {code}', 
                'content' => '<tbody style="text-align: center;"><tr><td style="padding-top: 0;"><div style="background: #fff;box-shadow: 0 3px 4px #ddd;border-bottom-left-radius: 20px;border-bottom-right-radius: 20px;padding: 15px 40px 30px;"><b style="margin-bottom: 10px; display: block;">Hi {customer_name},</b><p>You can also verify manually by entering the following OTP</p><div style="padding:10px;border: 2px dashed #cb202d;word-break:keep-all!important;width: calc(100% - 40px);margin: 25px auto;"><p style="Margin:0;Margin-bottom:16px;color:#cb202d;font-family:-apple-system,Helvetica,Arial,sans-serif;font-size:20px;font-weight:600;line-height:1.5;margin:0;margin-bottom:0;padding:0;text-align:center;word-break:keep-all!important">{code}</p></div><p>Note: The OTP will expire in 10 minutes and can only be used once.</p></div></td></tr></tbody>'
            ], 
            [
                'label'=>'Refund',
                'tags' => '{customer_name}, {code}, {description}', 
                'content' => '<tbody><tr><td><table style="width:100%;border: 1px solid rgb(221 221 221 / 41%);"> <thead> <tr> <th style="border-bottom: 1px solid rgb(221 221 221 / 41%);"><h3 style="color:rgb(51,51,51);font-weight:bold;line-height:27px;font-size:21px">Refund Confirmation</h3> </th> </tr> </thead> <tbody> <tr><td><b><span style="font-size:16px;line-height:21px"> Hello Share, </span> </b> <p style="margin:1px 0px 8px 0px;font-size:14px;line-height:18px;color:rgb(17,17,17)"> Lorem ipsum dolor sit amet consectetur, adipisicing elit. Totam sed vitae fugiat nam, ut natus officia optio a suscipit molestiae earum magni, voluptatum debitis repellat magnam. Officiis odit qui, provident doloremque dicta modi voluptatum placeat. </p></td></tr><tr><td><p style="margin:1px 0px 8px 0px;font-size:14px;line-height:18px;color:rgb(17,17,17)"> You can find the list of possible reasons why the package is being returned to us as undelivered <a href="#"><span style="color:#0066c0">here</span></a>. If you still want the item, please check your address and place a new order. </p> </td> </tr> <tr> <td> <a style="display: inline-block; padding: 6.7px 29px;border-radius: 4px;background:#8142ff;line-height: 20px; text-transform: uppercase;font-size: 14px;font-weight: 700;text-decoration: none;color: #fff;" href="#"> View return &amp; refund status </a> </td> </tr> <tr> <td> <div style="padding: 10px;border: 1px solid rgb(221 221 221 / 41%);margin-top: 15px;"> <ul style="display: flex;align-items: center;"> <li style="width: 80px;height: 80px;margin-right: 30px;"> <img src="https://imgproxy.royoorders.com/insecure/fill/300/300/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/YBWoqVAttQ3brP0965KCFLU62XYIyDOrbDSHEWBR.jpg" alt="" style="width: 100%;height: 100%;object-fit: cover;border-radius: 4px;"> </li> <li> <a href="#"><b>Peter England Trou..</b></a> </li> </ul> <hr style="border:0; border-bottom: 1px solid rgb(221 221 221 / 41%);margin: 15px 0 20px;"> <p align="right" style="margin:1px 0px 8px 0px;font-size:14px;line-height:18px;font-family:&quot;Roboto&quot;,&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;color:rgb(17,17,17)"><b> <span style="font-size:16px"> Refund total: <span style="font-size:16px">Rs.119.00* </span> </span> </b><br> <span style="display:inline-block;text-align:left"> Refund of Rs.119.00 is now initiated. </span> </p></div></td> </tr> <tr> <td> <table id="m_-2085618623145965177legalCopy" style="margin:0px 0px 0px 0px;font-weight:400;font-style:normal;font-size:13px;color:rgb(170,170,170);line-height:16px"> <tbody> <tr> <td><p style="font-size:13px;color:rgb(102,102,102);line-height:16px;margin:0"> * Learn more <a href="#"><span style="color:#0066c0">about refunds</span></a> </p></td> </tr> <tr> <td><p style="font-size:13px;color:rgb(102,102,102);line-height:16px;margin:0"> This email was sent from a notification-only address that cannot accept incoming email. Please do not reply to this message. </p></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody>'
            ], 
            [
                'label' => 'Orders',
                'tags' => '{customer_name}, {code}, {description}', 
                'content'=>'<table style="width: 100%;"><thead><tr><th colspan="2" style="text-align: center;"><a style="display: block;margin-bottom: 10px;" href="#"><img src="images/logo.png" alt=""> </a> <h1 style="margin: 0 0 10px;">Thanks for your order</h1> <p style="margin: 0 0 20px;">Hi Andrew, we have received your order #123456789 and working on it now. <br> We will email you an update as soon as your order is processed. </p> <a style="display: inline-block; padding: 6.7px 29px;border-radius: 4px;background:#8142ff;line-height: 20px; text-transform: uppercase;font-size: 14px;font-weight: 700;text-decoration: none;color: #fff;" href="#">View your order</a> </th> </tr> </thead> <tbody> <tr> <td colspan="2"> <table style="width:100%;border: 1px solid rgb(221 221 221 / 41%);"> <thead> <tr> <th colspan="2" style="border-bottom: 1px solid rgb(221 221 221 / 41%);"> <h3 style="font-weight: 700;">Items Ordered</h3> </th> </tr> </thead> <tbody> <tr style="vertical-align: top;"> <td style="border-bottom: 1px solid rgb(221 221 221 / 41%);border-right: 1px solid rgb(221 221 221 / 41%);width: 50%;"> <p style="margin-bottom: 5px;"><b>Shipping method:</b> Ship to store</p> <p><b>Est. arrival:</b> Friday 05/25/2021</p> </td> <td style="border-bottom: 1px solid rgb(221 221 221 / 41%);"> <address style="font-style: normal;"> <b style="margin-bottom: 5px;display: block;">Pickup Address:</b> <a style="color: #8142ff;" href="#"><b>REI San Francisco <i class="fa fa-arrow-right ml-1"></i></b></a> <p style="text-transform: uppercase;">840 Brannant st <br> San Francisco, ca 94013 <br> (514) 932-1952 </p> </address> </td> </tr> <tr> <td colspan="2" style="padding: 0;"> <table style="width:100%;"> <tbody> <tr> <td style="height: 80px;width: 80px;border-bottom: 1px solid rgb(221 221 221 / 41%);"> <img style="height: 100%;width: 100%;object-fit: cover;border-radius: 4px;" src="https://imgproxy.royoorders.com/insecure/fill/300/300/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/NVtOSeR3oh8PW8JPOMCHj4uIQuHUR49M5xqSQMoU.jpg" alt=""> </td> <td style="border-left: 1px solid rgb(221 221 221 / 41%);border-bottom: 1px solid rgb(221 221 221 / 41%);"> <a style="color: #8142ff;" href="#"><b>Pizza</b></a> <p style="margin: 2px 0;"><b>Item Number:</b> 205215421512</p> <ul style="display: flex; align-items: center;justify-content: space-between;"> <li> <b>Item Price:</b><span style="display: block;margin-top: 5px;"> $423.00</span> </li> <li style="text-align: center;"> <b>Qty:</b><span style="display: block;margin-top: 5px;"> 1</span> </li> <li> <b>Total:</b><span style="display: block;margin-top: 5px;"> $423.00</span> </li> </ul> </td> </tr> <tr> <td style="height: 80px;width: 80px;border-bottom: 1px solid rgb(221 221 221 / 41%);"> <img style="height: 100%;width: 100%;object-fit: cover;border-radius: 4px;" src="https://imgproxy.royoorders.com/insecure/fill/300/300/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/NVtOSeR3oh8PW8JPOMCHj4uIQuHUR49M5xqSQMoU.jpg" alt=""> </td> <td style="border-left: 1px solid rgb(221 221 221 / 41%);border-bottom: 1px solid rgb(221 221 221 / 41%);"> <a style="color: #8142ff;" href="#"><b>Pizza</b></a> <p style="margin: 2px 0;"><b>Item Number:</b> 205215421512</p> <ul style="display: flex; align-items: center;justify-content: space-between;"> <li> <b>Item Price:</b><span style="display: block;margin-top: 5px;"> $423.00</span> </li> <li style="text-align: center;"> <b>Qty:</b><span style="display: block;margin-top: 5px;"> 1</span> </li> <li> <b>Total:</b><span style="display: block;margin-top: 5px;"> $423.00</span> </li> </ul> </td> </tr> <tr> <td style="height: 80px;width: 80px;border-bottom: 1px solid rgb(221 221 221 / 41%);"> <img style="height: 100%;width: 100%;object-fit: cover;border-radius: 4px;" src="https://imgproxy.royoorders.com/insecure/fill/300/300/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/NVtOSeR3oh8PW8JPOMCHj4uIQuHUR49M5xqSQMoU.jpg" alt=""> </td> <td style="border-left: 1px solid rgb(221 221 221 / 41%);border-bottom: 1px solid rgb(221 221 221 / 41%);"> <a style="color: #8142ff;" href="#"><b>Pizza</b></a> <p style="margin: 2px 0;"><b>Item Number:</b> 205215421512</p> <ul style="display: flex; align-items: center;justify-content: space-between;"> <li> <b>Item Price:</b><span style="display: block;margin-top: 5px;"> $423.00</span> </li> <li style="text-align: center;"> <b>Qty:</b><span style="display: block;margin-top: 5px;"> 1</span> </li> <li> <b>Total:</b><span style="display: block;margin-top: 5px;"> $423.00</span> </li> </ul> </td> </tr> <tr> <td></td> <td style="border-left: 1px solid rgb(221 221 221 / 41%);"> <h3>Purcheses Summary</h3> <ul> <li style="display: flex; align-items: center;justify-content: space-between;margin-top: 10px;"> <b>Subtotal:</b> <span style="width: 100px;">$423.00</span> </li> <li style="display: flex; align-items: center;justify-content: space-between;margin-top: 10px;"> <b>Shipping:</b> <span style="width: 100px;">$0.00</span> </li> <li style="display: flex; align-items: center;justify-content: space-between;margin-top: 10px;"> <b>Estimated Sale Tax:</b> <span style="width: 100px;">$35.00</span> </li> <li style="display: flex; align-items: center;justify-content: space-between;border-top: 1px solid rgb(221 221 221 / 41%);margin: 15px 0 0;padding: 10px 0 30px;"> <b>Order Total:</b> <b style="width: 100px;">$35.00</b> </li> </ul> </td> </tr> </tbody> <tfoot> <tr> <td colspan="2" style="background-color: #8142ff;color: #fff; border-top: 1px solid rgb(221 221 221 / 41%);text-align: center;"> <b>Powered By Royo</b> </td> </tr> </tfoot> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table>'
            ]
        ];
        EmailTemplate::truncate();
        foreach ($create_array as $key => $array) {
            EmailTemplate::create(['label' => $array['label'], 'slug' => Str::slug($array['label'], "-"),'content' => $array['content'], 'subject' => 'demo', 'tags' => $array['tags']]);
        }
    }
}
