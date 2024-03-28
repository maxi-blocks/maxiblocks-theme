<?php
/**
 * Pure Footer Dark Pro PFD-PRO-105 footer
 */
return array(
    'title'	  => __('Pure Footer Dark Pro PFD-PRO-105', 'maxiblocks'),
    'categories' => array( 'mbt-footer' ),
    'blockTypes' => array( 'core/template-part/footer' ),
    'content'	=> file_get_contents(__DIR__ . '/code.html'),
);
