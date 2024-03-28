<?php
/**
 * Home Page Pro HEP-PRO-04
 */

return array(
    'title'	  => __('Home Page Pro HEP-PRO-04', 'maxiblocks'),
    'categories' => array( 'mbt-homepage' ),
    'content'    => (function () {
        ob_start();
        include(__DIR__ . '/code.php');
        return ob_get_clean();
    })(),
);
