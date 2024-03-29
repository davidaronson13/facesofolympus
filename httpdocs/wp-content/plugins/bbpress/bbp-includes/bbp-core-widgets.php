<?php

/**
 * bbPress Widgets
 *
 * Contains the forum list, topic list, reply list and login form widgets.
 *
 * @package bbPress
 * @subpackage Widgets
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * bbPress Login Widget
 *
 * Adds a widget which displays the login form
 *
 * @since bbPress (r2827)
 *
 * @uses WP_Widget
 */
class BBP_Login_Widget extends WP_Widget {

	/**
	 * bbPress Login Widget
	 *
	 * Registers the login widget
	 *
	 * @since bbPress (r2827)
	 *
	 * @uses apply_filters() Calls 'bbp_login_widget_options' with the
	 *                        widget options
	 */
	public function __construct() {
		$widget_ops = apply_filters( 'bbp_login_widget_options', array(
			'classname'   => 'bbp_widget_login',
			'description' => __( 'A simple login form with optional links to sign-up and lost password pages.', 'bbpress' )
		) );

		parent::__construct( false, __( '(bbPress) Login Widget', 'bbpress' ), $widget_ops );
	}

	/**
	 * Register the widget
	 *
	 * @since bbPress (r3389)
	 *
	 * @uses register_widget()
	 */
	public function register_widget() {
		register_widget( 'BBP_Login_Widget' );
	}

	/**
	 * Displays the output, the login form
	 *
	 * @since bbPress (r2827)
	 *
	 * @param mixed $args Arguments
	 * @param array $instance Instance
	 * @uses apply_filters() Calls 'bbp_login_widget_title' with the title
	 * @uses get_template_part() To get the login/logged in form
	 */
	public function widget( $args, $instance ) {
		extract( $args );

		$title    = apply_filters( 'bbp_login_widget_title',    $instance['title']    );
		$register = apply_filters( 'bbp_login_widget_register', $instance['register'] );
		$lostpass = apply_filters( 'bbp_login_widget_lostpass', $instance['lostpass'] );

		echo $before_widget;

		if ( !empty( $title ) )
			echo $before_title . $title . $after_title;

		if ( !is_user_logged_in() ) : ?>

			<form method="post" action="<?php bbp_wp_login_action( array( 'context' => 'login_post' ) ); ?>" class="bbp-login-form">
				<fieldset>
					<legend><?php _e( 'Log In', 'bbpress' ); ?></legend>

					<div class="bbp-username">
						<label for="user_login"><?php _e( 'Username', 'bbpress' ); ?>: </label>
						<input type="text" name="log" value="<?php bbp_sanitize_val( 'user_login', 'text' ); ?>" size="20" id="user_login" tabindex="<?php bbp_tab_index(); ?>" />
					</div>

					<div class="bbp-password">
						<label for="user_pass"><?php _e( 'Password', 'bbpress' ); ?>: </label>
						<input type="password" name="pwd" value="<?php bbp_sanitize_val( 'user_pass', 'password' ); ?>" size="20" id="user_pass" tabindex="<?php bbp_tab_index(); ?>" />
					</div>

					<div class="bbp-remember-me">
						<input type="checkbox" name="rememberme" value="forever" <?php checked( bbp_get_sanitize_val( 'rememberme', 'checkbox' ), true, true ); ?> id="rememberme" tabindex="<?php bbp_tab_index(); ?>" />
						<label for="rememberme"><?php _e( 'Remember Me', 'bbpress' ); ?></label>
					</div>

					<div class="bbp-submit-wrapper">

						<?php do_action( 'login_form' ); ?>

						<button type="submit" name="user-submit" id="user-submit" tabindex="<?php bbp_tab_index(); ?>" class="button submit user-submit"><?php _e( 'Log In', 'bbpress' ); ?></button>

						<?php bbp_user_login_fields(); ?>

					</div>

					<?php if ( !empty( $register ) || !empty( $lostpass ) ) : ?>

						<div class="bbp-login-links">

							<?php if ( !empty( $register ) ) : ?>

								<a href="<?php echo esc_url( $register ); ?>" title="<?php _e( 'Register', 'bbpress' ); ?>" class="bbp-register-link"><?php _e( 'Register', 'bbpress' ); ?></a>

							<?php endif; ?>

							<?php if ( !empty( $lostpass ) ) : ?>

								<a href="<?php echo esc_url( $lostpass ); ?>" title="<?php _e( 'Lost Password', 'bbpress' ); ?>" class="bbp-lostpass-link"><?php _e( 'Lost Password', 'bbpress' ); ?></a>

							<?php endif; ?>

						</div>

					<?php endif; ?>

				</fieldset>
			</form>

		<?php else : ?>

			<div class="bbp-logged-in">
				<a href="<?php bbp_user_profile_url( bbp_get_current_user_id() ); ?>" class="submit user-submit"><?php echo get_avatar( bbp_get_current_user_id(), '40' ); ?></a>
				<h4><?php bbp_user_profile_link( bbp_get_current_user_id() ); ?></h4>

				<?php bbp_logout_link(); ?>
			</div>

		<?php endif;

		echo $after_widget;
	}

