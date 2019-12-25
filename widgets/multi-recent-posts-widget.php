<?php
/**
 * Widget API: Multi_recent_post_widget class
 *
 * @package Multi Widgets
 * @subpackage Widgets
 * @since 1.0.0
 */

/**
 * Core class used to implement a Multi Recent Posts widget.
 *
 * @since 1.0.0
 *
 * @see WP_Widget
 */
class Multi_recent_post_widget extends WP_Widget {
	/**
		* Sets up a new Multi Recent Posts widget instance.
		*
		* @since 1.0.0
	*/
	public function __construct() {
		$widget_ops = array('classname' => 'multi_widget_recent_entries', 'description' => esc_html__( "Your site&#8217;s most recent Posts.",'multiwidget') );
		parent::__construct('ibt-recent-posts', esc_html__('Multiwidget Recent Posts','multiwidget'), $widget_ops);
		$this->alt_option_name = 'multi_widget_recent_entries';
	}

	/**
	 * Outputs the content for the current Recent Posts widget instance.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Recent Posts widget instance.
	 */
	public function widget( $args, $instance ) {
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : esc_html__( 'Recent Posts','multiwidget' );
		$imgsize = ( ! empty( $instance['imgsize'] ) ) ? $instance['imgsize'] : esc_html__( 'post-thumb-widget','multiwidget' );
		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
		$image_size = empty($instance['image_size']) ? '' : $instance['image_size'];
		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
		if ( ! $number )
		$number = 5;
		$slide_number = ( ! empty( $instance['slide_number'] ) ) ? absint( $instance['slide_number'] ) : 5;
		if ( ! $slide_number )
		$slide_number = 5;

		$excerptlength = ( ! empty( $instance['excerptlength'] ) ) ? absint( $instance['excerptlength'] ) : 10;
		if ( ! $excerptlength )
		$excerptlength = 10;

		$show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;
		$show_excerpt = isset( $instance['show_excerpt'] ) ? $instance['show_excerpt'] : false;
		$show_thumbnail = isset( $instance['show_thumbnail'] ) ? $instance['show_thumbnail'] : false;
		$show_categoriess = isset( $instance['show_categoriess'] ) ? $instance['show_categoriess'] : false;
		$show_title = isset( $instance['show_title'] ) ? $instance['show_title'] : false;
		$show_widget_title = isset( $instance['show_widget_title'] ) ? $instance['show_widget_title'] : false;
		$layouts = empty($instance['layouts']) ? '' : $instance['layouts'];
		$date_formats = empty($instance['date_formats']) ? '' : $instance['date_formats'];
		switch($date_formats)
		{
			case 'defaultlayout':
			$date_formats_style = 'M j Y';
			break;
			case 'layout-2':
			$date_formats_style = 'F j';
			break;
			case 'layout-3':
			$date_formats_style = 'j F Y';
			break;
			default :
			$date_formats_style = 'M j Y';
			break;
		}

		$r = new WP_Query( apply_filters( 'widget_posts_args', array(
		'posts_per_page'      => $number,
		'no_found_rows'       => true,
		'post_status'         => 'publish',
		'ignore_sticky_posts' => true
		) ) );

		if ($r->have_posts()) :
		$allowed_tags_before_after=array('div' => array('class'=>array(),'id'=>array()),'h3'=>array('class'=>array(),),'span'=>array(),'aside'=>array('class'=>array(),'id'=>array()));
		echo wp_kses(__($args['before_widget'],'multiwidget'),$allowed_tags_before_after);
		if($show_widget_title )
		{
			if(trim($title)!='' )
			{ ?>
				<h5 class="widget-title recent-post-ttile-icon-<?php echo esc_attr($layouts); ?>"><?php echo esc_html__($title,'multiwidget');?></h5>
			<?php
			}
		}
		$i=0;
		$j=1;
		$number_slide=4;
		?>
			<ul class="recent-post-layout-<?php echo esc_attr($layouts); ?>">
				<?php while ( $r->have_posts() ) : $r->the_post(); ?>
				<?php 
				if($layouts == 'sliderlayout') 

				{  // ===========slider layout start========== ?>
					<?php	
					   if($i==0)
					   {
					?>
								<li>
					<?php
					   }
					?>

						<div id="post-<?php echo get_the_ID(); ?>" class="item clearfix">
							<span class="serial-no"><?php echo ($j<10?'0':'').$j++; ?>.</span>						
						<!--Title-->
						<?php if ( $show_title ) : ?>
						<a href="<?php the_permalink(); ?>" class="post-title"><?php get_the_title() ? the_title() : the_ID(); ?></a>
						<?php endif; ?>
						<!--Title-->
							<!--thumbnail-->
							<?php if ( $show_thumbnail ) : ?>
								<?php if (has_post_thumbnail()) : ?>
									<div class="post-thumbnail recent-post-thumbnail-<?php echo esc_attr($layouts); ?>">
										<?php the_post_thumbnail($image_size , get_the_ID()); ?>
									</div>
								<?php endif; ?>
							<?php endif; ?>
							<!--thumbnail end-->
							<!--excerpt-->
							<?php if ( $show_excerpt ) : ?>
								<div class="recent-post-widget_post-excerpt-<?php echo esc_attr($layouts); ?>">
									<?php echo multi_excerpt($excerptlength); ?>
								</div>
							<?php endif; ?>
							<!--excerpt-->
							<!--Date-->
							<?php if ( $show_date ) : ?>
								<span class="post-date recent-post-date-<?php echo esc_attr($layouts); ?>">
									<?php echo get_the_date($date_formats_style);?>
								</span>
							<?php endif; ?>
							<!--Date-->
							<!--category-->
							<?php $category = get_the_category(get_the_ID()); ?>
							<?php if ( $show_categoriess ) : ?>
							 <span class="post-category recent-post-category-<?php echo esc_attr($layouts); ?>"><a href="<?php echo esc_url(get_category_link($category[0]->cat_ID)); ?>"><?php echo esc_html__($category[0]->cat_name,'multiwidget'); ?></a> </span>
							<?php endif; ?>
								<!--category-->
						</div>
						<?php
								$i++;
							  if($i==$number_slide)
							  {
							  	$i=0;
					?>
									</li>
					<?php
								}
					?>
				<?php } // ===========slider layout End========== ?>
				<!-- <li> -->
				<?php if($layouts == 'defaultlayout') { // ================defaultlayout start================ ?>
					<!--thumbnail-->
          <li>
						<?php if ( $show_thumbnail ) : ?>
							<?php if (has_post_thumbnail()) : ?>
								<div class="recent-post-thumbnail-<?php echo esc_attr($layouts); ?>">
									<?php the_post_thumbnail($image_size , get_the_ID()); ?>
								</div>
							<?php endif; ?>
						<?php endif; ?>
						<!--thumbnail end-->
						<div class="recent-post-title-date-wrap-<?php echo esc_attr($layouts); ?>">
							<!--Title-->
							<?php if ( $show_title ) : ?>
							<a href="<?php the_permalink(); ?>" class="post-title"><?php get_the_title() ? the_title() : the_ID(); ?></a>
							<?php endif; ?>
							<!--Title-->
								<!--excerpt-->
							<?php if ( $show_excerpt ) : ?>
								<div class="recent-post-widget_post-excerpt-<?php echo esc_attr($layouts); ?>">
									<?php echo multi_excerpt($excerptlength); ?>
								</div>
							<?php endif; ?>
							<!--excerpt-->
							<!--Date-->
							<?php if ( $show_date ) : ?>
							<span class="recent-post-date-<?php echo esc_attr($layouts); ?>">
							<?php echo get_the_date($date_formats_style);?></span>
							<?php endif; ?>
							<!--Date-->
							<!--category-->
							<?php $category = get_the_category(get_the_ID()); ?>
							<?php if ( $show_categoriess ) : ?>
							 <span class="post-category recent-post-category-<?php echo esc_attr($layouts); ?>"><a href="<?php echo esc_url(get_category_link($category[0]->cat_ID)); ?>"><?php echo esc_html__($category[0]->cat_name,'multiwidget'); ?></a> </span>
							<?php endif; ?>
								<!--category-->
						</div>
            </li>
					<?php } // ============defaultlayout end================== ?>
					
					<?php endwhile;  ?>
			</ul>
			<?php echo wp_kses(__($args['after_widget'],'multiwidget'),$allowed_tags_before_after); ?>
			<?php
			// Reset the global $the_post as this query will have stomped on it
			wp_reset_postdata();
		endif;
	}
	/**
	 * Handles updating the settings for the current Recent Posts widget instance.
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
		$instance['image_size'] = $new_instance['image_size'];
		$instance['number'] = (int) $new_instance['number'];
		$instance['slide_number'] = (int) $new_instance['slide_number'];
		$instance['excerptlength'] = (int) $new_instance['excerptlength'];
		$instance['show_date'] = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;
		$instance['show_excerpt'] = isset( $new_instance['show_excerpt'] ) ? (bool) $new_instance['show_excerpt'] : false;
		$instance['show_thumbnail'] = isset( $new_instance['show_thumbnail'] ) ? (bool) $new_instance['show_thumbnail'] : false;
		$instance['show_categoriess'] = isset( $new_instance['show_categoriess'] ) ? (bool) $new_instance['show_categoriess'] : false;
		$instance['show_title'] = isset( $new_instance['show_title'] ) ? (bool) $new_instance['show_title'] : false;
		$instance['show_widget_title'] = isset( $new_instance['show_widget_title'] ) ? (bool) $new_instance['show_widget_title'] : false;
		$instance['layouts'] = $new_instance['layouts'];
		$instance['date_formats'] = $new_instance['date_formats'];
		return $instance;
	}
	/**
	 * Outputs the settings form for the Recent Posts widget.
	 *
	 * @since 1.0.0
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) 
	{
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'layouts'=>'defaultlayout','date_formats' =>'defaultlayout' ) );
		$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$image_size = isset( $instance['image_size'] ) ? esc_attr( $instance['image_size'] ) : 'thumbnail';
		$number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		$slide_number    = isset( $instance['slide_number'] ) ? absint( $instance['slide_number'] ) : 4;
		$excerptlength    = isset( $instance['excerptlength'] ) ? absint( $instance['excerptlength'] ) : 10;
		$show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
		$show_excerpt = isset( $instance['show_excerpt'] ) ? (bool) $instance['show_excerpt'] : false;
		$show_thumbnail = isset( $instance['show_thumbnail'] ) ? (bool) $instance['show_thumbnail'] : false;
		$show_categoriess = isset( $instance['show_categoriess'] ) ? (bool) $instance['show_categoriess'] : false;
		$show_title = isset( $instance['show_title'] ) ? (bool) $instance['show_title'] : true;
		$show_widget_title = isset( $instance['show_widget_title'] ) ? (bool) $instance['show_widget_title'] : true;
		$layouts = $instance['layouts'];
		$date_formats = $instance['date_formats'];
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">
				<?php esc_html_e( 'Title:','multiwidget' ); ?>				
			</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_html($title); ?>" />
		</p>
		<p>
			<input class="checkbox" type="checkbox"<?php checked( $show_widget_title ); ?> id="<?php echo $this->get_field_id( 'show_widget_title' ); ?>" name="<?php echo $this->get_field_name( 'show_widget_title' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'show_widget_title' ); ?>">
				<?php esc_html_e( 'Display Widget Title','multiwidget' ); ?>
			</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">
				<?php esc_html_e( 'Select Image Size :','multiwidget' ); ?>
			</label>
			<?php
				$image_sizes = get_intermediate_image_sizes();
			?>
			<select class='widefat' id="<?php echo $this->get_field_id( 'image_size' ); ?>" name="<?php echo $this->get_field_name('image_size'); ?>" type="text">
				<?php
				foreach ($image_sizes as $size_name):
				?>
					<option <?php selected($size_name,  $image_size); ?> value="<?php printf(__( '%s', 'multiwidget' ),$size_name); ?>" >
					<?php printf(__( '%s', 'multiwidget' ),$size_name); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>">
				<?php esc_html_e( 'Number of posts to show:','multiwidget' ); ?>
			</label>
			<input class="tiny-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo esc_html($number); ?>" size="3" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'slide_number' ); ?>">
				<?php esc_html_e( 'Slide to show:','multiwidget' ); ?>
			</label>
			<input class="tiny-text" id="<?php echo $this->get_field_id( 'slide_number' ); ?>" name="<?php echo $this->get_field_name( 'slide_number' ); ?>" type="slide_number" step="1" min="1" value="<?php echo esc_html($slide_number); ?>" size="3" />
		</p>
		<p>
			<input class="checkbox" type="checkbox"<?php checked( $show_title ); ?> id="<?php echo $this->get_field_id( 'show_title' ); ?>" name="<?php echo $this->get_field_name( 'show_title' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'show_title' ); ?>"><?php esc_html_e( 'Display Title','multiwidget' ); ?>
			</label>
		</p>
		<p>
			<input class="checkbox" type="checkbox"<?php checked( $show_date ); ?> id="<?php echo $this->get_field_id( 'show_date' ); ?>" name="<?php echo $this->get_field_name( 'show_date' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'show_date' ); ?>">
				<?php esc_html_e( 'Display post date?','multiwidget' ); ?>				
			</label>
		</p>
		<p>
			<input class="checkbox" type="checkbox"<?php checked( $show_excerpt ); ?> id="<?php echo $this->get_field_id( 'show_excerpt' ); ?>" name="<?php echo $this->get_field_name( 'show_excerpt' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'show_excerpt' ); ?>">
			<?php esc_html_e( 'Display Excerpt','multiwidget' ); ?>				
			</label>
		</p>
		<p>
			<input class="checkbox" type="checkbox"<?php checked( $show_thumbnail ); ?> id="<?php echo $this->get_field_id( 'show_thumbnail' ); ?>" name="<?php echo $this->get_field_name( 'show_thumbnail' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'show_thumbnail' ); ?>">
				<?php esc_html_e( 'Display Thumbanil','multiwidget' ); ?>			
			</label>
		</p>
		<p>
			<input class="checkbox" type="checkbox"<?php checked( $show_categoriess ); ?> id="<?php echo $this->get_field_id( 'show_categoriess' ); ?>" name="<?php echo $this->get_field_name( 'show_categoriess' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'show_categoriess' ); ?>">
				<?php esc_html_e( 'Display Post Category','multiwidget' ); ?>
			</label>
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'excerptlength' ); ?>"><?php esc_html_e( 'Excerpt Lenght:','multiwidget' ); ?></label>
		<input class="tiny-text" id="<?php echo $this->get_field_id( 'excerptlength' ); ?>" name="<?php echo $this->get_field_name( 'excerptlength' ); ?>" type="excerptlength" step="1" min="1" value="<?php echo esc_html($excerptlength); ?>" size="3" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('text'); ?>">
				<?php esc_html_e('Layout','multiwidget'); ?>
				<select class='widefat' id="<?php echo $this->get_field_id('layouts'); ?>" name="<?php echo $this->get_field_name('layouts'); ?>" type="text">
					<option value='defaultlayout'<?php echo ($layouts=='defaultlayout')?'selected':''; ?>>
						<?php esc_html_e('Default Layout','multiwidget'); ?>
					</option>		
					<option value='sliderlayout'<?php echo ($layouts=='sliderlayout')?'selected':''; ?>>
					<?php esc_html_e('Slider Layout','multiwidget'); ?>
					</option>
				</select>
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('text'); ?>">
				<?php esc_html_e('Date formats','multiwidget'); ?>
				<select class='widefat' id="<?php echo $this->get_field_id('date_formats'); ?>" name="<?php echo $this->get_field_name('date_formats'); ?>" type="text">
					<option value='defaultlayout'<?php echo ($date_formats=='defaultlayout')?'selected':''; ?>>
						<?php esc_html_e('Layout (Aug 12 | 2017)','multiwidget'); ?>
					</option>
					<option value='layout-2'<?php echo ($date_formats=='layout-2')?'selected':''; ?>>
						<?php esc_html_e('Layout (August 12)','multiwidget'); ?>
					</option>
					<option value='layout-3'<?php echo ($date_formats=='layout-3')?'selected':''; ?>>
						<?php esc_html_e('Layout (12 August 2017)','multiwidget'); ?>
					</option>
				</select>
			</label>
		</p>
		<?php
	}
}
register_widget('Multi_recent_post_widget');
