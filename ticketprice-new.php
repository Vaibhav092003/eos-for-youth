<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';
require_once __DIR__ . '/ccav/db.php';

// ---- ID encrypt / decrypt (AES-256-GCM, URL-safe token) ----
// IMPORTANT: ID_CRYPT_KEY dono files (index.php + ticketprice-new.php) me SAME rakhna aur random secret se badalna
if (!defined('ID_CRYPT_KEY')) {
    define('ID_CRYPT_KEY', 'CHANGE_ME_to_a_long_random_secret_key_123');
}
if (!function_exists('id_encrypt')) {
    function id_encrypt($id)
    {
        $key    = hash('sha256', ID_CRYPT_KEY, true);
        $iv     = random_bytes(12);
        $tag    = '';
        $cipher = openssl_encrypt((string) (int) $id, 'aes-256-gcm', $key, OPENSSL_RAW_DATA, $iv, $tag);
        return rtrim(strtr(base64_encode($iv . $tag . $cipher), '+/', '-_'), '=');
    }
}
if (!function_exists('id_decrypt')) {
    function id_decrypt($token)
    {
        $raw = base64_decode(strtr((string) $token, '-_', '+/'), true);
        if ($raw === false || strlen($raw) < 29) {
            return 0;
        }
        $key    = hash('sha256', ID_CRYPT_KEY, true);
        $iv     = substr($raw, 0, 12);
        $tag    = substr($raw, 12, 16);
        $cipher = substr($raw, 28);
        $plain  = openssl_decrypt($cipher, 'aes-256-gcm', $key, OPENSSL_RAW_DATA, $iv, $tag);
        return ($plain !== false && ctype_digit($plain)) ? (int) $plain : 0;
    }
}
ob_start();
include __DIR__ . '/email/register.php';
$registerTemplate = ob_get_clean();

$participantId = 0;   // insert hone ke baad / ?id= se aane wala participant id

