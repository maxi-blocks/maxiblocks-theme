<?php
/**
  * Title: Basic Blog Home
  * Slug: maxiblocks/basic-blog-home
  * Categories: mbt-homepage, mbt-blog-index
  * Template Types: home, front-page, page
  */
?>
<!-- wp:group {"className":"post_group_landing template_width","style":{"spacing":{"padding":{"top":"150px","bottom":"150px"},"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group post_group_landing template_width" style="margin-top:0;margin-bottom:0;padding-top:150px;padding-bottom:150px"><!-- wp:query {"queryId":12,"query":{"perPage":10,"pages":0,"offset":"0","postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false},"align":"wide","layout":{"type":"default"}} -->
<div class="wp-block-query alignwide"><!-- wp:query-no-results -->
<!-- wp:paragraph -->
<p>No posts were found.</p>
<!-- /wp:paragraph -->
<!-- /wp:query-no-results -->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"0","right":"0"},"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group" style="margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--50);padding-right:0;padding-bottom:var(--wp--preset--spacing--50);padding-left:0"><!-- wp:post-template {"align":"full","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"grid","columnCount":3}} -->
<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"4/3","style":{"spacing":{"margin":{"bottom":"0"},"padding":{"bottom":"var:preset|spacing|20"}},"border":{"radius":"30px"}}} /-->

<!-- wp:group {"style":{"spacing":{"blockGap":"10px","margin":{"top":"var:preset|spacing|20"},"padding":{"top":"0"}}},"layout":{"type":"flex","orientation":"vertical","flexWrap":"nowrap"}} -->
<div class="wp-block-group" style="margin-top:var(--wp--preset--spacing--20);padding-top:0"><!-- wp:post-title {"isLink":true,"style":{"layout":{"flexSize":"min(2.5rem, 3vw)","selfStretch":"fixed"},"color":{"text":"#000000"},"elements":{"link":{"color":{"text":"#000000"}}},"typography":{"fontSize":"24px","fontStyle":"normal","fontWeight":"700"}}} /-->

<!-- wp:post-date {"isLink":true,"style":{"color":{"text":"#000000"},"elements":{"link":{"color":{"text":"#000000"}}}}} /-->

<!-- wp:post-excerpt {"moreText":"Read more","excerptLength":25,"style":{"layout":{"selfStretch":"fit","flexSize":null},"color":{"text":"#9b9b9b"},"elements":{"link":{"color":{"text":"#010101"},":hover":{"color":{"text":"#c9340a"}}}}}} /-->

<!-- wp:spacer {"height":"0px","style":{"layout":{"flexSize":"min(2.5rem, 3vw)","selfStretch":"fixed"}}} -->
<div style="height:0px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer --></div>
<!-- /wp:group -->
<!-- /wp:post-template -->

<!-- wp:query-pagination {"paginationArrow":"arrow","layout":{"type":"flex","justifyContent":"space-between"}} -->
<!-- wp:query-pagination-previous /-->

<!-- wp:query-pagination-next /-->
<!-- /wp:query-pagination --></div>
<!-- /wp:group --></div>
<!-- /wp:query --></div>
<!-- /wp:group -->
