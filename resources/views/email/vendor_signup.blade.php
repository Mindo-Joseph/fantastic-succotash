<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <title>New Vendor Signup</title>
      <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet">
      <style type="text/css">
         body{
            padding: 0;
            margin: 0;font-family: 'Lato', sans-serif;
            font-weight: 400;
         }
         a{
            text-decoration: none;
         }
         h1,h2,h3,h4{
            font-weight: 700;
            margin: 0;
         }
         p{
            font-size: 16px;
            line-height: 22px;
            margin: 0 0 5px;
         }
         .container {
            background: #fff;
            padding: 0;
            max-width: 560px;
            margin: 0 auto;
            border-radius: 4px;
            background-repeat: repeat;
            width: 700px;
         }
         table {
            border-collapse: separate;
            text-indent: initial;
            border-spacing: 0;
            text-align: left;
         }
         table th,table td{
            padding: 10px 15px;
         }
         ul {
            margin: 0;padding: 0;
         }
         ul li{
            list-style: none;
         }
      </style>
   </head>
   <body>
      <section class="wrapper">
         <div class="container" style="background: #fff;border-radius: 10px;">
            <table style="width: 100%;">
              <thead>
                 <tr>
                    <th style="text-align: center;">
                        <a style="display: block;" href="#">
                           <img src="{{asset('images/logo.png')}}" alt="">
                        </a>
                    </th>
                 </tr>
              </thead>
              <tbody>
                 <tr>
                    <td>
                        <div style="background: url(images/food-banner.jpg) no-repeat;height: 300px;background-size: cover;display: flex;align-items: center;justify-content: center;">
                           <div style="border-radius: 50%;width: 120px;height: 120px;padding: 20px;box-sizing: border-box;">
                              <img style="object-fit: contain; width: 100%;height: 100%;" src="{{asset('images/logo.png')}}" alt="">
                           </div>
                        </div>
                    </td>
                 </tr>
                 <tr>
                    <td>
                       <div style="margin-bottom: 20px;">
                          <h4 style="margin-bottom: 5px;">Name</h4>
                          <p>{{$mailData['vendor_name']}}</p>
                       </div>
                       <div style="margin-bottom: 20px;">
                          <h4 style="margin-bottom: 5px;">Description</h4>
                          <p>{{$mailData['description']}}</p>
                       </div>
                       <div style="margin-bottom: 20px;">
                          <h4 style="margin-bottom: 5px;">Email</h4>
                          <p>{{$mailData['email']}}</p>
                       </div>
                       <div style="margin-bottom: 20px;">
                          <h4 style="margin-bottom: 5px;">Phone Number</h4>
                          <p>{{$mailData['phone_no']}}</p>
                       </div>
                       <div style="margin-bottom: 20px;">
                          <h4 style="margin-bottom: 5px;">Address</h4>
                          <address style="font-style: normal;">
                           <a style="color: #8142ff;" href="#"><b>REI San Francisco <i class="fa fa-arrow-right ml-1"></i></b></a>
                           <p style="text-transform: uppercase;">840 Brannant st <br>
                              San Francisco, ca 94013 <br>
                              (514) 932-1952
                           </p>
                        </address>
                       </div>
                       <div style="margin-bottom: 20px;">
                        <h4 style="margin-bottom: 5px;">Website</h4>
                        <a style="color: #8142ff;" href="#"><b>{{$mailData['website']}}</b></a>
                     </div>
                    </td>
                 </tr>
              </tbody>
              <tfoot style="text-align: center;">
               <tr>
                  <td colspan="2" style="padding: 0 15px 20px;">
                     <div style="border-radius: 20px">
                        <p style="background-color: #8142ff;padding:5px 0;text-align:center;color: #fff;margin-top: 30px; ">Powered by <b>sales.royoorders.com</b></p>
                     </div>
                  </td>
               </tr>
              </tfoot>
            </table>
         </div>
      </section>
   </body>
</html>