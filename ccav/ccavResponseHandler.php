<?php include('Crypto.php')?>
<?php include('db.php')?>
<?php

//Array ( [0] => order_id=966 [1] => tracking_id=108617082570 [2] => bank_ref_no=007920 [3] => order_status=Success [4] => failure_message= [5] => payment_mode=Credit Card [6] => card_name=Visa [7] => status_code=null [8] => status_message=SUCCESS [9] => currency=INR [10] => amount=1.00 [11] => billing_name=sdf [12] => billing_address= [13] => billing_city= [14] => billing_state= [15] => billing_zip= [16] => billing_country= [17] => billing_tel=2435545 [18] => billing_email=vivek@gmail.com [19] => delivery_name= [20] => delivery_address= [21] => delivery_city= [22] => delivery_state= [23] => delivery_zip= [24] => delivery_country= [25] => delivery_tel= [26] => merchant_param1= [27] => merchant_param2=sdfd [28] => merchant_param3=vivek1@gmail.com [29] => merchant_param4=24355453 [30] => merchant_param5= [31] => vault=N [32] => offer_type=null [33] => offer_code=null [34] => discount_value=0.0 [35] => mer_amount=1.00 [36] => eci_value=null [37] => retry=N [38] => response_code=0 [39] => billing_notes= [40] => trans_date=01/07/2019 16:13:01 [41] => bin_country=INDIA )
	error_reporting(0);
	
	$workingKey='8EC904D5B2D4C6F0610EBEF0360343E5';		//Working Key should be provided here.
	$encResponse=$_POST["encResp"];			//This is the response sent by the CCAvenue Server
	$rcvdString=decrypt($encResponse,$workingKey);		//Crypto Decryption used as per the specified working key.
	$order_status="";
	$decryptValues=explode('&', $rcvdString);
	$dataSize=sizeof($decryptValues);
	//print_r($decryptValues);
	echo "<center>";

	for($i = 0; $i < $dataSize; $i++) 
	{
		$information=explode('=',$decryptValues[$i]);
		if($i==0){	$order_id=$information[1];}
		if($i==1){	$tracking_id=$information[1];}
		if($i==2){	$bank_ref_no=$information[1];}
		if($i==3){	$order_status=$information[1];}
		if($i==4){	$failure_message=$information[1];}
		if($i==5){	$payment_mode=$information[1];}
		if($i==8){	$status_message=$information[1];}
		if($i==40){	$trans_date=$information[1];}
		
	}
    echo $newDate = date("g:ia \o\n l jS F Y", strtotime($trans_date));
   
	$status_message=$status_message?$status_message:'NULL';
    $sql2 = "UPDATE user_payment SET trans_date='".$trans_date."',tracking_id=$tracking_id,bank_ref_no=$bank_ref_no,payment_mode='".$payment_mode."',failure_message='".$failure_message."',order_status='".$order_status."',status_message='".$status_message."' WHERE order_id=$order_id";
            mysqli_query($conn, $sql2);
            
	if($order_status==="Success")
	{
		echo "<br>Thank you for shopping with us. Your credit card has been charged and your transaction is successful. We will be shipping your order to you soon.";
		
	}
	else if($order_status==="Aborted")
	{
		echo "<br>Thank you for shopping with us.We will keep you posted regarding the status of your order through e-mail";
	
	}
	else if($order_status==="Failure")
	{
		echo "<br>Thank you for shopping with us.However,the transaction has been declined.";
	}
	else
	{
		echo "<br>Security Error. Illegal access detected";
	
	}

	echo "<br><br>";

	echo "<table cellspacing=4 cellpadding=4>";
	for($i = 0; $i < $dataSize; $i++) 
	{
		$information=explode('=',$decryptValues[$i]);
	    	echo '<tr><td>'.$information[0].'</td><td>'.urldecode($information[1]).'</td></tr>';
	}

	echo "</table><br>";
	echo "</center>";
?>
