<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;

class Elementor_Video_Popup_Widget extends Widget_Base {

    public function get_name() {
        return 'video_popup_widget';
    }

    public function get_title() {
        return esc_html__( 'Video Popup', 'elementor-video-popup-addon' );
    }

    public function get_icon() {
        return 'eicon-play';
    }

    public function get_categories() {
        return [ 'general' ];
    }

    protected function register_controls() {

        // Content Tab
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Video Items', 'elementor-video-popup-addon' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'video_type',
            [
                'label' => __( 'Video Type', 'elementor-video-popup-addon' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'youtube' => __( 'YouTube', 'elementor-video-popup-addon' ),
                    'self'    => __( 'Self Hosted', 'elementor-video-popup-addon' ),
                ],
                'default' => 'youtube',
            ]
        );

        $repeater->add_control(
            'video_url',
            [
                'label' => __( 'Video URL or File', 'elementor-video-popup-addon' ),
                'type' => Controls_Manager::MEDIA,
                'media_types' => ['video'],
                'default' => [ 'url' => '' ],
            ]
        );

        $repeater->add_control(
            'video_thumbnail',
            [
                'label' => __( 'Thumbnail', 'elementor-video-popup-addon' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [ 'url' => '' ],
            ]
        );

        $repeater->add_control(
            'video_title',
            [
                'label' => __( 'Title', 'elementor-video-popup-addon' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Video Title',
            ]
        );

        $repeater->add_control(
            'video_description',
            [
                'label' => __( 'Description', 'elementor-video-popup-addon' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => '',
            ]
        );

        $this->add_control(
            'videos',
            [
                'label' => __( 'Video List', 'elementor-video-popup-addon' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [],
                'title_field' => '{{{ video_title }}}',
            ]
        );

        $this->end_controls_section();

        // Style Tab
        $this->start_controls_section(
            'style_section',
            [
                'label' => __( 'Typography & Style', 'elementor-video-popup-addon' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Title Color', 'elementor-video-popup-addon' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .evpa-video-right h3' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .evpa-video-right h3',
            ]
        );

        $this->add_control(
            'desc_color',
            [
                'label' => __( 'Description Color', 'elementor-video-popup-addon' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .evpa-video-right p' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'desc_typography',
                'selector' => '{{WRAPPER}} .evpa-video-right p',
            ]
        );

        // Add controls for the container styles (background, border, border-radius, box-shadow)
        $this->add_control(
            'container_background_color',
            [
                'label' => __( 'Container Background Color', 'elementor-video-popup-addon' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .evpa-video-popup-container' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'container_border_color',
            [
                'label' => __( 'Container Border Color', 'elementor-video-popup-addon' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .evpa-video-popup-container' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'container_border_radius',
            [
                'label' => __( 'Container Border Radius', 'elementor-video-popup-addon' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .evpa-video-popup-container' => 'border-radius: {{SIZE}}px;',
                ],
            ]
        );

        $this->add_control(
            'container_box_shadow',
            [
                'label' => __( 'Container Box Shadow', 'elementor-video-popup-addon' ),
                'type' => Controls_Manager::BOX_SHADOW,
                'selectors' => [
                    '{{WRAPPER}} .evpa-video-popup-container' => 'box-shadow: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        if ( empty( $settings['videos'] ) ) return;

        foreach ( $settings['videos'] as $video ) {
            $video_url = $video['video_url']['url'];

            if ( $video['video_type'] === 'youtube' && strpos( $video_url, 'watch?v=' ) !== false ) {
                preg_match( '/v=([a-zA-Z0-9_-]+)/', $video_url, $matches );
                if ( isset( $matches[1] ) ) {
                    $video_url = 'https://www.youtube.com/embed/' . $matches[1];
                }
            }

            echo '<div class="evpa-video-popup-container" data-video-url="' . esc_url( $video_url ) . '">';
            echo '<div class="evpa-video-left">';
            echo '<img src="' . esc_url( $video['video_thumbnail']['url'] ) . '" alt="Video Thumbnail" />';
            echo '</div>';
            echo '<div class="evpa-video-right">';
            echo '<h3>' . esc_html( $video['video_title'] ) . '</h3>';
            echo '<p>' . esc_html( $video['video_description'] ) . '</p>';
            echo '</div>';
            echo '</div>';
        }

        echo '<div id="evpa-video-popup" class="evpa-video-popup">';
        echo '<div class="evpa-popup-content">';
        echo '<button class="evpa-close-popup">&times;</button>';
        echo '<div class="evpa-popup-video">';
        echo '<iframe id="evpa-video-frame" src="" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
}
