<?php
DEFINE('ALOHA_HFE_SINGLE', 'type_single');
DEFINE('ALOHA_HFE_STICKY_HEADER', 'type_header_sticky');
DEFINE('ALOHA_HFE_HEADER', 'type_header');

DEFINE('ALOHA_HFE_OVERRIDES_PATH', __DIR__);
DEFINE('ALOHA_HFE_OVERRIDES_URL', ALOHA_URL . basename(__DIR__) . '/');

/**
 * take all constants from the header-footer-elementor.php file
 */
function aloha_hfe_defines() {
    define('HFE_VER', '1.6.9');
    define('HFE_FILE', ALOHA_HFE_PATH . '/header-footer-elementor.php');
    define('HFE_DIR', plugin_dir_path(HFE_FILE));
    define('HFE_URL', plugins_url('/', HFE_FILE));
    define('HFE_PATH', plugin_basename(HFE_FILE));
    define('HFE_DOMAIN', trailingslashit('https://ultimateelementor.com'));
}

use HFE\Lib\Astra_Target_Rules_Fields;

/** the function must have another function after it with /** in the beginning otherwise it will fail
 * 
 * @param type $function
 */
function aloha_hfe_overrideHFEFunction($oldFunctionName, $newFunctionName, $searchReplace, $fileName) {
    $pattern = '/(function ' . $oldFunctionName . '.*?)(?=\/\*\*)/sm';
    $adminFileContents = file_get_contents(ALOHA_HFE_PATH . $fileName);

    $found = preg_match($pattern, $adminFileContents, $function);
    if ($found && isset($function[1])) {
        $functionRaw = $function[1]; //we get extra () { after the function
        $searchReplace[$oldFunctionName] = $newFunctionName;
        $searchReplace[ALOHA_HFE_OLD_POST_TYPE] = ALOHA_HFE_NEW_POST_TYPE; //replace all elementor-hf to elementor-thhf
        foreach ($searchReplace as $search => $replace) {
            $functionRaw = str_replace($search, $replace, $functionRaw);
        }

        $newFunction = $functionRaw;
        @eval($newFunction);
    }
}

function aloha_hfe_override_update_file() {
    aloha_hfe_override_file('/inc/class-hfe-update.php');
}

function aloha_hfe_override_header_footer_elementor_file() {
    $overrides = [
        "require_once HFE_DIR . 'admin/class-hfe-admin.php';" => '',
        "require_once HFE_DIR . 'inc/hfe-functions.php';" => '',
        "type_before_footer' == " . '$setting' => 'type_before_footer\' == $setting || \'' . ALOHA_HFE_SINGLE . '\' == $setting || \'' . ALOHA_HFE_STICKY_HEADER . '\' == $setting',
        'add_action( \'admin_init\', [ $this, \'register_notices\' ] );' => '',
        'add_action( \'admin_init\', [ $this, \'show_setup_wizard\' ] );' => '',
        'add_action( \'init\', [ $this, \'setup_fallback_support\' ] );' => '',
    ];
    $overrides["require HFE_DIR . 'inc/widgets-manager/class-widgets-loader.php'"] = '';

    aloha_hfe_override_file('/inc/class-header-footer-elementor.php', $overrides);
}

function aloha_hfe_override_hfe_admin_file() {
    $sticky_header = '<option value="<?php echo ALOHA_HFE_STICKY_HEADER ?>" <?php selected( $template_type, ALOHA_HFE_STICKY_HEADER ); ?> ><?php _e("Sticky Header", ALOHA_DOMAIN) ?></option>';
    $single = '<option value="<?php echo ALOHA_HFE_SINGLE ?>" <?php selected( $template_type, ALOHA_HFE_SINGLE ); ?> ><?php _e("Single", ALOHA_DOMAIN) ?></option>';
    $transparent_header_row = '<?php '
            . '$show_transparent_header_row = $template_type === ALOHA_HFE_HEADER || $template_type === ALOHA_HFE_STICKY_HEADER;'
            . '$transparent_header = isset( $values[\'transparent-header\'] ) ? true : false;?>';
    $transparent_header_row .= '<tr class="hfe-options-row transparent-header" <?php echo $show_transparent_header_row ? "" : \'style="display:none;"\' ?>>' .
            '<td class="hfe-options-row-heading">' .
            '<label for="transparent-header">' .
            "<?php echo __('Transparent Header Support', ALOHA_DOMAIN)?>" .
            '</label>' .
            '<i class="hfe-options-row-heading-help dashicons dashicons-editor-help" title="<?php echo __(\'Make this header the top layer and overlap page content. A great effect with semi-transparent header backgrounds.\', ALOHA_DOMAIN) ?>"></i>' .
            '</td>' .
            '<td class="hfe-options-row-content">' .
            '<input type="checkbox" id="transparent-header" name="transparent-header" value="1" <?php checked($transparent_header, true) ?> />' .
            '</td>' .
            '</tr>';
    $overrides = [
        'Elementor Header & Footer Builder' => 'Global Templates',
        'add_filter( \'manage_elementor-hf_posts_columns\', [ $this, \'set_shortcode_columns\' ] );' => '',
        "'id'    => get_hfe_footer_id()," => "'id'    => get_thhf_sticky_header_id()," .
        "'value' => 'Sticky Header'" .
        " ]," .
        "['id'    => get_hfe_footer_id(),",
        '<option value="type_before_footer"' => $sticky_header . '<option value="type_before_footer"',
        '<option value="custom"' => $single . '<option value="custom"',
        '<tr class="hfe-options-row enable-for-canvas' => $transparent_header_row . '<tr class="hfe-options-row enable-for-canvas',
    ];

    aloha_hfe_override_file('/admin/class-hfe-admin.php', $overrides);
    $hfeAdmin = HFE_Admin::instance();
    remove_action('admin_menu', [$hfeAdmin, 'register_admin_menu'], 50);
}