// ---- NEW: index.php ke AJAX insert ke baad  ticketprice-new.php?id=123  ----
if (isset($_GET['token']) && !isset($_POST['applicant-email'])) {

    $participantId = id_decrypt($_GET['token']);   // encrypted token -> real id
    if ($participantId <= 0) {
        header('location:https://www.skc.world/eos/ticketprice.php');
        exit;
    }

    $sel = $conn->prepare("SELECT participant_name, participant_age, participant_email, participant_phone_number,
                                  school_college_university, guardian_name, guardian_email, guardian_contact_number,
                                  relationship_to_participant, referred_by, location, question_comment
                           FROM participants_new WHERE id = ? LIMIT 1");
    $sel->bind_param("i", $participantId);
    $sel->execute();
    $sel->bind_result(
        $participant_name, $participant_age, $participant_email, $participant_phone,
        $institution, $guardian_name, $guardian_email, $guardian_phone,
        $relationship, $referred_by, $location, $message
    );

    if (!$sel->fetch()) {
        // id valid nahi mila
        $sel->close();
        header('location:https://www.skc.world/eos/ticketprice.php');
        exit;
    }
    $sel->close();

}elseif(isset($_POST['applicant-email'])){

   // Normalise input: support both the new registration form field names and the legacy ones
   $field = function (array $keys, $default = '') {
       foreach ($keys as $key) {
           if (isset($_POST[$key]) && trim((string) $_POST[$key]) !== '') {
               return trim((string) $_POST[$key]);
           }
       }
       return $default;
   };

   $participant_name  = $field(['applicant-name', 'participant_name']);
   $participant_age   = $field(['participant_age', 'applicant-age']);
   $participant_email = $field(['applicant-email', 'participant_email']);
   $participant_phone = $field(['participant_phone', 'applicant-number']);
   $institution       = $field(['visitor-organisation', 'school_college_university']);
   $guardian_name     = $field(['guardian_name']);
   $guardian_email    = $field(['guardian_email']);
   $guardian_phone    = $field(['guardian_phone', 'guardian_contact_number']);
   $relationship      = $field(['relationship', 'relationship_to_participant']);
   $referred_by       = $field(['referred_by']);
   $location          = $field(['location']);
   $message           = $field(['message']);

   // Save participant data
$sql = "INSERT INTO participants_new
        (participant_name, participant_age, participant_email, participant_phone_number,
         school_college_university, guardian_name, guardian_email, guardian_contact_number,
         relationship_to_participant, referred_by, location, question_comment)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "sissssssssss",
    $participant_name,
    $participant_age,
    $participant_email,
    $participant_phone,
    $institution,
    $guardian_name,
    $guardian_email,
    $guardian_phone,
    $relationship,
    $referred_by,
    $location,
    $message
);

$stmt->execute();
$stmt->close();
$participantId = (int) $conn->insert_id;

    
    $message1 = '
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse;">
      <tbody>';
    
    if (!empty($participant_name)) {
      $message1 .= '<tr>
        <td width="15%" align="left" height="30px" style="border: 1px solid #CCCCCC; padding-left:10px;"><strong>Name</strong> :</td>
        <td width="85%" align="left" style="border: 1px solid #CCCCCC; padding-left:10px"><strong>'.$participant_name.'</strong></td>
      </tr>';
    }
    
    if (!empty($participant_age)) {
      $message1 .= '<tr>
        <td align="left" height="30px" style="border: 1px solid #CCCCCC; padding-left:10px;"><strong>Age</strong> :</td>
        <td align="left" style="border: 1px solid #CCCCCC; padding-left:10px"><strong>'.$participant_age.'</strong></td>
      </tr>';
    }
    
    if (!empty($participant_email)) {
      $message1 .= '<tr>
        <td align="left" height="30px" style="border: 1px solid #CCCCCC; padding-left:10px;"><strong>Email</strong> :</td>
        <td align="left" style="border: 1px solid #CCCCCC; padding-left:10px"><strong>'.$participant_email.'</strong></td>
      </tr>';
    }
    
    if (!empty($participant_phone)) {
      $message1 .= '<tr>
        <td align="left" height="30px" style="border: 1px solid #CCCCCC; padding-left:10px;"><strong>Phone</strong> :</td>
        <td align="left" style="border: 1px solid #CCCCCC; padding-left:10px;"><strong>'.$participant_phone.'</strong></td>
      </tr>';
    }

    if (!empty($institution)) {
      $message1 .= '<tr>
        <td align="left" height="30px" style="border: 1px solid #CCCCCC; padding-left:10px;"><strong>School/College/University</strong> :</td>
        <td align="left" style="border: 1px solid #CCCCCC; padding-left:10px;"><strong>'.$institution.'</strong></td>
      </tr>';
    }

    if (!empty($guardian_name) || !empty($guardian_email) || !empty($guardian_phone)) {
      $message1 .= '<tr>
        <td align="left" height="30px" style="border: 1px solid #CCCCCC; padding-left:10px;"><strong>Guardian</strong> :</td>
        <td align="left" style="border: 1px solid #CCCCCC; padding-left:10px;"><strong>'.trim($guardian_name.' '.$guardian_email.' '.$guardian_phone).'</strong>'.($relationship !== '' ? ' ('.$relationship.')' : '').'</td>
      </tr>';
    }
    
    if (!empty($message)) {
      $message1 .= '<tr>
        <td align="left" height="30px" style="border: 1px solid #CCCCCC; padding-left:10px;"><strong>Message</strong> :</td>
        <td align="left" style="border: 1px solid #CCCCCC; padding-left:10px;"><strong>'.$message.'</strong></td>
      </tr>';
    }
    
    if (!empty($referred_by)) {
      $message1 .= '<tr>
        <td align="left" height="30px" style="border: 1px solid #CCCCCC; padding-left:10px;"><strong>Referred By</strong> :</td>
        <td align="left" style="border: 1px solid #CCCCCC; padding-left:10px;"><strong>'.$referred_by.'</strong></td>
      </tr>';
    }
    
    if (!empty($location)) {
      $message1 .= '<tr>
        <td align="left" height="30px" style="border: 1px solid #CCCCCC; padding-left:10px;"><strong>Location</strong> :</td>
        <td align="left" style="border: 1px solid #CCCCCC; padding-left:10px;"><strong>'.ucwords($location).'</strong></td>
      </tr>';
    }
    
    $message1 .= '
      </tbody>
    </table>';

    //ini_set( 'display_errors', 1 );
    //error_reporting( E_ALL );
    
    $to='contact@skc.world';
    /*$subject = "Anaavaran Workshop registration initiated";
    $message="testing mail";
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: <noreply@skc.world>' . "\r\n";
    $headers .= 'Bcc: avdhesh@detecvision.com' . "\r\n";
    
    if(mail($to,$subject,$message1, $headers)){
        //echo "send success";
    }else{
       // echo "something errors";
    }*/
    
    
    //  $mail = new PHPMailer();
    // try {
    //     //Server settings
    //     $mail->SMTPDebug = 0; // Enable verbose debug output
    //     $mail->isSMTP(); 
        
    //     // Set mailer to use SMTP
    //      $mail->Host       = 'sdseu.linuxhostingserver.com';  // Specify main and backup SMTP servers
    //     $mail->SMTPAuth   = true; // Enable SMTP authentication
    //     $mail->SMTPSecure = 'tls';
    //     $mail->Port = 587;
    //     $mail->Username = "noreply1@skc.world"; // SMTP username
    //     $mail->Password = "Noreply@123"; // SMTP password
    //     $mail->From = "noreply1@skc.world"; //do NOT fake header. The Username domain and the from domain must be the same.
    //     $mail->FromName = "SKC";
    //     //Recipients
    //     //$mail->setFrom('noreply@skc.world', 'SKC WORLD');
    //    // $mail->addAddress('vivek.kumar@detecvision.com');     //$to Add a recipient
      
    //     $mail->addAddress('contact@skc.world');     // Add a recipient
    //     $mail->addBCC('avdhesh@detecvision.com');
    //     $mail->addBCC('swskhushbooverma@gmail.com');
    //     $mail->addBCC('divya.m@skc.world');
    //     $mail->addBCC('disha@skc.world');
    //    // $mail->addBCC('preeta.goel@skc.world');
    //     $mail->addBCC('eos.connect@skc.world');
    //     $mail->addBCC('vijay@detecvision.com');
    //     $mail->addBCC('malakar.neeraj@gmail.com');
    //     //$mail->addBCC('vivek.kumar@detecvision.com');
    //    // $mail->addReplyTo('contact@skc.world', 'SKC WORLD');
    //     $mail->isHTML(true);                                  // Set email format to HTML
    //     $mail->Subject = 'Registration Success (Participant Details) - EoS for Youth ';
    //     $mail->Body    = $message1;
    //     $mail->send();
        
       
    //     //echo 'Message has been sent';
    //     //echo json_encode(array('message'=>'Message has been sent' ,'class'=>'success-msg'));
    //    // echo 'Message has been sent';
    // } catch (Exception $e) {
    //     //echo json_encode(array('message'=>'Message could not be sent' ,'class'=>'error-msg'));
    //     echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    // }
    
}else{
    header('location:https://www.skc.world/eos/ticketprice.php'); 
}

