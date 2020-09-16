<?php get_header();

$public_class = new Hetas_Certificate_Purchasing_Public('HETAS_CERTIFICATE_PURCHASING_VERSION', '1.0.0');
$results = $public_class->hetas_ccp_form_process();

?>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Installing Company</th>
                <th>Installing Reference</th>
                <th>Installation Date</th>
                <th>Postcode</th>
            </tr>
        </thead>
        
        <?php foreach($results as $k => $result) { //var_dump($result); ?>
            <tr>
                <td><a href="/hetas-copy-certificate-notification-details/?id=<?php echo esc_html($result->van_notificationid); ?>"><?php echo esc_html($result->van_name); ?></a></td>
                <td><?php echo esc_html($result->ak_x002e_name); ?></td>
                <td><?php echo esc_html($result->van_installersuppliedreference); ?></td>
                <td><?php echo esc_html(date('d-m-Y', strtotime($result->van_workcompletiondate))); ?></td>
                <td><?php echo esc_html($result->van_postcode); ?></td>
            </tr>
        <?php } ?>
        
    </table>


<?php get_footer(); ?>