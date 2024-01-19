<?php
/**
 * custom footer
 */
return array(
    'title'	  => __('MaxiBlocks Pure Footer Light PFL-PRO-105', 'maxiblocks'),
    'categories' => array( 'mbt-footer' ),
    'blockTypes' => array( 'core/template-part/footer' ),
    'content'	=> file_get_contents(__DIR__ . '/code.html'),
);
