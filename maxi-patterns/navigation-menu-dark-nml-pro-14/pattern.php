<?php
/**
 * Navigation Menu Dark NML-PRO-14
 */

return array(
    'title'	  => __('Navigation Menu Dark NML-PRO-14', 'maxiblocks'),
    'slug' => 'maxiblocks/navigation-menu-dark-nml-pro-14',
    'categories' => array( 'mbt-header-navigation' ),
    'content'    => (function () {
        ob_start();
        include(__DIR__ . '/code.php');
        return ob_get_clean();
    })(),
);
