<?php
/*
Plugin Name: BackType Most Tweeted Posts Widget
Plugin URI: http://www.extreme-media.co.uk/2010/08/backtype-most-tweeted-posts-widget/
Description: Show Most tweeted posts in the sidebar. Set Title, Number of posts to show and select a category
Author: Lee Everson
Version: 1.3
Author URI: http://www.extreme-media.co.uk
*/
function bttc_toptweets_widget_init() {
	if ( !function_exists('register_sidebar_widget') ) {
		return;
	}
		
	function bttc_toptweets_widget($args) {
		extract($args);
		global $wpdb;
				
		$options = get_option('bttc_toptweets_widget');
		
		$bttc_toptweets_category = !empty($options['bttc_toptweets_category']) ? htmlspecialchars($options['bttc_toptweets_category'], ENT_QUOTES) : -1;
		$title = !empty($options['title']) ? htmlspecialchars($options['title'], ENT_QUOTES) : 'Most Tweeted Posts';
		$bttc_toptweets_max_count = !empty($options['bttc_toptweets_max_count']) ? htmlspecialchars($options['bttc_toptweets_max_count'], ENT_QUOTES) : 5;
		
		// Get unpublished posts in the selected category
	 	$querystr = "
		SELECT guid, post_title, SUBSTR(meta_value, 12, 1) AS Tweet
		FROM $wpdb->posts  
		INNER JOIN $wpdb->postmeta  
		ON $wpdb->posts.ID = $wpdb->postmeta.post_id 
		INNER  JOIN $wpdb->term_relationships ON($wpdb->posts.ID = $wpdb->term_relationships.object_id)
		INNER  JOIN $wpdb->term_taxonomy ON($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)
		WHERE $wpdb->postmeta.meta_key='bttc_cache'
		AND $wpdb->posts.post_status='publish'
		AND SUBSTRING(meta_value, 12, 1) > 0 
			";
		if ($bttc_toptweets_category > 0) {
		$querystr .= "
			AND $wpdb->term_taxonomy.term_id = ".$bttc_toptweets_category."
			AND $wpdb->term_taxonomy.taxonomy = 'category'
		";
		}
		$querystr .= "
		GROUP BY post_title
		ORDER BY Tweet DESC 
		LIMIT ".($bttc_toptweets_max_count). "
	 	";
	 	$max_posts = $wpdb->get_results($querystr, OBJECT);

		echo $before_widget;
		echo $before_title . $title . $after_title;

		if (count($max_posts) > 0) {
			echo '
			<div>
			<ul>
			';
			for ($i=0; $i<count($max_posts); $i++) {
				echo '<li><a href="'.$max_posts[$i]->guid.'">'.$max_posts[$i]->post_title.' ['.$max_posts[$i]->Tweet.']</li>';
			}
			echo '
			</ul>
			</div>
			';
			$nextpost['post_title'] = $max_posts[3]->post_title;
			$nextpost['post_date'] = $max_posts[3]->post_date;
		} else {
			$nextpost['post_title'] = $max_posts[0]->post_title;
			$nextpost['post_date'] = $max_posts[0]->post_date;
		}
		
		echo $after_widget;
	}
	

	function bttc_toptweets_widget_control() {

		$options = get_option('bttc_toptweets_widget');
		if ( isset($_POST['bttc_toptweets_widget_submit']) ) {
			$options['title'] = strip_tags(stripslashes($_POST['bttc_toptweets_title']));
			$options['bttc_toptweets_max_count'] = strip_tags(stripslashes($_POST['bttc_toptweets_max_count']));
			$options['bttc_toptweets_category'] = strip_tags(stripslashes($_POST['bttc_toptweets_category']));
			
			update_option('bttc_toptweets_widget', $options);
		}
		
		$bttc_toptweets_category = htmlspecialchars($options['bttc_toptweets_category'], ENT_QUOTES);
		$title = htmlspecialchars($options['title'], ENT_QUOTES);
		$bttc_toptweets_max_count = htmlspecialchars($options['bttc_toptweets_max_count'], ENT_QUOTES);
		$categories = get_categories('hide_empty=0&orderby=ID&order=asc');
						
		echo '
		<p>
			<label for="bttc_toptweets_title">
			' . __('Title:') . '
				<input style="width: 250px;" name="bttc_toptweets_title" id="bttc_toptweets_title" type="text" value="'.$title.'" />
			</label>
		</p>
		<p>
			<label for="bttc_toptweets_max_count">
			' . __('Upcoming Count:') . '
				<input style="width: 250px;" name="bttc_toptweets_max_count" id="bttc_toptweets_max_count" type="text" value="'.$bttc_toptweets_max_count.'" />
			</label>
		</p>
		<p>
			<label for="bttc_toptweets_category">
			' . __('Category:') . '
				<select name="bttc_toptweets_category">';
					if ($bttc_toptweets_category == -1) {
						echo '<option selected="selected" value="-1">All categories</option>';
					} else {
						echo '<option value="-1">All categories</option>';
					}
				foreach ($categories as $cat) {
					if (empty($cat->category_parent)) {
						if ($bttc_toptweets_category == $cat->cat_ID) {
							echo '<option selected="selected" value="'.$cat->cat_ID.'">'.$cat->name.'</option>';
						} else {
							echo '<option value="'.$cat->cat_ID.'">'.$cat->name.'</option>';
						}
					}
				}
				echo '	
				</select>
			</label>
		</p>
		
		<input type="hidden" name="bttc_toptweets_widget_submit" id="bttc_toptweets_widget_submit" value="1" />
		';
	}
	
	register_widget_control(array('Most Tweeted Posts', 'widgets'), 'bttc_toptweets_widget_control', 350, 350);
	register_sidebar_widget(array('Most Tweeted Posts', 'widgets'), 'bttc_toptweets_widget');
}
add_action('plugins_loaded', 'bttc_toptweets_widget_init');