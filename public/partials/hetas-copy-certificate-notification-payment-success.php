<?php

if ( ! isset( $_POST['coc_nonce'] )|| ! wp_verify_nonce( $_POST['coc_nonce'], 'coc_action' )) {

    wp_die('Sorry, something was insecure while processing your post, please go back and try again.');

} else {


    $public_class = new Hetas_Certificate_Purchasing_Public('HETAS_CERTIFICATE_PURCHASING_VERSION', '2');
    $response = $public_class->process_ccp_sagepay_transaction($_POST);
    
}
get_header(); ?>


<h2>HETAS Copy Certificate Confirmation Page</h2>


<?php if($response->statusCode == '0000') { ?>

    <div id="ccp-successful-payment" data_invoicenumber="<?php echo esc_attr( $response->invoice->invoicenumber ); ?>" data_emailaddress="<?php echo esc_attr( $response->postdata["emailaddress"] ); ?>" data_notificationid="<?php echo esc_attr( $response->postdata["notification_id"] ); ?>" class="bg-success" style="padding:20px;">
        <h4>Payment Successful</h4>
        <p>You will receive an email with your certificate attached shortly.</p>
        <p>Please allow 30 minutes for your certificate to arrive and don't forget to check your junk mailbox</p>
    </div>

<?php } ?>

<?php if($response->statusCode == '2021') {

	$data = $public_class->format_data_for_3DS($response, $_POST);

?>

	<iframe src="/3d-redirect?acsurl=<?php echo urlencode($response->acsUrl); ?>&creq=<?php echo urlencode($response->cReq); ?>&postdata=<?php echo $data;?>" name="3Diframe" width="100%" height="600" style="border:none;" >
	</iframe>

<?php } ?>


<?php if($response->statusCode == '2007') {
	$data = $public_class->format_data_for_3DS($response, $_POST);
?>

	<form id="pa-form" method="post" action="<?php echo esc_url( $response->acsUrl ) ;?>">
		<input type="hidden" name="PaReq" value="<?php echo esc_attr( $response->paReq ) ;?>">
		<input type="hidden" name="TermUrl" value="<?php echo esc_url( home_url().'/3d-secure?PaReq='. $response->paReq .'&MD='. $response->transactionId); ?>">   
		<input type="hidden" name="MD" value="<?php echo esc_attr( $data );?> ">  
	</form>    
	<script>document.addEventListener("DOMContentLoaded",function(){var b=document.getElementById("pa-form");b&&b.submit()})</script>

<?php } ?>

<?php if($response->statusCode == '2001' || $response->statusCode == '4021' || $response->statusCode == '2000' || $response->statusCode == '5017' ) { ?>

    <div class="bg-danger" style="padding:20px;">
        <h4>There was a problem, your payment was unsuccessful!</h4>
        <p><?php echo esc_html( $response->statusDetail );?>.</p>
    </div>
	
	<?php
	error_log("COC Log: ".$response->transactionType. " " .$response->status . " " . $response->statusDetail . " Transaction ID: " . $response->transactionId);
	wp_mail(
		array("elliott@squareonemd.co.uk", "James.Macaulay@hetas.co.uk"),
		$response->transactionType. " " .$response->status,
		$response->statusDetail . " Transaction ID: " . $response->transactionId
	);
	?>
     
<?php } ?>

<?php get_footer();?>