function aloha_hfe_override_functions_file() {
    $transparent = '$transparent_header = get_post_meta(get_thhf_header_id(), "transparent-header", false);'
            . '$render_class = $transparent_header ? "transparent-header" : "";';
    $stickyheader = 'thhf_render_sticky_header(); ';
    
    $header_check = ' if(!hfe_header_enabled()){return false;}';
    $overrides = [
        'hfe_render_header() {' => 'hfe_render_header() {' . $stickyheader.$header_check.$transparent,
        'id="masthead"' => 'id="thhf-masthead" class="<?php echo $render_class; ?>"',
    ];

    aloha_hfe_override_file('/inc/hfe-functions.php', $overrides);
}

/**
 * Display sticky header markup.
 *
 * @since  1.0.2
 */
function thhf_render_sticky_header() {
    
    if (false == apply_filters('enable_thhf_render_sticky_header', true)) {
        return;
    }

    if(!thhf_sticky_header_enabled()){
        return false;
    }
    
    $transparent_header = get_post_meta(get_thhf_sticky_header_id(), 'transparent-header', false);
    $sticky_stacked = get_post_meta(get_thhf_sticky_header_id(), 'sticky-stacked', true);

    $render_class = '';
    if ($transparent_header) {
        $render_class .= 'transparent-header';
    }
    if ($sticky_stacked) {
        $render_class .= ' sticky-stacked';
    }
    ?>
    <header id="thhf-masthead-sticky" class="<?php echo $render_class; ?>" itemscope="itemscope" itemtype="https://schema.org/WPHeader">
        <p class="main-title bhf-hidden" itemprop="headline"><a href="<?php echo bloginfo('url'); ?>" title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" rel="home"><?php bloginfo('name'); ?></a></p>
        <?php aloha_hfe_get_sticky_header_content(); ?>
    </header>

    <?php
}

function thhf_header_enabled() {
    return hfe_header_enabled() || thhf_sticky_header_enabled();
}

function get_thhf_header_id() {
    return get_hfe_header_id();
}

function thhf_render_header() {
    if(thhf_header_enabled()){
        hfe_render_header();
    }
}

function thhf_is_before_footer_enabled() {
    return hfe_is_before_footer_enabled();
}

function thhf_render_before_footer() {
    hfe_render_before_footer();
}

function get_thhf_footer_id() {
    return get_hfe_footer_id();
}

function thhf_render_footer() {
    hfe_render_footer();
}

function thhf_footer_enabled() {
    return hfe_footer_enabled();
}

function thhf_is_single_post_enabled() {
    return thhf_single_enabled();
}

/**
 * Render the header if display template on elementor canvas is enabled
 * and current template is Elementor Canvas
 */
function aloha_hfe_canvas_render_sticky_header() {

    // bail if current page template is not Elemenntor Canvas.
    if ('elementor_canvas' !== get_page_template_slug()) {
        return;
    }

    $override_cannvas_template = get_post_meta(get_thhf_header_id(), 'display-on-canvas-template', true);

    if ('1' == $override_cannvas_template) {
        thhf_render_sticky_header();
    }
}

/**
 * Render the siugle if display template on elementor canvas is enabled
 * and current template is Elementor Canvas
 */
