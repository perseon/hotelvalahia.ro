<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Themo_Widget_MPHB_Booking_Form extends Widget_Base {
    
    public function get_name() {
        return 'themo-mphb-booking-form';
    }

    public function get_title() {
        return __( 'Accommodation Booking Request', ALOHA_DOMAIN );
    }

    public function get_icon() {
        return 'th-editor-icon-calendar-1';
    }

    public function get_categories() {
        return [ 'themo-mbhb' ];
    }

    public function get_help_url() {
        return ALOHA_WIDGETS_HELP_URL_PREFIX . $this->get_name();
    }
    
    public function is_reload_preview_required() {
        return true;
    }
    public function get_style_depends() {
        $modified2 = filemtime(THEMO_PATH . 'css/'.ALOHA_ELEMENTOR_CALENDAR_AND_OTHER_FORMS_FILENAME.'.css');
        wp_register_style(ALOHA_ELEMENTOR_CALENDAR_AND_OTHER_FORMS_FILENAME, THEMO_URL . 'css/'.ALOHA_ELEMENTOR_CALENDAR_AND_OTHER_FORMS_FILENAME.'.css', array(), $modified2);
        
        $styleArray = [ALOHA_ELEMENTOR_CALENDAR_AND_OTHER_FORMS_FILENAME];
        
        $themo_mphb_styling = get_theme_mod( 'themo_mphb_use_theme_styling', true );
        if ($themo_mphb_styling) {
            $modified = filemtime(THEMO_PATH . 'css/themo-mphb-booking-form.css');
            wp_register_style('themo-mphb-booking-form', THEMO_URL . 'css/themo-mphb-booking-form.css', array(), $modified);
            $styleArray[] = 'themo-mphb-booking-form';
            
        }
        return $styleArray;
    }
    public function get_script_depends() {
//       $modifiedJS = filemtime(THEMO_PATH . 'js/'.ALOHA_ELEMENTOR_CALENDAR_AND_OTHER_FORMS_FILENAME.'.js');
//        wp_register_script(ALOHA_ELEMENTOR_CALENDAR_AND_OTHER_FORMS_FILENAME, THEMO_URL . 'js/'.ALOHA_ELEMENTOR_CALENDAR_AND_OTHER_FORMS_FILENAME.'.js', ['jquery','elementor-frontend'], $modifiedJS, true);  
//        wp_localize_script(ALOHA_ELEMENTOR_CALENDAR_AND_OTHER_FORMS_FILENAME, 'themo_mphb_booking_form', array(
//          'calendar_prefix' => ALOHA_ELEMENTOR_CALENDAR_CLASS_PREFIX,
//        ));
//        return [ALOHA_ELEMENTOR_CALENDAR_AND_OTHER_FORMS_FILENAME];
        return [];
    }
    protected function register_controls() {
        $this->start_controls_section(
            'section_shortcode',
            [
                'label' => __( 'Availability Calendar', ALOHA_DOMAIN ),
            ]
        );


        $this->add_control('type_id', array(
            'type'        => Controls_Manager::TEXT,
            'label'       => __('Accommodation Type ID', ALOHA_DOMAIN),
            'default'     => '',
            'label_block' => true,
            'dynamic' => [
                'active' => true,
            ]
        ));

        $this->add_control(
            'inline_form',
            [
                'label' => __( 'Form Style', ALOHA_DOMAIN ),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => __( 'Default', ALOHA_DOMAIN ),
                    'inline' => __( 'Inline', ALOHA_DOMAIN ),
                    'stacked' => __( 'Stretched', ALOHA_DOMAIN ),

                ],
            ]
        );

        $this->add_control(
            'slide_shortcode_border',
            [
                'label' => __( 'Form Background', ALOHA_DOMAIN ),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => __( 'None', ALOHA_DOMAIN ),
                    'th-form-bg th-light-bg' => __( 'Light', ALOHA_DOMAIN ),
                    'th-form-bg th-dark-bg' => __( 'Dark', ALOHA_DOMAIN ),

                ],
                'condition' => [
                    'inline_form' => 'stacked',
                ],
            ]
        );



        $this->add_control(
            'slide_text_align',
            [
                'label' => __( 'Align', ALOHA_DOMAIN ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', ALOHA_DOMAIN ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', ALOHA_DOMAIN ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', ALOHA_DOMAIN ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default' => 'center',
            ]
        );

        /*$this->add_control(
            'calendar_align',
            [
                'label' => __( 'Center Align', ALOHA_DOMAIN ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => __( 'Yes', ALOHA_DOMAIN ),
                'label_off' => __( 'No', ALOHA_DOMAIN ),
                'selectors' => [
                    '{{WRAPPER}} .mphb_sc_booking_form-wrapper' => 'margin: auto;',
                    //'(mobile){{WRAPPER}} .mphb_sc_availability_calendar-wrapper .mphb-calendar .datepick' => 'margin: auto;'
                ],
            ]
        );
        $this->add_control(
            'text_align',
            [
                'label' => __( 'Text Align', ALOHA_DOMAIN ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => __( 'Yes', ALOHA_DOMAIN ),
                'label_off' => __( 'No', ALOHA_DOMAIN ),
                'selectors' => [
                    '{{WRAPPER}} .mphb_sc_booking_form-wrapper' => 'text-align: center;',
                ],
            ]
        );*/





        $this->add_control(
            'content_max_width',
            [
                'label' => __( 'Content Width', ALOHA_DOMAIN ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'size_units' => [ '%', 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .th-fo-form' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );


        $this->end_controls_section();


        $this->start_controls_section(
            'section_style_content',
            [
                'label' => __( 'Content', ALOHA_DOMAIN ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        
//        $this->add_control(
//            'calendar_styles_label',
//            [
//                'label' => __( 'Calendar Styling', ALOHA_DOMAIN ),
//                'type' => Controls_Manager::HEADING,
//                'separator' => 'before',
//            ]
//        );
//        $this->add_control(
//                'date_booked_color',
//                [
//                    'label' => esc_html__('Date Highlight Color', ALOHA_DOMAIN ),
//                    'type' => \Elementor\Controls_Manager::COLOR,
//                ]
//        );
//        
//        $this->add_control(
//                'date_available_color',
//                [
//                    'label' => esc_html__('Date Available Color', ALOHA_DOMAIN ),
//                    'type' => \Elementor\Controls_Manager::COLOR,
//                ]
//        );
        
        $this->add_control(
            'section_heading_form_labels',
            [
                'label' => __( 'Form Labels', 'elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'hide_form_lables',
            [
                'label' => __( 'Hide Field Labels', ALOHA_DOMAIN ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => __( 'Yes', ALOHA_DOMAIN ),
                'label_off' => __( 'No', ALOHA_DOMAIN ),
                'selectors' => [
                    '{{WRAPPER}} .mphb_sc_booking_form-wrapper label, 
                    {{WRAPPER}} .mphb_sc_booking_form-wrapper .mphb-check-in-date-wrapper br,
                    {{WRAPPER}} .mphb_sc_booking_form-wrapper .mphb-check-out-date-wrapper br' => 'display:none;',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Label Text Color', ALOHA_DOMAIN ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .mphb_sc_booking_form-wrapper label' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .mphb_sc_booking_form-wrapper .mphb-reserve-room-section p' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .mphb_sc_booking_form-wrapper .mphb-errors-wrapper p, {{WRAPPER}} .thmv_mphb_booking_form_help p' => 'color: {{VALUE}};',
                ],

                'condition' => [
                    'hide_form_lables' => '',
                ],
            ]
        );


        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_color_typography',
                'selector' => '{{WRAPPER}} .mphb_sc_booking_form-wrapper label',

                'condition' => [
                    'hide_form_lables' => '',
                ],
            ]
        );

        $this->add_control(
            'hide_required_notices',
            [
                'label' => __( 'Hide Required Tips', ALOHA_DOMAIN ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => __( 'Yes', ALOHA_DOMAIN ),
                'label_off' => __( 'No', ALOHA_DOMAIN ),
                'selectors' => [
                    '{{WRAPPER}} .mphb_sc_booking_form-wrapper .mphb-required-fields-tip' => 'display:none;',
                    '{{WRAPPER}} .mphb_sc_booking_form-wrapper label abbr' => 'display:none;',
                ],
            ]
        );

        $this->add_control(
            'tip_color',
            [
                'label' => __( 'Required Tips Colour', ALOHA_DOMAIN ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .mphb-required-fields-tip small' => 'color: {{VALUE}};',
                ],

                'condition' => [
                    'hide_required_notices' => '',
                ],

            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tip_color_typography',
                'selector' => '{{WRAPPER}} .mphb-required-fields-tip small',

                'condition' => [
                    'hide_required_notices' => '',
                ],

            ]
        );

        $this->add_control(
            'section_heading_form',
            [
                'label' => __( 'Form Fields', 'elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'form_field_text',
            [
                'label' => __( 'Text', ALOHA_DOMAIN ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .mphb_sc_booking_form-wrapper.frm_forms.with_frm_style input[type=text],
                    {{WRAPPER}} .mphb_sc_booking_form-wrapper.frm_forms.with_frm_style select' => 'color: {{VALUE}} !important;',
                    '{{WRAPPER}} .mphb_sc_booking_form-wrapper.frm_forms.with_frm_style .mphb-reserve-room-section p' => 'color: {{VALUE}};',

                ],

            ]
        );

        $this->add_control(
            'form_field_placeholder_text',
            [
                'label' => __( 'Placeholder', ALOHA_DOMAIN ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .mphb_sc_booking_form-wrapper.frm_forms.with_frm_style input[type=text]::placeholder' => 'color: {{VALUE}}; opacity: 1;',
                    '{{WRAPPER}} .mphb_sc_booking_form-wrapper.frm_forms.with_frm_style input[type=text]:-ms-input-placeholder' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .mphb_sc_booking_form-wrapper.frm_forms.with_frm_style input[type=text]::-ms-input-placeholder' => 'color: {{VALUE}};',
                ],

            ]
        );

        $this->add_control(
            'form_field_bg_colour',
            [
                'label' => __( 'Background', ALOHA_DOMAIN ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .mphb_sc_booking_form-wrapper.frm_forms.with_frm_style input[type=text]' => 'background-color: {{VALUE}};',
                ],

            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'label' => __( 'Border', 'elementor' ),
                'selector' => '{{WRAPPER}} .mphb_sc_booking_form-wrapper.frm_forms.with_frm_style input[type=text],
                {{WRAPPER}} .mphb_sc_booking_form-wrapper.frm_forms.with_frm_style input[type=text]:focus',
            ]
        );

        $this->add_control(
            'section_heading_form_button',
            [
                'label' => __( 'Form Button', 'elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'button_1_style',
            [
                'label' => __( 'Button Style', ALOHA_DOMAIN ),
                'type' => Controls_Manager::SELECT,
                'default' => 'standard-primary',
                'options' => [
                    'standard-primary' => __( 'Standard Primary', ALOHA_DOMAIN ),
                    'standard-accent' => __( 'Standard Accent', ALOHA_DOMAIN ),
                    'standard-light' => __( 'Standard Light', ALOHA_DOMAIN ),
                    'standard-dark' => __( 'Standard Dark', ALOHA_DOMAIN ),
                    'ghost-primary' => __( 'Ghost Primary', ALOHA_DOMAIN ),
                    'ghost-accent' => __( 'Ghost Accent', ALOHA_DOMAIN ),
                    'ghost-light' => __( 'Ghost Light', ALOHA_DOMAIN ),
                    'ghost-dark' => __( 'Ghost Dark', ALOHA_DOMAIN ),
                    'cta-primary' => __( 'CTA Primary', ALOHA_DOMAIN ),
                    'cta-accent' => __( 'CTA Accent', ALOHA_DOMAIN ),
                ],
            ]
        );

        $this->add_control(
            'button_text_colour',
            [
                'label' => __( 'Button Text Color', ALOHA_DOMAIN ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .mphb_sc_booking_form-wrapper .mphb-reserve-btn-wrapper.frm_submit input[type=submit],
                    {{WRAPPER}} .mphb_sc_booking_form-wrapper .mphb-confirm-reservation' => 'color: {{VALUE}};',
                ],


            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'button_text_typography',
                'selector' => '{{WRAPPER}} .mphb_sc_booking_form-wrapper .mphb-reserve-btn-wrapper.frm_submit input[type=submit],
                 {{WRAPPER}} .mphb_sc_booking_form-wrapper .mphb-confirm-reservation',

            ]
        );



        $this->end_controls_section();
    }

    private function setCalendarColors() {
        $settings = $this->get_settings_for_display();
        $suffix = ALOHA_ELEMENTOR_CALENDAR_CLASS_PREFIX . $this->get_id();
        aloha_print_calendar_styles($settings, $suffix);
    }

    protected function render() {

        global $post;

        $settings = $this->get_settings_for_display();

        // If Accommodation type id field is empty, try to get the id automatically.
        if ( !isset( $settings['type_id'] ) || empty( $settings['type_id']) ) {
            if(isset($post->ID )&& $post->ID > ""){
                $postID = $post->ID;
                $themo_post_type = get_post_type($postID);
                if(isset($themo_post_type) && $themo_post_type=='mphb_room_type'){
                    $settings['type_id'] = $postID;
                }
            }
        }

        if ( isset( $settings['type_id'] ) && ! empty( $settings['type_id']) && is_numeric($settings['type_id']) ) {
            $this->setCalendarColors();
            
            /*if ( isset( $settings['months_to_show'] ) && ! empty( $settings['months_to_show'] ) && is_numeric($settings['months_to_show'])) {
                $th_monthstoshow = $settings['months_to_show'];
            }else{
                $th_monthstoshow=2;
            }*/

            $th_shortcode = '[mphb_availability id="'.$settings['type_id'].'"]';
            $th_shortcode = sanitize_text_field( $th_shortcode );
            $th_shortcode = do_shortcode( shortcode_unautop( $th_shortcode ) );

            // Check to see if the shortcode returned a form. If not, there are no valid seasons or rates for this accommodation Type.
            $thmv_shortcode_neddle = '<form';
            $thmv_shortcode_error_msg = esc_html__('No valid season or rate found for this Accommodation. Has one been added yet?', ALOHA_DOMAIN);

            if (strpos($th_shortcode, $thmv_shortcode_neddle) !== false) {
                // Add in special classes
                if ( function_exists( 'get_theme_mod' ) ) {
                    $themo_mphb_styling = get_theme_mod('themo_mphb_use_theme_styling', true);
                    if ($themo_mphb_styling == true) {

                        // Check Availabilty button
                        $th_shortcode = str_replace(
                            'mphb-reserve-btn-wrapper',
                            'mphb-reserve-btn-wrapper frm_submit',
                            $th_shortcode
                        );
                        // Confirm Reservation button
                        $th_shortcode = str_replace(
                            'mphb-reserve-room-section',
                            'mphb-reserve-room-section frm_submit',
                            $th_shortcode
                        );
                        // Date picker / checkin / checkout form
                        $th_shortcode = str_replace(
                            'mphb_sc_booking_form-wrapper',
                            'mphb_sc_booking_form-wrapper frm_forms with_frm_style',
                            $th_shortcode
                        );
                        // Check-in field
                        $th_shortcode = str_replace(
                            'mphb-check-in-date-wrapper',
                            'mphb-check-in-date-wrapper frm_form_field',
                            $th_shortcode
                        );
                        // Check-out field
                        $th_shortcode = str_replace(
                            'mphb-check-out-date-wrapper',
                            'mphb-check-out-date-wrapper frm_form_field',
                            $th_shortcode
                        );

                        // Dropdowns Adults
                        $th_shortcode = str_replace(
                            'mphb-adults-wrapper',
                            'mphb-adults-wrapper frm_form_field',
                            $th_shortcode
                        );
                        // Dropdowns Children
                        $th_shortcode = str_replace(
                            'mphb-children-wrapper',
                            'mphb-children-wrapper frm_form_field',
                            $th_shortcode
                        );
                        // Dropdowns Children
                        /*$th_shortcode = str_replace(
                            'mphb-check-children-date-wrapper',
                            'mphb-check-children-date-wrapper frm_form_field',
                            $th_shortcode
                        );*/
                    }
                }



                $th_form_border_class = false;
                $th_formidable_class = 'th-form-default';
                if ( isset( $settings['inline_form'] ) && $settings['inline_form'] > "" ) :
                    switch ( $settings['inline_form'] ) {
                        case 'stacked':
                            $th_formidable_class = 'th-form-stacked';
                            if ( isset( $settings['slide_shortcode_border'] ) && $settings['slide_shortcode_border'] != 'none' ) {
                                $th_form_border_class = $settings['slide_shortcode_border'];
                            }
                            break;
                        case 'inline':
                            $th_formidable_class = 'th-conversion';
                            break;
                    }
                endif;

                /* Form Styling */
                $th_cal_align_class = false;
                if ( isset( $settings['slide_text_align'] ) && $settings['slide_text_align'] > "" ) {
                    switch ( $settings['slide_text_align'] ) {
                        case 'left':
                            $th_cal_align_class = ' th-left';
                            break;
                        case 'center':
                            $th_cal_align_class = ' th-centered';
                            break;
                        case 'right':
                            $th_cal_align_class = ' th-right';
                            break;
                    }
                }

                $this->add_render_attribute( 'th-form-class', 'class', 'th-fo-form');
                $this->add_render_attribute( 'th-form-class', 'class', esc_attr( $th_cal_align_class ) );
                $this->add_render_attribute( 'th-form-class', 'class', esc_attr( $th_formidable_class ) );
                $this->add_render_attribute( 'th-form-class', 'class', esc_attr( $th_form_border_class ) );
                $this->add_render_attribute( 'th-form-class', 'class', 'th-btn-form' );
                $this->add_render_attribute( 'th-form-class', 'class', 'btn-' . esc_attr( $settings['button_1_style'] . '-form' ) );

                $themo_form_styling = false;
                if ( function_exists( 'get_theme_mod' ) ) {
                    $themo_mphb_styling = get_theme_mod('themo_mphb_use_theme_styling', true);
                    if ($themo_mphb_styling == true) {
                        $themo_form_styling = $this->get_render_attribute_string( 'th-form-class');
                    }
                } ?>
                <div <?php echo $themo_form_styling; ?>><?php echo $th_shortcode; ?></div>
                <?php

            }else{
                echo "<div class='thmv_mphb_booking_form_help'><p>".$thmv_shortcode_error_msg."</p></div>";
            }
        }else{
            $thmv_shortcode_error_msg = esc_html__('Accommodation Type ID not found. Has one been entered?', ALOHA_DOMAIN);
            echo "<div class='thmv_mphb_booking_form_help'><p>".$thmv_shortcode_error_msg."</p></div>";
        }
    }

    public function render_plain_content() {
        // In plain mode, render without shortcode
        echo $this->get_settings( 'shortcode' );
    }

    protected function content_template() {}

}

Plugin::instance()->widgets_manager->register( new Themo_Widget_MPHB_Booking_Form() );
