<?php
/**
 * All Archive Page Light AAEP-PRO-02
 */

return array(
    'title'	  => __('All Archive Page Light AAEP-PRO-02', 'maxiblocks'),
    'categories' => array( 'mbt-all-archives' ),
    'content'    => (function () {
        ob_start();
        include(__DIR__ . '/code.php');
        return ob_get_clean();
    })(),
);
