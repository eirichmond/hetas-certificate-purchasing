<?php get_header();

    $dynamics_crm_class = new Hetas_Dynamics_crm_Public('Hetas Dynamics CRM', '1.0');
    $public_class = new Hetas_Certificate_Purchasing_Public('Hetas Cert Purchasing', '1.0.0');
    $product = $public_class->ccp_get_product_by_id('COPYBUS');
    $amount = number_format($product[0]->amount);
    $vat_rate = 20;
    $additionalVat = number_format($product[0]->amount / 100 * $vat_rate);
    $total = $product[0]->amount / 100 * $vat_rate + $product[0]->amount;
    $charge = $total * 100;
    $sub_total = $amount * 100;
    //$merchantsessionkey = $dynamics_crm_class->get_sagepay_merchant_session_key_live();
    $merchantsessionkey = $dynamics_crm_class->get_sagepay_merchant_session_key();


?>


<div class="hetas-copy-certificate">

    <h2>HETAS Copy Certificate Purchase</h2>

    <table class="table table-striped">

        <tbody>
            <tr>
                <td><?php echo esc_html($product[1]->productnumber); ?></td>
                <td><?php echo esc_html($product[1]->name); ?></td>
                <td><p class="text-right">&pound; <?php echo esc_html(number_format($amount, 2)); ?></p></td>
            </tr>
            <tr>
                <td></td>
                <td><p class="text-right">VAT</p></td>
                <td><p class="text-right">&pound; <?php echo esc_html(number_format($additionalVat, 2)); ?></p></td>
            </tr>
            <tr>
                <td></td>
                <td><p class="text-right">Total</p></td>
                <td><p class="text-right">&pound; <?php echo esc_html(number_format($total, 2)); ?></p></td>
            </tr>
        </tbody>
    
    </table>


    <form method="post" action="/hetas-copy-certificate-notification-payment-success/" id="ccp-checkout">


        <div class="personal-details form-horizontal">
            <h2>Your Details</h2>

            <div class="form-group">
                <label for="firstname" class="col-sm-2 control-label">First Name</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Firstname" value="" required>
                </div>
            </div>
            <div class="form-group">
                <label for="lastname" class="col-sm-2 control-label">Last Name</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Lastname" value="" required>
                </div>
            </div>
            <div class="form-group">
                <label for="billingaddress1" class="col-sm-2 control-label">Address Line 1</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="billingaddress1" name="billingaddress1" placeholder="Address Line 1" value="" required>
                </div>
            </div>
            <div class="form-group">
                <label for="billingaddress2" class="col-sm-2 control-label">Address Line 2</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="billingaddress2" name="billingaddress2" placeholder="Address Line 2" value="">
                </div>
            </div>
            <div class="form-group">
                <label for="billingaddresscity" class="col-sm-2 control-label">City</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="billingaddresscity" name="billingaddresscity" placeholder="City" value="" required>
                </div>
            </div>
            <div class="form-group">
                <label for="billingaddresspostcode" class="col-sm-2 control-label">Postcode</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="billingaddresspostcode" name="billingaddresspostcode" placeholder="Postcode" value="" required>
                </div>
            </div>
            <div class="form-group">
                <label for="billingaddressstate" class="col-sm-2 control-label">County</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="billingaddressstate" name="billingaddressstate" placeholder="County" value="">
                </div>
            </div>
            <div class="form-group">
                <label for="emailaddress" class="col-sm-2 control-label">Email</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="emailaddress" name="emailaddress" placeholder="Email" value="" required>
                </div>
                <div class="col-sm-2">

                    <button type="button" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Your email address is required as this is
where the copy certificate will be
delivered.">?</button>

                </div>

            </div>
            <div class="form-group">
                <label for="mobilephone" class="col-sm-2 control-label">Mobile</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="mobilephone" name="mobilephone" placeholder="Mobile" value="" required>
                </div>
                <div class="col-sm-2">

                    <button type="button" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Your phone number may be used to
contact you if there is a problem with
certificate delivery, you do not have to
provide this.">?</button>

                </div>

            </div>

            <div class="form-group">

                <label class="col-sm-2 control-label"> Marketing Preferences</label>

                <div class="checkbox">
                    <label for="donotemail" class="col-sm-8 control-label"><input type="checkbox" id="donotemail" name="donotemail" > I would like to subscribe to the HETAS newsletter which includes
tips and advice for you as the consumer as well as relevant safety
tips for your appliance. HETAS will not share your details with 3rd
parties.</label>
                </div>

            </div>


            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Credit Card Details</h3>
                </div>
                <div id="sp-container" class="panel-body">

                    <div class="form-group">
                        <label for="cardholderName" class="col-sm-3 control-label">Name</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="cardholderName" name="cardholderName" placeholder="Cardholder Name" value="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="cardNumber" class="col-sm-3 control-label">Card</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="cardNumber" name="cardNumber" placeholder="Card" value="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="expiryDate" class="col-sm-3 control-label">Expiry</label>
                        <div class="col-sm-6">
                            <input type="tel" class="form-control" id="expiryDate" name="expiryDate" placeholder="MMYY" value="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="securityCode" class="col-sm-3 control-label">CVC</label>
                        <div class="col-sm-6">
                            <input type="tel" class="form-control" id="securityCode" name="securityCode" placeholder="123" value="">
                        </div>
                    </div>

                
                </div>
            </div>

        </div>

        

        <input type="hidden" name="merchantsessionkey" value="<?php echo $merchantsessionkey; ?>">
        <input type="hidden" name="spamount" value="<?php echo esc_html($charge); ?>">
        <input type="hidden" name="sub_total" value="<?php echo esc_html($sub_total); ?>">
        <input type="hidden" name="notification_id" value="<?php echo esc_html($_GET['notification_id']); ?>">
        <div id="submit-container" class="text-right">
            <input class="btn btn-primary" type="submit" value="Checkout">
        </div>

    </form>



<?php get_footer(); ?>