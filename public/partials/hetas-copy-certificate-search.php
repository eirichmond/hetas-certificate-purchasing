<?php get_header(); ?>

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


<?php get_footer(); ?>