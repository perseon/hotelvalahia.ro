<?php
$quote = get_post_meta( get_the_ID(), '_format_quote_copy', true);
$quote_source_name = get_post_meta( get_the_ID(), '_format_quote_source_name', true);
$quote_source_title = get_post_meta( get_the_ID(), '_format_quote_source_title', true);
?>
<div class="post-inner">
        <?php if($quote > ""){
            echo '<blockquote>';
            echo '<p>'.esc_attr($quote).'</p>'; ?>
            <i class="themo-marker-icon fas fa-quote-left"></i>
        <?php
            echo '<footer>';
        if($quote_source_name > ""){
                echo esc_attr($quote_source_name);
            }
            if($quote_source_title > "") {
                echo ', <cite title="Source Title">' . esc_attr($quote_source_title) . '</cite>';
            }
            echo '</footer>';
            echo '</blockquote>';
        }else{
            $content = apply_filters( 'the_content', get_the_content() );
            $content = str_replace( ']]>', ']]&gt;', $content );
            if($content != ""){
                echo wp_kses_post( $content );
            }
        } ?>
	<?php include('entry-meta-footer-masonry.php'); ?>
</div>