function aloha_hfe_canvas_render_single() {
    if ('elementor_canvas' !== get_page_template_slug()) {
        return;
    }

    $override_cannvas_template = get_post_meta(get_thhf_single_id(), 'display-on-canvas-template', true);

    if ('1' == $override_cannvas_template) {
        thhf_render_single_post();
    }
}

/**
 * Display Single markup.
 *
 */
function thhf_render_single_post() {

    //check if elementor edit mode and editing the post or page type, then call the_content
    $post_type = get_post_type();
    $preview = isset($_REQUEST['elementor-preview']) ? true : false;
    if ($post_type !== ALOHA_HFE_NEW_POST_TYPE && $preview) {
        the_content();
    } else {
        if (false == apply_filters('enable_thhf_render_single', true)) {
            return;
        }
        ?>
        <div class="single-post-container">
            <?php aloha_hfe_get_single_post_content(); ?>
        </div>
        <?php
    }
}

function aloha_hfe_override_file($file, $searchReplace = []) {
    $adminFileContentsRaw = file_get_contents(ALOHA_HFE_PATH . $file);
    $searchReplace[ALOHA_HFE_OLD_POST_TYPE] = ALOHA_HFE_NEW_POST_TYPE; //replace all elementor-hf to elementor-thhf
    $adminFileContents = preg_replace('/<\?php/', '/**STARTING PHP REPLACED **/', $adminFileContentsRaw, 1);

    foreach ($searchReplace as $search => $replace) {
        $adminFileContents = str_replace($search, $replace, $adminFileContents);
    }
//    if (strpos($file, 'retina') !== false) {
//      echo $adminFileContents;exit;
//    }
    eval($adminFileContents);
}

function aloha_default_header_override(){
    
    if(get_the_ID()){
        $is_full_width_template = get_post_meta(get_the_ID(), '_wp_page_template', true) === 'elementor_header_footer'; 
        if($is_full_width_template){
            $overrides = [
             'new HFE_Default_Compat();'=>'',
             'hfe_header_enabled' => 'thhf_header_enabled',
             'hfe_render_header' => 'thhf_render_header',
             'HFE_Default_Compat'=>'THHF_Default_Compat'
            ];
            aloha_hfe_override_file('/themes/default/class-hfe-default-compat.php', $overrides);
            $default_compat = new HFE\Themes\THHF_Default_Compat();
            $default_compat->hooks();
        }
         
    }
}

function aloha_hfe_default_compat($isStickyHeader = false, $isSingle = false) {
    require_once ALOHA_HFE_PATH . '/themes/default/class-hfe-default-compat.php';
    $defaultCompat = new HFE\Themes\HFE_Default_Compat();
    if ($isStickyHeader) {
        // Replace header.php template.
        add_action('get_header', [$defaultCompat, 'override_header']);
        // Display HFE's sticky header in the replaced header.
        add_action('hfe_header', 'thhf_render_sticky_header');

        //for Canvas only
        // Action `elementor/page_templates/canvas/before_content` is introduced in Elementor Version 1.4.1.
        if (version_compare(ELEMENTOR_VERSION, '1.4.1', '>=')) {
            add_action('elementor/page_templates/canvas/before_content', 'aloha_hfe_canvas_render_sticky_header');
        } else {
            add_action('wp_head', 'aloha_hfe_canvas_render_sticky_header');
        }
    }
    if ($isSingle) {
//
//        //for Canvas only
//        // Action `elementor/page_templates/canvas/before_content` is introduced in Elementor Version 1.4.1.
//        if (version_compare(ELEMENTOR_VERSION, '1.9.0', '>=')) {
//            add_action('elementor/page_templates/canvas/after_content', 'aloha_hfe_canvas_render_single', 0);
//        } else {
//            add_action('wp_footer', 'aloha_hfe_canvas_render_single');
//        }
    }
}

