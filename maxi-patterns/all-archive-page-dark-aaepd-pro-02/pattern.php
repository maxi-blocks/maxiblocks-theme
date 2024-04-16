<?php
/**
 * All Archive Page Dark AAEP-PRO-02
 */

return array(
    'title'	  => __('All Archive Page Dark AAEPD-PRO-02', 'maxiblocks'),
    'slug' => 'maxiblocks/all-archive-page-dark-aaepd-pro-02',
    'categories' => array( 'mbt-all-archives' ),
    'content'    => (function () {
        ob_start();
        include(__DIR__ . '/code.php');
        return ob_get_clean();
    })(),
);
