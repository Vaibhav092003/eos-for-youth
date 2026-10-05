<?php 
if($_POST){
    session_start();
    $name=$_POST['name'];
    $age=$_POST['age'];
	$email=$_POST['email'];
	$phone=$_POST['number'];
	$location=$_POST['location'];
	$referred_by=$_POST['referred_by'];
	$profession=$_POST['profession'];
	$organisation=$_POST['organisation'];
	$message=$_POST['message'];
    $participant_id = isset($_POST['participant_id']) ? (int) $_POST['participant_id'] : 0;
    $_SESSION['ticket'] = $_POST['ticket'];
    $_SESSION['price'] = $_POST['price'];
    $_SESSION['totle_price'] = $_POST['price2'];
    $access_code='AVUX86GF22CE04XUEC';
    $merchant_id='79445';
}else{
    header("Location: https://skc.world/eos-for-youth/");
}
?>
<!DOCTYPE html>
<html lang="en">
   <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <meta name="msapplication-tap-highlight" content="no" />     
      <link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">
      <title>Essentials of Success (EOS) for Youth</title>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel=icon href='img/favicon.png' type='image/x-icon'/>
      <!--<link rel="stylesheet" href="css/custom.css?=v1.2">-->
      <link href="css/custom.css" rel="stylesheet" />
      <link rel="stylesheet" href="css/flexboxgrid.css?=v1.1">
      <link rel="shortcut icon" href="../assets/ico/favicon.png">
      <link rel="shortcut icon" href="favicon.ico">
      <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,600,700&display=swap" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css?family=PT+Sans:400,600&display=swap" rel="stylesheet">
      <!-- Yeah i know js should not be in header. Its required for demos.-->
      <!-- javascript -->
      <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
      
      
      
      
      <!-- DO NOT MODIFY -->
<!-- Quora Pixel Code (JS Helper) -->
<script>
!function(q,e,v,n,t,s){if(q.qp) return; n=q.qp=function(){n.qp?n.qp.apply(n,arguments):n.queue.push(arguments);}; n.queue=[];t=document.createElement(e);t.async=!0;t.src=v; s=document.getElementsByTagName(e)[0]; s.parentNode.insertBefore(t,s);}(window, 'script', 'https://a.quora.com/qevents.js');
qp('init', 'dbbcc3b6675f47a8914cb3fb4f42c8d4');
qp('track', 'ViewContent');
</script>


<script>
	window.onload = function() {
		var d = new Date().getTime();
		document.getElementById("tid").value = d;
	};
