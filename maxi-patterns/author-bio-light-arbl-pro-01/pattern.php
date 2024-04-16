<?php
/**
 * Author Bio Light ARBL-PRO-01
 */

return array(
    'title'	  => __('Author Bio Light ARBL-PRO-01', 'maxiblocks'),
    'categories' => array( 'mbt-author-bio' ),
    'content'    => (function () {
        ob_start();
        include(__DIR__ . '/code.php');
        return ob_get_clean();
    })(),
);
