<?php
/**
 * Widget API: MultiInstagramWidget class
 *
 * @package Multi Widgets
 * @subpackage Widgets
 * @since 1.0.0
 */

/**
 * Core class used to implement a Instagram feed widget.
 *
 * @since 1.0.0
 *
 * @see WP_Widget
 */
class MultiInstagramWidget extends WP_Widget {
	/**
	* Sets up a new Multi Instagram feed widget instance.
	*
	* @since 1.0.0
	*/
	public function __construct() {
		$widget_ops = array('classname' => 'multi-instagram-feed', 'description' => esc_html__( "Displays your latest Instagram photos",'multiwidget') );
		parent::__construct('multi-instagram-feed', esc_html__('Multiwidget Instagram Feed','multiwidget'), $widget_ops);
		$this->alt_option_name = 'multi-instagram-feed';
	}

	/**
	 * Outputs the content for the current  Multi Instagram feed widget instance.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current  Multi Instagram feed widget instance.
	 */

	public function widget( $args, $instance ) {
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : esc_html__( 'Instagram ','multiwidget' );		
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
		$image_size = empty($instance['image_size']) ? '' : $instance['image_size'];
		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 4;
		if ( ! $number )
		$number = 4;
		$number_slide = ( ! empty( $instance['number_slide'] ) ) ? absint( $instance['number_slide'] ) : 1;
		if ( ! $number_slide )
		$number_slide = 1;
	
		$access_token = empty($instance['access_token']) ? '' : $instance['access_token'];
		$image_size = empty($instance['image_size']) ? '' : $instance['image_size'];
		$layouts = empty($instance['layouts']) ? '' : $instance['layouts'];
		$show_widget_title = isset( $instance['show_widget_title'] ) ? $instance['show_widget_title'] : false;
		$show_like_counts = isset( $instance['show_like_counts'] ) ? $instance['show_like_counts'] : false;

		$allowed_tags_before_after = array( 'div' => array( 'class' => array(), 'id' => array() ),'h3' => array( 'class' => array() ),'span' => array(),'aside' => array( 'class' => array(), 'id' => array() ) );
		echo wp_kses( __( $args['before_widget'],'multiwidget' ),$allowed_tags_before_after );		
		
		?>
		<?php 
		if($show_widget_title)
		{
			if(trim($title)!='' )

			{ ?>

		<h2 class="widget-title ze-instagram-feed"><?php echo wp_kses( __( $title,'multiwidget' ),$allowed_tags_before_after );?></h2> 			 
			<?php 
			} 
		}
		if($access_token != '')
		{
			$json = @file_get_contents('https://api.instagram.com/v1/users/self/media/recent/?access_token='.$access_token.'&count='.$number.'');
			$instagram_feed_data = json_decode($json, true);			
			$instagram_feed_data['meta']['code'];

			if (isset($instagram_feed_data['data'])) 
			{	if($layouts=='photography') { echo '<div class="row">';} //Row start for photography layout
				if($layouts=='sliderlayout') { echo '<div class="instagram-slick-feeds" data-item="'.$number_slide.'">';} //Row start for sliderlayout layout
				foreach ($instagram_feed_data['data'] as $data) 
				{
					$link = $data['link'];
					$like_count = $data['likes']['count'];
					$caption = isset($data['caption']) ? $data['caption']['text'] : '';
					if($image_size == 'thumbnail')
					{
						$img_url = $data['images']['thumbnail']['url'];
					}
					elseif ($image_size== 'standard_resolution') {
						$img_url = $data['images']['standard_resolution']['url'];
					}
					elseif ($image_size== 'low_resolution') {
						$img_url = $data['images']['low_resolution']['url'];
					}else{
						$img_url = $data['images']['thumbnail']['url'];
					}
					//==================Laout Default started from here ===========
					if($layouts == 'defaultlayout')
					{
						?>
						<figure>
						<?php if($show_like_counts) {
							if($like_count != 0){

							?>
							<span class="instagram-feed-like"><i class="fa fa-heart"></i> <?php echo esc_html($like_count); ?></span>
							<?php } } ?>
							<a href="<?php echo esc_url($link); ?>" target="_blank">
								<img src="<?php echo esc_url($img_url); ?>" alt="<?php esc_html_e($caption); ?>" />
							</a>
						</figure>				
						<?php	
					}
					// =========================Layout default end here================
					// ======================Layout Photography  start here =========== 
					if($layouts=='photography')
					{ ?>

						<div class="col-md-4">
							<div class="photography-instagram-feed-wrap">
							<?php if($show_like_counts) {?>
							<span class="photography-instagram-feed-like"><i class="fa fa-heart"></i> <?php echo esc_html($like_count); ?></span>
							<?php } ?>
								<a href="<?php echo esc_url($link); ?>" target="_blanl">
									<img src="<?php echo esc_url($img_url); ?>" alt="<?php esc_html_e($caption); ?>" />
								</a>
							</div>
						</div>	
					
						<?php 
					} // ======================Layout Photography  start here =========== 
					// =========================Slider Layout============================
					if($layouts=='sliderlayout'){ ?>
					<div class="instagram-slick-feeds">
						<div class="insta-feed">
							<?php if($show_like_counts) {?>
							<span class="photography-instagram-feed-like"><i class="fa fa-heart"></i> <?php echo esc_html($like_count); ?></span>
							<?php } ?>							
							<a href="<?php echo esc_url($link); ?>" target="_blanl">
								<img src="<?php echo esc_url($img_url); ?>" alt="<?php esc_html_e($caption); ?>" />
							</a>
						</div>
					</div>
					<?php 
					}
				}
				if($layouts=='photography') { echo '</div>';} //Row end for photography layout
				if($layouts=='sliderlayout') { echo '</div>';} //Row end for sliderlayout layout				
			}
		}else{?>
				<h6><?php echo esc_html__('Enter your access token', 'multiwidget'); ?></h6>
		<?php }

		?>
			
			<?php echo wp_kses(__($args['after_widget'],'multiwidget'),$allowed_tags_before_after); 
			
	}
	/**
	 * Handles updating the settings for the current Multi Instagram feed widget instance.
	 *
	 * @since 1.0.0
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['access_token'] = sanitize_text_field( $new_instance['access_token'] );		
		$instance['number'] = (int) $new_instance['number'];
		$instance['number_slide'] = (int) $new_instance['number_slide'];		
		$instance['image_size'] = $new_instance['image_size'];
		$instance['layouts'] = $new_instance['layouts'];
		$instance['show_widget_title'] = isset( $new_instance['show_widget_title'] ) ? (bool) $new_instance['show_widget_title'] : false;
		$instance['show_like_counts'] = isset( $new_instance['show_like_counts'] ) ? (bool) $new_instance['show_like_counts'] : false;

		return $instance;
	}

	/**
	 * Outputs the settings form for the Multi Instagram feed widget.
	 *
	 * @since 1.0.0
	 *
	 * @param array $instance Current settings.
	 */