	/**
	 * Update the login widget options
	 *
	 * @since bbPress (r2827)
	 *
	 * @param array $new_instance The new instance options
	 * @param array $old_instance The old instance options
	 */
	public function update( $new_instance, $old_instance ) {
		$instance             = $old_instance;
		$instance['title']    = strip_tags( $new_instance['title'] );
		$instance['register'] = esc_url( $new_instance['register'] );
		$instance['lostpass'] = esc_url( $new_instance['lostpass'] );

		return $instance;
	}

	/**
	 * Output the login widget options form
	 *
	 * @since bbPress (r2827)
	 *
	 * @param $instance Instance
	 * @uses BBP_Login_Widget::get_field_id() To output the field id
	 * @uses BBP_Login_Widget::get_field_name() To output the field name
	 */
	public function form( $instance ) {

		// Form values
		$title    = !empty( $instance['title'] )    ? esc_attr( $instance['title'] )    : '';
		$register = !empty( $instance['register'] ) ? esc_attr( $instance['register'] ) : '';
		$lostpass = !empty( $instance['lostpass'] ) ? esc_attr( $instance['lostpass'] ) : '';

		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'bbpress' ); ?>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'register' ); ?>"><?php _e( 'Register URI:', 'bbpress' ); ?>
			<input class="widefat" id="<?php echo $this->get_field_id( 'register' ); ?>" name="<?php echo $this->get_field_name( 'register' ); ?>" type="text" value="<?php echo $register; ?>" /></label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'lostpass' ); ?>"><?php _e( 'Lost Password URI:', 'bbpress' ); ?>
			<input class="widefat" id="<?php echo $this->get_field_id( 'lostpass' ); ?>" name="<?php echo $this->get_field_name( 'lostpass' ); ?>" type="text" value="<?php echo $lostpass; ?>" /></label>
		</p>

		<?php
	}
}

/**
 * bbPress Views Widget
 *
 * Adds a widget which displays the view list
 *
 * @since bbPress (r3020)
 *
 * @uses WP_Widget
 */
class BBP_Views_Widget extends WP_Widget {

	/**
	 * bbPress View Widget
	 *
	 * Registers the view widget
	 *
	 * @since bbPress (r3020)
	 *
	 * @uses apply_filters() Calls 'bbp_views_widget_options' with the
	 *                        widget options
	 */
	public function __construct() {
		$widget_ops = apply_filters( 'bbp_views_widget_options', array(
			'classname'   => 'widget_display_views',
			'description' => __( 'A list of registered optional topic views.', 'bbpress' )
		) );

		parent::__construct( false, __( '(bbPress) Topic Views List', 'bbpress' ), $widget_ops );
	}

	/**
	 * Register the widget
	 *
	 * @since bbPress (r3389)
	 *
	 * @uses register_widget()
	 */
	public function register_widget() {
		register_widget( 'BBP_Views_Widget' );
	}

