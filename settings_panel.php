<?php 


 
 
 function mhshohel_faq_init() {
  $labels = array(
    'name'               => 'faqs',
    'singular_name'      => 'faq',
    'add_new'            => 'Add New',
    'add_new_item'       => 'Add New faq',
    'edit_item'          => 'Edit faq',
    'new_item'           => 'New faq',
    'all_items'          => 'All faqs',
    'view_item'          => 'View faq',
    'search_items'       => 'Search faqs',
    'not_found'          => 'No faqs found',
    'not_found_in_trash' => 'No faqs found in Trash',
    'parent_item_colon'  => '',
    'menu_name'          => 'faqs'
  );

  $args = array(
    'labels'             => $labels,
    'public'             => true,
    'publicly_queryable' => true,
	'taxonomies' 		 => array('faq_category'),  
    'show_ui'            => true,
	'Taxonomies'		 =>  true,
	'taxonomies' => array('category'),     
	'show_in_menu'       => true,
    'query_var'          => true,
    'rewrite'            => array( 'slug' => 'faq' ),
    'capability_type'    => 'post',
    'has_archive'        => true,
    'hierarchical'       => true,
    'menu_position'      => null,
    'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments',  )
  );

  register_post_type( 'faq', $args );
}
add_action( 'init', 'mhshohel_faq_init' );



function mhshohel_faq_updated_messages( $messages ) {
  global $post, $post_ID;

  $messages['faq'] = array(
    0 => '', // Unused. Messages start at index 1.
    1 => sprintf( __('faq updated. <a href="%s">View faq</a>', 'your_text_domain'), esc_url( get_permalink($post_ID) ) ),
    2 => __('Custom field updated.', 'your_text_domain'),
    3 => __('Custom field deleted.', 'your_text_domain'),
    4 => __('faq updated.', 'your_text_domain'),
    /* translators: %s: date and time of the revision */
    5 => isset($_GET['revision']) ? sprintf( __('faq restored to revision from %s', 'your_text_domain'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
    6 => sprintf( __('faq published. <a href="%s">View faq</a>', 'your_text_domain'), esc_url( get_permalink($post_ID) ) ),
    7 => __('faq saved.', 'your_text_domain'),
    8 => sprintf( __('faq submitted. <a target="_blank" href="%s">Preview faq</a>', 'your_text_domain'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
    9 => sprintf( __('faq scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview faq</a>', 'your_text_domain'),
      // translators: Publish box date format, see http://php.net/date
      date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
    10 => sprintf( __('faq draft updated. <a target="_blank" href="%s">Preview faq</a>', 'your_text_domain'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
  );

  return $messages;
}
add_filter( 'post_updated_messages', 'mhshohel_faq_updated_messages' );




add_action('admin_menu', 'faq_accordian_menu_settings');

function faq_accordian_menu_settings() {

	//create new top-level menu
	add_menu_page('Faq accordion Settings', 'Faq Settings', 'administrator', __FILE__, 'faq_accordian_seting',plugins_url('/asset/images/faq-icon.png', __FILE__));
	
add_submenu_page( __FILE__, 'Mhshohel Faqs Documentation', 'Documentation', 'administrator','mhshohel-faq-documentation', 'mhshohel_faqs_documentation');

	//call register settings function
	add_action( 'admin_init', 'accordion_settings' );
}


function accordion_settings() {
	//register our settings
	register_setting( 'faq_accordion_settings', 'load_place' );
	register_setting( 'faq_accordion_settings', 'faq_acc_custom_css' );
	register_setting( 'faq_accordion_settings', 'icon_style' );
	register_setting( 'faq_accordion_settings', 'count' );
	register_setting( 'faq_accordion_settings', 'faq_custom_text' );
}

function faq_accordian_seting() {
?>
<div class="wrap">
<h2>Mh Shohel Faq Accordion Settings</h2>

<form method="post" action="options.php">
    <?php settings_fields( 'faq_accordion_settings' ); ?>
    <?php do_settings_sections( 'faq_accordion_settings' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Load Jquery</th>
        <td>
        	<select name="load_place"> 
            <option value="">Select where load jquery</option>
            <option value="wp_head" <?php if(get_option('load_place')=='wp_head'){echo 'selected';} ?> >Head</option>
            <option value="wp_footer"  <?php if(get_option('load_place')=='wp_footer'){echo 'selected';} ?> >Footer</option>
            </select>
        
        	</td>
        </tr>
 
 
         <tr valign="top">
        <th scope="row">Icon Style</th>
        <td>
        	<select name="icon_style"> 
            <option value="">Select Icon Style</option>
            <option value="icon_default" <?php if(get_option('icon_style')=='icon_default'){echo 'selected';} ?> >Icon Default</option>
            <option value="icon_arrow"  <?php if(get_option('icon_style')=='icon_arrow'){echo 'selected';} ?> >Icon Arrow</option>
            <option value="icon_plus"  <?php if(get_option('icon_style')=='icon_plus'){echo 'selected';} ?> >Icon Plus</option>
            <option value="icon_null"  <?php if(get_option('icon_style')=='icon_null'){echo 'selected';} ?> >Icon Hide</option>
            </select>
        
        	</td>
        </tr>


         <tr valign="top">
        <th scope="row">Numaric Count</th>
        <td>
        	<select name="count"> 
            <option value="">Select Count Status</option>
            <option value="count_show" <?php if(get_option('count')=='count_show'){echo 'selected';} ?> >Show</option>
            <option value="count_hide"  <?php if(get_option('count')=='count_hide'){echo 'selected';} ?> >Hide</option>
            </select>
        
        	</td>
        </tr>

         <tr valign="top">
        <th scope="row">Custom Text</th>
        <td> <input type="text" name="faq_custom_text" value="<?php echo get_option('faq_custom_text'); ?> "  />
        	</td>
        </tr> 


        <tr valign="top">
        <th scope="row">Custom Css</th>
        <td><textarea name="faq_acc_custom_css" rows="7"><?php echo get_option('faq_acc_custom_css'); ?></textarea>
        
        	</td>
        </tr>


        <tr valign="top">
        <th scope="row">Like me on facebook</th>
        <td><iframe src="//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fmhshohel1&amp;width&amp;height=290&amp;colorscheme=light&amp;show_faces=true&amp;header=true&amp;stream=false&amp;show_border=true&amp;appId=404671166253209" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:290px;" allowTransparency="true"></iframe>
        
        	</td>
        </tr>

         
    </table>
    
    <?php submit_button(); ?>

</form>
</div>
<?php } 

function mhshohel_faqs_documentation(){
	include 'documentation.php';
}