</script>
<style>
@media only screen and (max-width: 460px){
    .banner-text-wrapper h4 {margin-top: 15px;}
    .banner-text-wrapper h4, .banner-text-wrapper h5 {  font-weight: 600;}
    .fly-man {
        position: relative;
        left: 0;
        right: 0;
        }
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


      <!-- Workshop Details  Open-->
      <section id="summary">
         <div class="workshop-details workshop-pad-top"> 
            <div class="container-fluid wrap">
                <form method="POST" name="customerData" class="customerData" id="customerData" action="ccav/ccavRequestHandler">
                    <input type="hidden" name="tid" id="tid" value="<?php echo(rand(100,10000)); ?>" />
                    <input type="hidden" name="currency" value="INR"/>
                    <input type="hidden" name="participant_id" value="<?php echo (int) $participant_id; ?>"/>
                    <input type="hidden" name="redirect_url" value="http://localhost/eos-for-youth/response.php"/>
                    <input type="hidden" name="cancel_url" value="http://localhost/eos-for-youth/response.php"/>
                    <input type="hidden" name="language" value="EN"/>
                    <input type="hidden" name="location" value="<?php echo $location;?>"/>
                    <input type="hidden" name="referred_by" value="<?php echo $referred_by;?>"/>
                    <input type="hidden" name="message" value="<?php echo $message;?>"/>
                    <input type="hidden" name="amount" value="<?php echo str_replace(',', '', $_SESSION['totle_price']);?>"/>
                    <input type="hidden" name="merchant_id" value="<?php echo $merchant_id;?>"/>
                    <input type="hidden" name="order_id" value="<?php echo(rand(100,10000)); ?>"/>
                
                <div class="ticket-chakar">
                    <div class="workshop-wrapper">
                        <div class="work-detail-hding">Personal Details</div>
                        <div class="row">
                            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                            </div>
                        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                        <?php
                            $ticket= $_SESSION['ticket'];
                            $i=1;
                            for ($ticket;$ticket>0;$ticket--){
                            if($i==1){
                            ?>  
                        <div class="ticket-detail-div">
                            <strong>Attendee 01</strong>
                            <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="pose-box">
                                    <input type="text"  placeholder="Enter Name" name="billing_name" value="<?=$name?>" required >
                                    <div class="help-block with-errors billing_name"></div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="pose-box">
                                    <input type="email"  placeholder="Enter Email Address" name="billing_email" value="<?=$email?>" required>
                                    <div class="help-block with-errors billing_email"></div>
                                </div>
                            </div>
                            </div>
                            <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="pose-box">
                                    <input type="tel"  placeholder="Enter Mobile Number" name="billing_tel" value="<?=$phone?>" required>
                                    <div class="help-block with-errors billing_tel"></div>
                                </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="pose-box">
                                    <input type="text"  placeholder="Enter Age" name="age" value="<?=$age?>" required>
                                    <div class="help-block with-errors age"></div>
                                </div>
                                </div>
                            </div>
                            <!--<div class="row">-->
                            <!--    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">-->
                            <!--        <div class="pose-box">-->
                            <!--            <input type="text"  placeholder="Enter Profession" name="profession" value="<?=$profession?>" required>-->
                            <!--            <div class="help-block with-errors profession"></div>-->
                            <!--        </div>-->
                            <!--    </div>   -->
                            <!--    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">-->
                            <!--        <div class="pose-box">-->
                            <!--            <input type="text"  placeholder="Enter Organisation" name="organisation" value="<?=$organisation?>" required>-->
                            <!--            <div class="help-block with-errors organisation"></div>-->
                            <!--        </div>-->
                            <!--    </div>   -->
                            <!--</div>-->
                        </div>
                        <?php $i++; }else{ ?>
                            <div class="ticket-detail-div">
                            <strong>Attendee <?=$i?></strong>
                            <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="pose-box">
                                    <input type="text"  placeholder="Enter Name" name="name[]" required>
                                    <div class="help-block with-errors name<?=$i?>"></div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="pose-box">
                                    <input type="email"  placeholder="Enter Email Address" name="email[]" required>
                                    <div class="help-block with-errors email<?=$i?>"></div>
                                </div>
                            </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="pose-box">
                                        <input type="tel"  placeholder="Enter Mobile Number" name="mobile[]" required>
                                        <div class="help-block with-errors mobile<?=$i?>"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="pose-box">
                                        <input type="text"  placeholder="Enter Age" name="other_age[]" required>
                                        <div class="help-block with-errors other_age<?=$i?>"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="pose-box">
                                        <input type="text"  placeholder="Enter Profession" name="other_profession[]" required>
                                        <div class="help-block with-errors other_profession<?=$i?>"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="pose-box">
                                        <input type="text"  placeholder="Enter Organisation" name="other_organisation[]" required>
                                        <div class="help-block with-errors other_organisation<?=$i?>"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        $i++;
                        } } ?>   
                        <div class="ticket-detail-div">
                        <div class="row">
                            
                               <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="pose-box">
                                    <strong>Payment mode</strong>
                                   
                                    <input type="radio" name="payment_mode" id="ccavenue" value="ccavenue" checked>
                                     <label for="ccavenue"><img src="img/ccavenue.png" width="80"></label>
                                    <input type="radio" name="payment_mode" id="paytm" value="paytm">
                                     <label for="paytm"><img src="img/Paytm_logo.png" width="65"></label>
                                   
                                </div>
                            </div>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                            <div class="work-bottom-hding">
                                <div class="row">
                                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <div class="price-box-div">
                                            <h3 class="detail-sub-hdings total-form-box">Sub Total</h3><div class="price-box"><span><?php echo $_SESSION['totle_price'];?>/-</span> </nav></div> 
                                        </div> 
                                    </div>
                                   
                                </div>
                                
                                 <div class="row">
                                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                    </div>
                                    
                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">                        
                                        <div class="big-btn">
                                                <button class="btn-four" name="submit" type="submit" id="booknow"> <span>Book</span></button>
                                        </div>
                                    </div>
                                    <input type="hidden" value="<?php echo $_SESSION['totle_price'];?>" name="ticket">
                                    
                                </div>
                        </div>
                    </div>
                </div>
                </form>
            </div>
         </div>
      </section>
      <!-- Workshop Details  close-->
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



<script language="javascript" type="text/javascript" src="ccav/json.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
<script type="text/javascript">

        function isValidEmailAddress(emailAddress) {
            var pattern = new RegExp(/^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i);
            return pattern.test(emailAddress);
        }
        
        jQuery( document ).ready( function( $ ){
            
            jQuery('#booknow').on('click',function() {
               
                var errorMSG = false;
                var email=[];
                var index1=2;
                jQuery("input[name='email[]']").each(function(index, element) {
                    email[index]=this.value;
                    if (this.value == '') {
                       errorMSG = true;
                        jQuery('.email'+index1).html('Email is required');
                        jQuery('.email'+index1).parent().addClass('has-error');
                    }else {
                        if (!isValidEmailAddress(this.value)) {
                            errorMSG = true;
                            jQuery('.email'+index1).html('Invalid email');
                            jQuery('.email'+index1).parent().addClass('has-error');
                        }
                    }
                index1++;
                });
                
                
                var index2=2;
                jQuery("input[name='name[]']").each(function(index, element) {
                    if (this.value == '') {
                       errorMSG = true;
                        jQuery('.name'+index2).html('Name is required');
                        jQuery('.name'+index2).parent().addClass('has-error');
                    }
                index2++;
                });
                var index3=2;
                jQuery("input[name='other_age[]']").each(function(index, element) {
                    if (this.value == '') {
                       errorMSG = true;
                        jQuery('.other_age'+index3).html('Age is required');
                        jQuery('.other_age'+index3).parent().addClass('has-error');
                    }
                index3++;
                });
                var index4=2;
                jQuery("input[name='other_profession[]']").each(function(index, element) {
                    if (this.value == '') {
                       errorMSG = true;
                        jQuery('.other_profession'+index4).html('Profession is required');
                        jQuery('.other_professionn'+index4).parent().addClass('has-error');
                    }
                index4++;
                });
               
                var index5=2;
                jQuery("input[name='mobile[]']").each(function(index, element) {
                    if (this.value == '') {
                       errorMSG = true;
                        jQuery('.mobile'+index5).html('Phone is required');
                        jQuery('.mobile'+index5).parent().addClass('has-error');
                    }else {
                        if (!jQuery.isNumeric(this.value)) {
                           errorMSG = true;
                            jQuery('.mobile'+index5).html('Invalid Phone no');
                            jQuery('.mobile'+index5).parent().addClass('has-error');
                        }
                    }
                index5++;
                });
                
                 var index6=2;
                jQuery("input[name='other_organisation[]']").each(function(index, element) {
                    if (this.value == '') {
                       errorMSG = true;
                        jQuery('.other_organisation'+index6).html('Organisation is required');
                        jQuery('.other_organisation'+index6).parent().addClass('has-error');
                    }
                index6++;
                });
                var billing_name = $("input[name='billing_name']").val();
                var billing_email = $("input[name='billing_email']").val();
                var billing_tel = $("input[name='billing_tel']").val();
                var age = $("input[name='age']").val();
                var profession = $("input[name='profession']").val();
                var organisation = $("input[name='organisation']").val();
                
                
                /* NAME */
                if (billing_name == '') {
                    errorMSG = true;
                    jQuery('.billing_name').html('Name is required'); 
                    jQuery('.billing_name').parent().addClass('has-error');
                    
                } 
                /* LNAME */
                if (age == '') {
                    errorMSG = true;
                    jQuery('.age').html('Age is required');   
                    jQuery('.age').parent().addClass('has-error');
                } 
                /* EMAIL */
                if (billing_email == '') {
                   errorMSG = true;
                    jQuery('.billing_email').html('Email is required');
                    jQuery('.billing_email').parent().addClass('has-error');
                }else {
                    if (!isValidEmailAddress(billing_email)) {
                        errorMSG = true;
                        jQuery('.billing_email').html('Invalid email');
                        jQuery('.billing_email').parent().addClass('has-error');
                    }
                }
                /* Phone */
                if (billing_tel == '') {
                   errorMSG = true;
                    jQuery('.billing_tel').html('Phone is required');
                    jQuery('.billing_tel').parent().addClass('has-error');
                }else {
                    if (!jQuery.isNumeric(billing_tel)) {
                       errorMSG = true;
                        jQuery('.billing_tel').html('Invalid Phone no');
                        jQuery('.billing_tel').parent().addClass('has-error');
                    }
                }
                /* designation */
                if (profession == '') {
                    errorMSG = true;
                    jQuery('.profession').html('Profession is required');
                    jQuery('.profession').parent().addClass('has-error');
                } 
                if (organisation == '') {
                    errorMSG = true;
                    jQuery('.organisation').html('Organisation is required');
                    jQuery('.organisation').parent().addClass('has-error');
                } 
                
               //Check duplicate email id 
                /*email.push($("input[name='billing_email']").val());
                var unique = email.filter(function(itm, i, email) {
                    if(i != email.indexOf(itm)){
                        return true;
                    }
                });
                if(unique!=''){
                    errorMSG = true;
                    alert(unique + ' Email is Duplicate');
                    
                    return false;
                }*/
                //End Check duplicate email id 
            
            if(errorMSG==false){
                jQuery('form').submit();
            }

            })
            
        });
        
    
   
  
</script>


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
     

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-88837334-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-88837334-1');
</script>

<!--Start of Tawk.to Script-->
    <!--<script type="text/javascript">
        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
        (function(){
        var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
        s1.async=true;
        s1.src='https://embed.tawk.to/5c8a37fb101df77a8be289f9/default';
        s1.charset='UTF-8';
        s1.setAttribute('crossorigin','*');
        s0.parentNode.insertBefore(s1,s0);
        })();
    </script>-->
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