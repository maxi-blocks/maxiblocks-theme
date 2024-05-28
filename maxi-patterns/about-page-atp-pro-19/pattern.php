<?php
/**
 * About Page ATP-PRO-19
 */

return array(
    'title'	  => __('About Page ATP-PRO-19', 'maxiblocks'),
    'categories' => array( 'mbt-about-page' ),
    'templateTypes' => array('page', 'singular'),
    'content'    => (function () {
        ob_start();
        include(__DIR__ . '/code.php');
        return ob_get_clean();
    })(),
);