function aloha_register_render_hooks() {
    $template = get_template();

    $isSingle = thhf_single_enabled() && (is_single() || is_page()) && is_main_query();
    $isStickyHeader = thhf_sticky_header_enabled();

    if (is_themovation_template()) {
        //aloha_hfe_default_compat($isStickyHeader, $isSingle);
    } elseif ('genesis' == $template) {
        
    } elseif ('astra' == $template) {
        $astraCompat = HFE_Astra_Compat::instance();
        if ($isStickyHeader) {
            add_action('template_redirect', [$astraCompat, 'astra_setup_header'], 10);
            add_action('astra_header', 'thhf_render_sticky_header');

            // Action `elementor/page_templates/canvas/before_content` is introduced in Elementor Version 1.4.1.
            if (version_compare(ELEMENTOR_VERSION, '1.4.1', '>=')) {
                add_action('elementor/page_templates/canvas/before_content', 'aloha_hfe_canvas_render_sticky_header');
            } else {
                add_action('wp_head', 'aloha_hfe_canvas_render_sticky_header');
            }
        }

        if ($isSingle) {
            $astra = Astra_Loop::get_instance();
            remove_action('astra_content_loop', array($astra, 'loop_markup'));
            remove_action('astra_content_page_loop', array($astra, 'loop_markup_page'));
            add_action('astra_content_loop', 'thhf_render_single_post');
            add_action('astra_content_page_loop', 'thhf_render_single_post');

            $astraWoo = Astra_Woocommerce::get_instance();

            remove_action('woocommerce_before_main_content', array($astraWoo, 'before_main_content_start'));
            add_action('woocommerce_before_main_content', 'thhf_render_single_post');
            remove_action('woocommerce_after_main_content', array($astraWoo, 'before_main_content_end'));
        }
    } elseif ('bb-theme' == $template || 'beaver-builder-theme' == $template) {
        $template = 'beaver-builder-theme';
    } elseif ('generatepress' == $template) {
        
    } elseif ('oceanwp' == $template) {
        
    } elseif ('storefront' == $template) {
        
    } elseif ('hello-elementor' == $template) {
        aloha_hfe_default_compat($isStickyHeader, $isSingle);
    } else {
        //unsupported templates
        aloha_hfe_default_compat($isStickyHeader, $isSingle);
    }


//    if (thhf_single_enabled()) {
//        // Action `elementor/page_templates/canvas/before_content` is introduced in Elementor Version 1.4.1.
//        if (version_compare(ELEMENTOR_VERSION, '1.9.0', '>=')) {
//            add_action('elementor/page_templates/canvas/after_content', [$this, 'render_single'], 0);
//        } else {
//            add_action('wp_footer', [$this, 'render_single']);
//        }
//    }
}

/** order by the the most recently modified
 * 
 * @param type $query
 */
function thhf_order_category($query) {

    if (is_admin() && $query->is_main_query() && $query->query_vars['post_type'] === 'elementor-thhf') {
        $query->set('order', 'DESC');
        $query->set('orderby', 'modified');
    }
}

function aloha_hfe_admin_scripts() {

    $postID = get_the_ID();
    $postType = get_post_type($postID);
    if (!$postID || $postType != ALOHA_HFE_NEW_POST_TYPE) {
        return;
    }

    //add before .hfe-options-row.enable-for-canvas
    $header_types = [ALOHA_HFE_HEADER, ALOHA_HFE_STICKY_HEADER];

    $file = 'aloha-hfe.js';
    $modified = filemtime(ALOHA_JS_PATH . '/' . $file);

    wp_enqueue_script('aloha-hfe', ALOHA_JS_URL . '/' . $file, array('jquery'), $modified, true);
    wp_localize_script('aloha-hfe', 'aloha_hfe_vars', array(
        'header_types' => $header_types,
    ));
}

/**
 * Get HFE Sticky Header ID
 *
 * @since  1.0.2
 * @return (String|boolean) sticky header id if it is set else returns false.
 */
function get_thhf_sticky_header_id() {
    $sticky_header_id = Header_Footer_Elementor::get_settings(ALOHA_HFE_STICKY_HEADER, '');

    if ('' === $sticky_header_id) {
        $sticky_header_id = false;
    }

    return apply_filters('get_thhf_sticky_header_id', $sticky_header_id);
}

/**
 * Checks if Sticky Header is enabled from HFE.
 *
 * @since  1.0.2
 * @return bool True if sticky header is enabled. False if header is not enabled
 */
function thhf_sticky_header_enabled() {
    $header_id = Header_Footer_Elementor::get_settings(ALOHA_HFE_STICKY_HEADER, '');
    $status = false;

    if ('' !== $header_id) {
        $status = true;
    }

    return apply_filters('thhf_sticky_header_enabled', $status);
}

/**
 * Get HFE Footer ID
 *
 * @since  1.0.2
 * @return (String|boolean) header id if it is set else returns false.
 */
function get_thhf_single_id() {
    $single_id = Header_Footer_Elementor::get_settings(ALOHA_HFE_SINGLE, '');

    if ('' === $single_id) {
        $single_id = false;
    }

    return apply_filters('get_thhf_single_id', $single_id);
}

/**
 * Checks if Single Post Template is enabled from HFE.
 *
 * @return bool
 */
function thhf_single_enabled() {
    $single_id = Header_Footer_Elementor::get_settings(ALOHA_HFE_SINGLE, '');
    $status = false;

    if ('' !== $single_id) {
        $status = true;
    }

    return apply_filters('thhf_single_enabled', $status);
}

