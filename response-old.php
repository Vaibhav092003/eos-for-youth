<?php
// Load Composer's autoloader
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';
include('ccav/Crypto.php');
include('ccav/db.php');
 error_reporting(E_ALL); ini_set('display_errors', 1);


?>
<!DOCTYPE html>
<html lang="en">
   <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <head>
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Essentials of Success (EOS) for Youth</title>
    <link rel=icon href='img/favicon.png' type='image/x-icon'/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/custom.css?=v1.2">
    <link rel="stylesheet" href="css/flexboxgrid.css?=v1.1">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,600,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=PT+Sans:400,600&display=swap" rel="stylesheet">
      
      <!-- DO NOT MODIFY -->
<!-- Quora Pixel Code (JS Helper) -->
<script>
!function(q,e,v,n,t,s){if(q.qp) return; n=q.qp=function(){n.qp?n.qp.apply(n,arguments):n.queue.push(arguments);}; n.queue=[];t=document.createElement(e);t.async=!0;t.src=v; s=document.getElementsByTagName(e)[0]; s.parentNode.insertBefore(t,s);}(window, 'script', 'https://a.quora.com/qevents.js');
qp('init', 'dbbcc3b6675f47a8914cb3fb4f42c8d4');
qp('track', 'ViewContent');
</script>
<style>
@media only screen and (max-width: 460px){
    .banner-text-wrapper h4 {margin-top: 15px;}
    .banner-text-wrapper h4, .banner-text-wrapper h5 {  font-weight: 600;}
    .fly-man { position: relative; left: 0; right: 0; }
    }
</style>
   </head>
   <body>
       
