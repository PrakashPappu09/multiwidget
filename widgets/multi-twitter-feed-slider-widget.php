<?php
/**
 * Widget API: MultiTwitterFeedSliderWidget class
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
require_once ( plugin_dir_path( __FILE__).'classes/TwitterAPIExchange.php'); 

class MultiTwitterFeedSliderWidget extends WP_Widget {

    /**
    * Sets up a new Multiwidget Twitter feed widget instance.
    *
    * @since 1.0.0
    */

    public function __construct() 
    {
      $widget_ops = array('classname' => 'multiwidget_twitter_feed_slider', 'description' => esc_html__( "Show any user twitter feed at your site.","multiwidget") );
      parent::__construct('multiwidget-twitter-slider-feeds', esc_html__('Multiwidget Twitter Sider Feed','multiwidget'), $widget_ops);
      $this->alt_option_name = 'multiwidget_twitter_feed_slider';

      add_action( 'save_post', array($this, 'flush_widget_cache') );
      add_action( 'deleted_post', array($this, 'flush_widget_cache') );
      add_action( 'switch_theme', array($this, 'flush_widget_cache') );
    }
    /**
     * Outputs the content for the current  Multiwidget Twitter feed widget instance.
     *
     * @since 1.0.0
     *
     * @param array $args     Display arguments including 'before_title', 'after_title',
     *                        'before_widget', and 'after_widget'.
     * @param array $instance Settings for the current Multiwidget Twitter feed widget instance.
     */
  public function widget($args, $instance) 
  {
    $cache = array();
    if ( ! $this->is_preview() ) 
    {
        $cache = wp_cache_get( 'multiwidget-twitter-slider-feeds', 'multiwidget' );
    }
    if ( ! is_array( $cache ) ) 
    {
        $cache = array();
    }
    if ( ! isset( $args['widget_id'] ) ) 
    {
        $args['widget_id'] = $this->id;
    }

    if ( isset( $cache[ $args['widget_id'] ] ) ) 
    {
        echo $cache[ $args['widget_id'] ];
        return;
    }

    ob_start();

    $title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : esc_html__( 'Latest Tweets','multiwidget' );     
    $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
    $show_widget_title = isset( $instance['show_widget_title'] ) ? $instance['show_widget_title'] : false;  
    $username = apply_filters('multiwidget_twitter_feed', empty($instance['username']) ? '' : $instance['username'], $instance);
    $consumerkey = apply_filters('multiwidget_twitter_feed', empty($instance['consumerkey']) ? '' : $instance['consumerkey'], $instance);
    $consumersecret = apply_filters('multiwidget_twitter_feed', empty($instance['consumersecret']) ? '' : $instance['consumersecret'], $instance);
    $accesstoken = apply_filters('multiwidget_twitter_feed', empty($instance['accesstoken']) ? '' : $instance['accesstoken'], $instance);
    $accesstokensecret = apply_filters('multiwidget_twitter_feed', empty($instance['accesstokensecret']) ? '' : $instance['accesstokensecret'], $instance);
    
    $number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 3;
    if ( ! $number ) $number = 3;

    $tweet_no_item = ( ! empty( $instance['tweet_no_item'] ) ) ? absint( $instance['tweet_no_item'] ) : 2;
    if ( ! $tweet_no_item ) $tweet_no_item = 2;

    $allowed_tags_before_after=array('div' => array('class'=>array(),'id'=>array()),'h3'=>array('class'=>array(),),'span'=>array(),'aside'=>array('class'=>array(),'id'=>array()));  
    echo wp_kses(__($args['before_widget'],'multiwidget'),$allowed_tags_before_after); 

    ?>
    <div class="twitter-posts">
    <?php if($show_widget_title)
      { ?>
        <h5 class="widget-title"><?php printf(__('%s','multiwidget'),$title);?></h5>  
        <?php 
      } ?>          
        <?php 
        $settings = array(
            'oauth_access_token' => "$accesstoken",
            'oauth_access_token_secret' => "$accesstokensecret",
            'consumer_key' => "$consumerkey",
            'consumer_secret' => "$consumersecret"
            );

        if($accesstoken != '' & $accesstokensecret != '' & $consumerkey != '' & $consumersecret != ''){

            $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
            $getfield = '?screen_name='.$username.'&count='.$number.'';

            $requestMethod = 'GET';

            $twitter = new TwitterAPIExchange($settings);
            $response = $twitter->setGetfield($getfield)
            ->buildOauth($url, $requestMethod)
            ->performRequest();

                        $valid_data = json_decode($response); //JSON data to PHP.   

                        if(!is_object($valid_data)) 
                        { 
                            ?>    
                              
    <div class="multiwidget-twitter-wrapper">
      <div id="twitter-<?php echo esc_attr( $args['widget_id'] ); ?>" class="twitter-slick-feeds" data-item="<?php echo esc_attr($tweet_no_item);?>">
                                <?php 
                                foreach ($valid_data as $key=>$feed) 
                                {                                     
                                    ?>
                                    <div class="item"><i class="fa fa-twitter"></i><?php printf(__('%s','multiwidget'),$twitter->make_clickable($feed)); ?></div>
                                    <?php   
                                } 
                                ?>
                              </div>
                            </div>
                            <?php 
                        } 
                        else 
                        {
                            printf(__('%s','multiwidget'),$valid_data->errors[0]->message);
                        }
                    } 
                    else 
                    {
                        ?>
                        <p><?php esc_html_e('Enter Your Twitter Details','multiwidget'); ?></p>
                        <?php
                    } 
                    ?>
                </div>
                <?php echo wp_kses(__($args['after_widget'],'multiwidget'),$allowed_tags_before_after);?>

                <?php
                if(!$this->is_preview() ) 
                {
                 $cache[ $args['widget_id'] ] = ob_get_flush();
                 wp_cache_set( 'multiwidget-twitter-feeds', $cache, 'multiwidget' );
             }
             else
             {
                 ob_end_flush();
             }
         }
     /**
      * Handles updating the settings for the current Multiwidget Twitter feed instance.
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
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['show_widget_title'] = isset( $new_instance['show_widget_title'] ) ? (bool) $new_instance['show_widget_title'] : false;
        $instance['username'] = strip_tags($new_instance['username']);
        $instance['consumerkey'] = strip_tags($new_instance['consumerkey']);
        $instance['consumersecret'] = strip_tags($new_instance['consumersecret']);
        $instance['accesstoken'] = strip_tags($new_instance['accesstoken']);
        $instance['accesstokensecret'] = strip_tags($new_instance['accesstokensecret']);

        $instance['number'] = (int) $new_instance['number'];
        $instance['tweet_no_item'] = (int) $new_instance['tweet_no_item'];
        $this->flush_widget_cache();

        $alloptions = wp_cache_get( 'alloptions', 'options' );
        if ( isset($alloptions['multiwidget_twitter_feed']) )
           delete_option('multiwidget_twitter_feed');

        return $instance;
        }

         public function flush_widget_cache() {
          wp_cache_delete('multiwidget-twitter-feeds', 'multiwidget');
  }
  /**
   * Outputs the settings form for the  Multiwidget Twitter feed widget.
   *
   * @since 1.0.0
   *
   * @param array $instance Current settings.
   */
  public function form( $instance ) {
      $title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
      $show_widget_title = isset( $instance['show_widget_title'] ) ? (bool) $instance['show_widget_title'] : true;
      $username = isset( $instance['username'] ) ? esc_attr( $instance['username'] ) : '';
      $consumerkey = isset( $instance['consumerkey'] ) ? esc_attr( $instance['consumerkey'] ) : ''; 
      $consumersecret = isset( $instance['consumersecret'] ) ? esc_attr( $instance['consumersecret'] ) : ''; 
      $accesstoken = isset( $instance['accesstoken'] ) ? esc_attr( $instance['accesstoken'] ) : ''; 
      $accesstokensecret = isset( $instance['accesstokensecret'] ) ? esc_attr( $instance['accesstokensecret'] ) : ''; 
      $number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 3; 
      $tweet_no_item    = isset( $instance['tweet_no_item'] ) ? absint( $instance['tweet_no_item'] ) : 1; 
      ?>

      <p>
        <label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>">
            <?php esc_html_e( 'Twitter feed widget title:','multiwidget' ); ?>
        </label>
        <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name( 'title')); ?>" type="text" value="<?php printf(__('%s','multiwidget'),$title); ?>" />
    </p>
    <p>
    <input class="checkbox" type="checkbox"<?php checked( $show_widget_title ); ?> id="<?php echo $this->get_field_id( 'show_widget_title' ); ?>" name="<?php echo $this->get_field_name( 'show_widget_title' ); ?>" />
    <label for="<?php echo $this->get_field_id( 'show_widget_title' ); ?>"><?php esc_html_e( 'Display Widget Title','multiwidget' ); ?></label>
    </p>

    <p>
        <label for="<?php echo $this->get_field_id( 'username' ); ?>">
            <?php esc_html_e( 'Screen Name:','multiwidget' ); ?>
        </label>
        <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'username' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'username' )); ?>" type="text" value="<?php printf(__('%s','multiwidget'),$username); ?>" />
    </p>
    <p>
        <label for="<?php echo esc_attr($this->get_field_id( 'consumerkey' )); ?>">
            <?php esc_html_e( 'Consumer Key :','multiwidget' ); ?>
        </label>
        <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'consumerkey' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'consumerkey' )); ?>" type="text" value="<?php printf(__('%s','multiwidget'),$consumerkey); ?>" />
    </p>
    <p>
        <label for="<?php echo esc_attr($this->get_field_id( 'consumersecret' )); ?>">
            <?php esc_html_e( 'Consumer Secret :','multiwidget' ); ?>
        </label>
        <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'consumersecret' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'consumersecret' )); ?>" type="text" value="<?php printf(__('%s','multiwidget'),$consumersecret); ?>" />
    </p>
    <p>
        <label for="<?php echo esc_attr($this->get_field_id( 'accesstoken' )); ?>">
            <?php esc_html_e( 'Access Token:','multiwidget' ); ?>
        </label>
        <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'accesstoken' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'accesstoken' )); ?>" type="text" value="<?php printf(__('%s','multiwidget'),$accesstoken); ?>" />
    </p>
    <p>
        <label for="<?php echo esc_attr($this->get_field_id( 'accesstokensecret' )); ?>">
            <?php esc_html_e( 'Access Token Secret:','multiwidget' ); ?>
        </label>
        <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'accesstokensecret' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'accesstokensecret' )); ?>" type="text" value="<?php printf(__('%s','multiwidget'),$accesstokensecret); ?>" />
    </p> 
    <p>
        <label for="<?php echo esc_attr($this->get_field_id( 'number' )); ?>">
            <?php esc_html_e( 'Number of tweet to show:','multiwidget' ); ?>
        </label>
        <input id="<?php echo esc_attr($this->get_field_id( 'number' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'number' )); ?>" type="text" value="<?php printf(__('%s','multiwidget'),$number); ?>" size="3" />
    </p>

 <p>
        <label for="<?php echo esc_attr($this->get_field_id( 'tweet_no_item' )); ?>">
            <?php esc_html_e( 'Number slide to show:','multiwidget' ); ?>
        </label>
        <input id="<?php echo esc_attr($this->get_field_id( 'tweet_no_item' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'tweet_no_item' )); ?>" type="text" value="<?php printf(__('%s','multiwidget'),$tweet_no_item); ?>" size="3" />
    </p>

    <?php   }
}

/*
* Register widget for the  Multiwidget Twitter feed
*/
register_widget('MultiTwitterFeedSliderWidget');