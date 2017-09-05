<?php
function noo_customizer_preloader()
{

    ob_start();

    ?>

    <style type="text/css" id="noo-customizer-preloader-css">

        body {
            overflow: hidden !important;
        }

    </style>

    <div class="noo-preloader" id="noo-customizer-preloader">
        <div class="sk-folding-cube">
            <div class="sk-cube1 sk-cube"></div>
            <div class="sk-cube2 sk-cube"></div>
            <div class="sk-cube4 sk-cube"></div>
            <div class="sk-cube3 sk-cube"></div>
        </div>
        <div class="noo-preloader-text"><?php echo esc_html__('Powered by NooTheme.com', 'noo-landmark-core'); ?></div>
    </div>
    <?php
    $output = ob_get_contents();
    ob_end_clean();
    echo $output;
}

add_action('customize_controls_print_styles', 'noo_customizer_preloader');