<?php get_header();
    
    $public_class = new Hetas_Certificate_Purchasing_Public('HETAS_CERTIFICATE_PURCHASING_VERSION', '2');
    $results = $public_class->hetas_get_ccp_notification_details($_GET['id']);

?>



<div class="hetas-copy-certificate">

    <h2>HETAS Copy Certificate Details</h2>

    <div class="row">
        <div class="bg-info" style="padding:20px; margin:20px;">If you believe any of the details on this page to be incorrect please <a href="mailto:<?php echo antispambot('support@hetas.co.uk'); ?>">email support</a> for further assistance.</div>
    </div>

    <div class="row">
        <div class="col-md-6">
        
            <table class="table table-striped">
                
                <tr>
                    <td class="text-right"><strong>Notification ID:</strong></td>
                    <td><?php echo esc_html($results['notification']->van_name); ?></td>
                </tr>

                <tr>
                    <td class="text-right"><strong>Scheme Type:</strong></td>
                    <td><?php echo esc_html($results['scheme_type']); ?></td>
                </tr>

                <tr>
                    <td class="text-right"><strong><?php echo esc_html( $results['scheme_type_index'] == 0 ? 'Installing' : 'Maintenance'); ?> Company:</strong></td>
                    <td><?php echo esc_html($results['notification']->ak_x002e_name); ?></td>
                </tr>

                <tr>
                    <td class="text-right"><strong><?php echo esc_html( $results['scheme_type_index'] == 0 ? 'Installer' : 'Operative'); ?> ID:</strong></td>
                    <td><?php echo esc_html($results['notification']->ak_x002e_van_hetasid); ?></td>
                </tr>

                <!-- <tr>
                    <td class="text-right"><strong>Installer Reference:</strong></td>
                    <td><?php //echo esc_html($results['notification']->van_installersuppliedreference); ?></td>
                </tr> -->

                <!-- <tr>
                    <td class="text-right"><strong>Address Line 1:</strong></td>
                    <td><?php // echo esc_html($results['notification']->van_addressline1); ?></td>
                </tr>

                <tr>
                    <td class="text-right"><strong>Address Line 2:</strong></td>
                    <td><?php // echo esc_html($results['notification']->van_addressline2); ?></td>
                </tr>

                <tr>
                    <td class="text-right"><strong>Address Line 3:</strong></td>
                    <td><?php // echo esc_html($results['notification']->van_addressline3); ?></td>
                </tr>

                <tr>
                    <td class="text-right"><strong>Town / City:</strong></td>
                    <td><?php // echo esc_html($results['notification']->van_towncity); ?></td>
                </tr> -->

                <tr>
                    <td class="text-right"><strong>Postcode:</strong></td>
                    <td><?php echo esc_html($results['notification']->van_postcode); ?></td>
                </tr>

                <tr>
                    <td class="text-right"><strong><?php echo esc_html( $results['scheme_type_index'] == 0 ? 'Installation' : 'Maintenance'); ?> Date:</strong></td>
                    <td><?php echo esc_html(date('d-m-Y', strtotime($results['notification']->van_workcompletiondate))); ?></td>
                </tr>

                
            </table>

        </div>
        <div class="col-md-6">
        
            <table class="table table-striped">
                <?php foreach($results['notification_items'] as $k => $item) { $i = $k; ++$i; ?>

                    <tr>
                        <td class="text-right"><strong>Appliance <?php echo esc_html($i); ?>:</strong></strong></td>
                        <td><?php echo esc_html($item->van_name); ?></td>
                    </tr>

                    <tr>
                        <td class="text-right"><strong>Manufacturer <?php echo esc_html($i); ?>:</strong></td>
                        <td><?php echo esc_html($item->van_manufacturername); ?></td>
                    </tr>

                    <tr>
                        <td class="text-right"><strong>Operative:</strong></td>
                        <td><?php echo esc_html($item->at_x002e_van_name); ?></td>
                    </tr>

                <?php } ?>
                
            </table>

        </div>
    </div>

    

    <div class="row">

        <div class="col-md-6 text-left">


            <a href="/hetas-copy-certificate-search/" class="btn btn-info" role="button">Back to search</a>


        </div>

        <div class="col-md-6 text-right">


            <a href="/hetas-copy-certificate-notification-purchase/?notification_id=<?php echo esc_html($results['notification']->van_name); ?>&notification_uid=<?php echo esc_html($_GET['id']); ?>" class="btn btn-primary" role="button">Purchase Checkout</a>


        </div>
    </div>



</div>



<?php get_footer(); ?>