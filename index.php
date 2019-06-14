<?php
/*
Plugin Name: Fedora Badges
Plugin URI: http://blackfile.dev/
Description: Show your Fedora Badge on your blog
Author: Luis M. Segundo
Version: 1.0
Author URI: http://blackfile.dev/
License: GPL2
*/

class Fedora_Badge_Widget extends WP_Widget {

	public function __construct() {
		$widget_options = array( 'classname' => 'Fedora_Badge', 'description' => 'Widget to display Fedora Badges' );
		parent::__construct( 'Fedora_Badge', 'Fedora Badges', $widget_options );
	}

	public function widget( $args, $instance ) {
		$fasID = apply_filters( 'widget_badge', $instance[ 'fedora_fasID' ] );

				if($fasID!=""){
					echo $this->getBadge($fasID);
				}

	}

	public function form( $instance ) {
		$badges = ! empty( $instance['fedora_fasID'] ) ? $instance['fedora_fasID'] : ''; ?>
		<div>
			<label for="<?php echo $this->get_field_id( 'fedora_fasID' ); ?>">Your FAS ID:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'fedora_fasID' ); ?>" name="<?php echo $this->get_field_name( 'fedora_fasID' ); ?>" value="<?php echo esc_attr( $badges ); ?>" /> <br>
			</div><?php
		}

		public function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance[ 'fedora_fasID' ] = strip_tags( $new_instance[ 'fedora_fasID' ] );
			return $instance;
  }

      function getBadge($fasID){

        $rss = simplexml_load_file('https://badges.fedoraproject.org/user/'.$fasID.'/rss');
        $count = 0;
        $html .= '<div  class="widget">';
        $html .= '<h1 class="widget-title">Badges for <a href="https://badges.fedoraproject.org/user/'.$fasID.'" target="_blank">'.$fasID.'</a></h1>';
        foreach($rss->channel->item as$item) {
            $count++;
            $imgs = explode('>', $item->description);
            $html .= "<span style='width:50px;float:left'>". $imgs[0]."></span>";
        }

        $html .= '</div>';
        if($count>0){
        	return $html;
        }
    }

 }

add_action( 'widgets_init', function(){
	register_widget( 'Fedora_Badge_Widget' );
});

?>