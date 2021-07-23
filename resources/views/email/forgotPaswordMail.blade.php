<!DOCTYPE html>
<html lang="en">
   <head>
      <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
      <title>Reset Password Email Template</title>
      <meta name="description" content="Reset Password Email Template.">
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
            /*background-image: url(./assets/invoice-images/pattern.png);*/
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
   <body marginheight="0" topmargin="0" marginwidth="0" style="margin: 0px; background-color: #f2f3f8;" leftmargin="0">
      <table cellspacing="0" border="0" cellpadding="0" width="100%" bgcolor="#f2f3f8"
          style="@import url(https://fonts.googleapis.com/css?family=Rubik:300,400,500,700|Open+Sans:300,400,600,700); font-family: 'Open Sans', sans-serif;">
          <thead>
            <tr>
                <th style="height:80px;">&nbsp;</th>
            </tr>
            <tr>
                <th style="text-align:center;">
                  <a href="https://rakeshmandal.com" title="logo" target="_blank">
                    <img width="100" src="{{$mailData['logo']}}" title="logo" alt="logo">
                  </a>
                </th>
            </tr>
          </thead>
          <tbody>
            <tr>
                <td>
                    <table style="background-color: #f2f3f8; max-width:670px;  margin:0 auto;" width="100%" border="0"
                        align="center" cellpadding="0" cellspacing="0">
                        
                        <tr>
                            <td>
                                <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0"
                                    style="max-width:670px;background:#fff; border-radius:3px; text-align:center;-webkit-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);-moz-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);box-shadow:0 6px 18px 0 rgba(0,0,0,.06);">
                                    <tr>
                                        <td style="height:20px;">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td style="padding:0 35px;">
                                            <h1 style="color:rgb(51,51,51);font-weight:500;line-height:27px;font-size:21px">You have
                                                requested to reset your password</h1>
                                            <span
                                                style="display:inline-block; vertical-align:middle; margin:29px 0 26px; border-bottom:1px solid #cecece; width:100px;"></span>
                                            <p style="color:#455056; font-size:15px;line-height:24px; margin:0;"> A unique link to reset your
                                                password has been generated for you. To reset your password, click the
                                                following link and follow the instructions.
                                            </p>
                                            <a href="{{$mailData['link']}}" style="display: inline-block; padding: 6.7px 29px;border-radius: 4px;background:#8142ff;line-height: 20px; text-transform: uppercase;font-size: 14px;font-weight: 700;text-decoration: none;color: #fff;margin-top: 35px;">Reset Password</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="height:20px;">&nbsp;</td>
                                    </tr>
                                </table>
                            </td>
                          </tr>
                        <tr>
                            <td style="height:20px;">&nbsp;</td>
                        </tr>
                    </table>
                </td>
            </tr>
          </tbody>
      </table>
  </body>
</html>