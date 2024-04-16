<?php
/**
 * Pure Footer Dark PFD-PRO-105 footer
 */
return array(
    'title'	  => __('Pure Footer Dark PFD-PRO-105', 'maxiblocks'),
    'slug' => 'maxiblocks/pure-footer-dark-pfd-pro-105',
    'categories' => array( 'mbt-footer' ),
    'blockTypes' => array( 'core/template-part/footer' ),
    'content'	=> file_get_contents(__DIR__ . '/code.html'),
);