?>
<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Essentials of Success (EOS) for Youth</title>
    <link rel=icon href='img/favicon.png' type='image/x-icon'/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $__scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http'; $__dir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'); ?>
    <base href="<?php echo $__scheme . '://' . $_SERVER['HTTP_HOST'] . $__dir; ?>/">
    <link href="css/custom.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/flexboxgrid.css?=v1.1">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,600,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=PT+Sans:400,600&display=swap" rel="stylesheet">
    <style>
        @media only screen and (max-width: 460px){
            .banner-text-wrapper h4 {margin-top: 15px;}
            .banner-text-wrapper h4, .banner-text-wrapper h5 {  font-weight: 600;}
            .fly-man { position: relative; left: 0; right: 0; }
            section#summary {padding: 50px 0px 20px 0px;}
            div#Book-div { padding-bottom: 20px; }
        }
    </style>
</head>

<body>

<!-- top section -->
    <!--Banner Open-->
        <section>
            <div class="container-fluid" style="box-shadow: 0px 1px 7px -6px #333;">
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
    
    
    <!--
    <section class="tkt-prize-banner">
        <div class="container-fluid" style="padding:0px;">
            <div class="row ptop">
                <div class="col-lg-12 col-sm-12 col-sm-12 col-xs-12"  style="padding:0px;">
                    <div>
                        <img src="img/eos-1st-slide-v1.jpg" class="img-responsive" style="border-radius:0px;">
                    </div>
                </div>
            </div>
        </div>
    </section>-->

    <!-- top  section -->

    <!-- first body section -->
   
    <section id="summary">
        <div class="workshop-details workshop-pad-top">
           <div class="container-fluid wrap">
                 <div id="Book-div" >
                      <div class="workshop-wrapper">
                      <div class="row work-detail-hding">
                        <div class="col-lg-12 col-sm-12 col-sm-12 col-xs-12">
                            <div>Ticket Type</div> 
                        </div>
                        </div>
                        <div class="row ticket-chakar">
                        <div class="col-lg-12 col-sm-12 col-sm-12 col-xs-12">
                            <div class="anaavaranquote mt35 mb25">
                                <h2>
                                    Congratulations! You have submitted your interest for the Essentials of Success (EOS) for Youth. Please make the payment to confirm your seat.
                                </h2>
                            </div>
                          <!--<img src="img/workshop-img-form.jpg" class="samarth-right-img img-responsive hidden-sm hidden-xs">-->
                        </div>
                        <div class="col-lg-12 col-sm-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-lg-6" style="margin: 0 auto;">
                          <div class="ticket-detail-div h-100">
                              <div class="boxpayment h-100">
                                  <h1>Pay Booking Amount</h1>
                                  <div class="innerboxprice mt40 mb60">
                                    <div class="price-box">
                                        <span style="color:#000;">Rs 20,000/-</span>
                                    </div>
                                    
                                    
                                     <?php
                                        $baseAmount = 1;
                                        $gstRate = 0;
                                        $gstAmount = ($baseAmount * $gstRate) / 100;
                                        $totalAmount = $baseAmount + $gstAmount;
                                        
                                        ?>
                                     <form method="post" action="booknow">
                                    <div class="list-items mrpitems mt15 mb15">
                                          <p><b>No. Of Ticket:</b> </p>
                                            <select id="dropdown" class="NoOfTicket" name="NoOfTicket" onchange="selectTicket(this,'ticketbooking','<?php echo $totalAmount ?>');" required  style="width: 125px;">
                                               <option value="1">01</option>
                                               <!--<option value="2">02</option>-->
                                               <!--<option value="3">03</option>-->
                                               <!--<option value="4">04</option>-->
                                               <!--<option value="5">05</option>-->
                                               <!--<option value="6">06</option>-->
                                               <!--<option value="7">07</option>-->
                                               <!--<option value="8">08</option>-->
                                               <!--<option value="9">09</option>-->
                                               <!--<option value="10">10</option>-->
                                               <!--<option value="11">11</option>-->
                                            </select>
                                         </div>
                                    <!--<p><b>Note:</b> Adjustable towards program fee or refundable if needed</p>-->
                                  </div>
                                  <div class="paybooking big-btn">
                                    
                                        <input type="hidden" name="ticket" value="1" id="fticketbooking">
                                        <input type="hidden" name="ticket_type" value="General" id="ticket_type">
                                      
                                        
                                        
                                         <input type="hidden" name="price" value="<?php echo $totalAmount ?>" id="priceticketbooking">
                                        <input type="hidden" name="price2" value="<?php echo $totalAmount ?>" id="totalticketbooking">
                                        
                                        <input type="hidden" name="name" value="<?= htmlspecialchars((string) ($participant_name ?? ''), ENT_QUOTES, 'UTF-8') ?>" >
                                        <input type="hidden" name="age" value="<?= htmlspecialchars((string) ($participant_age ?? ''), ENT_QUOTES, 'UTF-8') ?>" >
                                        <input type="hidden" name="email" value="<?= htmlspecialchars((string) ($participant_email ?? ''), ENT_QUOTES, 'UTF-8') ?>" >
                                        <input type="hidden" name="number" value="<?= htmlspecialchars((string) ($participant_phone ?? ''), ENT_QUOTES, 'UTF-8') ?>" >
                                        <input type="hidden" name="profession" value="">
                                        <input type="hidden" name="participant_id" value="<?= (int) $participantId ?>">
                                        <input type="hidden" name="organisation" value="<?= htmlspecialchars((string) ($institution ?? ''), ENT_QUOTES, 'UTF-8') ?>" >
                                        <input type="hidden" name="location" value="<?= htmlspecialchars((string) ($location ?? ''), ENT_QUOTES, 'UTF-8') ?>" >
                                        <input type="hidden" name="referred_by" value="<?= htmlspecialchars((string) ($referred_by ?? ''), ENT_QUOTES, 'UTF-8') ?>" >
                                        <input type="hidden" name="message" value="<?= htmlspecialchars((string) ($message ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                                          <button class="btn-three"> <span>Rs. <spam class="ticketbooking"><?php echo number_format($totalAmount, 2) ?></spam> Pay</span></button>
                                   
                                  
                                  </div>
                                   </form>
                              </div>
                            
                          </div>
                        </div>   
                          
                        </div>
                        </div>
                      </div>
                     
                    </div>
                  </div> 

           </div>
        </div>
     </section>


    <!-- footer -->
    <footer>
        <div class="container-fluid wrap">
            <!-- logo and social icon section -->
            <div class="row">
                <div class="col-lg-6 col-sm-6 col-sm-12 col-xs-12">
                    Copyright  © <?php echo date('Y'); ?> SKC.World. All Rights Reserved.
                </div>
                
            </div>
        </div>
    </footer>
    <style>
        .form_error{color:red; display: none;}

    </style>

    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script>
        $(document).ready(function () {
            $("a").on('click', function (event) {
                if (this.hash !== "") {
                    event.preventDefault();
                    var hash = this.hash;
                    $('html, body').animate({
                        scrollTop: $(hash).offset().top
                    }, 1500, function () {
                        window.location.hash = hash;
                    });
                }
            });  
            
        });
    </script>

    
     <script>
      
   //Book Now toggle js
      $(document).ready(function(){
        
      
        
    //Book Now calculate price and quantity 
        
        // $(".NoOfTicket").change(function(){
        $(document).on('click change hover','.NoOfTicket,.NoOfTicketbtn',function(){  
            var TicketTypePrice="1"; //19500
            var ticket=$('.NoOfTicket').val();
            var total=ticket*TicketTypePrice;
            
            
            total=total.toFixed(2);
            //$('#total').val(total); 
            //$('.total').html(' ');
            getTotle(total,ticket); 
            
             if($('.NoOfTicket').val()==''){
                $(".NoOfTicketbtn").attr('disabled', true);
            }else{
                $(".NoOfTicketbtn").attr('disabled', false);
            }
            //console.log(total);
        });
        
        $("#applyCoupan").click(function(){
            var coupan=$('#coupan').val();
            
            $.ajax({
              url: "ccav/coupan.txt",
              success: function (data){
                   
                   var lines = data.split("\n");
                    var queryArr=[];
                    $.each(lines, function(n, elem) {
                        var lines2 = elem.split(":");
                        //console.log(lines2[0]);
                        //queryArr[lines2[0]]=lines2[1];
                        if(coupan===lines2[0]){
                            var price=$('#total').val();
                            var discount=jQuery.trim(lines2[1]);
                            var total=price - (price * (discount / 100));
                            $('.coupan-msg').html(' ');
                            $('.coupan-msg').html('Apply Successfully');
                            $('.total').html('₹ '+price);
                            total=total.toFixed(2);
                            getTotle(total);
                            return false;
                        } else{
                            console.log(lines2[0]);
                            $('.coupan-msg').html(' ');
                            $('.coupan-msg').html('<span style="color:red">Coupon code is not valid<span>');
                        }
                    });
                    //console.log(queryArr);
                  //console.log(jQuery.inArray( "coupan", queryArr )  );
              }
            });
            
        });
        
      });
      
      function getTotle(total,ticket=''){
          $('.price-total .price-box span').html('');
          if(ticket){$('#fticket').val('');}
          $('#total').val('');
          
          $('.price-total .price-box span').html(total+'/-');
          if(ticket){$('#fticket').val(ticket);}
          $('#total').val(total);
          
          
      }
     function selectTicket(e, element, amt) {
        var ticket = $(e).val();
        var total = amt * ticket;
    
        // Format the total number with commas as thousand separators
        var formattedTotal = total.toLocaleString();
    
        // Update the text of the element with the formatted total
        $('.' + element).text(formattedTotal);
        $('#total'+element).val(formattedTotal);
        $('#price'+element).val(formattedTotal);
        $('#f'+element).val(ticket);
    }

      
  
    </script>
</body>

</html>