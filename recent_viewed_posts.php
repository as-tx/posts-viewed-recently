<?php
/*
Plugin Name: Posts Viewed Recently
Plugin URI: http://dev.fellowtuts.com/posts-viewed-recently-plugin/
Description: Posts Viewed Recently plugin shows posts/pages viewed by a visitor as a responsive sidebar widget or in post/page using shortcode
Version: 1.2
Author: Amit Sonkhiya, Kamal Agrawal
Author URI: http://dev.fellowtuts.com
License: GPLv2 or later
*/

 
 class ftRecentViewedPosts extends WP_Widget {

	// constructor
	function ftRecentViewedPosts() {
		parent::__construct(
		'recent_viewed_posts', // Base ID
			'Posts Viewed Recently', // Name
			array( 'description' => __( 'Display recent viewed posts/pages by a visitor as a responsive sidebar widget or in page/post using shortcode', 'text_domain' ) ) // Args
		);
		
	}
	
	
	// widget form creation
	function form($instance) {	
	
	$widgetID = str_replace('recent_viewed_posts-', '', $this->id);
	// Check values	
	$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
	$numberofposts   = isset( $instance['numberofposts'] ) ? absint( $instance['numberofposts'] ) : 5;
	$show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
	$showthumbnail = isset( $instance['showthumbnail'] ) ? (bool) $instance['showthumbnail'] : false;
	$width = isset( $instance['width'] ) ?  esc_attr($instance['width']) : '';
	$height = isset( $instance['height'] ) ? esc_attr( $instance['height']) : '';
	$alternateImg = isset( $instance['alternateImg'] ) ? esc_attr( $instance['alternateImg']) : '';
	$selected_posttypes = isset($instance['selected_posttypes']) ? $instance['selected_posttypes'] : array();
	
	$custom_post_types = get_post_types( array('public' => true,'_builtin' => false), 'names', 'and'); // for custom post type
    $default_post_types = array('post'=>'post','page'=>'page'); // get_post_types('', 'names'); // for Default post type
    $post_types = array_merge($custom_post_types, $default_post_types);  
	
	
		?>
	<p>
	<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'wp_widget_plugin'); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
	</p>

	<p class="typeholder">
	<label><?php _e('Select Types:', 'wp_widget_plugin'); ?></label><br />	
	
    <?php 
	
	 foreach ($post_types as $post_type) {

            $obj = get_post_type_object( $post_type );
             $postName = $obj->name;
			 $is_selected = false;
			 if(in_array($post_type,$selected_posttypes) )
			 		$is_selected = true;
	?>
            
            <input type="checkbox" class="checkbox" id="FT_checkbox_<?php echo $post_type ;?>" 
            name="<?php echo $this->get_field_name('selected_posttypes').'[]'; ?>" value="<?php echo $post_type ?>" <?php checked( $is_selected ); ?> >
           <label><?php echo $postName; ?></label><br/>
           
     <?php   }
	?>
    </p>
	<p>
	<label for="<?php echo $this->get_field_id('numberofposts'); ?>"><?php _e('Number of posts to show:', 'wp_widget_plugin'); ?></label>
	<input  id="<?php echo $this->get_field_id('numberofposts'); ?>" name="<?php echo $this->get_field_name('numberofposts'); ?>" type="text"  size="3" value="<?php echo $numberofposts; ?>" />
	</p>
    
	<p>
	
	<input class="checkbox showthumbnail" id="<?php echo $this->get_field_id('showthumbnail'); ?>" name="<?php echo $this->get_field_name('showthumbnail'); ?>" type="checkbox" <?php checked( $showthumbnail ); ?> /><label for="<?php echo $this->get_field_id('showthumbnail'); ?>"><?php _e('Show Thumbnail?', 'wp_widget_plugin'); ?></label>
    
	</p>
    <div class="thumbnailAttr">
    <p>
		<label for="<?php echo $this->get_field_id( 'width' ); ?>"><?php _e( 'Width: ' ); ?></label> 
		<input  size="3" id="<?php echo $this->get_field_id( 'width' ); ?>" name="<?php echo $this->get_field_name( 'width' ); ?>" type="text" value="<?php echo $width; ?>" /> px
		</p>
        <p>
		<label for="<?php echo $this->get_field_id( 'height' ); ?>"><?php _e( 'Height: ' ); ?></label> 
		<input  size="3"  id="<?php echo $this->get_field_id( 'height' ); ?>" name="<?php echo $this->get_field_name( 'height' ); ?>" type="text" value="<?php echo $height; ?>" /> px
		</p>
        
        <p>
	<label for="<?php echo $this->get_field_id('alternateImg'); ?>"><?php _e('Alternate image url:', 'wp_widget_plugin'); ?></label>
	<input class="widefat"  id="<?php echo $this->get_field_id('alternateImg'); ?>" name="<?php echo $this->get_field_name('alternateImg'); ?>" type="text"   value="<?php echo $alternateImg; ?>" />
	</p>
        </div>
    
    <p><input class="checkbox" type="checkbox" <?php checked( $show_date ); ?> id="<?php echo $this->get_field_id( 'show_date' ); ?>" name="<?php echo $this->get_field_name( 'show_date' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'show_date' ); ?>"><?php _e( 'Display post date?' ); ?></label></p>
        <?php if($widgetID != "__i__") { ?>
     <p style="font-size: 11px; opacity:0.6">
            <span class="shortcodeTtitle">Shortcode:</span>
            <span class="shortcode">[ft-recentlyviewed widget_id="<?php echo $widgetID; ?>"]</span>
            </p>
    
<?php } // for widget id checking
	}

	// widget update
	function update($new_instance, $old_instance) {
		
		$old_instance['title'] = $new_instance['title'];		
		$old_instance['selected_posttypes'] = $new_instance['selected_posttypes'];
		$old_instance['numberofposts'] = isset($new_instance['numberofposts'])?(int)$new_instance['numberofposts']:'';		
		$old_instance['showthumbnail'] = isset( $new_instance['showthumbnail'] ) ? (bool) $new_instance['showthumbnail'] : false;
		$old_instance['width'] = isset($new_instance['width'])?$new_instance['width']:'';
		$old_instance['height'] = isset($new_instance['height'])?$new_instance['height']:'';
		$old_instance['alternateImg'] = isset($new_instance['alternateImg'])?$new_instance['alternateImg']:'';
		$old_instance['show_date'] = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;
		
		return $old_instance ;
	
	}
	
	
	
	// widget display
	function widget($args,$instance1) {
		
		$widgetID = $args['widget_id'];	
		$widgetID = str_replace('recent_viewed_posts-', '', $widgetID);
        $widgetOptions = get_option($this->option_name);
		$instance1 = $widgetOptions[$widgetID];
			

		$title = ( ! empty( $instance1['title'] ) ) ? $instance1['title'] : __( 'Recent Visited Posts' );
		$title = apply_filters( 'widget_title', $title, $instance1, $this->id_base );
		$number = ( ! empty( $instance1['numberofposts'] ) ) ? absint( $instance1['numberofposts'] ) : 5;
		$showthumbnail = isset( $instance1['showthumbnail'] ) ? $instance1['showthumbnail'] : false;
		$width_image = empty($instance1['width']) ? '50' : apply_filters('widget_image_width', $instance1['width']);
        $height_image = empty($instance1['height']) ? '50' : apply_filters('widget_image_height', $instance1['height']);
		$alternateImg = !empty($instance1['alternateImg']) ? $instance1['alternateImg']:'';
		
		$show_date = isset( $instance1['show_date'] ) ? $instance1['show_date'] : false;
		$selected_posttypes = isset($instance1["selected_posttypes"]) ? $instance1["selected_posttypes"] : array();
		extract($args, EXTR_SKIP);
		
		// global  $post;
        
		// checking cookie exist or not
		if(isset($_COOKIE['ft_recent_posts']) && $_COOKIE['ft_recent_posts']!='')
		{   $ft_cookie_posts =  unserialize($_COOKIE['ft_recent_posts']);
			// for removing current post
			$ft_cookie_posts = array_diff($ft_cookie_posts, array(get_the_ID()));
			
	if (count($ft_cookie_posts) > 0) :
		
	$currentPostId = get_the_ID();
	$count = 0;
		?>
		
		<?php foreach ( $ft_cookie_posts as $postId ) { 
		    
			if($count >= $number) return;
			 global $wpdb;
              $post_exists = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE id = '" . $postId . "'", 'ARRAY_A');
              $ft_post = get_post($postId); //Gets post ID
			  
		 if ($post_exists && $ft_post && $ft_post->ID != $currentPostId && in_array($ft_post->post_type,$selected_posttypes) ) {			
			 $count++;
			if($count == 1)
			{
			 echo $before_widget; 
		
			echo $before_title . $title . $after_title; 
		
			echo '<ul class="recentviewed_post">' ;
			}
		?>
        
			<li>
            <?php if($showthumbnail) : ?>
            <div class="recentviewed_left" style="width:<?php echo $width_image; ?>px;height:<?php echo $height_image; ?>px;">           
            <?php
			if( $showthumbnail ){
				if( has_post_thumbnail( $ft_post->ID )){
				?>
                 <a href="<?php echo get_permalink( $ft_post->ID ) ; ?>">
                <?php
				echo get_the_post_thumbnail($ft_post->ID, array($width_image,$height_image)); ?>
                </a>
			<?php }elseif($alternateImg != ''){?>
				
				<a href="<?php echo get_permalink( $ft_post->ID ) ; ?>">
                <img src="<?php echo $alternateImg; ?>" width="<?php echo $width_image; ?>" height="<?php echo $height_image; ?>" class="wp-post-image" />
                </a>
				
			<?php } }?>
            
            </div>
            <?php endif; ?>
            <div class="recentviewed_right">
            <a href="<?php echo get_permalink( $ft_post->ID ) ; ?>">
            <?php			
            echo apply_filters( 'the_title', $ft_post->post_title, $ft_post->ID ); ?>
            </a>
			<?php if ( $show_date ) : ?>
				<br /><span class="post-date"><small><?php echo date(get_option('date_format'),strtotime($ft_post->post_date)); ?></small></span>
			<?php endif; ?>
            </div>
			</li>
		<?php } } ?>
		
		<?php if($count > 0) echo '</ul>'. $after_widget; ?>
<?php
		
		endif ;
		} // cookie found condition ends here
	}// widget function end here

	
 } // class end tag
