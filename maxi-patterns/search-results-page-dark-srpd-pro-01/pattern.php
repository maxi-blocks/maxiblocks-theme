<?php
/**
 * Search Results Page Dark SRPD-PRO-01
 */

return array(
    'title'	  => __('Search Results Page Dark SRPD-PRO-01', 'maxiblocks'),
    'slug' => 'maxiblocks/search-results-page-dark-srpd-pro-01',
    'categories' => array( 'mbt-search-results' ),
    'templateTypes' => array('search'),
    'content'    => (function () {
        ob_start();
        include(__DIR__ . '/code.php');
        return ob_get_clean();
    })(),
);
