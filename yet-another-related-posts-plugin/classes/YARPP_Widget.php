<?php
/**
 * Vaguely based on code by MK Safi
 * http://msafi.com/fix-yet-another-related-posts-plugin-yarpp-widget-and-add-it-to-the-sidebar/
 */
class YARPP_Widget extends WP_Widget {

	public function __construct() {
		$widget_ops = array(
			'description' => 'Related Posts and/or Sponsored Content',
			'show_instance_in_rest' => true
		);
		parent::__construct( false, 'Related Posts (YARPP)', $widget_ops );
	}

	public function widget( $args, $instance ) {
		if ( ! is_singular() ) {
			return;
		}

		global $yarpp;
		extract( $args );

		/* Compatibility with pre-3.5 settings: */
		if ( isset( $instance['use_template'] ) ) {
			$instance['template'] = ( $instance['use_template'] ) ? ( $instance['template_file'] ) : false;
		}

		$title  = apply_filters( 'widget_title', $instance['title'] );
		$output = $before_widget;
		if ( ! $instance['template'] ) {
			$output .= $before_title;
			$output .= $title;
			$output .= $after_title;
		}
		$instance['domain'] = 'widget';
		$output            .= $yarpp->display_related( null, $instance, false );
		$output            .= $after_widget;
		echo $output;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array(
			'template'           => false,
			'title'              => $new_instance['title'],
			'thumbnails_heading' => $new_instance['thumbnails_heading'],
			'use_pro'            => false,
			'pro_dpid'           => null,
			'promote_yarpp'      => false,
		);

		if ( $new_instance['use_template'] === 'thumbnails' ) {
			$instance['template'] = 'thumbnails';
		} elseif ( $new_instance['use_template'] === 'custom' ) {
			$instance['template'] = $new_instance['template_file'];
		}

		return $instance;
	}

	public function form( $instance ) {
		global $yarpp;
		$id       = rtrim( $this->get_field_id( null ), '-' );
		$instance = wp_parse_args(
			$instance,
			array(
				'title'              => 'Related Posts (YARPP)',
				'thumbnails_heading' => $yarpp->get_option( 'thumbnails_heading' ),
				'template'           => false,
				'use_pro'            => false,
				'pro_dpid'           => null,
				'promote_yarpp'      => false,
			)
		);

		/*
		 * TODO: Deprecate
		 * Compatibility with pre-3.5 settings
		 */
		if ( isset( $instance['use_template'] ) ) {
			$instance['template'] = $instance['template_file'];
		}

		$choice = ( $instance['template'] ) ? ( ( $instance['template'] === 'thumbnails' ) ? 'thumbnails' : 'custom' ) : 'builtin';

		/* Check if YARPP templates are installed */
		$templates = $yarpp->get_templates();

		if ( ! $yarpp->diagnostic_custom_templates() && $choice === 'custom' ) {
			$choice = 'builtin';
		}

		include YARPP_DIR . '/includes/phtmls/yarpp_widget_form.phtml';
	}
}

/**
 * @since 2.0 Add as a widget
 */
function yarpp_widget_init() {
	register_widget( 'YARPP_Widget' );
}

add_action( 'widgets_init', 'yarpp_widget_init' );
