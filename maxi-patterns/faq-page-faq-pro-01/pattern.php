<?php
/**
 * FAQ Page FAQ-PRO-01
 */

return array(
    'title'	  => __('FAQ Page FAQ-PRO-01', 'maxiblocks'),
    'categories' => array( 'mbt-faq-page' ),
    'templateTypes' => array('page', 'singular'),
    'content'    => (function () {
        ob_start();
        include(__DIR__ . '/code.php');
        return ob_get_clean();
    })(),
);
