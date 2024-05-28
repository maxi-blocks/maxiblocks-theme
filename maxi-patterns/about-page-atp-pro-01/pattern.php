<?php
/**
 * About Page ATP-PRO-01
 */

return array(
    'title'	  => __('About Page ATP-PRO-01', 'maxiblocks'),
    'categories' => array( 'mbt-about-page' ),
    'templateTypes' => array('page', 'singular'),
    'content'    => (function () {
        ob_start();
        include(__DIR__ . '/code.php');
        return ob_get_clean();
    })(),
);
