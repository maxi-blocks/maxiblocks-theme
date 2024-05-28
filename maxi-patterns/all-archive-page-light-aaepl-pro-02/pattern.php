<?php
/**
 * All Archive Page Light AAEPL-PRO-02
 */

return array(
    'title'	  => __('All Archive Page Light AAEPL-PRO-02', 'maxiblocks'),
    'categories' => array( 'mbt-all-archives' ),
    'templateTypes' => array('author', 'category', 'tag', 'date', 'archive', 'taxonomy'),
    'content'    => (function () {
        ob_start();
        include(__DIR__ . '/code.php');
        return ob_get_clean();
    })(),
);
