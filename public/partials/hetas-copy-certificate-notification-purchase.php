<?php get_header();

    $dynamics_crm_class = new Hetas_Dynamics_crm_Public('Hetas Dynamics CRM', '1.0');
    $public_class = new Hetas_Certificate_Purchasing_Public('Hetas Cert Purchasing', '1.0.0');
    $product = $public_class->ccp_get_product_by_id('COPYCOC');
    $amount = number_format($product[0]->amount, 2, '.', '');
    $vat_rate = 20;
    $additionalVat = number_format($product[0]->amount / 100 * $vat_rate, 2, '.', '');
    $total = $product[0]->amount / 100 * $vat_rate + $product[0]->amount;
    $charge = $total * 100;
    $sub_total = $amount * 100;
    if(defined('SAGEPAY_TEST_MODE') && SAGEPAY_TEST_MODE == true) {
        $merchantsessionkey = $dynamics_crm_class->get_sagepay_merchant_session_key();
    } else {
        $merchantsessionkey = $dynamics_crm_class->get_sagepay_merchant_session_key_live();
    }


?>

<script src="https://www.paypal.com/sdk/js?client-id=<?php echo esc_attr( PAYPAL_CLIENT_ID ); ?>&currency=GBP&intent=capture"></script>

<div class="processing" style="display:none;"><img src="https://www.hetas.co.uk/wp-content/plugins/hetas-dynamics-crm/public/images/throbber_12.gif" alt="Loading_icon"></div>

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
                    <input type="text" class="form-control coc-req" id="firstname" name="firstname" placeholder="Firstname" value="" required>
                </div>
            </div>
            <div class="form-group">
                <label for="lastname" class="col-sm-2 control-label">Last Name</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control coc-req" id="lastname" name="lastname" placeholder="Lastname" value="" required>
                </div>
            </div>
            <div class="form-group">
                <label for="billingaddress1" class="col-sm-2 control-label">Address Line 1</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control coc-req" id="billingaddress1" name="billingaddress1" placeholder="Address Line 1" value="" required>
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
                    <input type="text" class="form-control coc-req" id="billingaddresscity" name="billingaddresscity" placeholder="City" value="" required>
                </div>
            </div>
            <div class="form-group">
                <label for="billingaddresspostcode" class="col-sm-2 control-label">Postcode</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control coc-req" id="billingaddresspostcode" name="billingaddresspostcode" placeholder="Postcode" value="" required>
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
                    <input type="text" class="form-control coc-req" id="emailaddress" name="emailaddress" placeholder="Email" value="" required>
                </div>
                <div class="col-sm-2">

                    <button type="button" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Your email address is required as this is where the copy certificate will be delivered.">?</button>

                </div>

            </div>
            <div class="form-group">
                <label for="mobilephone" class="col-sm-2 control-label">Mobile</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control coc-req" id="mobilephone" name="mobilephone" placeholder="Mobile" value="" required>
                </div>
                <div class="col-sm-2">

                    <button type="button" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Your phone number may be used to contact you if there is a problem with certificate delivery, you do not have to provide this.">?</button>

                </div>

            </div>

            <div class="form-group">

                <label class="col-sm-2 control-label"> Marketing Preferences</label>

                <div class="checkbox">
                    <label for="donotemail" class="col-sm-8 control-label"><input type="checkbox" id="donotemail" name="donotemail" > I would like to subscribe to the HETAS newsletter which includestips and advice for you as the consumer as well as relevant safety tips for your appliance. HETAS will not share your details with 3rd parties.</label>
                </div>

            </div>


            <div id="payment-option" class="row" style="display:none;">
                <div class="col-sm-6 text-left">
                    <p class="bg-warning">Please choose your payment option</p>
                </div>

                <div class="col-sm-3 text-right">
                    <div id="paypal-button-container"></div>
                </div>

                <div class="col-sm-3 text-left">
                    <button type="button" class="btn btn-primary pay-by-credit-card"><span class="glyphicon glyphicon-credit-card" aria-hidden="true"></span> Credit Card</button>
                </div>

            </div>
            

            <div id="pay-by-credit-card" class="panel panel-default" style="display:none;">
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

                
                    <input type="hidden" name="merchantsessionkey" value="<?php echo $merchantsessionkey; ?>">
                    <input type="hidden" id="spamount" name="spamount" value="<?php echo esc_html($charge); ?>">
                    <input type="hidden" id="sub_total" name="sub_total" value="<?php echo esc_html($sub_total); ?>">
                    <input type="hidden" id="notification_id" name="notification_id" value="<?php echo esc_html($_GET['notification_id']); ?>">
                    <input type="hidden" id="notification_uid" name="notification_uid" value="<?php echo esc_html($_GET['notification_uid']); ?>">
                    <?php wp_nonce_field( 'coc_action', 'coc_nonce' ); ?>
                    <div id="submit-container" class="text-right">
                        <input class="btn btn-primary" type="submit" value="Checkout">
                    </div>


                </div>
            </div>

        </div>
    </form>

    <script>



        paypal.Buttons({
            // Set style of buttons
            style: {
                layout: 'horizontal',   // horizontal | vertical
                size:   'large',   // medium | large | responsive
                shape:  'rect',         // pill | rect
                color:  'blue',         // gold | blue | silver | black,
                fundingicons: false,    // true | false,
                tagline: false          // true | false,
            },

            fundingSource: paypal.FUNDING.PAYPAL,
            createOrder: function(data, actions) {


                // This function sets up the details of the transaction, including the amount and line item details.
                return actions.order.create({
                    purchase_units: [{
                        reference_id: 'COC',
                        description: 'Copy Certificate Notification',
                        amount: {
                            currency_code: 'GBP',
                            value: <?php echo esc_html(number_format($total, 2)); ?>,
                            breakdown: {
                                item_total: {
                                    currency_code: 'GBP',
                                    value: <?php echo esc_html(number_format($amount, 2)); ?>
                                },
                                tax_total: {
                                    currency_code: 'GBP',
                                    value: <?php echo esc_html(number_format($additionalVat, 2)); ?>
                                }
                            }
                        }
                    }]
                });

            },
            onApprove: function(data, actions) {

// var bodyElement = document.querySelector('.hetas-copy-certificate');
// bodyElement.innerHTML += '<div class="processing"></div>';

                let formData = new FormData();
                formData.append('firstname', document.getElementById('firstname').value);
                formData.append('lastname', document.getElementById('lastname').value);
                formData.append('billingaddress1', document.getElementById('billingaddress1').value);
                formData.append('billingaddress2', document.getElementById('billingaddress2').value);
                formData.append('billingaddresscity', document.getElementById('billingaddresscity').value);
                formData.append('billingaddresspostcode', document.getElementById('billingaddresspostcode').value);
                formData.append('billingaddressstate', document.getElementById('billingaddressstate').value);
                formData.append('emailaddress', document.getElementById('emailaddress').value);
                formData.append('mobilephone', document.getElementById('mobilephone').value);
                formData.append('donotemail', document.getElementById('donotemail').value);
                formData.append('spamount', document.getElementById('spamount').value);
                formData.append('sub_total', document.getElementById('sub_total').value);
                formData.append('notification_id', document.getElementById('notification_id').value);
                formData.append('notification_uid', document.getElementById('notification_uid').value);
                formData.append('payment_type', 'paypal');
                formData.append('coc_nonce', document.getElementById('coc_nonce').value);
                
                var processing = document.querySelector('.processing');
                processing.style.display = 'block';

                return fetch(
                    '/hetas-copy-certificate-notification-process-paypal/',
                    {
                        method: 'POST',
                        body: formData
                    }
                ).then(function(response) {
                    
                    return response.json();
                }).then(function(resJson) {


                    // This function captures the funds from the transaction.
                    return actions.order.capture().then(function(details) {
                        // This function shows a transaction success message to your buyer.
                        // alert('Transaction completed by ' + details.payer.name.given_name);
                        // window.location.href = '/hetas-copy-certificate-notification-payment-success/';
                        var processing = document.querySelector('.processing');
                        processing.style.display = 'none';

                        // console.log(details);
                        // console.log(resJson);

                        var successContent = document.querySelector('.hetas-copy-certificate');

                        successContent.innerHTML = '<h2>HETAS Copy Certificate Confirmation Page</h2><div id="ccp-successful-payment" class="bg-success" data_invoicenumber="'+resJson.invoice.invoicenumber+'" data_emailaddress="'+resJson.postdata.emailaddress+'" data_notificationid="'+resJson.postdata.notification_id+'" style="padding:20px;"><h4>Payment Successful</h4> <p>You will receive an email with your certificate attached shortly</p></div>';
                        
                        async_update_ccp_notification(resJson.invoice.invoicenumber, resJson.postdata.emailaddress, resJson.postdata.notification_id);


                    });



                    // window.location.href = '/hetas-copy-certificate-notification-payment-success/';
                    // return resJson;
                });



            }

        }).render('#paypal-button-container');// This function displays Smart Payment Buttons on your web page.
    </script>


<?php get_footer(); ?>