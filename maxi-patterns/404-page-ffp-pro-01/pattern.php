<?php
/**
 * 404 Page FFP-PRO-01
 */

return array(
    'title'	  => __('404 Page FFP-PRO-01', 'maxiblocks'),
    'slug' => 'maxiblocks/404-page-ffp-pro-01',
    'categories' => array( 'mbt-not-found-404' ),
    'content'    => (function () {
        ob_start();
        include(__DIR__ . '/code.php');
        return ob_get_clean();
    })(),
);