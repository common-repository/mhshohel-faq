<?php
/**
 * Plugin Name: Mhshohel Faq
 * Plugin URI: http://wordpress.org/plugins/mhshohel-faq
 * Description: A brief description of the Plugin.
 * Version: 1.2
 * Author: mhshohel
 * Author URI: http://facebook.com/mhshohel1
 */


function mhshohel_faq_query($atts,$content=null){

	extract(shortcode_atts(array("limit" => '', 'order'=>'','category'=>''), $atts));

	// Define limit
	if( $limit ) {
		$posts_per_page = $limit;
	} else {
		$posts_per_page = '-1';
	}

	ob_start();

	$query = new WP_Query( array (
			'post_type'      => 'faq',
			'posts_per_page' => $posts_per_page,
			'cat'			=> $category,
			'order'          => $order,
			'no_found_rows'  => 1
		)
	);

	$post_count = $query->post_count;

	if( $post_count > 0) :

		echo '<div id="accordion">';
		$icount=0;
		while ($query->have_posts()) : $query->the_post(); $icount++;
			?>

			<h3><?php if(get_option('faq_custom_text') !=''){ echo get_option('faq_custom_text');} ?>
				<?php if(get_option('count')=='count_show'){echo $icount.'.';} ?>
				<?php the_title(); ?>
			</h3>

			<div>
				<p><?php echo get_the_content(); ?></p>
			</div>
			<?php
		endwhile;
		echo '</div>';
	endif;
	wp_reset_query();

	return ob_get_clean();
}

add_shortcode('mhshohel_faq','mhshohel_faq_query');


function accordian_style() {
	wp_enqueue_style( 'accordianstylesheet', plugins_url('/asset/css/jquery-ui-css-1.10.3.css', __FILE__) );
}

add_action( 'wp_enqueue_scripts', 'accordian_style' );


function mhshohel_faqs_script(){
	if(!is_admin()){
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-core');
		wp_register_script('mhshohel_faqs_js', plugins_url('/asset/js/faq-accordion.js', __FILE__ ), array('jquery-ui-accordion'),true);
		wp_enqueue_script('mhshohel_faqs_js');
	}
}
add_action( 'init', 'mhshohel_faqs_script');


function jquery_accordian_selector() {
	?>

	<script type="text/javascript" >
		var $faqaccordion = jQuery.noConflict();
		$faqaccordion(function() {
			$faqaccordion( "#accordion" ).accordion({
				collapsible: true,
				active: 500000
				<?php if(get_option('icon_style')=='icon_plus'){echo ', icons: { "header": "ui-icon-plus", "activeHeader": "ui-icon-minus" }';}
			elseif(get_option('icon_style')=='icon_arrow'){echo ', icons: {  header: "ui-icon-circle-arrow-e", activeHeader: "ui-icon-circle-arrow-s" }';}
			elseif(get_option('icon_style')=='icon_null'){echo ', icons: null';}
				?>

			});
		});
	</script>
	<?php
}

add_action( 'wp_footer', 'jquery_accordian_selector' );

if(get_option('load_place')=='wp_head'){ add_action( 'wp_head', 'jquery_accordian_selector' );}
else{ add_action( 'wp_footer', 'jquery_accordian_selector' );}




function mhshohel_faqs_css(){
	echo '<style type="text/css">';
	echo get_option('faq_acc_custom_css');
	echo '</style>';
}

add_action('wp_head', 'mhshohel_faqs_css');

function mhshohel_faqs_settings( $links )
{
	$links[] = '<a href="'.admin_url('admin.php?page=mhshohel-faq/settings_panel.php').'">Settings</a>';
	return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'mhshohel_faqs_settings' );

require_once('settings_panel.php');

/**
 * Set some activation hook
 */
function mhshohel_faq_activation() {
	$is_activated = get_option('is_mhshohel_faq_activated');
	if ( ! $is_activated){
		update_option('is_mhshohel_faq_activated', true);
		update_option('load_place', 'wp_head');
		update_option('faq_acc_custom_css', '');
		update_option('icon_style', 'icon_arrow');
		update_option('count', 'count_show');
	}
}
register_activation_hook( __FILE__, 'mhshohel_faq_activation' );