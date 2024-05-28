<?php
/**
 * Services Page SSP-PRO-07
 */

return array(
    'title'	  => __('Services Page SSP-PRO-07', 'maxiblocks'),
    'categories' => array( 'mbt-services-page' ),
    'templateTypes' => array('page', 'singular'),
    'content'    => (function () {
        ob_start();
        include(__DIR__ . '/code.php');
        return ob_get_clean();
    })(),
);
