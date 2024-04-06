<?php
/**
 * Home Page HEP-PRO-01
 */

return array(
    'title'	  => __('Home Page HEP-PRO-01', 'maxiblocks'),
    'categories' => array( 'mbt-homepage' ),
    'content'    => (function () {
        ob_start();
        include(__DIR__ . '/code.php');
        return ob_get_clean();
    })(),
);