	/**
	 * Displays the output, the view list
	 *
	 * @since bbPress (r3020)
	 *
	 * @param mixed $args Arguments
	 * @param array $instance Instance
	 * @uses apply_filters() Calls 'bbp_view_widget_title' with the title
	 * @uses bbp_get_views() To get the views
	 * @uses bbp_view_url() To output the view url
	 * @uses bbp_view_title() To output the view title
	 */
	public function widget( $args, $instance ) {

		// Only output widget contents if views exist
		if ( bbp_get_views() ) :

			extract( $args );

			$title = apply_filters( 'bbp_view_widget_title', $instance['title'] );

			echo $before_widget;
			echo $before_title . $title . $after_title; ?>

			<ul>

				<?php foreach ( bbp_get_views() as $view => $args ) : ?>

					<li><a class="bbp-view-title" href="<?php bbp_view_url( $view ); ?>" title="<?php bbp_view_title( $view ); ?>"><?php bbp_view_title( $view ); ?></a></li>

				<?php endforeach; ?>

			</ul>

			<?php echo $after_widget;

		endif;
	}

	/**
	 * Update the view widget options
	 *
	 * @since bbPress (r3020)
	 *
	 * @param array $new_instance The new instance options
	 * @param array $old_instance The old instance options
	 */
	public function update( $new_instance, $old_instance ) {
		$instance          = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}

	/**
	 * Output the view widget options form
	 *
	 * @since bbPress (r3020)
	 *
	 * @param $instance Instance
	 * @uses BBP_Views_Widget::get_field_id() To output the field id
	 * @uses BBP_Views_Widget::get_field_name() To output the field name
	 */
	public function form( $instance ) {
		$title = !empty( $instance['title'] ) ? esc_attr( $instance['title'] ) : ''; ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'bbpress' ); ?>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
			</label>
		</p>

		<?php
	}
}

/**
 * bbPress Forum Widget
 *
 * Adds a widget which displays the forum list
 *
 * @since bbPress (r2653)
 *
 * @uses WP_Widget
 */
class BBP_Forums_Widget extends WP_Widget {

	/**
	 * bbPress Forum Widget
	 *
	 * Registers the forum widget
	 *
	 * @since bbPress (r2653)
	 *
	 * @uses apply_filters() Calls 'bbp_forums_widget_options' with the
	 *                        widget options
	 */
	public function __construct() {
		$widget_ops = apply_filters( 'bbp_forums_widget_options', array(
			'classname'   => 'widget_display_forums',
			'description' => __( 'A list of forums with an option to set the parent.', 'bbpress' )
		) );

		parent::__construct( false, __( '(bbPress) Forums List', 'bbpress' ), $widget_ops );
	}

	/**
	 * Register the widget
	 *
	 * @since bbPress (r3389)
	 *
	 * @uses register_widget()
	 */
	public function register_widget() {
		register_widget( 'BBP_Forums_Widget' );
	}

	/**
	 * Displays the output, the forum list
	 *
	 * @since bbPress (r2653)
	 *
	 * @param mixed $args Arguments
	 * @param array $instance Instance
	 * @uses apply_filters() Calls 'bbp_forum_widget_title' with the title
	 * @uses get_option() To get the forums per page option
	 * @uses current_user_can() To check if the current user can read
	 *                           private() To resety name
	 * @uses bbp_has_forums() The main forum loop
	 * @uses bbp_forums() To check whether there are more forums available
	 *                     in the loop
	 * @uses bbp_the_forum() Loads up the current forum in the loop
	 * @uses bbp_forum_permalink() To display the forum permalink
	 * @uses bbp_forum_title() To display the forum title
	 */
	public function widget( $args, $instance ) {
		extract( $args );

		$title        = apply_filters( 'bbp_forum_widget_title', $instance['title'] );
		$parent_forum = !empty( $instance['parent_forum'] ) ? $instance['parent_forum'] : '0';
		$widget_query = new WP_Query( array(
			'post_parent'    => $parent_forum,
			'post_type'      => bbp_get_forum_post_type(),
			'posts_per_page' => get_option( '_bbp_forums_per_page', 50 ),
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
			'meta_query'     => array( bbp_exclude_forum_ids( 'meta_query' ) )
		) );

		if ( $widget_query->have_posts() ) :

			echo $before_widget;
			echo $before_title . $title . $after_title; ?>

			<ul>

				<?php while ( $widget_query->have_posts() ) : $widget_query->the_post(); ?>

					<li><a class="bbp-forum-title" href="<?php bbp_forum_permalink( $widget_query->post->ID ); ?>" title="<?php bbp_forum_title( $widget_query->post->ID ); ?>"><?php bbp_forum_title( $widget_query->post->ID ); ?></a></li>

				<?php endwhile; ?>

			</ul>

		<?php echo $after_widget;

		endif;
	}

