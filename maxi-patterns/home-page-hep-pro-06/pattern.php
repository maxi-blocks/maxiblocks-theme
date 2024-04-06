<?php
/**
 * Home Page HEP-PRO-06
 */

return array(
    'title'	  => __('Home Page HEP-PRO-06', 'maxiblocks'),
    'categories' => array( 'mbt-homepage' ),
    'content'    => (function () {
        ob_start();
        include(__DIR__ . '/code.php');
        return ob_get_clean();
    })(),
);