<!--Banner Open-->
        <section>
            <div class="container-fluid" style="padding:; box-shadow: 0px 1px 7px -6px #333;">
                <div class="wrap container-banner-wrapper">
                    <div class="row horizontally-center" style="position: relative;">
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-7">
                            <div class="banner-text-wrapper">
                                <h1>Essentials of Success<sup>TM</sup></h1>
                                <h2>for Youth</h2>
                                <div>
                                    <h4>BREAK THE MAZE</h4>
                                    <h5>No lectures. No clichés.<br> Just real tools for real life.</h5>
                                    <h6 class="text-center">11th - 12th July 2026 <br> Delhi</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-5 fly-man-warp">
                            <img src="img/youth/fly-man.jpg" alt="Essentials of Success for Youth" style="width:110%;" style="margin: 0 auto;" class="fly-man">
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <!--Banner Close-->

      <!--Ticket Confirmation Details-->

      <section class="workshop-details" id="summary">
        <div class="container-fluid wrap">
            
            <?php
                error_reporting(0);
                $workingKey='8EC904D5B2D4C6F0610EBEF0360343E5';     //Working Key should be provided here.
                $encResponse=$_POST["encResp"];         //This is the response sent by the CCAvenue Server
                $rcvdString=decrypt($encResponse,$workingKey);      //Crypto Decryption used as per the specified working key.
                $order_status="";
                $decryptValues=explode('&', $rcvdString);
                $dataSize=sizeof($decryptValues);
                //print_r($decryptValues);
                //echo "<center>";
                $row=array();
                for($i = 0; $i < $dataSize; $i++) 
                {
                    $information=explode('=',$decryptValues[$i]);
                    if($i==0){  $order_id=$information[1];}
                    if($i==1){  $tracking_id=$information[1];}
                    if($i==2){  $bank_ref_no=$information[1];}
                    if($i==3){  $order_status=$information[1];}
                    if($i==4){  $failure_message=$information[1];}
                    if($i==5){  $payment_mode=$information[1];}
                    if($i==8){  $status_message=$information[1];}
                    
                    if($i==10){ $amount=$information[1];}
                    if($i==11){ $billing_name=$information[1];}
                    if($i==17){ $billing_tel=$information[1];}
                    if($i==18){ $billing_email=$information[1];}
                    if($i==26){ $merchant_param1=$information[1];}
                    if($i==27){ $merchant_param2=$information[1];}
                    
                    if($i==40){ $trans_date=$information[1];}
                    
                }
            
                if($trans_date!='null'){ 
                    $out = explode('/', $trans_date);
                    $out2 = explode(' ', $out[2]);
                    $trans_date=$out2[0].'-'.$out[1].'-'.$out[0];
                }else{ $trans_date=date("Y-m-d");}
                
                
                $status_message=$status_message?$status_message:'NULL';
                //save data in database
                $sql2 = "UPDATE user_payment SET trans_date='".$trans_date."',tracking_id=$tracking_id,bank_ref_no=$bank_ref_no,payment_mode='".$payment_mode."',failure_message='".$failure_message."',order_status='".$order_status."',status_message='".$status_message."' WHERE order_id='".$order_id."'";
                 mysqli_query($conn, $sql2);
                //get data in database  
                $sql3 = "SELECT * FROM user_payment WHERE order_id='".$order_id."'";
                $result=mysqli_query($conn, $sql3);
                $row = mysqli_fetch_assoc($result);
                @$name3 = explode(",",$row['other_name']);
                
                //get first name
                $name=explode(' ',$billing_name);
                
                if(!empty($merchant_param2)){
                    $count_person=explode(',',$merchant_param2);
                    $person=count($count_person);
                }
                
                $person=@$person?($person+1):1;
                
             
                $to = $billing_email;
                $from = 'noreply@skc.world';
                 // Create email headers
                //$headers  = 'MIME-Version: 1.0' . "\r\n";
                //$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                //$headers .= "From: SKC WORLD <noreply@skc.world>". "\r\n";
                //$headers .= 'Cc: avdhesh@detecvision.com' . "\r\n";
                
            ?>
            
               <div class="row mt20">
                        <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12">

                        </div>
                     <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
                     <div class="ticket-confirmation-div">
                            <div class="samarth-ticket-hding">
                                <fieldset>
                                    <legend>Ticket confirmation details</legend>
                                    <div class="order-success-massage">
                <?php
                $comma = "'s";
                
                    if($order_status==="Success")
                        {
                            
                            
                            echo "<p class='success'><strong>Thank You for Your Participation. Your payment transaction is successful.</strong></p>";
                            $subject = 'Registration Successful - Essentials of Success (EoS) for Youth';
                            
                             $html = '
                                <html>
                                <head>
                                <style>
                                body{margin:0;padding:0;font-family:Arial,Helvetica,sans-serif;color:#1b1b1b;background:#f6f6f6;font-size:13px}.wrapper{margin:1em auto;width:700px;background:#fff;border:1px solid #f55d29}strong{color:#000}p{color:#414141}td{box-sizing:border-box}
                                p { margin: 0pt; }
                                table.items {
                                    border: 0.1mm solid #000000;
                                }
                                td { vertical-align: top; }
                                .items td {
                                    border-left: 0.1mm solid #000000;
                                    border-right: 0.1mm solid #000000;
                                }
                                .wrapper {
                                    margin: 1em auto;
                                    width: 700px;
                                    background: #fff;
                                    border: 1px solid #f55d29;
                                }
                                table thead td { background-color: #EEEEEE;
                                    text-align: center;
                                    border: 0.1mm solid #000000;
                                    font-variant: small-caps;
                                }
                                .items td.blanktotal {
                                    background-color: #EEEEEE;
                                    border: 0.1mm solid #000000;
                                    background-color: #FFFFFF;
                                    border: 0mm none #000000;
                                    border-top: 0.1mm solid #000000;
                                    border-right: 0.1mm solid #000000;
                                }
                                .items td.totals {
                                    text-align: right;
                                    border: 0.1mm solid #000000;
                                }
                                .items td.cost {
                                    text-align: "." center;
                                }
                                </style>
                                </head>
                                <body>
                                
                                <!--mpdf
                                <htmlpageheader name="myheader">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:1.5rem 1rem;">
                                  <tbody>
                                    <tr>
                                      <td width="60%"><img src="https://www.skc.world/wp-content/uploads/2019/03/color-logo.png"></td>
                                      <td width="40%" 
                                        style="padding: 25px 10px; 
                                                font-size: 15px; 
                                                background: #f2f2f2; 
                                                text-align: center; 
                                                border: 1px solid #dddddd; 
                                                font-weight:300;"><strong>
                                          Paid On -
                                          '.$trans_date.'
                                        </td>
                                    </tr>
                                  </tbody>
                                </table>
                                
                                
                                
                                </htmlpageheader>
                                
                                <htmlpagefooter name="myfooter">
                                <div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
                                Page {PAGENO} of {nb}
                                </div>
                                </htmlpagefooter>
                                
                                <sethtmlpageheader name="myheader" value="on" show-this-page="1" />
                                <sethtmlpagefooter name="myfooter" value="on" />
                                mpdf-->
                                
                                <div  style="padding:0px 20px 0 0">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tbody>
                                <tr>
                                <td style="width:65%" style="padding:10px 15px; margin:0;  line-height: 20px; ">
                                <p><strong>Payment Id : </strong> '.$order_id.'</p>
                                <p><strong>Event Name : </strong> SKC World - Essentials of Success for Youth</p>
                                <p><strong>Ticket Quantity :  </strong> '. $person.' </p>
                                
                                </td>
                                
                                      <td width="35%" valign="middle" 
                                        style="padding-top: 30px;
                                                font-size: 15px; 
                                                background: #f2f2f2; 
                                                text-align: center; 
                                                border: 1px solid #dddddd; 
                                                font-weight:300;
                                                color:#64004e;">
                                          <div style="font-size:18px;color:#64004e;">Amount</div>
                                          <b style="font-size:30px;">₹ '.$amount.'</b>
                                        </td>
                                </tr>
                                </tbody>
                                </table>
                                <br><br>
                                </div>';
                                
                               $html .= '<div style="padding:0 20px;">
                                <table width="100%" border=0 cellspacing=0 cellpadding=0 style="border:1px solid #dddcdd;border-collapse:collapse;text-align:left">
                                <tbody>
                                <tr>
                                <td colspan=2 style="background:#64004e;padding:.8rem;color:#fff;font-weight:bold">Attendee 1:</td>
                                </tr>';
                                
                                if(!empty($billing_name)){
                                    $html .= '<tr>
                                    <td style="padding:.6rem;font-weight:bold;width:30%">Participant'.$comma.' Name</td>
                                    <td style="padding:.6rem;width:70%"> : '.$billing_name.'</td>
                                    </tr>';
                                }
                                
                                if(!empty($billing_email)){
                                    $html .= '<tr>
                                    <td style="padding:.6rem;font-weight:bold;width:30%">Parent'.$comma.' Email Address</td>
                                    <td style="padding:.6rem;width:70%"> : '.$billing_email.'</td>
                                    </tr>';
                                }
                                
                                if(!empty($billing_tel)){
                                    $html .= '<tr>
                                    <td style="padding:.6rem;font-weight:bold;width:30%">Parent'.$comma.' Contact Number</td>
                                    <td style="padding:.6rem;width:70%"> : '.$billing_tel.'</td>
                                    </tr>';
                                }
                                
                                if(!empty($row['age'])){
                                    $html .= '<tr>
                                    <td style="padding:.6rem;font-weight:bold;width:30%">Age</td>
                                    <td style="padding:.6rem;width:70%"> : '.$row['age'].'</td>
                                    </tr>';
                                }
                              
                                
                                if(!empty($row['organisation'])){
                                    $html .= '<tr>
                                    <td style="padding:.6rem;font-weight:bold;width:30%">School/ College/ University</td>
                                    <td style="padding:.6rem;width:70%"> : '.$row['organisation'].'</td>
                                    </tr>';
                                }
                                
                                if(!empty($row['questions'])){
                                    $html .= '<tr>
                                    <td style="padding:.6rem;font-weight:bold;width:30%">Questions</td>
                                    <td style="padding:.6rem;width:70%"> : '.$row['questions'].'</td>
                                    </tr>';
                                }
                                
                                if(!empty($row['referred_by'])){
                                    $html .= '<tr>
                                    <td style="padding:.6rem;font-weight:bold;width:30%">Referred By</td>
                                    <td style="padding:.6rem;width:70%"> : '.$row['referred_by'].'</td>
                                    </tr>';
                                }
                                
                                if(!empty($row['location'])){
                                    $html .= '<tr>
                                    <td style="padding:.6rem;font-weight:bold;width:30%">Location</td>
                                    <td style="padding:.6rem;width:70%"> : '.$row['location'].'</td>
                                    </tr>';
                                }
                                if (!empty($row['message'])) {
                                        $html .= '<tr>
                                            <td style="padding:.6rem .6rem .3rem;font-weight:bold;width:30%">Your question / comment (if any)</td>
                                            <td style="padding:.6rem .6rem .3rem;width:70%"> : '.$row['message'].'</td>
                                        </tr>';
                                    }

                                $html .= '</tbody></table></div>';

                                 
                                @$name3 = explode(",",$row['other_name']);
                                @$other_profession = explode(",",$row['other_profession']);
                                @$other_age = explode(",",$row['other_age']);
                                
                               
                                @$other_email = explode(",",$row['other_email']);
                                @$other_phone = explode(",",$row['other_phone']);
                                
                                 @$other_questions = explode(",",$row['other_questions']);
                                @$other_designation = explode(",",$row['other_designation']);
                                
                               
                                
                                $html .='<div style="height:40px;width:100%;border-bottom:1px solid #999"></div>
                                <div style="padding:25px; background:#ecebeb !important;">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ecebeb">
                                <tbody>
                                <tr>
                                <td><strong style=line-height:20px>SKC CONSULTING PRIVATE LIMITED</strong></td>
                                </tr>
                                <tr>
                                <td style=line-height:20px> SpaceTime Center, A-16, NH-19, Mohan Cooperative Industrial Estate Badarpur, New Delhi, Delhi 110044, IN</td>
                                </tr>
                                <tr>
                                <td style=line-height:20px>Phone: 9319432227</td>
                                </tr>
                                <tr>
                                <td style=height:20px></td>
                                </tr>
                                <tr>
                                <td style="line-height:20px;text-align:center">In case of any issue with this payment please feel free to contact <a href="#" style="text-decoration:none;color:#d95800">contact@skc.world</a> or call at <span style="color:#d95800">9319432227</span> </td>
                                </tr>
                                </tbody>
                                </table>
                                
                                
                                
                                
                                
                                </body>
                                </html>
                                ';
                                
                                $mpdf = new \Mpdf\Mpdf([
                                    'margin_left' => 20,
                                    'margin_right' => 15,
                                    'margin_top' => 48,
                                    'margin_bottom' => 25,
                                    'margin_header' => 10,
                                    'margin_footer' => 10
                                ]);
                                
                                $mpdf->SetProtection(array('print'));
                                $mpdf->SetTitle("skc.world. - Payment Receipt");
                                $mpdf->SetAuthor("skc.world.");
                                $mpdf->SetWatermarkText("Paid");
                                $mpdf->showWatermarkText = true;
                                $mpdf->watermark_font = 'DejaVuSansCondensed';
                                $mpdf->watermarkTextAlpha = 0.1;
                                $mpdf->SetDisplayMode('fullpage');
                                
                                $mpdf->WriteHTML($html);
                                
                                //$mpdf->Output();
                                $mpdf->Output('PaymentReceipt.pdf','F');
                                
                                //$attach_pdf = $mpdf->Output('', 'S'); // Saving pdf to attach to email 
                                //$attach_pdf = chunk_split(base64_encode($attach_pdf));
                            
                            
                            //Send Admin mail 
                                 $message2 = '<table class="nl-container" style="table-layout: fixed;background:#f1f1f1; vertical-align: top; min-width: 320px; Margin: 0 auto; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;" cellpadding="0" cellspacing="0" role="presentation" width="100%" valign="top">
                                    <tbody>
                                        <tr style="vertical-align: top;" valign="top">
                                            <td style="word-break: break-word; vertical-align: top; border-collapse: collapse;" valign="top">
                                                
                                                <div style="background-color:transparent;">
                                                    <div class="block-grid " rel="col-num-container-box-father" data-body-width-father="600px" style="Margin: 0 auto; min-width: 320px; max-width: 600px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #64004e;">
                                                        <div style="border-collapse: collapse;display: table;width: 100%;background-color:#64004e;">
                                                            
                                                            <div class="col num12" rel="col-num-container-box-son" data-body-width-son="600" style="min-width: 320px; max-width: 600px; display: table-cell; vertical-align: top;">
                                                                <div style="width:100% !important;">
                                                                
                                                                    <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                                                                    
                                                                        <div class="img-container center  autowidth " align="center" style="padding-right: 0px; padding-top: 15px; padding-bottom: 15px; padding-left: 0px;">
                                                                            <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr style="line-height:0px"><td style="padding-right: 0px;padding-left: 0px;" align="center"><![endif]-->
                                                                            <div style="font-size:1px;line-height:10px"> </div><img class="center  autowidth " align="center" border="0" src="https://i0.wp.com/www.skc.world/wp-content/uploads/2019/03/logo.png" alt="Image" title="Image" style="outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; clear: both; border: 0; height: auto; float: none; width: 100%; max-width: 161px; display: block;" width="161">
                                                                            <div style="font-size:1px;line-height:10px"> </div>
                                                                            <!--[if mso]></td></tr></table><![endif]-->
                                                                        </div>
                                                                    
                                                                    </div>
                                                                    
                                                                </div>
                                                            </div>
                                                        
                                                        </div>
                                                    </div>
                                                </div>
                                                <div style="background-color:transparent;">
                                                    <div class="block-grid " rel="col-num-container-box-father" data-body-width-father="600px" style="Margin: 0 auto; min-width: 320px; max-width: 600px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word;">
                                                        <div style="border-collapse: collapse;display: table;width: 100%;">
                                                            
                                                            <div class="col num12" rel="col-num-container-box-son" data-body-width-son="600" style="min-width: 320px; max-width: 600px; display: table-cell; vertical-align: top;">
                                                                <div style="width:100% !important;">
                                                                    
                                                                    <div style="border-top:0px solid transparent; background-color:#FFFFFF; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                                                                        
                                                                        <table class="divider" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" role="presentation" valign="top">
                                                                            <tbody>
                                                                                <tr style="vertical-align: top;" valign="top">
                                                                                    <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px; border-collapse: collapse;" valign="top">
                                                                                        <table class="divider_content" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; border-top: 1px solid transparent; height: 0px;" align="center" role="presentation" height="0" valign="top">
                                                                                            <tbody>
                                                                                                <tr style="vertical-align: top;" valign="top">
                                                                                                    <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse;" height="0" valign="top"><span></span></td>
                                                                                                </tr>
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                        
                                                                        <div style="color:#000000;font-family: Tahoma, sans-serif;line-height:120%;padding-top:10px;padding-right:30px;padding-bottom:10px;padding-left:30px; background-color:#FFFFFF;">
                                                                            <div style="font-family:  Tahoma, sans-serif; font-size: 12px; line-height: 14px; color: #000000;">
                                                                                <h2 style=" margin: 0; line-height: 22px;"><strong>Congratulations!<br>
                                                                                You have received one more registration </strong>,</h2>
                                                                            </div>
                                                                        </div>
                                                                    
                                                                    </div>
                                                                
                                                                </div>
                                                            </div>
                                                        
                                                        </div>
                                                    </div>
                                                </div>
                                                <div style="background-color:transparent;">
                                                    <div class="block-grid " rel="col-num-container-box-father" data-body-width-father="600px" style="Margin: 0 auto; min-width: 320px; max-width: 600px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #FFFFFF;">
                                                        <div style="border-collapse: collapse;display: table;width: 100%;background-color:#FFFFFF;">
                                                        
                                                            <div class="col num12" rel="col-num-container-box-son" data-body-width-son="600" style="min-width: 320px; max-width: 600px; display: table-cell; vertical-align: top;">
                                                                <div style="width:100% !important;">
                                                                    
                                                                    <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                                                                        
                                                                        <div style="color:#000000;font-family: Tahoma, sans-serif;line-height:150%;padding-top:10px;padding-right:30px;padding-bottom:10px;padding-left:30px;">
                                                                            <div style="font-family:  Tahoma, sans-serif; font-size: 12px; line-height: 18px; color: #000000;">
                                                                                
                                                                                <p style="font-size: 14px; line-height: 21px; text-align: left; margin: 0;"><strong><span style="color: #000000; font-size: 14px; line-height: 21px;">ORDER INFORMATION:</span></strong></p>
                                                                                <p style="font-size: 14px; line-height: 21px; margin: 0;"><span style="color: #000000; font-size: 14px; line-height: 21px;">Order  Date: '.$trans_date.'.</span></p>
                                                                                <p style="font-size: 14px; line-height: 21px; margin: 0;"><span style="color: #000000; font-size: 14px; line-height: 21px;">Order  ID: '.$order_id.'.</span></p>
                                                                                <p style="font-size: 14px; line-height: 21px; margin: 0;"><span style="color: #000000; font-size: 14px; line-height: 21px;">Paid with: '.$payment_mode.'.</span></p>
                                                                                <p style="font-size: 14px; line-height: 21px; text-align: left; margin: 0;"><strong><span style="color: #000000; font-size: 14px; line-height: 21px;">Event Name: Essentials of Success for Youth by SKC World</span></strong></p>
                                                                                <p style="font-size: 14px; height: 21px; text-align: left; margin: 0;"> </p>
                                                                                <p style="font-size: 14px; line-height: 21px; text-align: left; margin: 0;"><strong><span style="color: #000000; font-size: 14px; line-height: 21px;">Payment Details:</span></strong></p>
                                                                                <p style="font-size: 14px; line-height: 21px; text-align: left; margin: 0;"><span style="color: #000000; font-size: 14px; line-height: 21px;">Ticket: '.$amount.' ['.$merchant_param1.']</span></p>
                                                                                <p style="font-size: 14px; height: 21px; text-align: left; margin: 0;"> </p>
                                                                                <p style="font-size: 14px; line-height: 21px; text-align: left; margin: 0;"><span style="color: #000000; font-size: 14px; line-height: 21px;">In case you have any queries or need any help call us at SKC Growth-Line: <br>+91 9319 432 227<br>
                                                                                    You can also write to us at <a href="mailto:contact@skc.world">contact@skc.world</a></span></p>
                                                                                <p style="font-size: 14px; height: 21px; text-align: left; margin: 0;"> </p>
                                                                                <p style="font-size: 14px; line-height: 21px; text-align: left; margin: 0;"><span style="color: #000000; font-size: 14px; line-height: 21px;">Wish You SUCCESS | SCALE | JOY, <br>
                                                                                Divya Muraleedhar<br>Lead - Growth Team</span></p>
                                                                                <p style="font-size: 14px; height: 21px; text-align: left; margin: 0;"> </p>';
                                                                                
                                                                            $message2 .= '<div style="padding:0 20px;">
                                                                            <table width="100%" border=0 cellspacing=0 cellpadding=0 style="border:1px solid #dddcdd;border-collapse:collapse;text-align:left">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td colspan=2 style="background:#64004e;padding:.8rem;color:#fff;font-weight:bold">Attendee 1:</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td style="padding:.6rem .6rem .3rem;font-weight:bold;width:30%">Participant'.$comma.' Name</td>
                                                                                    <td style="padding:.6rem .6rem .3rem;width:70%"> : '.$billing_name.'</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td style="padding:.6rem .6rem .3rem;font-weight:bold;width:30%">Parent'.$comma.' Email Address</td>
                                                                                    <td style="padding:.6rem .6rem .3rem;width:70%"> : '.$billing_email.'</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td style="padding:.6rem .6rem .3rem;font-weight:bold;width:30%">Parent'.$comma.' Contact Number</td>
                                                                                    <td style="padding:.6rem .6rem .3rem;width:70%"> : '.$billing_tel.'</td>
                                                                                </tr>';
                                                                        
                                                                        if (!empty($row['age'])) {
                                                                            $message2 .= '<tr>
                                                                                <td style="padding:.6rem .6rem .3rem;font-weight:bold;width:30%">Age</td>
                                                                                <td style="padding:.6rem .6rem .3rem;width:70%"> : '.$row['age'].'</td>
                                                                            </tr>';
                                                                        }
                                                                        
                                                                        
                                                                        if (!empty($row['organisation'])) {
                                                                            $message2 .= '<tr>
                                                                                <td style="padding:.6rem .6rem .3rem;font-weight:bold;width:30%">School/ College/ University</td>
                                                                                <td style="padding:.6rem .6rem .3rem;width:70%"> : '.$row['organisation'].'</td>
                                                                            </tr>';
                                                                        }
                                                                        
                                                                        if (!empty($row['questions'])) {
                                                                            $message2 .= '<tr>
                                                                                <td style="padding:.6rem .6rem .3rem;font-weight:bold;width:30%">Questions</td>
                                                                                <td style="padding:.6rem .6rem .3rem;width:70%"> : '.$row['questions'].'</td>
                                                                            </tr>';
                                                                        }
                                                                        
                                                                        if (!empty($row['referred_by'])) {
                                                                            $message2 .= '<tr>
                                                                                <td style="padding:.6rem .6rem .3rem;font-weight:bold;width:30%">Referred By</td>
                                                                                <td style="padding:.6rem .6rem .3rem;width:70%"> : '.$row['referred_by'].'</td>
                                                                            </tr>';
                                                                        }
                                                                        
                                                                        if (!empty($row['location'])) {
                                                                            $message2 .= '<tr>
                                                                                <td style="padding:.6rem .6rem .3rem;font-weight:bold;width:30%">Location</td>
                                                                                <td style="padding:.6rem .6rem .3rem;width:70%"> : '.$row['location'].'</td>
                                                                            </tr>';
                                                                        }
                                                                        if (!empty($row['message'])) {
                                                                            $message2 .= '<tr>
                                                                                <td style="padding:.6rem .6rem .3rem;font-weight:bold;width:30%">Your question / comment (if any)</td>
                                                                                <td style="padding:.6rem .6rem .3rem;width:70%"> : '.$row['message'].'</td>
                                                                            </tr>';
                                                                        }
                                                                        
                                                                        $message2 .= '</tbody>
                                                                            </table>
                                                                        </div>';

                                                                                 
                                                                                @$name3 = explode(",",$row['other_name']);
                                                                                @$other_profession = explode(",",$row['other_profession']);
                                                                                @$other_age = explode(",",$row['other_age']);
                                                                                
                                                                                
                                                                                @$other_email = explode(",",$row['other_email']);
                                                                                @$other_phone = explode(",",$row['other_phone']);
                                                                                
                                                                                @$other_questions = explode(",",$row['other_questions']);
                                                                                @$other_designation = explode(",",$row['other_designation']);
                                                                               
                                    
                                                                                
                                                                            $message2.='</div>
                                                                        </div>
                                                                        
                                                                    </div>
                                                                
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </td>
                                        </tr>
                                    </tbody>
                                ';
                                
                               // mail('contact@skc.world', $subject, $message2, $headers);
                            //End send admin mail
                            
                            $message = '
                            <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                                        <html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
                                        
                                        <head>
                                            <!--[if gte mso 9]><xml><o:OfficeDocumentSettings><o:AllowPNG/><o:PixelsPerInch>96</o:PixelsPerInch></o:OfficeDocumentSettings></xml><![endif]-->
                                            
                                            <meta name="viewport" content="width=device-width">
                                            <!--[if !mso]><!-->
                                            <meta http-equiv="X-UA-Compatible" content="IE=edge">
                                            <!--<![endif]-->
                                            <title></title>
                                            <!--[if !mso]><!-->
                                            <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,600" rel="stylesheet" type="text/css">
                                            <!--<![endif]-->
                                            <style type="text/css">
                                                body {
                                                    margin: 0;
                                                    padding: 0;
                                                }
                                        
                                                table,
                                                td,
                                                tr {
                                                    vertical-align: top;
                                                    border-collapse: collapse;
                                                }
                                        
                                                * {
                                                    line-height: inherit;
                                                }
                                        
                                                a[x-apple-data-detectors=true] {
                                                    color: inherit !important;
                                                    text-decoration: none !important;
                                                }
                                        
                                                .ie-browser table {
                                                    table-layout: fixed;
                                                }
                                        
                                                [owa] .img-container div,
                                                [owa] .img-container button {
                                                    display: block !important;
                                                }
                                        
                                                [owa] .fullwidth button {
                                                    width: 100% !important;
                                                }
                                        
                                                [owa] .block-grid .col {
                                                    display: table-cell;
                                                    float: none !important;
                                                    vertical-align: top;
                                                }
                                        
                                                .ie-browser .block-grid,
                                                .ie-browser .num12,
                                                [owa] .num12,
                                                [owa] .block-grid {
                                                    width: 700px !important;
                                                }
                                        
                                                .ie-browser .mixed-two-up .num4,
                                                [owa] .mixed-two-up .num4 {
                                                    width: 232px !important;
                                                }
                                        
                                                .ie-browser .mixed-two-up .num8,
                                                [owa] .mixed-two-up .num8 {
                                                    width: 464px !important;
                                                }
                                        
                                                .ie-browser .block-grid.two-up .col,
                                                [owa] .block-grid.two-up .col {
                                                    width: 348px !important;
                                                }
                                        
                                                .ie-browser .block-grid.three-up .col,
                                                [owa] .block-grid.three-up .col {
                                                    width: 348px !important;
                                                }
                                        
                                                .ie-browser .block-grid.four-up .col [owa] .block-grid.four-up .col {
                                                    width: 174px !important;
                                                }
                                        
                                                .ie-browser .block-grid.five-up .col [owa] .block-grid.five-up .col {
                                                    width: 140px !important;
                                                }
                                        
                                                .ie-browser .block-grid.six-up .col,
                                                [owa] .block-grid.six-up .col {
                                                    width: 116px !important;
                                                }
                                        
                                                .ie-browser .block-grid.seven-up .col,
                                                [owa] .block-grid.seven-up .col {
                                                    width: 100px !important;
                                                }
                                        
                                                .ie-browser .block-grid.eight-up .col,
                                                [owa] .block-grid.eight-up .col {
                                                    width: 87px !important;
                                                }
                                        
                                                .ie-browser .block-grid.nine-up .col,
                                                [owa] .block-grid.nine-up .col {
                                                    width: 77px !important;
                                                }
                                        
                                                .ie-browser .block-grid.ten-up .col,
                                                [owa] .block-grid.ten-up .col {
                                                    width: 60px !important;
                                                }
                                        
                                                .ie-browser .block-grid.eleven-up .col,
                                                [owa] .block-grid.eleven-up .col {
                                                    width: 54px !important;
                                                }
                                        
                                                .ie-browser .block-grid.twelve-up .col,
                                                [owa] .block-grid.twelve-up .col {
                                                    width: 50px !important;
                                                }
                                            </style>
                                            <style type="text/css" id="media-query">
                                                @media only screen and (min-width: 720px) {
                                                    .block-grid {
                                                        width: 700px !important;
                                                    }
                                        
                                                    .block-grid .col {
                                                        vertical-align: top;
                                                    }
                                        
                                                    .block-grid .col.num12 {
                                                        width: 700px !important;
                                                    }
                                        
                                                    .block-grid.mixed-two-up .col.num3 {
                                                        width: 174px !important;
                                                    }
                                        
                                                    .block-grid.mixed-two-up .col.num4 {
                                                        width: 232px !important;
                                                    }
                                        
                                                    .block-grid.mixed-two-up .col.num8 {
                                                        width: 464px !important;
                                                    }
                                        
                                                    .block-grid.mixed-two-up .col.num9 {
                                                        width: 522px !important;
                                                    }
                                        
                                                    .block-grid.two-up .col {
                                                        width: 350px !important;
                                                    }
                                        
                                                    .block-grid.three-up .col {
                                                        width: 233px !important;
                                                    }
                                        
                                                    .block-grid.four-up .col {
                                                        width: 175px !important;
                                                    }
                                        
                                                    .block-grid.five-up .col {
                                                        width: 140px !important;
                                                    }
                                        
                                                    .block-grid.six-up .col {
                                                        width: 116px !important;
                                                    }
                                        
                                                    .block-grid.seven-up .col {
                                                        width: 100px !important;
                                                    }
                                        
                                                    .block-grid.eight-up .col {
                                                        width: 87px !important;
                                                    }
                                        
                                                    .block-grid.nine-up .col {
                                                        width: 77px !important;
                                                    }
                                        
                                                    .block-grid.ten-up .col {
                                                        width: 70px !important;
                                                    }
                                        
                                                    .block-grid.eleven-up .col {
                                                        width: 63px !important;
                                                    }
                                        
                                                    .block-grid.twelve-up .col {
                                                        width: 58px !important;
                                                    }
                                                }
                                        
                                                @media (max-width: 720px) {
                                        
                                                    .block-grid,
                                                    .col {
                                                        min-width: 320px !important;
                                                        max-width: 100% !important;
                                                        display: block !important;
                                                    }
                                        
                                                    .block-grid {
                                                        width: 100% !important;
                                                    }
                                        
                                                    .col {
                                                        width: 100% !important;
                                                    }
                                        
                                                    .col>div {
                                                        margin: 0 auto;
                                                    }
                                        
                                                    img.fullwidth,
                                                    img.fullwidthOnMobile {
                                                        max-width: 100% !important;
                                                    }
                                        
                                                    .no-stack .col {
                                                        min-width: 0 !important;
                                                        display: table-cell !important;
                                                    }
                                        
                                                    .no-stack.two-up .col {
                                                        width: 50% !important;
                                                    }
                                        
                                                    .no-stack .col.num4 {
                                                        width: 33% !important;
                                                    }
                                        
                                                    .no-stack .col.num8 {
                                                        width: 66% !important;
                                                    }
                                        
                                                    .no-stack .col.num4 {
                                                        width: 33% !important;
                                                    }
                                        
                                                    .no-stack .col.num3 {
                                                        width: 25% !important;
                                                    }
                                        
                                                    .no-stack .col.num6 {
                                                        width: 50% !important;
                                                    }
                                        
                                                    .no-stack .col.num9 {
                                                        width: 75% !important;
                                                    }
                                        
                                                    .video-block {
                                                        max-width: none !important;
                                                    }
                                        
                                                    .mobile_hide {
                                                        min-height: 0px;
                                                        max-height: 0px;
                                                        max-width: 0px;
                                                        display: none;
                                                        overflow: hidden;
                                                        font-size: 0px;
                                                    }
                                        
                                                    .desktop_hide {
                                                        display: block !important;
                                                        max-height: none !important;
                                                    }
                                                }
                                            </style>
                                        </head>
                                        
                                        <body  style="margin: 0; padding: 0; -webkit-text-size-adjust: 100%; background:#f2f3f8 ">
                                            <!--[if IE]><div class="ie-browser"><![endif]-->
                                            <table class="nl-container" style="table-layout: fixed; border-top:#f2f3f8 10px solid; vertical-align: top; min-width: 320px; Margin: 0 auto; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #FFFFFF; width: 100%;" cellpadding="0" cellspacing="0" role="presentation" width="100%" bgcolor="#FFFFFF" valign="top">
                                                <tbody>
                                                    <tr style="vertical-align: top;" valign="top">
                                                        <td style="word-break: break-word; vertical-align: top; border-collapse: collapse;" valign="top">
                                                            <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td align="center" style="background-color:#FFFFFF"><![endif]-->
                                                            <div style="background-color:#f2f3f8 ">
                                                                <div class="block-grid " rel="col-num-container-box-father" data-body-width-father="700px" style="Margin: 0 auto; min-width: 320px; max-width: 700px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #FFFFFF;">
                                                                    <div style="border-collapse: collapse;display: table;width: 100%;background-color:#64004E;">
                                                                        <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:transparent;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:700px"><tr class="layout-full-width" style="background-color:transparent"><![endif]-->
                                                                        <!--[if (mso)|(IE)]><td align="center" width="700" style="background-color:transparent;width:700px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:0px; padding-bottom:0px;"><![endif]-->
                                                                        <div class="col num12" rel="col-num-container-box-son" data-body-width-son="700" style="min-width: 320px; max-width: 700px; display: table-cell; vertical-align: top;">
                                                                            <div style="width:100% !important;">
                                                                                <!--[if (!mso)&(!IE)]><!-->
                                                                                <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:15px; padding-bottom:15px; padding-right: 0px; padding-left: 15px;">
                                                                                    <!--<![endif]-->
                                                                                    <div class="img-container center  autowidth " align="center" style="padding-right: 0px;padding-left: 0px; text-align: center">
                                                                                        <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr style="line-height:0px"><td style="padding-right: 0px;padding-left: 0px;" align="center"><![endif]--><a href="https://skc.world/" target="_blank"> <img class="center  autowidth " align="center" border="0" src="https://www.skc.world/wp-content/uploads/2020/02/white-logo.png" alt="Image" title="Image" style="outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; clear: both; height: auto; float: none; border: none; margin:0 auto width: 100%; max-width: 208px; display: block;" width="208px"></a>
                                                                                        <!--[if mso]></td></tr></table><![endif]-->
                                                                                    </div>
                                                                                    <!--[if (!mso)&(!IE)]><!-->
                                                                                </div>
                                                                                <!--<![endif]-->
                                                                            </div>
                                                                        </div>
                                                                        <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
                                                                        <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div style="background-color:#f2f3f8 ">
                                                                <div class="block-grid " rel="col-num-container-box-father" data-body-width-father="700px" style="Margin: 0 auto; min-width: 320px; max-width: 700px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #FFFFFF;">
                                                                    <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                                                                        <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:transparent;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:700px"><tr class="layout-full-width" style="background-color:transparent"><![endif]-->
                                                                        <!--[if (mso)|(IE)]><td align="center" width="700" style="background-color:transparent;width:700px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:0px; padding-bottom:0px;"><![endif]-->
                                                                        <div class="col num12" rel="col-num-container-box-son" data-body-width-son="700" style="min-width: 320px; max-width: 700px; display: table-cell; vertical-align: top;">
                                                                            <div style="width:100% !important;">
                                                                                <!--[if (!mso)&(!IE)]><!-->
                                                                                <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                                                                                    <!--<![endif]-->
                                                                                    <table class="divider" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" role="presentation" valign="top">
                                                                                        <tbody>
                                                                                            <tr style="vertical-align: top;" valign="top">
                                                                                                <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px; border-collapse: collapse;" valign="top"></td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                    <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 0; padding-top: 10px; padding-bottom: 10px; font-family:  \'Trebuchet MS\', Tahoma, sans-serif"><![endif]-->
                                                                                    <div style="color:#555555;font-family:\'Montserrat\', \'Trebuchet MS\', \'Lucida Grande\', \'Lucida Sans Unicode\', \'Lucida Sans\', Tahoma, sans-serif;line-height:120%;padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:0;">
                                                                                        <div style="font-family: \'Montserrat\', \'Trebuchet MS\', \'Lucida Grande\', \'Lucida Sans Unicode\', \'Lucida Sans\', Tahoma, sans-serif; font-size: 12px; line-height: 14px; color: #555555;">
                                                                                            <div style="color:#000000;font-family: Tahoma, sans-serif;line-height:120%;padding-top:10px;padding-right:30px;padding-bottom:10px;padding-left:30px; background-color:#FFFFFF;">
                                                                                                <div style="font-family:  Tahoma, sans-serif; font-size: 12px; line-height: 14px; color: #000000;">
                                                                                                    <h4>Namaskar '.$name[0].',</h4>
                                                                                                    <h2 style=" margin: 0; line-height: 22px;"><strong>Thank You!<br>
                                                                                                            You have successfully registered for Essentials of Success (EOS) for Youth </strong></h2>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    
                                                                                    <!--[if mso]></td></tr></table><![endif]-->
                                                                                    <table class="divider" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" role="presentation" valign="top">
                                                                                        <tbody>
                                                                                            <tr style="vertical-align: top;" valign="top">
                                                                                                <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px; border-collapse: collapse;" valign="top">
                                                                                                    <table class="divider_content" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; border-top: 1px solid transparent; height: 0px;" align="center" role="presentation" height="0" valign="top">
                                                                                                        <tbody>
                                                                                                            <tr style="vertical-align: top;" valign="top">
                                                                                                                <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse;" height="0" valign="top"><span></span></td>
                                                                                                            </tr>
                                                                                                        </tbody>
                                                                                                    </table>
                                                                                                </td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                    <!--[if (!mso)&(!IE)]><!-->
                                                                                </div>
                                                                                <!--<![endif]-->
                                                                            </div>
                                                                        </div>
                                                                        <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
                                                                        <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div style="background-color:#f2f3f8 ">
                                                                <div class="block-grid " rel="col-num-container-box-father" data-body-width-father="700px" style="Margin: 0 auto; min-width: 320px; max-width: 700px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #FFFFFF;">
                                                                    <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                                                                        <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:transparent;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:700px"><tr class="layout-full-width" style="background-color:transparent"><![endif]-->
                                                                        <!--[if (mso)|(IE)]><td align="center" width="700" style="background-color:transparent;width:700px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:0px; padding-bottom:0px;"><![endif]-->
                                                                        <div class="col num12" rel="col-num-container-box-son" data-body-width-son="700" style="min-width: 320px; max-width: 700px; display: table-cell; vertical-align: top;">
                                                                            <div style="width:100% !important;">
                                                                                <!--[if (!mso)&(!IE)]><!-->
                                                                                <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                                                                                    <!--<![endif]-->
                                                                                    <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 30px; padding-left: 30px; padding-top: 0px; padding-bottom: 5px; font-family:  \'Trebuchet MS\', Tahoma, sans-serif"><![endif]-->
                                                                                    <div style="color:#555555;font-family:\'Montserrat\', \'Trebuchet MS\', \'Lucida Grande\', \'Lucida Sans Unicode\', \'Lucida Sans\', Tahoma, sans-serif;line-height:150%;padding-top:0px;padding-right:30px;padding-bottom:5px;padding-left:30px;">
                                                                                        <div style="font-family: \'Montserrat\', \'Trebuchet MS\', \'Lucida Grande\', \'Lucida Sans Unicode\', \'Lucida Sans\', Tahoma, sans-serif; font-size: 12px; line-height: 18px; color: #555555;">
                                                                                            <p style="font-size: 14px; line-height: 21px; margin: 0;"><span style="color: #333333; font-size: 14px; line-height: 21px;">Thank you for registering for the  <strong>Essentials of Success (EOS) for Youth</strong> to be held in person from <strong>Dates- 11th - 12th July 2026 <br> Venue- '.ucwords($row['location']).'.</strong></span></p>
                                                                                            <p style="font-size: 14px; line-height: 21px; margin: 0;"> </p>
                                                                                            
                                                                                        </div>
                                                                                    </div>
                                                                                    <!--[if mso]></td></tr></table><![endif]-->
                                                                                    <!--[if (!mso)&(!IE)]><!--> 
                                                                                </div>
                                                                                <!--<![endif]-->
                                                                            </div>
                                                                        </div>
                                                                        <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
                                                                        <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            

                                                            <div style="background-color:#f2f3f8 ">
                                                                <div class="block-grid " rel="col-num-container-box-father" data-body-width-father="700px" style="Margin: 0 auto; min-width: 320px; max-width: 700px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #FFFFFF;">
                                                                    <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                                                                        <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:transparent;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:700px"><tr class="layout-full-width" style="background-color:transparent"><![endif]-->
                                                                        <!--[if (mso)|(IE)]><td align="center" width="700" style="background-color:transparent;width:700px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:0px; padding-bottom:0px;"><![endif]-->
                                                                        <div class="col num12" rel="col-num-container-box-son" data-body-width-son="700" style="min-width: 320px; max-width: 700px; display: table-cell; vertical-align: top;">
                                                                            <div style="width:100% !important;">
                                                                                <!--[if (!mso)&(!IE)]><!-->
                                                                                <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                                                                                    <!--<![endif]-->
                                                                                    <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 30px; padding-left: 30px; padding-top: 0px; padding-bottom: 5px; font-family:  \'Trebuchet MS\', Tahoma, sans-serif"><![endif]-->
                                                                                    <div style="color:#555555;font-family:\'Montserrat\', \'Trebuchet MS\', \'Lucida Grande\', \'Lucida Sans Unicode\', \'Lucida Sans\', Tahoma, sans-serif;line-height:150%;padding-top:0px;padding-right:30px;padding-bottom:5px;padding-left:30px;">
                                                                                        <div style="font-family: \'Montserrat\', \'Trebuchet MS\', \'Lucida Grande\', \'Lucida Sans Unicode\', \'Lucida Sans\', Tahoma, sans-serif; font-size: 12px; line-height: 18px; color: #555555;">
                                                                                            <p style="font-size: 14px; line-height: 21px; margin: 0;">Please treat this email as a confirmation that we have received your registration details and payment. Your profile has been shared with our Growth Team for assessment. Divya Muraleedhar, our Growth Manager will contact you shortly for profile completion and confirmation of registration.
                                        </p>
                                                                                        </div>
                                                                                    </div>
                                                                                    <!--[if mso]></td></tr></table><![endif]-->
                                                                                    
                                                                                    
                                                                                    <!--[if (!mso)&(!IE)]><!-->
                                                                                </div>
                                                                                <!--<![endif]-->
                                                                            </div>
                                                                        </div>
                                                                        <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
                                                                        <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div style="background-color:#f2f3f8 ">
                                                                <div class="block-grid " rel="col-num-container-box-father" data-body-width-father="700px" style="Margin: 0 auto; min-width: 320px; max-width: 700px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #FFFFFF;">
                                                                    <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                                                                        <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:transparent;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:700px"><tr class="layout-full-width" style="background-color:transparent"><![endif]-->
                                                                        <!--[if (mso)|(IE)]><td align="center" width="700" style="background-color:transparent;width:700px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:0px; padding-bottom:0px;"><![endif]-->
                                                                        <div class="col num12" rel="col-num-container-box-son" data-body-width-son="700" style="min-width: 320px; max-width: 700px; display: table-cell; vertical-align: top;">
                                                                            <div style="width:100% !important;">
                                                                                <!--[if (!mso)&(!IE)]><!-->
                                                                                <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:15px; padding-bottom:15px; padding-right: 0px; padding-left: 0px;">
                                                                                    <!--<![endif]-->
                                                                                    <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 30px; padding-left: 30px; padding-top: 0px; padding-bottom: 5px; font-family:  \'Trebuchet MS\', Tahoma, sans-serif"><![endif]-->
                                                                                    <div style="color:#555555;font-family:\'Montserrat\', \'Trebuchet MS\', \'Lucida Grande\', \'Lucida Sans Unicode\', \'Lucida Sans\', Tahoma, sans-serif;line-height:150%;padding-top:0px;padding-right:30px;padding-bottom:5px;padding-left:30px;">
                                                                                        <div style="font-family: \'Montserrat\', \'Trebuchet MS\', \'Lucida Grande\', \'Lucida Sans Unicode\', \'Lucida Sans\', Tahoma, sans-serif; font-size: 12px; line-height: 18px; color: #555555;">
                                                                                            <p style="font-size: 14px; line-height: 21px; margin: 0;">We are glad that you took this opportunity to grow and evolve in your life.</p>
                                                                                        </div>
                                                                                    </div>
                                                                                    <!--[if mso]></td></tr></table><![endif]-->
                                                                                    
                                                                                    
                                                                                    <!--[if (!mso)&(!IE)]><!-->
                                                                                </div>
                                                                                <!--<![endif]-->
                                                                            </div>
                                                                        </div>
                                                                        <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
                                                                        <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div style="background-color:#f2f3f8 ">
                                                                <div class="block-grid " rel="col-num-container-box-father" data-body-width-father="700px" style="Margin: 0 auto; min-width: 320px; max-width: 700px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #FFFFFF;">
                                                                    <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                                                                        <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:transparent;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:700px"><tr class="layout-full-width" style="background-color:transparent"><![endif]-->
                                                                        <!--[if (mso)|(IE)]><td align="center" width="700" style="background-color:transparent;width:700px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:0px; padding-bottom:0px;"><![endif]-->
                                                                        <div class="col num12" rel="col-num-container-box-son" data-body-width-son="700" style="min-width: 320px; max-width: 700px; display: table-cell; vertical-align: top;">
                                                                            <div style="width:100% !important;">
                                                                                <!--[if (!mso)&(!IE)]><!-->
                                                                                <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                                                                                    <!--<![endif]-->
                                                                                    <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 30px; padding-left: 30px; padding-top: 0px; padding-bottom: 5px; font-family:  \'Trebuchet MS\', Tahoma, sans-serif"><![endif]-->
                                                                                    <div style="color:#555555;font-family:\'Montserrat\', \'Trebuchet MS\', \'Lucida Grande\', \'Lucida Sans Unicode\', \'Lucida Sans\', Tahoma, sans-serif;line-height:150%;padding-top:0px;padding-right:30px;padding-bottom:5px;padding-left:30px;">
                                                                                        <div style="font-family: \'Montserrat\', \'Trebuchet MS\', \'Lucida Grande\', \'Lucida Sans Unicode\', \'Lucida Sans\', Tahoma, sans-serif; font-size: 12px; line-height: 18px; color: #555555;">
                                                                                            <p style="font-size: 14px; line-height: 21px; text-align: left; margin: 0;"><strong><span style="color: #000000; font-size: 14px; line-height: 21px;">ORDER INFORMATION:</span></strong></p>
                                                                                            <p style="font-size: 14px; line-height: 21px; margin: 0;"><span style="color: #000000; font-size: 14px; line-height: 21px;">Order  Date: '.$trans_date.' .</span></p>
                                                                                            <p style="font-size: 14px; line-height: 21px; margin: 0;"><span style="color: #000000; font-size: 14px; line-height: 21px;">Order  ID: '.$order_id.' .</span></p>
                                                                                            <p style="font-size: 14px; line-height: 21px; margin: 0;"><span style="color: #000000; font-size: 14px; line-height: 21px;">Paid with: '.$payment_mode.' .</span></p>
                                                                                            <p style="font-size: 14px; line-height: 21px; text-align: left; margin: 0;"><strong><span style="color: #000000; font-size: 14px; line-height: 21px;">Event Name: SKC World - Essentials of Success (EOS) for Youth</span></strong></p>
                                                                                            <p style="font-size: 14px; height: 21px; text-align: left; margin: 0;"> </p>
                                                                                            <p style="font-size: 14px; line-height: 21px; text-align: left; margin: 0;"><strong><span style="color: #000000; font-size: 14px; line-height: 21px;">Payment Details:</span></strong></p>
                                                                                            <p style="font-size: 14px; line-height: 21px; text-align: left; margin: 0;"><span style="color: #000000; font-size: 14px; line-height: 21px;">Ticket: '.$amount.' '.$merchant_param1.'</span></p>
                                                                                            <p style="font-size: 14px; height: 21px; text-align: left; margin: 0;"> </p>
                                                                                            <p style="font-size: 14px; line-height: 21px; text-align: left; margin: 0;"><span style="color: #000000; font-size: 14px; line-height: 21px;">In case you have any queries or need any help call us at SKC Growth-Line: <br>+91 93194 32227<br>
                                                                                                You can also write to us at <a href="mailto:contact@skc.world">contact@skc.world</a></span></p>
                                                                                            <p style="font-size: 14px; height: 21px; text-align: left; margin: 0;"> </p>
                                                                                            <p style="font-size: 14px; line-height: 21px; text-align: left; margin: 0;"><span style="color: #000000; font-size: 14px; line-height: 21px;">
                                                                                                Stay safe and take care,<br><br>
                                                                                                Wish You SUCCESS | SCALE | JOY, <br>
                                                                                                Divya Muraleedhar<br>Lead - Growth Team<br>
                                                                                                <strong>Team SKC World</strong>
                                                                                                </span>
                                                                                            </p>
                                                                                            <p style="font-size: 14px; height: 21px; text-align: left; margin: 0;"> </p>
                                                                                        </div>
                                                                                    </div>
                                                                                    <!--[if mso]></td></tr></table><![endif]-->
                                                                                    
                                                                                    
                                                                                    <!--[if (!mso)&(!IE)]><!-->
                                                                                </div>
                                                                                <!--<![endif]-->
                                                                            </div>
                                                                        </div>
                                                                        <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
                                                                        <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            
                                                            
                                                            
                                                            
                                                        
                                                            <div style="background-color:#f2f3f8 ">
                                                                <div class="block-grid two-up" rel="col-num-container-box-father" data-body-width-father="700px" style="Margin: 0 auto; min-width: 320px; max-width: 700px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #e0e0e0;">
                                                                    <div style="border-collapse: collapse;display: table;width: 100%;background-color:#e0e0e0;">
                                                                        <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:transparent;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:700px"><tr class="layout-full-width" style="background-color:#e0e0e0"><![endif]-->
                                                                        <!--[if (mso)|(IE)]><td align="center" width="350" style="background-color:#e0e0e0;width:350px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:0px; padding-bottom:0px;"><![endif]-->
                                                                        <div class="col num6" rel="col-num-container-box-son" data-body-width-son="350" style="min-width: 320px; max-width: 350px; display: table-cell; vertical-align: top;">
                                                                            <div style="width:100% !important;">
                                                                                <!--[if (!mso)&(!IE)]><!-->
                                                                                <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                                                                                    <!--<![endif]-->
                                                                                    <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 30px; padding-top: 10px; padding-bottom: 10px; font-family: Arial, sans-serif"><![endif]-->
                                                                                    <div style="color:#555555;font-family:Arial, \'Helvetica Neue\', Helvetica, sans-serif;line-height:120%;padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:30px;">
                                                                                        <div style="font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; font-size: 12px; line-height: 14px; color: #555555;">
                                                                                            <p style="font-size: 14px; line-height: 12px; margin: 0;"><span style="font-size: 10px; color: #333333;">Copyright &copy; 2026 SKC.World. All Rights Reserved</span></p>
                                                                                        </div>
                                                                                    </div>
                                                                                    <!--[if mso]></td></tr></table><![endif]-->
                                                                                    <!--[if (!mso)&(!IE)]><!-->
                                                                                </div>
                                                                                <!--<![endif]-->
                                                                            </div>
                                                                        </div>
                                                                        <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
                                                                        <!--[if (mso)|(IE)]></td><td align="center" width="350" style="background-color:#e0e0e0;width:350px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:0px; padding-bottom:0px;"><![endif]-->
                                                                        <div class="col num6" rel="col-num-container-box-son" data-body-width-son="350" style="min-width: 320px; max-width: 350px; display: table-cell; vertical-align: top;">
                                                                            <div style="width:100% !important;">
                                                                                <!--[if (!mso)&(!IE)]><!-->
                                                                                <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                                                                                    <!--<![endif]-->
                                                                                    <table class="social_icons" cellpadding="0" cellspacing="0" width="100%" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" valign="top">
                                                                                        <tbody>
                                                                                            <tr style="vertical-align: top;" valign="top">
                                                                                                <td style="word-break: break-word; vertical-align: top; padding-top: 5px; padding-right: 10px; padding-bottom: 5px; padding-left: 10px; border-collapse: collapse;" valign="top">
                                                                                                    <table class="social_table" align="center" to="to" activate="activate" alignment="alignment" cellpadding="0" cellspacing="0" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: undefined; mso-table-tspace: 0; mso-table-rspace: 0; mso-table-bspace: 0; mso-table-lspace: 0;" valign="top">
                                                                                                        <tbody>
                                                                                                            <tr style="vertical-align: top; display: inline-block; text-align: center;" align="center" valign="top">
                                                                                                                <td style="word-break: break-word; vertical-align: top; padding-bottom: 5px; padding-right: 3px; padding-left: 3px; border-collapse: collapse;" valign="top"><a href="https://www.facebook.com/TheOfficialSKC" target="_blank"><img width="32" height="32" src="https://d2fi4ri5dhpqd1.cloudfront.net/public/resources/social-networks-icon-sets/circle-gray/facebook@2x.png" alt="Facebook" title="Facebook" style="outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; clear: both; height: auto; float: none; border: none; display: block;"></a></td>
                                                                                                               
                                                                                                                <td style="word-break: break-word; vertical-align: top; padding-bottom: 5px; padding-right: 3px; padding-left: 3px; border-collapse: collapse;" valign="top"><a href="https://www.youtube.com/channel/UCXLmfpS5sbGohMqFGvLw4-w" target="_blank"><img width="32" height="32" src="https://d2fi4ri5dhpqd1.cloudfront.net/public/resources/social-networks-icon-sets/circle-gray/youtube@2x.png" alt="YouTube" title="YouTube" style="outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; clear: both; height: auto; float: none; border: none; display: block;"></a></td>
                                                                                                                <td style="word-break: break-word; vertical-align: top; padding-bottom: 5px; padding-right: 3px; padding-left: 3px; border-collapse: collapse;" valign="top"><a href="https://www.linkedin.com/company/theofficialskc/" target="_blank"><img width="32" height="32" src="https://d2fi4ri5dhpqd1.cloudfront.net/public/resources/social-networks-icon-sets/circle-gray/linkedin@2x.png" alt="LinkedIn" title="LinkedIn" style="outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; clear: both; height: auto; float: none; border: none; display: block;"></a></td>
                                                                                                                <td style="word-break: break-word; vertical-align: top; padding-bottom: 5px; padding-right: 3px; padding-left: 3px; border-collapse: collapse;" valign="top"><a href="https://www.instagram.com/skcworld/" target="_blank"><img width="32" height="32" src="https://d2fi4ri5dhpqd1.cloudfront.net/public/resources/social-networks-icon-sets/circle-gray/instagram@2x.png" alt="Instagram" title="Instagram" style="outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; clear: both; height: auto; float: none; border: none; display: block;"></a></td>
                                                                                                            </tr>
                                                                                                        </tbody>
                                                                                                    </table>
                                                                                                </td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                    <!--[if (!mso)&(!IE)]><!-->
                                                                                </div>
                                                                                <!--<![endif]-->
                                                                            </div>
                                                                        </div>
                                                                        <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
                                                                        <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <!--[if (IE)]></div><![endif]-->
                                        </body>
                                        
                                        </html>

                            
                                ';
                                
                                $mail = new PHPMailer(true);
                                $mail2 = new PHPMailer(true);
                                try {
                                    //Server settings
                                    $mail->SMTPDebug = 0;                                       // Enable verbose debug output
                                    $mail->isSMTP();                                            // Set mailer to use SMTP
                                    
                                    $mail->Host       = 'sdseu.linuxhostingserver.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth   = true; // Enable SMTP authentication
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->Username = "noreply1@skc.world"; // SMTP username
        $mail->Password = "Noreply@123"; // SMTP password
        $mail->From = "noreply1@skc.world"; //do NOT fake header. The Username domain and the from domain must be the same.
        $mail->FromName = "SKC";
                                                                 
                                    //$mail->setFrom('noreply@skc.world', 'SKC WORLD');
                                    $mail->addAddress($to);               // Name is optional
                                    $mail->CharSet = 'UTF-8';
                                    
                                    $mail->addBCC('malakar.neeraj@gmail.com');
                                    $mail->addBCC('avdhesh@detecvision.com');
                                    $mail->addBCC('disha@skc.world');
                                    $mail->addBCC('divya.m@skc.world');
                                    $mail->addBCC('preeta.goel@skc.world');
                                    $mail->addBCC('vijay@detecvision.com');
                                    $mail->addBCC('contact@skc.world');
                                    //$mail->addReplyTo('noreply@skc.world', 'skc.world');
                                    
                                    // Attachments
                                    //$mail->addAttachment($attach_pdf);         // Add attachments
                                    //$mail->addAttachment($mpdf->Output('PaymentReceipt.pdf', 'S'), 'PaymentReceipt.pdf','base64','application/pdf');         // Add attachments
                                    $mail->addAttachment('PaymentReceipt.pdf', 'PaymentReceipt.pdf');    // Optional name
                                $mail->CharSet = 'UTF-8';
                                    // Content
                                    $mail->isHTML(true);                                  // Set email format to HTML
                                    $mail->Subject = $subject;
                                    $mail->Body    = $message;
                                    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
                                    $mail->send();
                                    
                                    $mail2->SMTPDebug = 0;
                                    $mail2->isSMTP();
                                     $mail2->Host       = 'sdseu.linuxhostingserver.com';  // Specify main and backup SMTP servers
        $mail2->SMTPAuth   = true; // Enable SMTP authentication
        $mail2->SMTPSecure = 'tls';
        $mail2->Port = 587;
        $mail2->Username = "noreply1@skc.world"; // SMTP username
        $mail2->Password = "Noreply@123"; // SMTP password
        $mail2->From = "noreply1@skc.world"; //do NOT fake header. The Username domain and the from domain must be the same.
        $mail2->FromName = "SKC";
                                    //Recipients
                                    //$mail2->setFrom('noreply@skc.world', 'SKC World');
                                  
                                    //$mail2->addAddress('vivek.kumar@detecvision.com');
                                    $mail2->addBCC('avdhesh@detecvision.com'); 
                                    $mail2->addBCC('malakar.neeraj@gmail.com');
                                    $mail2->addBCC('disha@skc.world');
                                    $mail2->addBCC('divya.m@skc.world');
                                    $mail2->addBCC('preeta.goel@skc.world');
                                    $mail2->addBCC('vijay@detecvision.com');
                                    $mail2->addBCC('contact@skc.world');
                                    //$mail2->addAddress('karamjeet.singh@skc.world', 'skc.world');
                                    
                                    //$mail2->addReplyTo('noreply@skc.world', 'skc.world');
                                    $mail2->isHTML(true);  
                                    $mail2->CharSet = 'UTF-8';
                                    $mail2->Subject = 'Registration Successful – Essentials of Success (EoS) for Youth';
                                    $mail2->Body    = $message2;
                                    $mail2->addAttachment('PaymentReceipt.pdf', 'PaymentReceipt.pdf');    // Optional name
                                    $mail2->send();
                                    
                                    //echo 'Message has been sent';
                                    unlink('PaymentReceipt.pdf');
                                } catch (Exception $e) {
                                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                                }   

                        }
                        else if($order_status==="Aborted")
                        {
                            echo "<div class='failure'>We Were Unable to Process Your Payment. Please Try Again.</div>";
                            $subject = 'Payment Aborted – Essentials of Success (Eos) for Youth';
                            $message = '<table class="nl-container" style="table-layout: fixed;background:#f1f1f1; vertical-align: top; min-width: 320px; Margin: 0 auto; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;" cellpadding="0" cellspacing="0" role="presentation" width="100%" valign="top">
                                    <tbody>
                                        <tr style="vertical-align: top;" valign="top">
                                            <td style="word-break: break-word; vertical-align: top; border-collapse: collapse;" valign="top">
                                                
                                                <div style="background-color:transparent;">
                                                    <div class="block-grid " rel="col-num-container-box-father" data-body-width-father="600px" style="Margin: 0 auto; min-width: 320px; max-width: 600px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #64004e;">
                                                        <div style="border-collapse: collapse;display: table;width: 100%;background-color:#64004e;">
                                                            
                                                            <div class="col num12" rel="col-num-container-box-son" data-body-width-son="600" style="min-width: 320px; max-width: 600px; display: table-cell; vertical-align: top;">
                                                                <div style="width:100% !important;">
                                                                
                                                                    <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                                                                    
                                                                        <div class="img-container center  autowidth " align="center" style="padding-right: 0px; padding-top: 15px; padding-bottom: 15px; padding-left: 0px;">
                                                                            <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr style="line-height:0px"><td style="padding-right: 0px;padding-left: 0px;" align="center"><![endif]-->
                                                                            <div style="font-size:1px;line-height:10px"> </div><img class="center  autowidth " align="center" border="0" src="https://i0.wp.com/www.skc.world/wp-content/uploads/2019/03/logo.png" alt="Image" title="Image" style="outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; clear: both; border: 0; height: auto; float: none; width: 100%; max-width: 161px; display: block;" width="161">
                                                                            <div style="font-size:1px;line-height:10px"> </div>
                                                                            <!--[if mso]></td></tr></table><![endif]-->
                                                                        </div>
                                                                    
                                                                    </div>
                                                                    
                                                                </div>
                                                            </div>
                                                        
                                                        </div>
                                                    </div>
                                                </div>
                                                <div style="background-color:transparent;">
                                                    <div class="block-grid " rel="col-num-container-box-father" data-body-width-father="600px" style="Margin: 0 auto; min-width: 320px; max-width: 600px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word;">
                                                        <div style="border-collapse: collapse;display: table;width: 100%;">
                                                            
                                                            <div class="col num12" rel="col-num-container-box-son" data-body-width-son="600" style="min-width: 320px; max-width: 600px; display: table-cell; vertical-align: top;">
                                                                <div style="width:100% !important;">
                                                                    
                                                                    <div style="border-top:0px solid transparent; background-color:#FFFFFF; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                                                                        
                                                                        <table class="divider" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" role="presentation" valign="top">
                                                                            <tbody>
                                                                                <tr style="vertical-align: top;" valign="top">
                                                                                    <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px; border-collapse: collapse;" valign="top">
                                                                                        <table class="divider_content" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; border-top: 1px solid transparent; height: 0px;" align="center" role="presentation" height="0" valign="top">
                                                                                            <tbody>
                                                                                                <tr style="vertical-align: top;" valign="top">
                                                                                                    <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse;" height="0" valign="top"><span></span></td>
                                                                                                </tr>
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                        
                                                                        <div style="color:#000000;font-family: Tahoma, sans-serif;line-height:120%;padding-top:10px;padding-right:30px;padding-bottom:10px;padding-left:30px; background-color:#FFFFFF;">
                                                                            <div style="font-family:  Tahoma, sans-serif; font-size: 12px; line-height: 14px; color: #000000;">
                                                                                <h4>Namaskar '.$name[0].',</h4>
                                                                                <h3 style=" margin: 0; line-height: 22px;"><strong>Thank you for showing your interest in Essentials of Success (EOS) for Youth <br>
                                                                                    We are sorry to inform you that your payment has aborted! Pls try again! </strong></h3>
                                                                            </div>
                                                                        </div>
                                                                    
                                                                    </div>
                                                                
                                                                </div>
                                                            </div>
                                                        
                                                        </div>
                                                    </div>
                                                </div>
                                                <div style="background-color:transparent;">
                                                    <div class="block-grid " rel="col-num-container-box-father" data-body-width-father="600px" style="Margin: 0 auto; min-width: 320px; max-width: 600px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #FFFFFF;">
                                                        <div style="border-collapse: collapse;display: table;width: 100%;background-color:#FFFFFF;">
                                                        
                                                            <div class="col num12" rel="col-num-container-box-son" data-body-width-son="600" style="min-width: 320px; max-width: 600px; display: table-cell; vertical-align: top;">
                                                                <div style="width:100% !important;">
                                                                    
                                                                    <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                                                                        
                                                                        <div style="color:#555555;font-family: Tahoma, sans-serif;line-height:150%;padding-top:10px;padding-right:30px;padding-bottom:10px;padding-left:30px;">
                                                                            <div style="font-family:  Tahoma, sans-serif; font-size: 12px; line-height: 18px; color: #000000;">
                                                                                
                                                                                <p style="font-size: 14px; height: 21px; text-align: left; margin: 0;">Click the button below to try again.</p>
                                                                                <p><a href="https://skc.world/eos-for-youth/" class="btn" style="padding:10px 20px;background:#64004e;color:#fff;text-decoration: none;">Book now</a></p>
                                                                                <p style="font-size: 14px; text-align: left; margin-bottom: 15px;">You can reach out to one of our growth managers for call support as SKC Growth-Line: +91 93194 32227</p>
                                                                                <p style="font-size: 14px; line-height: 21px; text-align: left; margin: 0;"><span style="color: #000000; font-size: 14px; line-height: 21px;">You can also write to us at <a href="mailto:contact@skc.world">contact@skc.world</a></span></p>                                                              
                                                                                
                                                                                <p style="font-size: 14px; height: 21px; text-align: left; margin: 0;"> </p>
                                                                                <p style="font-size: 14px; line-height: 21px; text-align: left; margin: 0;"><span style="color: #000000; font-size: 14px; line-height: 21px;">Wish You SUCCESS | SCALE | JOY, <br>Divya Muraleedhar<br>Lead - Growth Team</span></p>
                                                                                <p style="font-size: 14px; height: 21px; text-align: left; margin: 0;"> </p>
                                                                            </div>
                                                                        </div>
                                                                        
                                                                    </div>
                                                                
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </td>
                                        </tr>
                                    </tbody>';
                                
                                
                                    $message1= '<table class="nl-container" style="table-layout: fixed;background:#f1f1f1; vertical-align: top; min-width: 320px; Margin: 0 auto; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;" cellpadding="0" cellspacing="0" role="presentation" width="100%" valign="top">
                                    <tbody>
                                        <tr style="vertical-align: top;" valign="top">
                                            <td style="word-break: break-word; vertical-align: top; border-collapse: collapse;" valign="top">
                                                
                                                <div style="background-color:transparent;">
                                                    <div class="block-grid " rel="col-num-container-box-father" data-body-width-father="600px" style="Margin: 0 auto; min-width: 320px; max-width: 600px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #64004e;">
                                                        <div style="border-collapse: collapse;display: table;width: 100%;background-color:#64004e;">
                                                            
                                                            <div class="col num12" rel="col-num-container-box-son" data-body-width-son="600" style="min-width: 320px; max-width: 600px; display: table-cell; vertical-align: top;">
                                                                <div style="width:100% !important;">
                                                                
                                                                    <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                                                                    
                                                                        <div class="img-container center  autowidth " align="center" style="padding-right: 0px; padding-top: 15px; padding-bottom: 15px; padding-left: 0px;">
                                                                            <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr style="line-height:0px"><td style="padding-right: 0px;padding-left: 0px;" align="center"><![endif]-->
                                                                            <div style="font-size:1px;line-height:10px"> </div><img class="center  autowidth " align="center" border="0" src="https://i0.wp.com/www.skc.world/wp-content/uploads/2019/03/logo.png" alt="Image" title="Image" style="outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; clear: both; border: 0; height: auto; float: none; width: 100%; max-width: 161px; display: block;" width="161">
                                                                            <div style="font-size:1px;line-height:10px"> </div>
                                                                            <!--[if mso]></td></tr></table><![endif]-->
                                                                        </div>
                                                                    
                                                                    </div>
                                                                    
                                                                </div>
                                                            </div>
                                                        
                                                        </div>
                                                    </div>
                                                </div>
                                                <div style="background-color:transparent;">
                                                    <div class="block-grid " rel="col-num-container-box-father" data-body-width-father="600px" style="Margin: 0 auto; min-width: 320px; max-width: 600px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word;">
                                                        <div style="border-collapse: collapse;display: table;width: 100%;">
                                                            
                                                            <div class="col num12" rel="col-num-container-box-son" data-body-width-son="600" style="min-width: 320px; max-width: 600px; display: table-cell; vertical-align: top;">
                                                                <div style="width:100% !important;">
                                                                    
                                                                    <div style="border-top:0px solid transparent; background-color:#FFFFFF; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                                                                        
                                                                        <table class="divider" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" role="presentation" valign="top">
                                                                            <tbody>
                                                                                <tr style="vertical-align: top;" valign="top">
                                                                                    <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px; border-collapse: collapse;" valign="top">
                                                                                        <table class="divider_content" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; border-top: 1px solid transparent; height: 0px;" align="center" role="presentation" height="0" valign="top">
                                                                                            <tbody>
                                                                                                <tr style="vertical-align: top;" valign="top">
                                                                                                    <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse;" height="0" valign="top"><span></span></td>
                                                                                                </tr>
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                        
                                                                        <div style="color:#000000;font-family: Tahoma, sans-serif;line-height:120%;padding-top:10px;padding-right:30px;padding-bottom:10px;padding-left:30px; background-color:#FFFFFF;">
                                                                            <div style="font-family:  Tahoma, sans-serif; font-size: 12px; line-height: 14px; color: #000000;">
                                                                                <h4>Namaskar ,</h4>
                                                                                <h4 style=" margin: 0; line-height: 22px;"><strong>
                                                                                '.$name[0].' tried to make a purchase but Aborted! </strong></h4>
                                                                                
                                                                            </div>
                                                                        </div>
                                                                    
                                                                    </div>
                                                                
                                                                </div>
                                                            </div>
                                                        
                                                        </div>
                                                    </div>
                                                </div>
                                                <div style="background-color:transparent;">
                                                    <div class="block-grid " rel="col-num-container-box-father" data-body-width-father="600px" style="Margin: 0 auto; min-width: 320px; max-width: 600px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #FFFFFF;">
                                                        <div style="border-collapse: collapse;display: table;width: 100%;background-color:#FFFFFF;">
                                                        
                                                            <div class="col num12" rel="col-num-container-box-son" data-body-width-son="600" style="min-width: 320px; max-width: 600px; display: table-cell; vertical-align: top;">
                                                                <div style="width:100% !important;">
                                                                    
                                                                    <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                                                                        
                                                                        <div style="color:#555555;font-family: Tahoma, sans-serif;line-height:150%;padding-top:10px;padding-right:30px;padding-bottom:10px;padding-left:30px;">
                                                                            <div style="font-family:  Tahoma, sans-serif; font-size: 12px; line-height: 18px; color: #000000;">
                                                                                ';
                                                                            $message1 .= '<div style="padding:0 20px;">
                                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #dddcdd;border-collapse:collapse;text-align:left">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td colspan="2" style="background:#64004e;padding:.8rem;color:#fff;font-weight:bold">
                                                                                        Attendee 1:
                                                                                    </td>
                                                                                </tr>';
                                                                    
                                                                    if (!empty($billing_name)) {
                                                                        $message1 .= '<tr>
                                                                            <td style="padding:.6rem;font-weight:bold;width:30%">Participant'.$comma.' Name</td>
                                                                            <td style="padding:.6rem;width:70%"> : '.$billing_name.'</td>
                                                                        </tr>';
                                                                    }
                                                                    
                                                                    if (!empty($billing_email)) {
                                                                        $message1 .= '<tr>
                                                                            <td style="padding:.6rem;font-weight:bold;width:30%">Parent'.$comma.' Email Address</td>
                                                                            <td style="padding:.6rem;width:70%"> : '.$billing_email.'</td>
                                                                        </tr>';
                                                                    }
                                                                    
                                                                    if (!empty($billing_tel)) {
                                                                        $message1 .= '<tr>
                                                                            <td style="padding:.6rem;font-weight:bold;width:30%">Parent'.$comma.' Contact Number</td>
                                                                            <td style="padding:.6rem;width:70%"> : '.$billing_tel.'</td>
                                                                        </tr>';
                                                                    }
                                                                    
                                                                    if (!empty($row['age'])) {
                                                                        $message1 .= '<tr>
                                                                            <td style="padding:.6rem;font-weight:bold;width:30%">Age</td>
                                                                            <td style="padding:.6rem;width:70%"> : '.$row['age'].'</td>
                                                                        </tr>';
                                                                    }
                                                                    
                                                                    
                                                                  
                                                                    
                                                                    if (!empty($row['organisation'])) {
                                                                        $message1 .= '<tr>
                                                                            <td style="padding:.6rem;font-weight:bold;width:30%">School/ College/ University</td>
                                                                            <td style="padding:.6rem;width:70%"> : '.$row['organisation'].'</td>
                                                                        </tr>';
                                                                    }
                                                                    
                                                                    if (!empty($row['questions'])) {
                                                                        $message1 .= '<tr>
                                                                            <td style="padding:.6rem;font-weight:bold;width:30%">Questions</td>
                                                                            <td style="padding:.6rem;width:70%"> : '.$row['questions'].'</td>
                                                                        </tr>';
                                                                    }
                                                                    
                                                                    if (!empty($row['referred_by'])) {
                                                                        $message1 .= '<tr>
                                                                            <td style="padding:.6rem;font-weight:bold;width:30%">Referred By</td>
                                                                            <td style="padding:.6rem;width:70%"> : '.$row['referred_by'].'</td>
                                                                        </tr>';
                                                                    }
                                                                    
                                                                    if (!empty($row['location'])) {
                                                                        $message1 .= '<tr>
                                                                            <td style="padding:.6rem;font-weight:bold;width:30%">Location</td>
                                                                            <td style="padding:.6rem;width:70%"> : '.$row['location'].'</td>
                                                                        </tr>';
                                                                    }
                                                                    if (!empty($row['message'])) {
                                                                            $message1 .= '<tr>
                                                                                <td style="padding:.6rem .6rem .3rem;font-weight:bold;width:30%">Your question / comment (if any)</td>
                                                                                <td style="padding:.6rem .6rem .3rem;width:70%"> : '.$row['message'].'</td>
                                                                            </tr>';
                                                                        }
                                                                    
                                                                    $message1 .= '</tbody></table></div>';

                                                                                 
                                                                                @$name3 = explode(",",$row['other_name']);
                                                                                @$other_profession = explode(",",$row['other_profession']);
                                                                                @$other_age = explode(",",$row['other_age']);
                                                                                
                                                                                
                                                                                @$other_email = explode(",",$row['other_email']);
                                                                                @$other_phone = explode(",",$row['other_phone']);
                                                                                
                                                                                @$other_questions = explode(",",$row['other_questions']);
                                                                                @$other_designation = explode(",",$row['other_designation']);
                                                                               
                                    
                                                                                
                                                                            $message1.='</div>
                                                                        </div>
                                                                        
                                                                    </div>
                                                                
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </td>
                                        </tr>
                                    </tbody>';
                                
                                
                            // Instantiation and passing `true` enables exceptions
                            $mail = new PHPMailer(true);
                            $mail2 = new PHPMailer(true);
                            try {
                                //Server settings
                                $mail->SMTPDebug = 0;                                       // Enable verbose debug output
                                $mail->isSMTP();                                            // Set mailer to use SMTP
                                  $mail->Host       = 'sdseu.linuxhostingserver.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth   = true; // Enable SMTP authentication
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->Username = "noreply1@skc.world"; // SMTP username
        $mail->Password = "Noreply@123"; // SMTP password
        $mail->From = "noreply1@skc.world"; //do NOT fake header. The Username domain and the from domain must be the same.
        $mail->FromName = "SKC";                             // TCP port to connect to
                            
                                //Recipients
                                //$mail->setFrom('noreply@skc.world', 'SKC WORLD');
                                
                                $mail->addAddress($to);               // Name is optional
                                $mail->addBCC('malakar.neeraj@gmail.com');
                                $mail->addBCC('disha@skc.world');
                                $mail->addBCC('divya.m@skc.world');
                                $mail->addBCC('preeta.goel@skc.world');
                                $mail->addBCC('vijay@detecvision.com');
                                $mail->addBCC('contact@skc.world');
                                //$mail->addReplyTo('noreply@skc.world', 'skc.world');
                                //$mail->addCC('cc@example.com');
                                //$mail->addBCC('bcc@example.com');
                            
                                // Attachments
                                //$mail->addAttachment($attach_pdf);         // Add attachments
                                //$mail->addAttachment($mpdf->Output('PaymentReceipt.pdf', 'S'), 'PaymentReceipt.pdf','base64','application/pdf');         // Add attachments
                                //$mail->addAttachment('PaymentReceipt.pdf', 'PaymentReceipt.pdf');    // Optional name
                            
                                // Content
                                $mail->CharSet = 'UTF-8';
                                $mail->isHTML(true);                                  // Set email format to HTML
                                $mail->Subject = $subject;
                                $mail->Body    = $message;
                                $mail->AltBody = '';
                              
                                
                                $mail->Body    = $message1;
                                $mail->addAddress('contact@skc.world', 'skc.world');     
                                $mail->addBCC('malakar.neeraj@gmail.com');
                                $mail->addBCC('disha@skc.world');
                                $mail->addBCC('divya.m@skc.world');
                                $mail->addBCC('preeta.goel@skc.world');
                                $mail->addBCC('vijay@detecvision.com');
                                $mail->send();
                                
                                
                                  $mail2->Host       = 'sdseu.linuxhostingserver.com';  // Specify main and backup SMTP servers
        $mail2->SMTPAuth   = true; // Enable SMTP authentication
        $mail2->SMTPSecure = 'tls';
        $mail2->Port = 587;
        $mail2->Username = "noreply1@skc.world"; // SMTP username
        $mail2->Password = "Noreply@123"; // SMTP password
        $mail2->From = "noreply1@skc.world"; //do NOT fake header. The Username domain and the from domain must be the same.
        $mail2->FromName = "SKC";
                                //Recipients
                                //$mail2->setFrom('noreply@skc.world', 'SKC World');
                                $mail2->addAddress('contact@skc.world');     // Add a recipient avdhesh@detecvision.com
                              
                                
                                $mail2->addBCC('avdhesh@detecvision.com'); 
                                $mail2->addBCC('malakar.neeraj@gmail.com');
                                $mail2->addBCC('disha@skc.world');
                                $mail2->addBCC('divya.m@skc.world');
                                $mail2->addBCC('preeta.goel@skc.world');
                                $mail2->addBCC('vijay@detecvision.com');

                                $mail2->isHTML(true);                                  // Set email format to HTML
                                $mail2->Subject = 'Essentials of Success (EOS) for Youth Registration Failed';
                                $mail2->Body    = $message1;
                                $mail2->send();
                                //echo 'Message has been sent';
                            } catch (Exception $e) {
                                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                            }   
                        }
                        else if($order_status==="Failure")
                        {
                            echo "<div class='failure'>We Were Unable to Process Your Payment. Please Try Again.</div>";
                            $subject = 'Payment Aborted – Essentials of Success (Eos) for Youth';
                            $message1= '<table class="nl-container" style="table-layout: fixed;background:#f1f1f1; vertical-align: top; min-width: 320px; Margin: 0 auto; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;" cellpadding="0" cellspacing="0" role="presentation" width="100%" valign="top">
                                    <tbody>
                                        <tr style="vertical-align: top;" valign="top">
                                            <td style="word-break: break-word; vertical-align: top; border-collapse: collapse;" valign="top">
                                                
                                                <div style="background-color:transparent;">
                                                    <div class="block-grid " rel="col-num-container-box-father" data-body-width-father="600px" style="Margin: 0 auto; min-width: 320px; max-width: 600px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #64004e;">
                                                        <div style="border-collapse: collapse;display: table;width: 100%;background-color:#64004e;">
                                                            
                                                            <div class="col num12" rel="col-num-container-box-son" data-body-width-son="600" style="min-width: 320px; max-width: 600px; display: table-cell; vertical-align: top;">
                                                                <div style="width:100% !important;">
                                                                
                                                                    <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                                                                    
                                                                        <div class="img-container center  autowidth " align="center" style="padding-right: 0px; padding-top: 15px; padding-bottom: 15px; padding-left: 0px;">
                                                                            <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr style="line-height:0px"><td style="padding-right: 0px;padding-left: 0px;" align="center"><![endif]-->
                                                                            <div style="font-size:1px;line-height:10px"> </div><img class="center  autowidth " align="center" border="0" src="https://i0.wp.com/www.skc.world/wp-content/uploads/2019/03/logo.png" alt="Image" title="Image" style="outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; clear: both; border: 0; height: auto; float: none; width: 100%; max-width: 161px; display: block;" width="161">
                                                                            <div style="font-size:1px;line-height:10px"> </div>
                                                                            <!--[if mso]></td></tr></table><![endif]-->
                                                                        </div>
                                                                    
                                                                    </div>
                                                                    
                                                                </div>
                                                            </div>
                                                        
                                                        </div>
                                                    </div>
                                                </div>
                                                <div style="background-color:transparent;">
                                                    <div class="block-grid " rel="col-num-container-box-father" data-body-width-father="600px" style="Margin: 0 auto; min-width: 320px; max-width: 600px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word;">
                                                        <div style="border-collapse: collapse;display: table;width: 100%;">
                                                            
                                                            <div class="col num12" rel="col-num-container-box-son" data-body-width-son="600" style="min-width: 320px; max-width: 600px; display: table-cell; vertical-align: top;">
                                                                <div style="width:100% !important;">
                                                                    
                                                                    <div style="border-top:0px solid transparent; background-color:#FFFFFF; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                                                                        
                                                                        <table class="divider" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" role="presentation" valign="top">
                                                                            <tbody>
                                                                                <tr style="vertical-align: top;" valign="top">
                                                                                    <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px; border-collapse: collapse;" valign="top">
                                                                                        <table class="divider_content" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; border-top: 1px solid transparent; height: 0px;" align="center" role="presentation" height="0" valign="top">
                                                                                            <tbody>
                                                                                                <tr style="vertical-align: top;" valign="top">
                                                                                                    <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse;" height="0" valign="top"><span></span></td>
                                                                                                </tr>
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                        
                                                                        <div style="color:#000000;font-family: Tahoma, sans-serif;line-height:120%;padding-top:10px;padding-right:30px;padding-bottom:10px;padding-left:30px; background-color:#FFFFFF;">
                                                                            <div style="font-family:  Tahoma, sans-serif; font-size: 12px; line-height: 14px; color: #000000;">
                                                                                <h4>Namaskar,</h4>
                                                                                <h2 style=" margin: 0; line-height: 22px;"><strong>
                                                                                '.$name[0].' tried to make a purchase <br> But Failure! </strong></h2>
                                                                                
                                                                            </div>
                                                                        </div>
                                                                    
                                                                    </div>
                                                                
                                                                </div>
                                                            </div>
                                                        
                                                        </div>
                                                    </div>
                                                </div>
                                                <div style="background-color:transparent;">
                                                    <div class="block-grid " rel="col-num-container-box-father" data-body-width-father="600px" style="Margin: 0 auto; min-width: 320px; max-width: 600px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #FFFFFF;">
                                                        <div style="border-collapse: collapse;display: table;width: 100%;background-color:#FFFFFF;">
                                                        
                                                            <div class="col num12" rel="col-num-container-box-son" data-body-width-son="600" style="min-width: 320px; max-width: 600px; display: table-cell; vertical-align: top;">
                                                                <div style="width:100% !important;">
                                                                    
                                                                    <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                                                                        
                                                                        <div style="color:#555555;font-family: Tahoma, sans-serif;line-height:150%;padding-top:10px;padding-right:30px;padding-bottom:10px;padding-left:30px;">
                                                                            <div style="font-family:  Tahoma, sans-serif; font-size: 12px; line-height: 18px; color: #000000;">
                                                                                ';
                                                                          $message1 .= '
                                                                        <div style="padding:0 20px;">
                                                                          <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse; border:1px solid #CCCCCC; text-align:left;">
                                                                            <tbody>
                                                                              <tr>
                                                                                <td colspan="2" style="background:#64004e; padding:10px; color:#fff; font-weight:bold; border:1px solid #CCCCCC;">
                                                                                  Attendee 1
                                                                                </td>
                                                                              </tr>';
                                                                        
                                                                        if (!empty($billing_name)) {
                                                                          $message1 .= '
                                                                              <tr>
                                                                                <td style="padding:8px; font-weight:bold; width:30%; border:1px solid #CCCCCC;">Participant'.$comma.' Name</td>
                                                                                <td style="padding:8px; width:70%; border:1px solid #CCCCCC;">'.$billing_name.'</td>
                                                                              </tr>';
                                                                        }
                                                                        
                                                                        if (!empty($billing_email)) {
                                                                          $message1 .= '
                                                                              <tr>
                                                                                <td style="padding:8px; font-weight:bold; border:1px solid #CCCCCC;">Parent'.$comma.' Email Address</td>
                                                                                <td style="padding:8px; border:1px solid #CCCCCC;">'.$billing_email.'</td>
                                                                              </tr>';
                                                                        }
                                                                        
                                                                        if (!empty($billing_tel)) {
                                                                          $message1 .= '
                                                                              <tr>
                                                                                <td style="padding:8px; font-weight:bold; border:1px solid #CCCCCC;">Parent'.$comma.' Contact Number</td>
                                                                                <td style="padding:8px; border:1px solid #CCCCCC;">'.$billing_tel.'</td>
                                                                              </tr>';
                                                                        }
                                                                        
                                                                        if (!empty($row['age'])) {
                                                                          $message1 .= '
                                                                              <tr>
                                                                                <td style="padding:8px; font-weight:bold; border:1px solid #CCCCCC;">Age</td>
                                                                                <td style="padding:8px; border:1px solid #CCCCCC;">'.$row['age'].'</td>
                                                                              </tr>';
                                                                        }
                                                                        
                                                                        
                                                                       
                                                                       
                                                                        
                                                                        if (!empty($row['organisation'])) {
                                                                          $message1 .= '
                                                                              <tr>
                                                                                <td style="padding:8px; font-weight:bold; border:1px solid #CCCCCC;">School / College / University</td>
                                                                                <td style="padding:8px; border:1px solid #CCCCCC;">'.$row['organisation'].'</td>
                                                                              </tr>';
                                                                        }
                                                                        
                                                                        if (!empty($row['questions'])) {
                                                                          $message1 .= '
                                                                              <tr>
                                                                                <td style="padding:8px; font-weight:bold; border:1px solid #CCCCCC;">Questions</td>
                                                                                <td style="padding:8px; border:1px solid #CCCCCC;">'.$row['questions'].'</td>
                                                                              </tr>';
                                                                        }
                                                                        
                                                                        if (!empty($row['referred_by'])) {
                                                                          $message1 .= '
                                                                              <tr>
                                                                                <td style="padding:8px; font-weight:bold; border:1px solid #CCCCCC;">Referred By</td>
                                                                                <td style="padding:8px; border:1px solid #CCCCCC;">'.$row['referred_by'].'</td>
                                                                              </tr>';
                                                                        }
                                                                        
                                                                        if (!empty($row['location'])) {
                                                                          $message1 .= '
                                                                              <tr>
                                                                                <td style="padding:8px; font-weight:bold; border:1px solid #CCCCCC;">Location</td>
                                                                                <td style="padding:8px; border:1px solid #CCCCCC;">'.$row['location'].'</td>
                                                                              </tr>';
                                                                        }
                                                                        if (!empty($row['message'])) {
                                                                            $message1 .= '<tr>
                                                                                <td style="padding:.6rem .6rem .3rem;font-weight:bold;width:30%">Your question / comment (if any)</td>
                                                                                <td style="padding:.6rem .6rem .3rem;width:70%"> : '.$row['message'].'</td>
                                                                            </tr>';
                                                                        }
                                                                        $message1 .= '
                                                                            </tbody>
                                                                          </table>
                                                                        </div>';

                                                                                 
                                                                                @$name3 = explode(",",$row['other_name']);
                                                                                @$other_profession = explode(",",$row['other_profession']);
                                                                                @$other_age = explode(",",$row['other_age']);
                                                                                
                                                                                
                                                                                @$other_email = explode(",",$row['other_email']);
                                                                                @$other_phone = explode(",",$row['other_phone']);
                                                                                
                                                                                @$other_questions = explode(",",$row['other_questions']);
                                                                                @$other_designation = explode(",",$row['other_designation']);
                                                                               
                                    
                                                                                
                                                                            $message1.='</div>
                                                                        </div>
                                                                        
                                                                    </div>
                                                                
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </td>
                                        </tr>
                                    </tbody>';
                            $message = '<table class="nl-container" style="table-layout: fixed;background:#f1f1f1; vertical-align: top; min-width: 320px; Margin: 0 auto; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;" cellpadding="0" cellspacing="0" role="presentation" width="100%" valign="top">
                                    <tbody>
                                        <tr style="vertical-align: top;" valign="top">
                                            <td style="word-break: break-word; vertical-align: top; border-collapse: collapse;" valign="top">
                                                
                                                <div style="background-color:transparent;">
                                                    <div class="block-grid " rel="col-num-container-box-father" data-body-width-father="600px" style="Margin: 0 auto; min-width: 320px; max-width: 600px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #64004e;">
                                                        <div style="border-collapse: collapse;display: table;width: 100%;background-color:#64004e;">
                                                            
                                                            <div class="col num12" rel="col-num-container-box-son" data-body-width-son="600" style="min-width: 320px; max-width: 600px; display: table-cell; vertical-align: top;">
                                                                <div style="width:100% !important;">
                                                                
                                                                    <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                                                                    
                                                                        <div class="img-container center  autowidth " align="center" style="padding-right: 0px; padding-top: 15px; padding-bottom: 15px; padding-left: 0px;">
                                                                            <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr style="line-height:0px"><td style="padding-right: 0px;padding-left: 0px;" align="center"><![endif]-->
                                                                            <div style="font-size:1px;line-height:10px"> </div><img class="center  autowidth " align="center" border="0" src="https://i0.wp.com/www.skc.world/wp-content/uploads/2019/03/logo.png" alt="Image" title="Image" style="outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; clear: both; border: 0; height: auto; float: none; width: 100%; max-width: 161px; display: block;" width="161">
                                                                            <div style="font-size:1px;line-height:10px"> </div>
                                                                            <!--[if mso]></td></tr></table><![endif]-->
                                                                        </div>
                                                                    
                                                                    </div>
                                                                    
                                                                </div>
                                                            </div>
                                                        
                                                        </div>
                                                    </div>
                                                </div>
                                                <div style="background-color:transparent;">
                                                    <div class="block-grid " rel="col-num-container-box-father" data-body-width-father="600px" style="Margin: 0 auto; min-width: 320px; max-width: 600px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word;">
                                                        <div style="border-collapse: collapse;display: table;width: 100%;">
                                                            
                                                            <div class="col num12" rel="col-num-container-box-son" data-body-width-son="600" style="min-width: 320px; max-width: 600px; display: table-cell; vertical-align: top;">
                                                                <div style="width:100% !important;">
                                                                    
                                                                    <div style="border-top:0px solid transparent; background-color:#FFFFFF; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                                                                        
                                                                        <table class="divider" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" role="presentation" valign="top">
                                                                            <tbody>
                                                                                <tr style="vertical-align: top;" valign="top">
                                                                                    <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px; border-collapse: collapse;" valign="top">
                                                                                        <table class="divider_content" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; border-top: 1px solid transparent; height: 0px;" align="center" role="presentation" height="0" valign="top">
                                                                                            <tbody>
                                                                                                <tr style="vertical-align: top;" valign="top">
                                                                                                    <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse;" height="0" valign="top"><span></span></td>
                                                                                                </tr>
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                        
                                                                        <div style="color:#000000;font-family: Tahoma, sans-serif;line-height:120%;padding-top:10px;padding-right:30px;padding-bottom:10px;padding-left:30px; background-color:#FFFFFF;">
                                                                            <div style="font-family:  Tahoma, sans-serif; font-size: 12px; line-height: 14px; color: #000000;">
                                                                                <h4>Namaskar '.$name[0].',</h4>
                                                                                <h2 style=" margin: 0; line-height: 22px;"><strong>Uh- Ohh! <br>
                                                                                    Your payment has failed ! Pls try again! </strong>,</h2>
                                                                            </div>
                                                                        </div>
                                                                    
                                                                    </div>
                                                                
                                                                </div>
                                                            </div>
                                                        
                                                        </div>
                                                    </div>
                                                </div>
                                                <div style="background-color:transparent;">
                                                    <div class="block-grid " rel="col-num-container-box-father" data-body-width-father="600px" style="Margin: 0 auto; min-width: 320px; max-width: 600px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #FFFFFF;">
                                                        <div style="border-collapse: collapse;display: table;width: 100%;background-color:#FFFFFF;">
                                                        
                                                            <div class="col num12" rel="col-num-container-box-son" data-body-width-son="600" style="min-width: 320px; max-width: 600px; display: table-cell; vertical-align: top;">
                                                                <div style="width:100% !important;">
                                                                    
                                                                    <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                                                                        
                                                                        <div style="color:#555555;font-family: Tahoma, sans-serif;line-height:150%;padding-top:10px;padding-right:30px;padding-bottom:10px;padding-left:30px;">
                                                                            <div style="font-family:  Tahoma, sans-serif; font-size: 12px; line-height: 18px; color: #000000;">
                                                                            
                                                                                <p style="font-size: 14px; height: 21px; text-align: left; margin: 0;">It is sad your payment failed, but worry not, click the button below to try again.</p>
                                                                                <p><a href="https://skc.world/eos-for-youth/" class="btn" style="padding:10px 20px;background:#64004e;color:#fff; text-decoration:none">Book now</a></p>
                                                                                <p style="font-size: 14px;  text-align: left; margin-bottom: 15px;">You can reach out to one of our growth managers for support at SKC Growth-Line: <br>+91 93194 32227</p>
                                                                                <p style="font-size: 14px; line-height: 21px; text-align: left; margin: 0;"><span style="color: #000000; font-size: 14px; line-height: 21px;">You can also write to us at <a href="mailto:contact@skc.world">contact@skc.world</a></span></p>
                                                                                <p style="font-size: 14px; height: 21px; text-align: left; margin: 0;"> </p>
                                                                                <p style="font-size: 14px; line-height: 21px; text-align: left; margin: 0;"><span style="color: #000000; font-size: 14px; line-height: 21px;">Wish You SUCCESS | SCALE | JOY, <br>
                                                                                Divya Muraleedhar<br>Lead - Growth Team</span></p>
                                                                                <p style="font-size: 14px; height: 21px; text-align: left; margin: 0;"> </p>
                                                                            </div>
                                                                        </div>
                                                                        
                                                                    </div>
                                                                
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </td>
                                        </tr>
                                    </tbody>
                                ';
                            // Sending email
                            //mail('contact@skc.world', $subject, $message, $headers);
                             $mail = new PHPMailer(true);
                            $mail2 = new PHPMailer(true);
                            try {
                                //Server settings
                                $mail->SMTPDebug = 0;                                       // Enable verbose debug output
                                $mail->isSMTP();                                            // Set mailer to use SMTP
                                 $mail->Host       = 'sdseu.linuxhostingserver.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth   = true; // Enable SMTP authentication
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->Username = "noreply1@skc.world"; // SMTP username
        $mail->Password = "Noreply@123"; // SMTP password
        $mail->From = "noreply1@skc.world"; //do NOT fake header. The Username domain and the from domain must be the same.
        $mail->FromName = "SKC";                              // TCP port to connect to
                            
                                //Recipients
                                //$mail->setFrom('noreply@skc.world', 'SKC WORLD');
                                $mail->addAddress('contact@skc.world', 'skc.world');     // Add a recipient
                                $mail->addAddress($to);    
                                $mail->addBCC('malakar.neeraj@gmail.com');
                                $mail->addBCC('disha@skc.world');
                                $mail->addBCC('divya.m@skc.world');
                                $mail->addBCC('preeta.goel@skc.world');
                                $mail->addBCC('vijay@detecvision.com');
                               
                                    // Name is optional
                                //$mail->addReplyTo('noreply@skc.world', 'skc.world');
                                //$mail->addCC('cc@example.com');
                                //$mail->addBCC('bcc@example.com');
                            
                                // Attachments
                                //$mail->addAttachment($attach_pdf);         // Add attachments
                                //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
                            $mail->CharSet = 'UTF-8';
                                // Content
                                $mail->isHTML(true);                                  // Set email format to HTML
                                $mail->Subject = $subject;
                                $mail->Body    = $message;
                                $mail->AltBody = '';
                                $mail->send();
                                
                                
                                $mail2->Host       = 'skc.world';  // Specify main and backup SMTP servers
                                $mail2->SMTPAuth   = true;                                   // Enable SMTP authentication
                                $mail2->Username   = 'noreply1@skc.world';                     // SMTP username
                                $mail2->Password   = 'Noreply@123';                                // SMTP password
                                //$mail2->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
                                //$mail2->Port       = 587; 
                                $mail2->From = 'noreply1@skc.world';
                                $mail2->FromName = 'skc.world';
                                //Recipients
                                //$mail2->setFrom('noreply@skc.world', 'SKC World');
                                
                                $mail2->CharSet = 'UTF-8';
                                $mail2->addAddress('contact@skc.world');     
                                $mail2->addBCC('avdhesh@detecvision.com');
                                $mail2->addBCC('malakar.neeraj@gmail.com');
                                $mail2->addBCC('disha@skc.world');
                                $mail2->addBCC('divya.m@skc.world');
                                $mail2->addBCC('preeta.goel@skc.world');
                                $mail2->addBCC('vijay@detecvision.com');
                               
                                
                                //$mail2->addBCC('vivek.kumar@detecvision.com');
                                //$mail2->addReplyTo('noreply@skc.world', 'skc.world');
                                $mail2->isHTML(true);                                  // Set email format to HTML
                                $mail2->Subject = 'Registration Failed – Essentials of Success';
                                $mail2->CharSet = 'UTF-8';

                                $mail2->Body    = $message1;
                                $mail2->send();
                                
                               
                                //echo 'Message has been sent';
                            } catch (Exception $e) {
                                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                            }
                        }
                        else
                        {
                            echo "<div class='failure'>Security Error. Illegal access detected</div>";
                        
                        
                        }
                    
                        
                   ?>
               
                           <div class="ticket-details-outer">
                              <div class="ticket-box-one">
                                    <h3>Ticket Details</h3>
                                    <p>Participant's Name: <?=@$billing_name?> <?php if(!empty($merchant_param2)){ echo '+ ('.@count($count_person).')';} ?></p>
                                    <p>Parent's Email: <?=@$billing_email?></p>
                                    <p>Parent's Contact Number: <?=@$billing_tel?></p>
                                    <p>Amount: <?=@$amount?>/-</p>
                                    <p>Status: <strong><?php if($order_status==="Success"){ echo '<span class="success">';}else{ echo '<span class="failure">';} echo @$order_status;?><span></strong></p>
                                     <p>Booking Date: <?php echo @$newDate = date("jS F Y", strtotime($trans_date));?> 
                                             <?//php echo @$newDate = date("g:ia l", strtotime($trans_date));?>
                                             </p>
                              </div>
                              <div class="ticket-box-one ticket-bor-left">
                                    <h3>Essentials of Success</h3>
                                        <!--    <ul>-->
                                            
                                        <!--    <li>-->
                                        <!--            Date: <br>-->
                                        <!--            14th & 15th March, 2023<br>-->
                                                
                                        <!--    </li>-->
                                        <!--</ul>-->
                              </div>
                           </div>
                           
                          <p class="ticket-contact">For more information please  write to <a href="#">contact@skc.world </a> or call us at SKC Growth-Line: +91-9319432227</p>

                          <p class="big-btn"> <?php if($order_status!=="Success"){ echo '<a class="btn-one" href="https://skc.world/eos-for-youth/"><span>Try Again</span></a>';}?></p>
                          </fieldset>
                     </div>
                  </div>
                    </div>
               </div>
               
            </div>
      </section>

      <!--ticket confirmation details-->





<script>
    $(window).scroll(function(){
    if ($(window).scrollTop() >= 10) {
        $('.navbar').addClass('fixed-header');
    }
    else {
        $('.navbar').removeClass('fixed-header');
    }
});
</script>

      <!-- OWL -->
      <script>
         $(document).ready(function() {
           $('.owl-carousel').owlCarousel({
             loop: true,
             margin: 10,
             responsiveClass: true,
             responsive: {
               0: {
                 items: 1,
                 nav: true
               },
               600: {
                 items: 2,
                 nav: false
               },
               1000: {
                 items: 3,
                 nav: true,
                 loop: false,
                 margin: 20
               }
             }
           })
         })
      </script>
      <!-- OWL -->

      <!-- Accordian -->
      <script>
         var acc = document.getElementsByClassName("accordion-1");
         var i;
         
         for (i = 0; i < acc.length; i++) {
           acc[i].addEventListener("click", function() {
             this.classList.toggle("active-1");
             var panel = this.nextElementSibling;
             if (panel.style.maxHeight){
               panel.style.maxHeight = null;
             } else {
               panel.style.maxHeight = panel.scrollHeight + "px";
             } 
           });
         }
      </script>
      <!-- Accordian -->

      <!-- vendors -->
      <script src="owlcarousel/js/highlight.js"></script>
      <script src="owlcarousel/js/app.js"></script>
      <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
      <!-- Accordian -->
      
      <!--Model-->
      <script>
         $(document).ready(function(){
           $("#myBtn").click(function(){
             $("#myModal").modal();
           });
         });
      </script>

   <script>
      $(document).ready(function(){
        $("#BookNow").click(function(){
          $("#Book-div").slideToggle("1000");
        });
      });
   </script>
      
      <script data-pagespeed-no-defer="">//<![CDATA[
(function(){function f(b){var a=window;if(a.addEventListener)a.addEventListener("load",b,!1);else if(a.attachEvent)a.attachEvent("onload",b);else{var c=a.onload;a.onload=function(){b.call(this);c&&c.call(this)}}};window.pagespeed=window.pagespeed||{};var k=window.pagespeed;function l(b,a,c,g,h){this.h=b;this.i=a;this.l=c;this.j=g;this.b=h;this.c=[];this.a=0}l.prototype.f=function(b){for(var a=0;250>a&&this.a<this.b.length;++a,++this.a)try{document.querySelector(this.b[this.a])&&this.c.push(this.b[this.a])}catch(c){}this.a<this.b.length?window.setTimeout(this.f.bind(this),0,b):b()};
k.g=function(b,a,c,g,h){if(document.querySelector&&Function.prototype.bind){var d=new l(b,a,c,g,h);f(function(){window.setTimeout(function(){d.f(function(){for(var a="oh="+d.l+"&n="+d.j,a=a+"&cs=",b=0;b<d.c.length;++b){var c=0<b?",":"",c=c+encodeURIComponent(d.c[b]);if(131072<a.length+c.length)break;a+=c}k.criticalCssBeaconData=a;var b=d.h,c=d.i,e;if(window.XMLHttpRequest)e=new XMLHttpRequest;else if(window.ActiveXObject)try{e=new ActiveXObject("Msxml2.XMLHTTP")}catch(m){try{e=new ActiveXObject("Microsoft.XMLHTTP")}catch(n){}}e&&
(e.open("POST",b+(-1==b.indexOf("?")?"?":"&")+"url="+encodeURIComponent(c)),e.setRequestHeader("Content-Type","application/x-www-form-urlencoded"),e.send(a))})},0)})}};k.criticalCssBeaconInit=k.g;})();
pagespeed.selectors=[".pp_default",".video-container",".video-container iframe"];pagespeed.criticalCssBeaconInit('/mod_pagespeed_beacon','https://www.skc.world/samarth/','jGxiK-iUAQ','lAqnre8GKfQ',pagespeed.selectors);
//]]></script>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-88837334-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-88837334-1');
</script>

<!--Start of Tawk.to Script-->
<script type="text/javascript">
// var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
// (function(){
// var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
// s1.async=true;
// s1.src='https://embed.tawk.to/5c8a37fb101df77a8be289f9/default';
// s1.charset='UTF-8';
// s1.setAttribute('crossorigin','*');
// s0.parentNode.insertBefore(s1,s0);
// })();
</script>
<!--End of Tawk.to Script-->

<!-- Global site tag (gtag.js) - Google Ads: 868997888 -->
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-868997888"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'AW-868997888');
</script>

<script type="text/javascript">
_linkedin_partner_id = "294299";
window._linkedin_data_partner_ids = window._linkedin_data_partner_ids || [];
window._linkedin_data_partner_ids.push(_linkedin_partner_id);
</script><script type="text/javascript">
(function(){var s = document.getElementsByTagName("script")[0];
var b = document.createElement("script");
b.type = "text/javascript";b.async = true;
b.src = "https://snap.licdn.com/li.lms-analytics/insight.min.js";
s.parentNode.insertBefore(b, s);})();
</script>
<noscript>
<img height="1" width="1" style="display:none;" alt="" src="https://dc.ads.linkedin.com/collect/?pid=294299&fmt=gif" />
</noscript>

   
   </body>
</html>