	/**
	 * Update the forum widget options
	 *
	 * @since bbPress (r2653)
	 *
	 * @param array $new_instance The new instance options
	 * @param array $old_instance The old instance options
	 */
	public function update( $new_instance, $old_instance ) {
		$instance                 = $old_instance;
		$instance['title']        = strip_tags( $new_instance['title'] );
		$instance['parent_forum'] = $new_instance['parent_forum'];

		// Force to any
		if ( !empty( $instance['parent_forum'] ) && !is_numeric( $instance['parent_forum'] ) ) {
			$instance['parent_forum'] = 'any';
		}

		return $instance;
	}

	/**
	 * Output the forum widget options form
	 *
	 * @since bbPress (r2653)
	 *
	 * @param $instance Instance
	 * @uses BBP_Forums_Widget::get_field_id() To output the field id
	 * @uses BBP_Forums_Widget::get_field_name() To output the field name
	 */
	public function form( $instance ) {
		$title        = !empty( $instance['title']        ) ? esc_attr( $instance['title']        ) : '';
		$parent_forum = !empty( $instance['parent_forum'] ) ? esc_attr( $instance['parent_forum'] ) : '0'; ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'bbpress' ); ?>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
			</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'parent_forum' ); ?>"><?php _e( 'Parent Forum ID:', 'bbpress' ); ?>
				<input class="widefat" id="<?php echo $this->get_field_id( 'parent_forum' ); ?>" name="<?php echo $this->get_field_name( 'parent_forum' ); ?>" type="text" value="<?php echo $parent_forum; ?>" />
			</label>

			<br />

			<small><?php _e( '"0" to show only root - "any" to show all', 'bbpress' ); ?></small>
		</p>

		<?php
	}
}

/**
 * bbPress Topic Widget
 *
 * Adds a widget which displays the topic list
 *
 * @since bbPress (r2653)
 *
 * @uses WP_Widget
 */
class BBP_Topics_Widget extends WP_Widget {

	/**
	 * bbPress Topic Widget
	 *
	 * Registers the topic widget
	 *
	 * @since bbPress (r2653)
	 *
	 * @uses apply_filters() Calls 'bbp_topics_widget_options' with the
	 *                        widget options
	 */
	public function __construct() {
		$widget_ops = apply_filters( 'bbp_topics_widget_options', array(
			'classname'   => 'widget_display_topics',
			'description' => __( 'A list of recent topics, sorted by popularity or freshness.', 'bbpress' )
		) );

		parent::__construct( false, __( '(bbPress) Recent Topics', 'bbpress' ), $widget_ops );
	}

	/**
	 * Register the widget
	 *
	 * @since bbPress (r3389)
	 *
	 * @uses register_widget()
	 */
	public function register_widget() {
		register_widget( 'BBP_Topics_Widget' );
	}

