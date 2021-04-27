<?php get_header();
    
    $public_class = new Hetas_Certificate_Purchasing_Public('HETAS_CERTIFICATE_PURCHASING_VERSION', '2');
    $results = $public_class->hetas_get_ccp_notification_details($_GET['id']);

?>



<div class="hetas-copy-certificate">

    <h2>HETAS Copy Certificate Details</h2>

    <div class="row">
        <div class="col-md-6">
        
            <table class="table table-striped">
                
                <tr>
                    <td class="text-right"><strong>Notification ID:</strong></td>
                    <td><?php echo esc_html($results['notification']->van_name); ?></td>
                </tr>

                <tr>
                    <td class="text-right"><strong>Installing Company:</strong></td>
                    <td><?php echo esc_html($results['notification']->ak_x002e_name); ?></td>
                </tr>

                <tr>
                    <td class="text-right"><strong>Installer ID:</strong></td>
                    <td><?php echo esc_html($results['notification']->ak_x002e_van_hetasid); ?></td>
                </tr>

                <tr>
                    <td class="text-right"><strong>Installer Reference:</strong></td>
                    <td><?php echo esc_html($results['notification']->van_installersuppliedreference); ?></td>
                </tr>

                <tr>
                    <td class="text-right"><strong>Address Line 1:</strong></td>
                    <td><?php echo esc_html($results['notification']->ak_x002e_address1_line1); ?></td>
                </tr>

                <tr>
                    <td class="text-right"><strong>Address Line 2:</strong></td>
                    <td><?php echo esc_html($results['notification']->ak_x002e_address1_line2); ?></td>
                </tr>

                <tr>
                    <td class="text-right"><strong>Town / City:</strong></td>
                    <td><?php echo esc_html($results['notification']->van_dispatchtowncity); ?></td>
                </tr>

                <tr>
                    <td class="text-right"><strong>Postcode:</strong></td>
                    <td><?php echo esc_html($results['notification']->van_postcode); ?></td>
                </tr>

                <tr>
                    <td class="text-right"><strong>Installation Date:</strong></td>
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
        <div class="col-md-12 text-right">

            <h2>Purchase Certificate</h2>

            <a href="/hetas-copy-certificate-notification-purchase/?notification_id=<?php echo esc_html($results['notification']->van_name); ?>&notification_uid=<?php echo esc_html($_GET['id']); ?>" class="btn btn-primary" role="button">Purchase Checkout</a>


        </div>
    </div>



</div>



<?php get_footer(); ?>