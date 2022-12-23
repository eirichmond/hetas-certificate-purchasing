<?php get_header();

$public_class = new Hetas_Certificate_Purchasing_Public('HETAS_CERTIFICATE_PURCHASING_VERSION', '1.1.0');
$results = $public_class->hetas_ccp_form_process();

?>

    <div class="hetas-copy-certificate">

        <h2>HETAS Copy Certificate Search</h2>
        <p>Please choose one of the following to search for your notification by...</p>
        

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#by-postcode" aria-controls="by-postcode" role="tab" data-toggle="tab">Postcode</a>
            </li>
            <li role="presentation">
                <a href="#by-hetas-installation-id" aria-controls="by-hetas-installation-id" role="tab" data-toggle="tab">Reference Number</a>
            </li>
            <li role="presentation">
                <a href="#by-installer-supplied-reference" aria-controls="by-installer-supplied-reference" role="tab" data-toggle="tab">Installer Supplied Reference</a>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content ccpsearchpanel">
            <div role="tabpanel" class="tab-pane fade in active text-center" id="by-postcode">

                <p>

                    <form class="form-inline" action="/hetas-copy-certificate-results/" method="post">
                        <div class="form-group">
                            <label class="sr-only" for="by-postcode">by Postcode</label>
                            <input type="text" class="form-control" id="by-postcode" name="van_postcode" placeholder="by Postcode">
                        </div>
                        <input type="hidden" name="action" value="sbpc">
                        <?php wp_nonce_field( 'ccp_front_end_post_action', 'ccp_front_end_post' ); ?>
                        <button type="submit" class="btn btn-default">Search</button>
                    </form>

                </p>


            </div>
            <div role="tabpanel" class="tab-pane fade text-center" id="by-hetas-installation-id">

                <p>

                    <form class="form-inline" action="/hetas-copy-certificate-results/" method="post">
                        <div class="form-group">
                            <label class="sr-only" for="by-hetas-id">by HETAS ID</label>
                            <input type="text" class="form-control" id="by-hetas-id" name="van_name" placeholder="by Reference Number">
                        </div>
                        <input type="hidden" name="action" value="sbhi">
                        <?php wp_nonce_field( 'ccp_front_end_post_action', 'ccp_front_end_post' ); ?>
                        <button type="submit" class="btn btn-default">Search</button>
                    </form>

                </p>
            
            </div>
            <div role="tabpanel" class="tab-pane fade text-center" id="by-installer-supplied-reference">
            
                <p>

                    <form class="form-inline" action="/hetas-copy-certificate-results/" method="post">
                        <div class="form-group">
                            <label class="sr-only" for="by-installer-reference">by Installer Reference</label>
                            <input type="text" class="form-control" id="by-installer-reference" name="van_installersuppliedreference" placeholder="by Installer Reference">
                        </div>
                        <input type="hidden" name="action" value="sbir">
                        <?php wp_nonce_field( 'ccp_front_end_post_action', 'ccp_front_end_post' ); ?>
                        <button type="submit" class="btn btn-default">Search</button>
                    </form>

                </p>

            </div>
        </div>

    </div>

    <small>If the record for your property does not appear, please contact your Installer in the first instance before raising this with HETAS via <a href="<?php echo antispambot( 'support@hetas.co.uk' );?>">Consumer Support</a> or 01684278170</small>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Installing Company</th>
                <!-- <th>Installing Reference</th> -->
                <th>Installation Date</th>
                <th>Address</th>
                <th>Postcode</th>
            </tr>
        </thead>
        
        <?php foreach($results as $k => $result) { ?>
            <tr>
                <td><a href="/hetas-copy-certificate-notification-details/?id=<?php echo esc_html($result->van_notificationid); ?>"><?php echo esc_html($result->van_name); ?></a></td>
                <td><?php echo esc_html($result->ak_x002e_name); ?></td>
                <!-- <td><?php //echo esc_html($result->van_installersuppliedreference); ?></td> -->
                <td><?php echo esc_html(date('d-m-Y', strtotime($result->van_workcompletiondate))); ?></td>
                <td><?php echo esc_html($result->composite_address); ?></td>
                <td><?php echo esc_html($result->van_postcode); ?></td>
            </tr>
        <?php } ?>
        
    </table>


<?php get_footer(); ?>