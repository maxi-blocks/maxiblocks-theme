<?php
/**
 * All Archive Page Dark AAEPD-PRO-01
 */

return array(
    'title'	  => __('All Archive Page Dark AAEPD-PRO-01', 'maxiblocks'),
    'categories' => array( 'mbt-all-archives' ),
    'templateTypes' => array('author', 'category', 'tag', 'date', 'archive', 'taxonomy'),
    'content'	=> file_get_contents(__DIR__ . '/code.html'),
);
