<?php
/**
 * Home Page Pro HEP-PRO-05
 */

return array(
    'title'	  => __('Home Page Pro HEP-PRO-05', 'maxiblocks'),
    'categories' => array( 'mbt-homepage' ),
    'content'    => (function () {
        ob_start();
        include(__DIR__ . '/code.php');
        return ob_get_clean();
    })(),
);
