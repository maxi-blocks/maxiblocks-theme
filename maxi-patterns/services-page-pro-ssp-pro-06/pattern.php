<?php
/**
 * Services Page Pro SSP-PRO-06
 */

return array(
    'title'	  => __('Services Page Pro SSP-PRO-06', 'maxiblocks'),
    'categories' => array( 'mbt-services-page' ),
    'content'    => (function () {
        ob_start();
        include(__DIR__ . '/code.php');
        return ob_get_clean();
    })(),
);
