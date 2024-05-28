<?php
/**
 * Blog Single Post BSP-01
 */

return array(
    'title'	  => __('Blog Single Post BSP-01', 'maxiblocks'),
    'slug' => 'maxiblocks/blog-single-post-bsp-01',
    'categories' => array( 'mbt-post-single' ),
    'templateTypes' => array('single'),
    'content'    => (function () {
        ob_start();
        include(__DIR__ . '/code.php');
        return ob_get_clean();
    })(),
);