	/**
	 * Displays the output, the topic list
	 *
	 * @since bbPress (r2653)
	 *
	 * @param mixed $args
	 * @param array $instance
	 * @uses apply_filters() Calls 'bbp_topic_widget_title' with the title
	 * @uses bbp_topic_permalink() To display the topic permalink
	 * @uses bbp_topic_title() To display the topic title
	 * @uses bbp_get_topic_last_active_time() To get the topic last active
	 *                                         time
	 * @uses bbp_get_topic_id() To get the topic id
	 * @uses bbp_get_topic_reply_count() To get the topic reply count
	 */
	public function widget( $args, $instance ) {

		extract( $args );

		$title        = apply_filters( 'bbp_topic_widget_title', $instance['title'] );
		$max_shown    = !empty( $instance['max_shown']    ) ? (int) $instance['max_shown'] : 5;
		$show_date    = !empty( $instance['show_date']    ) ? 'on'                         : false;
		$parent_forum = !empty( $instance['parent_forum'] ) ? $instance['parent_forum']    : 'any';
		$order_by     = !empty( $instance['order_by']     ) ? $instance['order_by']        : false;

		// How do we want to order our results?
		switch ( $order_by ) {

			// Order by most recent replies
			case 'freshness' :
				$topics_query = array(
					'author'         => 0,
					'post_type'      => bbp_get_topic_post_type(),
					'post_parent'    => $parent_forum,
					'posts_per_page' => $max_shown,
					'post_status'    => join( ',', array( bbp_get_public_status_id(), bbp_get_closed_status_id() ) ),
					'show_stickes'   => false,
					'meta_key'       => '_bbp_last_active_time',
					'orderby'        => 'meta_value',
					'order'          => 'DESC',
					'meta_query'     => array( bbp_exclude_forum_ids( 'meta_query' ) )
				);
				break;

			// Order by total number of replies
			case 'popular' :
				$topics_query = array(
					'author'         => 0,
					'post_type'      => bbp_get_topic_post_type(),
					'post_parent'    => $parent_forum,
					'posts_per_page' => $max_shown,
					'post_status'    => join( ',', array( bbp_get_public_status_id(), bbp_get_closed_status_id() ) ),
					'show_stickes'   => false,
					'meta_key'       => '_bbp_reply_count',
					'orderby'        => 'meta_value',
					'order'          => 'DESC',
					'meta_query'     => array( bbp_exclude_forum_ids( 'meta_query' ) )
				);			
				break;

			// Order by which topic was created most recently
			case 'newness' :
			default :
				$topics_query = array(
					'author'         => 0,
					'post_type'      => bbp_get_topic_post_type(),
					'post_parent'    => $parent_forum,
					'posts_per_page' => $max_shown,
					'post_status'    => join( ',', array( bbp_get_public_status_id(), bbp_get_closed_status_id() ) ),
					'show_stickes'   => false,
					'order'          => 'DESC',
					'meta_query'     => array( bbp_exclude_forum_ids( 'meta_query' ) )
				);			
				break;
		}
		
		// Query defaults
		$widget_query = new WP_Query( $topics_query );

		// Topics exist
		if ( $widget_query->have_posts() ) : 
			
			echo $before_widget;
			echo $before_title . $title . $after_title; ?>

			<ul>

				<?php while ( $widget_query->have_posts() ) :

					$widget_query->the_post();
					$topic_id = bbp_get_topic_id( $widget_query->post->ID ); ?>

					<li>
						<a class="bbp-forum-title" href="<?php bbp_topic_permalink( $topic_id ); ?>" title="<?php bbp_topic_title( $topic_id ); ?>"><?php bbp_topic_title( $topic_id ); ?></a>

						<?php if ( 'on' == $show_date ) : ?>

							<div><?php bbp_topic_last_active_time( $topic_id ); ?></div>

						<?php endif; ?>

					</li>

				<?php endwhile; ?>

			</ul>

			<?php echo $after_widget;

		endif;
	}

	/**
	 * Update the forum widget options
	 *
	 * @since bbPress (r2653)
	 *
	 * @param array $new_instance The new instance options
	 * @param array $old_instance The old instance options
	 */
	public function update( $new_instance, $old_instance ) {
		$instance              = $old_instance;
		$instance['title']     = strip_tags( $new_instance['title']     );
		$instance['max_shown'] = strip_tags( $new_instance['max_shown'] );
		$instance['show_date'] = strip_tags( $new_instance['show_date'] );
		$instance['order_by']  = strip_tags( $new_instance['order_by']  );

		return $instance;
	}

