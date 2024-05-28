<?php
/**
 * Blog Home Page Dark BHPD-PRO-02
 */

return array(
    'title'	  => __('Blog Home Page Dark BHPD-PRO-02', 'maxiblocks'),
    'slug' => 'maxiblocks/blog-home-page-dark-bhpd-pro-02',
    'categories' => array( 'mbt-homepage', 'mbt-blog-index' ),
    'templateTypes' => array('home', 'front-page'),
    'content'    => (function () {
        ob_start();
        include(__DIR__ . '/code.php');
        return ob_get_clean();
    })(),
);
