<?php
/**
 * Navigation Menu Light NML-PRO-12
 */

return array(
    'title'	  => __('Navigation Menu Light NML-PRO-12', 'maxiblocks'),
    'categories' => array( 'mbt-header-navigation' ),
    'content'    => (function () {
        ob_start();
        include(__DIR__ . '/code.php');
        return ob_get_clean();
    })(),
);