	/**
	 * Output the topic widget options form
	 *
	 * @since bbPress (r2653)
	 *
	 * @param $instance Instance
	 * @uses BBP_Topics_Widget::get_field_id() To output the field id
	 * @uses BBP_Topics_Widget::get_field_name() To output the field name
	 */
	public function form( $instance ) {
		$title     = !empty( $instance['title']     ) ? esc_attr( $instance['title']     ) : '';
		$max_shown = !empty( $instance['max_shown'] ) ? esc_attr( $instance['max_shown'] ) : '';
		$show_date = !empty( $instance['show_date'] ) ? esc_attr( $instance['show_date'] ) : '';
		$order_by  = !empty( $instance['order_by']  ) ? esc_attr( $instance['order_by']  ) : ''; ?>

		<p><label for="<?php echo $this->get_field_id( 'title'     ); ?>"><?php _e( 'Title:',                  'bbpress' ); ?> <input class="widefat" id="<?php echo $this->get_field_id( 'title'     ); ?>" name="<?php echo $this->get_field_name( 'title'     ); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id( 'max_shown' ); ?>"><?php _e( 'Maximum topics to show:', 'bbpress' ); ?> <input class="widefat" id="<?php echo $this->get_field_id( 'max_shown' ); ?>" name="<?php echo $this->get_field_name( 'max_shown' ); ?>" type="text" value="<?php echo $max_shown; ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id( 'show_date' ); ?>"><?php _e( 'Show post date:',         'bbpress' ); ?> <input type="checkbox" id="<?php echo $this->get_field_id( 'show_date' ); ?>" name="<?php echo $this->get_field_name( 'show_date' ); ?>" <?php checked( 'on', $show_date ); ?> /></label></p>
		<p>
			<label for="<?php echo $this->get_field_id( 'order_by' ); ?>"><?php _e( 'Order By:',        'bbpress' ); ?></label>
			<select name="<?php echo $this->get_field_name( 'order_by' ); ?>" id="<?php echo $this->get_field_name( 'order_by' ); ?>">
				<option <?php selected( $order_by, 'newness' );   ?> value="newness"><?php _e( 'Newest Topics',                'bbpress' ); ?></option>
				<option <?php selected( $order_by, 'popular' );   ?> value="popular"><?php _e( 'Popular Topics',               'bbpress' ); ?></option>
				<option <?php selected( $order_by, 'freshness' ); ?> value="freshness"><?php _e( 'Topics With Recent Replies', 'bbpress' ); ?></option>
			</select>
		</p>

		<?php
	}
}

/**
 * bbPress Replies Widget
 *
 * Adds a widget which displays the replies list
 *
 * @since bbPress (r2653)
 *
 * @uses WP_Widget
 */
class BBP_Replies_Widget extends WP_Widget {

	/**
	 * bbPress Replies Widget
	 *
	 * Registers the replies widget
	 *
	 * @since bbPress (r2653)
	 *
	 * @uses apply_filters() Calls 'bbp_replies_widget_options' with the
	 *                        widget options
	 */
	public function __construct() {
		$widget_ops = apply_filters( 'bbp_replies_widget_options', array(
			'classname'   => 'widget_display_replies',
			'description' => __( 'A list of the most recent replies.', 'bbpress' )
		) );

		parent::__construct( false, __( '(bbPress) Recent Replies', 'bbpress' ), $widget_ops );
	}

	/**
	 * Register the widget
	 *
	 * @since bbPress (r3389)
	 *
	 * @uses register_widget()
	 */
	public function register_widget() {
		register_widget( 'BBP_Replies_Widget' );
	}

