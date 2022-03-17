<?php get_header('simple'); ?>
<div class="container">

	<div class="row">
	
		<div class="bg-info" style="padding:20px; margin:20px 0;">
			We need to authenticate your card payment with your bank.
		</div>
	
		<form action="<?php echo rawurldecode($_GET['acsurl']); ?>" method="post">
			 <input type="hidden" name="creq" value="<?php echo $_GET['creq']; ?>" />
			 <input type="hidden" name="threeDSSessionData" value="<?php echo $_GET['postdata'];?>" />
			 <p>Please click button below to proceed to 3D secure.</p>
			 <input class="btn btn-primary" type="submit" value="Proceed"/>
		</form>
	</div>

</div>
<?php get_footer('simple');?>