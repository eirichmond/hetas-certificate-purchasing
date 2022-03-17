<?php get_header('simple'); ?>
<div class="container">

	<div class="row">


		<?php 

		$new_data = base64_decode($_POST['threeDSSessionData']);
		$new_data = json_decode($new_data);
		$postdata = array(
			'firstname' => $new_data[1],
			'lastname' => $new_data[2],
			'billingaddress1' => $new_data[3],
			'billingaddresspostcode' => $new_data[4],
			'emailaddress' => $new_data[5],
			'notification_id' => $new_data[6],
			'notification_uid' => $new_data[7],
			'spamount' => $new_data[8]	
		);

		$creq = array(
			'cRes' => $_POST['cres']
		);

		$creq = json_encode($creq);

		$transaction = $new_data[0];

		if(defined('SAGEPAY_TEST_MODE') && SAGEPAY_TEST_MODE == true) {
			$sage_transaction_url = 'https://pi-test.sagepay.com/api/v1/transactions/';
			$sage_httpheader = array(
				'Content-Type: application/json',
				'Authorization: Basic eGVpVGpTMWtieWoycnNWTFRDeW9uY3JVNE8yY3prSGttMnpoeTJxeHh6UVJSNjJyOGs6TkF3cWZ2eEc1NzkyM2VoZ0xwdUU2aGk2QVdUWnRtRU1kczBub3RVS2I4U2xiUWZpVnd4b0xqMDRLYUVjNVI0bHg='
			);
		} else {
			$sage_transaction_url = 'https://pi-live.sagepay.com/api/v1/transactions/';
			$sage_httpheader = array(
				'Content-Type: application/json',
				'Authorization: Basic Tk5TMjlXTjFqbUZhM3haZ2dnRGYwZ2JkcGNaeXlsOEhQZTRiSzNIQkVia1ZyYXBQcHM6RXFaNHhpdVRHelMwYXZ3RnpwemgyOVdoMVRUNWJzcEVGRjBuZ016VzZ2MXNtZjhLYmh6RUZKNjFPNHFMd09pMHk=',
				'Cookie: AWSALB=N3m1zSqCk6KcnhcrhhVFj/tA+7aV+uyntTNP6k83Fg558rIQNBHN+zbZKm3KenKOA4teQXSQLTjhblcGNSqYWAlRM6saJivgybvmf3vDgFHfO8tueajai0renwVF; AWSALBCORS=N3m1zSqCk6KcnhcrhhVFj/tA+7aV+uyntTNP6k83Fg558rIQNBHN+zbZKm3KenKOA4teQXSQLTjhblcGNSqYWAlRM6saJivgybvmf3vDgFHfO8tueajai0renwVF'
			);
		}


		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $sage_transaction_url.$transaction.'/3d-secure-challenge',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_FOLLOWLOCATION => false,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => $creq,
			CURLOPT_HTTPHEADER => $sage_httpheader,
		));

		$response = curl_exec($curl);
		$response = json_decode($response);
		curl_close($curl);

		if($response->statusCode == '0000') {
			$public_class = new Hetas_Certificate_Purchasing_Public('Hetas_Certificate_Purchasing_Public', '1.0.1');
			$response = $public_class->successful_ccp_payment($postdata, $response);
		?>

			<div id="ccp-successful-payment" data_invoicenumber="<?php echo esc_attr( $response->invoice->invoicenumber ); ?>" data_emailaddress="<?php echo esc_attr( $response->postdata["emailaddress"] ); ?>" data_notificationid="<?php echo esc_attr( $response->postdata["notification_id"] ); ?>" class="bg-success" style="padding:20px;">
				<h4>Payment Successful</h4>
				<p>You will receive an email with your certificate attached shortly.</p>
				<p>Please allow 30 minutes for your certificate to arrive and don't forget to check your junk mailbox</p>
			</div>

		<?php } ?>
	
	</div>
</div>

<?php get_footer('simple');?>