	/**
	 * Displays the output, the replies list
	 *
	 * @since bbPress (r2653)
	 *
	 * @param mixed $args
	 * @param array $instance
	 * @uses apply_filters() Calls 'bbp_reply_widget_title' with the title
	 * @uses bbp_get_reply_author_link() To get the reply author link
	 * @uses bbp_get_reply_author() To get the reply author name
	 * @uses bbp_get_reply_id() To get the reply id
	 * @uses bbp_get_reply_url() To get the reply url
	 * @uses bbp_get_reply_excerpt() To get the reply excerpt
	 * @uses bbp_get_reply_topic_title() To get the reply topic title
	 * @uses get_the_date() To get the date of the reply
	 * @uses get_the_time() To get the time of the reply
	 */
	public function widget( $args, $instance ) {

		extract( $args );

		$title        = apply_filters( 'bbp_replies_widget_title', $instance['title'] );
		$max_shown    = !empty( $instance['max_shown'] ) ? $instance['max_shown']    : '5';
		$show_date    = !empty( $instance['show_date'] ) ? 'on'                      : false;
		$post_types   = !empty( $instance['post_type'] ) ? array( bbp_get_topic_post_type(), bbp_get_reply_post_type() ) : bbp_get_reply_post_type();
		$widget_query = new WP_Query( array(
			'post_type'      => $post_types,
			'post_status'    => join( ',', array( bbp_get_public_status_id(), bbp_get_closed_status_id() ) ),
			'posts_per_page' => $max_shown,
			'meta_query'     => array( bbp_exclude_forum_ids( 'meta_query' ) )
		) );

		// Get replies and display them
		if ( $widget_query->have_posts() ) :

			echo $before_widget;
			echo $before_title . $title . $after_title; ?>

			<ul>

				<?php while ( $widget_query->have_posts() ) : $widget_query->the_post(); ?>

					<li>
						<?php

						$reply_id    = bbp_get_reply_id( $widget_query->post->ID );
						$author_link = bbp_get_reply_author_link( array( 'post_id' => $reply_id, 'type' => 'both', 'size' => 14 ) );
						$reply_link  = '<a class="bbp-reply-topic-title" href="' . esc_url( bbp_get_reply_url( $reply_id ) ) . '" title="' . bbp_get_reply_excerpt( $reply_id, 50 ) . '">' . bbp_get_reply_topic_title( $reply_id ) . '</a>';

						/* translators: bbpress replies widget: 1: reply author, 2: reply link, 3: reply date, 4: reply time */
						if ( $show_date == 'on' ) {
							printf( _x( '%1$s on %2$s %3$s, %4$s', 'widgets', 'bbpress' ), $author_link, $reply_link, '<div>' . get_the_date(), get_the_time() . '</div>' );
						} else {
							printf( _x( '%1$s on %2$s',            'widgets', 'bbpress' ), $author_link, $reply_link );
						}

						?>

					</li>

				<?php endwhile; ?>

			</ul>

			<?php echo $after_widget;

		endif;
	}

	/**
	 * Update the forum widget options
	 *
	 * @since bbPress (r2653)
	 *
	 * @param array $new_instance The new instance options
	 * @param array $old_instance The old instance options
	 */
	public function update( $new_instance, $old_instance ) {
		$instance              = $old_instance;
		$instance['title']     = strip_tags( $new_instance['title']     );
		$instance['max_shown'] = strip_tags( $new_instance['max_shown'] );
		$instance['show_date'] = strip_tags( $new_instance['show_date'] );

		return $instance;
	}

	/**
	 * Output the reply widget options form
	 *
	 * @since bbPress (r2653)
	 *
	 * @param $instance Instance
	 * @uses BBP_Replies_Widget::get_field_id() To output the field id
	 * @uses BBP_Replies_Widget::get_field_name() To output the field name
	 */
	public function form( $instance ) {
		$title     = !empty( $instance['title']     ) ? esc_attr( $instance['title']     ) : '';
		$max_shown = !empty( $instance['max_shown'] ) ? esc_attr( $instance['max_shown'] ) : '';
		$show_date = !empty( $instance['show_date'] ) ? esc_attr( $instance['show_date'] ) : ''; ?>

		<p><label for="<?php echo $this->get_field_id( 'title'     ); ?>"><?php _e( 'Title:',                   'bbpress' ); ?> <input class="widefat" id="<?php echo $this->get_field_id( 'title'     ); ?>" name="<?php echo $this->get_field_name( 'title'     ); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id( 'max_shown' ); ?>"><?php _e( 'Maximum replies to show:', 'bbpress' ); ?> <input class="widefat" id="<?php echo $this->get_field_id( 'max_shown' ); ?>" name="<?php echo $this->get_field_name( 'max_shown' ); ?>" type="text" value="<?php echo $max_shown; ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id( 'show_date' ); ?>"><?php _e( 'Show post date:',          'bbpress' ); ?> <input type="checkbox" id="<?php echo $this->get_field_id( 'show_date' ); ?>" name="<?php echo $this->get_field_name( 'show_date' ); ?>" <?php checked( 'on', $show_date ); ?> /></label></p>

		<?php
	}
}
