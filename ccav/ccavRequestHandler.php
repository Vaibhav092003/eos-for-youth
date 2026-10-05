<?php
header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Expires: 0");
// following files need to be included
require_once("paytm_lib/config_paytm.php");
require_once("paytm_lib/encdec_paytm.php");

?>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title> Custom Form Kit </title>
</head>
<body>
    <?php
  
    include('Crypto.php');
    include('db.php');
   	error_reporting(0);
   //error_reporting(E_ALL); ini_set('display_errors', 1);
    
   // $conn->close();

    if(isset($_POST)){
        // echo '<pre>'; print_R($_POST);die;
        $name = $_POST['name'];
        $email = $_POST['email'];
        $mobile = $_POST['mobile'];
        $age = $_POST['other_age'];
        $profession = $_POST['other_profession'];
        $organisation = $_POST['other_organisation'];
        //$gst_no = implode(",",$_POST['other_gst_no']);
        //$_POST['merchant_param1']='merchant_param1';
        $_POST['merchant_param2']=$name;
        $_POST['merchant_param3']=$email;
        $_POST['merchant_param4']=$mobile;
        $amt=$_POST['amount'];
        // $amt = str_replace(',', '', $amt); // Remove commas

        $billing_tel=$_POST['billing_tel'];
        $gst_no1=$_POST['gst_no'];
        $order_id=$_POST['order_id'];
        $tid=$_POST['tid'];
        $payment_mode=$_POST['payment_mode'];
        $participant_id = isset($_POST['participant_id']) ? (int) $_POST['participant_id'] : 0;
$_POST['merchant_param1'] = $participant_id; // CCAvenue response me wapas milegi
        
      $sql = "INSERT INTO `user_payment` 
        (name, email, phone, age, profession, organisation, gst_no, order_id, tid, amount, currency, order_status, other_name, other_email, other_phone, other_age, other_profession, other_organisation, payment_method, location, referred_by, message, participant_id) 
        VALUES 
        ('".$_POST['billing_name']."', 
         '".$_POST['billing_email']."', 
         '".$billing_tel."', 
         '".$_POST['age']."', 
         '".$_POST['profession']."', 
         '".$_POST['organisation']."', 
         '".$gst_no1."', 
         '".$order_id."', 
         '".$tid."', 
         '".$amt."', 
         '".$_POST['currency']."', 
         'Success', 
         '".$name."', 
         '".$email."', 
         '".$mobile."', 
         '".$age."', 
         '".$profession."', 
         '".$organisation."', 
         '".$payment_mode."',
         '".$_POST['location']."', '".$_POST['referred_by']."', '".$_POST['message']."', '".$participant_id."')";
         
         
         
//   print_r($sql);die;
        if ($conn->query($sql) === TRUE) {
            $last_id = $conn->insert_id;
            $_POST['tid']=$last_id;
            $_POST['order_id']='SKC-Eosforyouth-'.$last_id;
            $order_id2=$_POST['order_id'];
            $sql2 = "UPDATE user_payment SET tid=$last_id,order_id='".$order_id2."' WHERE id=$last_id";
            mysqli_query($conn, $sql2);
            if(!empty($last_id)){
                 if($payment_mode == 'ccavenue'){ ?>
                <center>
                <?php 
                	$merchant_data='79445';
                	$working_key='8EC904D5B2D4C6F0610EBEF0360343E5';//Shared by CCAVENUES
                	$access_code='AVUX86GF22CE04XUEC';//Shared by CCAVENUES
                	
                	foreach ($_POST as $key => $value){
                		$merchant_data.=$key.'='.urlencode($value).'&';
                	}
                	$encrypted_data=encrypt($merchant_data,$working_key); // Method for encrypting the data.
                //secure
                 //echo $last_id.'/'.$order_id2;exit();
                ?>
                <form method="post" name="redirect" action="https://secure.ccavenue.com/transaction/transaction.do?command=initiateTransaction"> 
                <?php
                echo "<input type=hidden name=encRequest value=$encrypted_data>";
                echo "<input type=hidden name=access_code value=$access_code>";
                ?>
                </form>
                </center>
                <script language='javascript'>document.redirect.submit();</script>
                 <?php    
            }else{
               
                
                     $checkSum = "";
            $paramList = array();
            
            $order_id = $_POST['order_id'];
            $cust_id = $_POST['order_id'];
            $industry_type_id = 'PrivateEducation';
            $channel_id = 'WEB';
            $amount = $amt;
            $mobile = $_POST['billing_tel'];
            $email = $_POST['billing_email'];
            
            // Create an array having all required parameters for creating checksum.
            $paramList["MID"] = PAYTM_MERCHANT_MID;
            $paramList["ORDER_ID"] = $order_id;
            $paramList["CUST_ID"] = $cust_id;
            $paramList["INDUSTRY_TYPE_ID"] = $industry_type_id;
            $paramList["CHANNEL_ID"] = $channel_id;
            $paramList["TXN_AMOUNT"] = $amount;
            $paramList["WEBSITE"] = 'WEBPROD';
            
            // $paramList["CALLBACK_URL"] = "https://www.skc.world/eos-for-youth/paytm_response.php";
            $paramList["CALLBACK_URL"] = "http://localhost/eos-for-youth/paytm_response";

            $paramList["MOBILE_NO"] = $mobile; //Mobile number of customer
            $paramList["EMAIL"] = $email; //Email ID of customer
            
            //echo '<pre>';print_r($paramList);die;
            //echo PAYTM_MERCHANT_KEY;die;
           $checkSum = getChecksumFromArray($paramList,PAYTM_MERCHANT_KEY);
          //echo $checkSum;die;
               ?>
               
               	<form method="post" action="<?php echo PAYTM_TXN_URL ?>" name="f1">
            		<table border="1">
            			<tbody>
            			<?php
            			foreach($paramList as $name => $value) {
            				echo '<input type="hidden" name="' . $name .'" value="' . $value . '">';
            			}
            			?>
            			<input type="hidden" name="CHECKSUMHASH" value="<?php echo $checkSum ?>">
            			</tbody>
            		</table>
            		<script type="text/javascript">
            			document.f1.submit();
            		</script>
            	</form>
            	
            <?php    
            }}
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        
    }
    ?>

</body>
</html>