	public function form( $instance ) {		
		// echo "<pre>";
		// print_r($instance);
		// echo "</pre>";
	$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'image_size'=>'thumbnail','layouts' =>'defaultlayout' ) );
	$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
	$access_token     = isset( $instance['access_token'] ) ? esc_attr( $instance['access_token'] ) : '';	
	$number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 4;
	$number_slide    = isset( $instance['number_slide'] ) ? absint( $instance['number_slide'] ) : 1;
	$image_size = $instance['image_size'];   
   	$layouts = $instance['layouts']; 
   	$show_widget_title = isset( $instance['show_widget_title'] ) ? (bool) $instance['show_widget_title'] : true;
   	$show_like_counts = isset( $instance['show_like_counts'] ) ? (bool) $instance['show_like_counts'] : true;

	?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:','multiwidget' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_html($title); ?>" />
		</p>
		<p>
		<input class="checkbox" type="checkbox"<?php checked( $show_widget_title ); ?> id="<?php echo $this->get_field_id( 'show_widget_title' ); ?>" name="<?php echo $this->get_field_name( 'show_widget_title' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'show_widget_title' ); ?>"><?php esc_html_e( 'Display Widget Title','multiwidget' ); ?></label>
		</p>
		<p>
		<input class="checkbox" type="checkbox"<?php checked( $show_like_counts ); ?> id="<?php echo $this->get_field_id( 'show_like_counts' ); ?>" name="<?php echo $this->get_field_name( 'show_like_counts' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'show_like_counts' ); ?>"><?php esc_html_e( 'Show Like counts','multiwidget' ); ?></label>
		</p>

		<p>
		<label for="<?php echo $this->get_field_id( 'access_token' ); ?>"><?php esc_html_e( 'Enter Instagram access token:','multiwidget' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'access_token' ); ?>" name="<?php echo $this->get_field_name( 'access_token' ); ?>" type="text" value="<?php echo esc_html($access_token); ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php esc_html_e( 'Number Of Images','multiwidget' ); ?></label>
		<input class="tiny-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo esc_html($number); ?>" size="3" />
		</p>

		<p>
		<label for="<?php echo $this->get_field_id( 'number_slide' ); ?>"><?php esc_html_e( 'Number of image to slide','multiwidget' ); ?></label>
		<input class="tiny-text" id="<?php echo $this->get_field_id( 'number_slide' ); ?>" name="<?php echo $this->get_field_name( 'number_slide' ); ?>" type="number" step="1" min="1" value="<?php echo esc_html($number_slide); ?>" size="1" />
		<?php esc_html_e('[ Only applicable in the slider layouts ]','multiwidget'); ?>
		</p>

		<p>
		<label for="<?php echo $this->get_field_id('text'); ?>"><?php esc_html_e('Image Size ','multiwidget'); ?> 

		<select class='widefat' id="<?php echo $this->get_field_id('image_size'); ?>"
		name="<?php echo $this->get_field_name('image_size'); ?>" type="text">

		<option value='thumbnail'<?php echo ($image_size=='thumbnail')?'selected':''; ?>>
		<?php esc_html_e('Thumbnail','multiwidget'); ?>
		</option>

		<option value='low_resolution'<?php echo ($image_size=='low_resolution')?'selected':''; ?>>
		<?php esc_html_e('Medium','multiwidget'); ?>
		</option>

		<option value='standard_resolution'<?php echo ($image_size=='standard_resolution')?'selected':''; ?>>
		<?php esc_html_e('Large','multiwidget'); ?>
		</option> 

		</select>                
		</label>
		</p>
			<p>
		<label for="<?php echo $this->get_field_id('text'); ?>"><?php esc_html_e('Layout','multiwidget'); ?> 

		<select class='widefat' id="<?php echo $this->get_field_id('layouts'); ?>"
		name="<?php echo $this->get_field_name('layouts'); ?>" type="text">

		<option value='defaultlayout'<?php echo ($layouts=='defaultlayout')?'selected':''; ?>>
		<?php esc_html_e('Default Layout','multiwidget'); ?>
		</option>

		<option value='photography'<?php echo ($layouts=='photography')?'selected':''; ?>>
		<?php esc_html_e('Layout 2','multiwidget'); ?>
		</option>	

		<option value='sliderlayout'<?php echo ($layouts=='sliderlayout')?'selected':''; ?>>
		<?php esc_html_e('Slider Layout','multiwidget'); ?>
		</option> 

		</select>                
		</label>
		</p>


<?php
	}
}
register_widget('MultiInstagramWidget');