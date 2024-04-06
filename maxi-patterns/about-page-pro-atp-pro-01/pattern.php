<?php
/**
 * About Page Pro ATP-PRO-01
 */

return array(
    'title'	  => __('About Page Pro ATP-PRO-01', 'maxiblocks'),
    'categories' => array( 'mbt-about-page' ),
    'content'    => (function () {
        ob_start();
        include(__DIR__ . '/code.php');
        return ob_get_clean();
    })(),
);
