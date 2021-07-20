@extends('layouts.vertical', ['demo' => 'creative', 'title' => 'Emails'])
@section('css')
<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Emails</h4>
            </div>
        </div>
    </div>
    <div class="row cms-cols">
        <div class="col-lg-5 col-xl-3 mb-2">
            <div class="card">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h4>List</h4>
                    </div> 
                   <div class="table-responsive pages-list-data">
                        <table class="table table-striped w-100">
                            <thead>
                                <tr>
                                    <th class="border-bottom-0">Template Name</th>
                                    <th class="text-right border-bottom-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                    <tr class="page-title active-page page-detail" data-page_id="" data-show_url="">
                                        <td>
                                            <a class="text-body" href="javascript:void(0)" id="">Orders</a>
                                        </td>
                                        <td align="right">
                                            <a class="text-body delete-page" href="javascript:void(0)" data-page_id="">
                                                <i class="mdi mdi-pencil-box-outline"></i>
                                            </a>
                                            <a class="text-body delete-page" href="javascript:void(0)" data-page_id="">
                                                <i class="mdi mdi-delete"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr class="page-title active-page page-detail" data-page_id="" data-show_url="">
                                        <td>
                                            <a class="text-body" href="javascript:void(0)" id="">New Vendor Signup</a>
                                        </td>
                                        <td align="right">
                                            <a class="text-body delete-page" href="javascript:void(0)" data-page_id="">
                                                <i class="mdi mdi-pencil-box-outline"></i>
                                            </a>
                                            <a class="text-body delete-page" href="javascript:void(0)" data-page_id="">
                                                <i class="mdi mdi-delete"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr class="page-title active-page page-detail" data-page_id="" data-show_url="">
                                        <td>
                                            <a class="text-body" href="javascript:void(0)" id="">Refund</a>
                                        </td>
                                        <td align="right">
                                            <a class="text-body delete-page" href="javascript:void(0)" data-page_id="">
                                                <i class="mdi mdi-pencil-box-outline"></i>
                                            </a>
                                            <a class="text-body delete-page" href="javascript:void(0)" data-page_id="">
                                                <i class="mdi mdi-delete"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr class="page-title active-page page-detail" data-page_id="" data-show_url="">
                                        <td>
                                            <a class="text-body" href="javascript:void(0)" id="">Verify mail</a>
                                        </td>
                                        <td align="right">
                                            <a class="text-body delete-page" href="javascript:void(0)" data-page_id="">
                                                <i class="mdi mdi-pencil-box-outline"></i>
                                            </a>
                                            <a class="text-body delete-page" href="javascript:void(0)" data-page_id="">
                                                <i class="mdi mdi-delete"></i>
                                            </a>
                                        </td>
                                    </tr>
                            </tbody>
                        </table>
                   </div>
                </div>            
            </div>
        </div>
        <div class="col-lg-7 col-xl-9 mb-2">
            <div class="card">
                <div class="card-body p-3" id="edit_page_content">
                    <div class="row">
                        <div class="col-12 text-right">
                            <button type="button" class="btn btn-info" id=""> Publish</button>
                        </div>
                    </div>
                    <div class="row">
                        <input type="hidden" id="page_id" value="">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label for="title" class="control-label">Email Title</label>
                                    <input class="form-control" id="edit_title" placeholder="Email Title" name="title" type="text">
                                    <span class="text-danger error-text updatetitleError"></span>
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="title" class="control-label">Email Content</label>
                                    <textarea class="form-control" id="edit_meta_keyword" placeholder="Meta Keyword" rows="6" name="meta_keyword" cols="10">
                                        <!DOCTYPE html>
                                            <html lang="en">
                                               <head>
                                                  <meta charset="utf-8">
                                                  <title>New Order</title>
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
                                                        max-width: 730px;
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
                                             <body> <section class="wrapper"> <div class="container" style="background: #fff;border-radius: 10px;"> <table style="width: 100%;"> <thead> <tr> <th colspan="2" style="text-align: center;"> <a style="display: block;margin-bottom: 10px;" href="#"> <img src="images/logo.png" alt=""> </a> <h1 style="margin: 0 0 10px;">Thanks for your order</h1> <p style="margin: 0 0 20px;">Hi Andrew, we have received your order #123456789 and working on it now. <br> We'll email you an update as soon as your order is processed. </p> <a style="display: inline-block; padding: 6.7px 29px;border-radius: 4px;background:#8142ff;line-height: 20px; text-transform: uppercase;font-size: 14px;font-weight: 700;text-decoration: none;color: #fff;" href="#">View your order</a> </th> </tr> </thead> <tbody> <tr> <td colspan="2"> <table style="width:100%;border: 1px solid rgb(221 221 221 / 41%);"> <thead> <tr> <th colspan="2" style="border-bottom: 1px solid rgb(221 221 221 / 41%);"> <h3 style="font-weight: 700;">Items Ordered</h3> </th> </tr> </thead> <tbody> <tr style="vertical-align: top;"> <td style="border-bottom: 1px solid rgb(221 221 221 / 41%);border-right: 1px solid rgb(221 221 221 / 41%);width: 50%;"> <p style="margin-bottom: 5px;"><b>Shipping method:</b> Ship to store</p> <p><b>Est. arrival:</b> Friday 05/25/2021</p> </td> <td style="border-bottom: 1px solid rgb(221 221 221 / 41%);"> <address style="font-style: normal;"> <b style="margin-bottom: 5px;display: block;">Pickup Address:</b> <a style="color: #8142ff;" href="#"><b>REI San Francisco <i class="fa fa-arrow-right ml-1"></i></b></a> <p style="text-transform: uppercase;">840 Brannant st <br> San Francisco, ca 94013 <br> (514) 932-1952 </p> </address> </td> </tr> <tr> <td colspan="2" style="padding: 0;"> <table style="width:100%;"> <tbody> <tr> <td style="height: 80px;width: 80px;border-bottom: 1px solid rgb(221 221 221 / 41%);"> <img style="height: 100%;width: 100%;object-fit: cover;border-radius: 4px;" src="https://imgproxy.royoorders.com/insecure/fill/300/300/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/NVtOSeR3oh8PW8JPOMCHj4uIQuHUR49M5xqSQMoU.jpg" alt=""> </td> <td style="border-left: 1px solid rgb(221 221 221 / 41%);border-bottom: 1px solid rgb(221 221 221 / 41%);"> <a style="color: #8142ff;" href="#"><b>Pizza</b></a> <p style="margin: 2px 0;"><b>Item Number:</b> 205215421512</p> <ul style="display: flex; align-items: center;justify-content: space-between;"> <li> <b>Item Price:</b><span style="display: block;margin-top: 5px;"> $423.00</span> </li> <li style="text-align: center;"> <b>Qty:</b><span style="display: block;margin-top: 5px;"> 1</span> </li> <li> <b>Total:</b><span style="display: block;margin-top: 5px;"> $423.00</span> </li> </ul> </td> </tr> <tr> <td style="height: 80px;width: 80px;border-bottom: 1px solid rgb(221 221 221 / 41%);"> <img style="height: 100%;width: 100%;object-fit: cover;border-radius: 4px;" src="https://imgproxy.royoorders.com/insecure/fill/300/300/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/NVtOSeR3oh8PW8JPOMCHj4uIQuHUR49M5xqSQMoU.jpg" alt=""> </td> <td style="border-left: 1px solid rgb(221 221 221 / 41%);border-bottom: 1px solid rgb(221 221 221 / 41%);"> <a style="color: #8142ff;" href="#"><b>Pizza</b></a> <p style="margin: 2px 0;"><b>Item Number:</b> 205215421512</p> <ul style="display: flex; align-items: center;justify-content: space-between;"> <li> <b>Item Price:</b><span style="display: block;margin-top: 5px;"> $423.00</span> </li> <li style="text-align: center;"> <b>Qty:</b><span style="display: block;margin-top: 5px;"> 1</span> </li> <li> <b>Total:</b><span style="display: block;margin-top: 5px;"> $423.00</span> </li> </ul> </td> </tr> <tr> <td style="height: 80px;width: 80px;border-bottom: 1px solid rgb(221 221 221 / 41%);"> <img style="height: 100%;width: 100%;object-fit: cover;border-radius: 4px;" src="https://imgproxy.royoorders.com/insecure/fill/300/300/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/NVtOSeR3oh8PW8JPOMCHj4uIQuHUR49M5xqSQMoU.jpg" alt=""> </td> <td style="border-left: 1px solid rgb(221 221 221 / 41%);border-bottom: 1px solid rgb(221 221 221 / 41%);"> <a style="color: #8142ff;" href="#"><b>Pizza</b></a> <p style="margin: 2px 0;"><b>Item Number:</b> 205215421512</p> <ul style="display: flex; align-items: center;justify-content: space-between;"> <li> <b>Item Price:</b><span style="display: block;margin-top: 5px;"> $423.00</span> </li> <li style="text-align: center;"> <b>Qty:</b><span style="display: block;margin-top: 5px;"> 1</span> </li> <li> <b>Total:</b><span style="display: block;margin-top: 5px;"> $423.00</span> </li> </ul> </td> </tr> <tr> <td></td> <td style="border-left: 1px solid rgb(221 221 221 / 41%);"> <h3>Purcheses Summary</h3> <ul> <li style="display: flex; align-items: center;justify-content: space-between;margin-top: 10px;"> <b>Subtotal:</b> <span style="width: 100px;">$423.00</span> </li> <li style="display: flex; align-items: center;justify-content: space-between;margin-top: 10px;"> <b>Shipping:</b> <span style="width: 100px;">$0.00</span> </li> <li style="display: flex; align-items: center;justify-content: space-between;margin-top: 10px;"> <b>Estimated Sale Tax:</b> <span style="width: 100px;">$35.00</span> </li> <li style="display: flex; align-items: center;justify-content: space-between;border-top: 1px solid rgb(221 221 221 / 41%);margin: 15px 0 0;padding: 10px 0 30px;"> <b>Order Total:</b> <b style="width: 100px;">$35.00</b> </li> </ul> </td> </tr> </tbody> <tfoot> <tr> <td colspan="2" style="background-color: #8142ff;color: #fff; border-top: 1px solid rgb(221 221 221 / 41%);text-align: center;"> <b>Powered By Royo</b> </td> </tr> </tfoot> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </div> </section> </body></html>
                                    </textarea>
                                </div>
                            </div>         
                        </div>
                        
                    </div>
                </div>            
            </div>
        </div>         
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
         $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });
        $('#edit_meta_keyword').summernote({'height':450});
    });
</script>
@endsection
@section('script')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
@endsection