function aloha_hfe_save_meta($post_id) {
    // Bail if we're doing an auto save.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // if our nonce isn't there, or we can't verify it, bail.
    if (!isset($_POST['ehf_meta_nounce']) || !wp_verify_nonce($_POST['ehf_meta_nounce'], 'ehf_meta_nounce')) {
        return;
    }

    // if our current user can't edit this post, bail.
    if (!current_user_can('edit_posts')) {
        return;
    }

    if (isset($_POST['transparent-header'])) {
        update_post_meta($post_id, 'transparent-header', esc_attr($_POST['transparent-header']));
    } else {
        delete_post_meta($post_id, 'transparent-header');
    }
}

/**
 * Prints the Header content.
 */
function aloha_hfe_get_sticky_header_content() {
    $elementor_instance = aloha_hfe_get_elementor_instance();
    if ($elementor_instance) {
        echo $elementor_instance->frontend->get_builder_content_for_display(get_thhf_sticky_header_id());
    }
}

/**
 * Prints the post content.
 */
function aloha_hfe_get_single_post_content() {
    $elementor_instance = aloha_hfe_get_elementor_instance();
    if ($elementor_instance) {
        echo "<div class='single-post-width-fixer'>";
        echo $elementor_instance->frontend->get_builder_content_for_display(get_thhf_single_id());
        echo '</div>';
    }
}

function aloha_enqueue_scripts() {
    if (thhf_sticky_header_enabled()) {
        if (class_exists('\Elementor\Core\Files\CSS\Post')) {
            $css_file = new \Elementor\Core\Files\CSS\Post(get_thhf_sticky_header_id());
        } elseif (class_exists('\Elementor\Post_CSS_File')) {
            $css_file = new \Elementor\Post_CSS_File(get_thhf_sticky_header_id());
        }

        $css_file->enqueue();
    }
}

/** remove the woocommerce default output * */
function aloha_hfe_override_woocommerce_template_part($template, $slug, $name) {
    $current_template = get_template();
    $supported_templates = ['astra'];
    $isSingle = thhf_single_enabled() && (is_single() || is_page()) && is_main_query();
    if (in_array($current_template, $supported_templates) && $isSingle && !is_themovation_template() && $name === 'single-product') {
        return false;
    }
    return $template;
}

/**
 * Load the Plugin Class.
 */
function aloha_hfe_init() {

    if (!isElementorActive()) {
        return;
    }

    aloha_hfe_defines();

    //load before the hfe script so our new template types are picked up by the _init function in the scripts.
    add_action('admin_enqueue_scripts', 'aloha_hfe_admin_scripts');

    //load functions file as we need to make a change in it and we load it ourselves
    aloha_hfe_override_functions_file();

    //override the main file and instantiate it
    aloha_hfe_override_header_footer_elementor_file();
    Header_Footer_Elementor::instance();

    //override the update class, we can't override its functions directly
    aloha_hfe_override_update_file();
    
    add_action('wp', 'aloha_default_header_override');
    //add_action('wp', 'aloha_register_render_hooks');
    //do things after the files are overriden
    //load widgets and overrides
    require_once ALOHA_HFE_OVERRIDES_PATH . '/aloha_hfe_widgets.php';



    add_action('wc_get_template_part', 'aloha_hfe_override_woocommerce_template_part', 10, 3);

    add_action('pre_get_posts', 'thhf_order_category', 1);

    remove_action('plugins_loaded', 'hfe_init');

    //remove HFE functions and replace with ours where needed
    aloha_hfe_override_hfe_admin_file();

    add_action('save_post', 'aloha_hfe_save_meta');

    add_action('wp_enqueue_scripts', 'aloha_enqueue_scripts');

    if (is_admin()) {
        $search = Astra_Target_Rules_Fields::get_instance();
        //ajax search in the custom type
        remove_action('wp_ajax_hfe_get_posts_by_query', array($search, 'hfe_get_posts_by_query'));
        $searchReplaceSearch = [
            'check_ajax_referer' => '$Astra_Target_Rules_Fields = Astra_Target_Rules_Fields::get_instance(); check_ajax_referer',
            '$this' => '$Astra_Target_Rules_Fields',
            'function' => 'use HFE\Lib\Astra_Target_Rules_Fields; function'
        ];
        aloha_hfe_overrideHFEFunction('hfe_get_posts_by_query', 'aloha_hfe_get_posts_by_query', $searchReplaceSearch, '/inc/lib/target-rule/class-astra-target-rules-fields.php');
        add_action('wp_ajax_hfe_get_posts_by_query', 'aloha_hfe_get_posts_by_query');
    }
}

aloha_hfe_init();
