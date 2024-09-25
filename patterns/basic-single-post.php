<?php
/**
  * Title: Basic Single Post
  * Slug: maxiblocks-go/basic-single-post
  * Categories: maxiblocks-go-post-single
  * Template Types: single
  */
?>
<!-- wp:group {"tagName":"main","className":"maxiblocks-go post_group template_width","style":{"spacing":{"padding":{"top":"150px","bottom":"150px"},"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"default"}} -->
<main class="wp-block-group maxiblocks-go post_group template_width" style="margin-top:0;margin-bottom:0;padding-top:150px;padding-bottom:150px"><!-- wp:post-title {"level":1,"style":{"typography":{"fontSize":"80px"}}} /-->

<!-- wp:post-featured-image {"style":{"border":{"radius":"30px"}}} /-->

<!-- wp:post-content {"style":{"color":{"text":"#9b9b9b"},"elements":{"link":{"color":{"text":"#9b9b9b"},":hover":{"color":{"text":"#c9340a"}}}}}} /-->

<!-- wp:comments -->
<div class="wp-block-comments"><!-- wp:comments-title {"style":{"color":{"text":"#000000"},"elements":{"link":{"color":{"text":"#000000"}}}}} /-->

<!-- wp:comment-template -->
<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column {"width":"40px"} -->
<div class="wp-block-column" style="flex-basis:40px"><!-- wp:avatar {"size":40,"style":{"border":{"radius":"20px"}}} /--></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:comment-author-name {"fontSize":"small"} /-->

<!-- wp:group {"style":{"spacing":{"margin":{"top":"0px","bottom":"0px"}}},"layout":{"type":"flex"}} -->
<div class="wp-block-group" style="margin-top:0px;margin-bottom:0px"><!-- wp:comment-date {"fontSize":"small"} /-->

<!-- wp:comment-edit-link {"fontSize":"small"} /--></div>
<!-- /wp:group -->

<!-- wp:comment-content /-->

<!-- wp:comment-reply-link {"fontSize":"small"} /--></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->
<!-- /wp:comment-template -->

<!-- wp:comments-pagination /-->

<!-- wp:post-comments-form /--></div>
<!-- /wp:comments --></main>
<!-- /wp:group -->
