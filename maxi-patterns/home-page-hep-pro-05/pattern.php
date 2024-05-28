<?php
/**
 * Home Page HEP-PRO-05
 */

return array(
    'title'	  => __('Home Page HEP-PRO-05', 'maxiblocks'),
    'categories' => array( 'mbt-homepage' ),
    'templateTypes' => array('home', 'front-page'),
    'content'    => (function () {
        ob_start();
        include(__DIR__ . '/code.php');
        return ob_get_clean();
    })(),
);
