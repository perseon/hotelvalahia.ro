<?php
$audio_embed = sanitize_text_field(get_post_meta( get_the_ID(), '_format_audio_embed', true));
$audio_shortcode = sanitize_text_field(get_post_meta( get_the_ID(), '_format_audio_shortcode', true));

$embed_code = false;
if (isset($audio_embed) && $audio_embed > ""){
	$embed_code = wp_oembed_get($audio_embed, array('width'=>328));
}elseif($audio_shortcode > ""){
	$embed_code = do_shortcode($audio_shortcode);
}
?>
<div class="post-inner">
    <?php
	if ($embed_code > ""){ ?>
    <div class="audio-embed">
        <?php echo $embed_code; // sanitize_text_field() just above. ?>
    </div>
    <?php } ?>
	<?php
	if (! is_single()){ ?>
		<<?php echo esc_attr($settings['title_size']); ?> class="post-title"><a href="<?php esc_url(the_permalink()); ?>"><?php the_title(); ?></a></<?php echo esc_attr($settings['title_size']); ?>>
	<?php } ?>
   <?php include('entry-meta-masonry.php'); ?>
    <?php
	if (is_single() || (!is_single() && !$automatic_post_excerpts)){
			$content = apply_filters( 'the_content', get_the_content() );
			$content = str_replace( ']]>', ']]&gt;', $content );
			if($content != ""){ ?>
            	<div class="entry-content">
					<?php echo $content; // get_the_content() already sanitized. used just above. ?>
                </div>
			<?php }
	}else{
		$excerpt = apply_filters( 'the_excerpt', get_the_excerpt() );
		$excerpt = str_replace( ']]>', ']]&gt;', $excerpt );
			if($excerpt != ""){ ?>
            	<div class="entry-content post-excerpt">
					<?php echo wp_kses_post( $excerpt ); ?>
                </div>
			<?php }
    } ?>
    <?php include('entry-meta-footer-masonry.php'); ?>
</div>