// register widget
function ft_admin_recent_viewed_posts_js() {
            wp_register_script('admin_ftrecentviewed_script', plugins_url('js/ftrecentviewedposts.js', __FILE__) );
            wp_enqueue_script('admin_ftrecentviewed_script');
        }
add_action ('admin_enqueue_scripts','ft_admin_recent_viewed_posts_js');
add_action('widgets_init', create_function('', 'return register_widget("ftRecentViewedPosts");'));

/* Register the style sheet */

function ft_recentviewed_posts_stylesheet() {
            wp_register_style('ft_viewed_stylesheet', plugins_url('css/ftViewedPostsStyle.css', __FILE__) );
            wp_enqueue_style('ft_viewed_stylesheet');
        }
add_action('wp_enqueue_scripts', 'ft_recentviewed_posts_stylesheet');

function ft_posts_visited(){ 
	$ft_posts = array();
	
		if ( is_single() || is_page()){
		
		 if(isset($_COOKIE['ft_recent_posts']) && $_COOKIE['ft_recent_posts']!='')
		  { 
		  
		  $ft_posts =  unserialize($_COOKIE['ft_recent_posts']);
		  	
			if (! is_array($ft_posts)) {
			
					$ft_posts = array(get_the_ID()); // If array is fucked up...just build a new one.
				}else{
		   				 // for removing current post in cookie 
		  			 $ft_posts = array_diff($ft_posts, array(get_the_ID()));
		  
		 			 array_unshift($ft_posts,get_the_ID());// update cookie with current post
				}	
		
		  }
		  else
		  $ft_posts = array(get_the_ID());
		  
		  $ft_blog_url_array = parse_url(get_bloginfo('url')); // Get URL of blog
		  $ft_blog_url = $ft_blog_url_array['host']; // Get domain
		  $ft_blog_url = str_replace('www.', '', $ft_blog_url);
		  $ft_blog_url_dot = '.';
		  $ft_blog_url_dot .= $ft_blog_url;
			
		   setcookie( 'ft_recent_posts', serialize($ft_posts) ,time() + ( DAY_IN_SECONDS * 31 ),'/');
	
		}
	}

add_action('template_redirect', 'ft_posts_visited');
 require_once('ft_shortcode.php');
?>