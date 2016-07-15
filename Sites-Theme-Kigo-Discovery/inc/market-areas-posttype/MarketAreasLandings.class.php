<?php
/**
 * MarketAreasLandings class
 * This class creates the Market areas post types, its custom fields and the widget to list them in the site..
 */
namespace Discovery\MarketAreas;
if (!session_id()) {
    session_start();
}
require_once 'MetaBox.php';
require_once 'GenerateLandingMetaBox.class.php';
require_once 'PropsNMarketAreas.class.php';

use Discovery\MarketAreas\MetaBox;

if(!defined(DS))
    define('DS', DIRECTORY_SEPARATOR);

class MarketAreasLandings
{
    private $templates_dir;
    private $custom_fields;

    function __construct($templatesdir='')
    {

        $this->custom_fields = $this->get_custom_fields();
        // Set the location of the market areas templates
        $this->templates_dir = $templatesdir;

        //Get Templates Info:
        $this->get_templates_info();

        //Meta boxes
        $this->meta_boxes();

        //Create the custom post
        add_action( 'init', [$this,'create_ma_custom_post'] );

        // Load admin scripts
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);

        /*
           Debug preview with custom fields
        */

        /* source: https://wordpress.org/support/topic/preview-and-post-meta-custom-fields-solution */
        add_filter('_wp_post_revision_fields', [$this, 'add_field_debug_preview']);
        add_action( 'edit_form_after_title', [$this, 'add_input_debug_preview'] );

        /* source: https://johnblackbourn.com/post-meta-revisions-wordpress */
        add_action( 'save_post', [$this,'market_areas_save_post'] );
        add_action( 'wp_restore_post_revision', [$this,'market_areas_restore_revision'], 10, 2 );
        add_filter( '_wp_post_revision_fields', [$this,'market_areas_revision_fields'] );
        foreach($this->custom_fields as $custom_field)
        {
            add_filter( '_wp_post_revision_field_' . $custom_field['id'], [$this,'market_areas_revision_field'], 10, 2 );
        }
    }

    function market_areas_save_post( $post_id ) {

    	$parent_id = wp_is_post_revision( $post_id );

    	if ( $parent_id ) {

    		$parent  = get_post( $parent_id );
            $metas_array = [];
            foreach($this->custom_fields as $custom_field)
            {

                $my_meta = filter_input( INPUT_POST, $custom_field['id']);

        		if ( false !== $my_meta )
    			{
                     //Putting the meta data in the revision excerpt
                    $metas_array[$custom_field['id']] = json_decode($my_meta);
                }
            }
            $this->hold_preview_metas($metas_array);
    	}

    }

    function hold_preview_metas($metas_array)
    {

        $_SESSION['market_area_preview_metas'] = $metas_array;

    }

    function market_areas_restore_revision( $post_id, $revision_id ) {

    	$post     = get_post( $post_id );
    	$revision = get_post( $revision_id );
        foreach($this->custom_fields as $custom_field)
        {
            $my_meta  = get_metadata( 'post', $revision->ID, $custom_field['id'], true );

        	if ( false !== $my_meta )
        		update_post_meta( $post_id, $custom_field['id'], $my_meta );
        	else
        		delete_post_meta( $post_id, $custom_field['id'] );
        }


    }

    function market_areas_revision_fields( $fields ) {
        foreach($this->custom_fields as $custom_field)
        {
            $fields[$custom_field['id']] = $custom_field['id'];
        }

    	return $fields;

    }

    function market_areas_revision_field( $value, $field ) {

    	global $revision;
    	return get_metadata( 'post', $revision->ID, $field, true );

    }

    function add_field_debug_preview($fields){
       $fields['market_area_props_n_areas'] = 'debug_preview';
       return $fields;
    }

    function add_input_debug_preview() {
       echo '<input type="hidden" name="market_area_props_n_areas" value="market_area_props_n_areas">';
    }



    function create_ma_custom_post()
    {
        //Market areas post type
        $post_types = array(
            'market-areas'          => array(
                'label'             => 'Market Areas',
                'labels'            => array(
                    'name'          => __('Market Areas', 'kd'),
                    'singular_name' => __('Market Area', 'kd'),
                ),
                'description'       => __('Group properties in a single landing page.','kd'),
                'public'            => true,
                'capability_type'   => 'page',
                'has_archive'       => true,
                'menu_icon'         => 'dashicons-groups',
                'show_ui'           => true,
                'show_in_menu'      => true,
                'show_in_nav_menus' => true,
                'hierarchical'      => true,
                'show_in_admin_bar' => true,
                'taxonomies'        => array('category'),
                'menu_position'     => 10,
                'supports' => array('title', 'editor','thumbnail', 'page-attributes'),
                'description'       => 'Market Areas custom posts',
            ),
        );

        foreach ($post_types as $name => $args) {
            register_post_type($name, $args);
        }
    }

    function meta_boxes()
    {
          $args_array = $this->custom_fields;

          foreach ($args_array as $args) {

                if($args['id']!=='market_area_generate_landing')
                    $newMeta = new MetaBox($args,dirname(__FILE__) . DIRECTORY_SEPARATOR .'meta_templates');
                else
                    $newMeta = new GemerateLandingMetaBox($args,dirname(__FILE__) . DIRECTORY_SEPARATOR .'meta_templates'); //special class to
            }

    }

    /**
     * Gets the first block comment of the page
     * @param  string $filename the name of the file to parse (a valid path)
     * @return array           the comments.
     */
    function getFileDocBlock($filename)
    {
        $docComments = array_filter(
            token_get_all( file_get_contents( $filename ) ), function($entry) {
                return $entry[0] == T_DOC_COMMENT;
            }
        );
        $fileDocComment = array_shift( $docComments );
        return $fileDocComment[1];
    }


    /**
     * Saves an array of the market areas templates info as a theme mod as a json string
     * @return void this function does not return any value
     */
    function get_templates_info()
    {
        $templates_info = [];
        // 1. get all templates inside the templates dir if available

        try {
            // echo $this->templates_dir; die;
            $files_in_dir = scandir($this->templates_dir);

            foreach($files_in_dir as $filename)
            {
                $template_name = substr($filename, 0, -4);
                if(!preg_match('/market-areas/', $filename))
                    continue;

                $filedoc = $this->getFileDocBlock($this->templates_dir . DS . $filename);
                preg_match_all('/@[a-z0-9A-Z]+:.*/',$filedoc, $matches);

                if(count($matches))
                {
                    $templates_info[$template_name] = [];

                    foreach($matches[0] as $infoline)
                    {
                        $parts = explode(':',substr($infoline, 1));
                        $templates_info[$template_name][$parts[0]] = trim($parts[1]);
                    }

                }

            }

        } catch (\Exception $e) {
            //pass
        }

        set_theme_mod('market-areas-templates-info', json_encode($templates_info)) ;
    }


    function enqueue_admin_scripts()
    {
        global $pagenow, $typenow;

        /*===============================
        - Only in market areas page -
        ==================================*/
        if (is_admin() && $pagenow=='post-new.php' OR $pagenow=='post.php' && $typenow=='market-areas')
        {
            wp_enqueue_script('sortable-jquery', get_stylesheet_directory_uri() . "/kd-common/js/vendor/jquery-sortable.js",[], '0.9.13', true); //Loaded in the footer to overwrite the one by default.
            wp_enqueue_script('market-areas-vars-and-funcs-script',get_stylesheet_directory_uri() . "/inc/market-areas-posttype/static/js/market-areas-vars-and-functions.min.js",[],'13.07.2016',true); //Market
            wp_enqueue_script('market-areas-script',get_stylesheet_directory_uri() . "/inc/market-areas-posttype/static/js/market-areas.min.js",[],'13.07.2016',true); //Market area script
            wp_enqueue_style('market-areas-css', get_stylesheet_directory_uri(). "/inc/market-areas-posttype/static/css/market-areas.min.css", [],'13.07.2016'); //Market area style
        }
    }

    function get_custom_fields()
    {
        return array(
              // Market area description
              // array(
              //     'id'            => 'market_area_description',
              //     'title'         => '<h4>Description</h4>',
              //     'screen'        => 'market-areas',
              //     'context'       => 'normal',
              //     'priority'      => 'default',
              //     'template'      => 'market_area_description.php',
              //     'need_sanitize' => false,
              //     'description'   => 'A description of the market area.',
              // ),
              // Images
              array(
                  'id'            => 'market_area_photos',
                  'title'         => '<h4>Photos</h4>',
                  'screen'        => 'market-areas',
                  'context'       => 'normal',
                  'priority'      => 'default',
                  'template'      => 'market_area_photos.php',
                  'need_sanitize' => false,
                  'description'   => 'A set of images. You can sort them by dragging them.',
              ),
              // Use landing : select template
              array(
                  'id'            => 'market_area_use_landing_page',
                  'title'         => '<h4>Landing</h4>',
                  'screen'        => 'market-areas',
                  'context'       => 'normal',
                  'priority'      => 'default',
                  'template'      => 'market_area_landing.php',
                  'need_sanitize' => false,
                  'description'   => '',
              ),
              // Market areas tree
              array(
                  'id'            => 'market_area_props_n_areas',
                  'title'         => '<h4>Define Location, Sub-areas (optional) and Properties.</h4>',
                  'screen'        => 'market-areas',
                  'context'       => 'advanced',
                  'priority'      => 'default',
                  'template'      => 'market_area_props_n_areas.php',
                  'need_sanitize' => false,
                  'description'   => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean molestie luctus lorem, sed sollicitudin eros suscipit vitae. Suspendisse potenti. Nunc fermentum purus in mi porttitor, non congue erat rhoncus. Duis ut turpis a ex bibendum sollicitudin. Vivamus condimentum rutrum mi, ultrices commodo ex egestas in. Vestibulum sapien justo, suscipit in commodo et, sodales vitae libero. Nulla id luctus nisi, ac porttitor neque. Integer laoreet, augue nec sollicitudin vestibulum, arcu ligula suscipit elit, vel ornare arcu ipsum et ligula. Maecenas porta sapien quis facilisis porta. Maecenas placerat et dolor nec facilisis. Quisque molestie cursus urna, ut tincidunt ante malesuada vitae. Fusce odio ante, lacinia at tortor ut, egestas vehicula velit.',
                  'rules'         =>
                  [
                          '/`/',// No backticks
                          // '/\'/',// No simplequotes
                          '/[<>]+/', // No tags
                  ]
              ),
              // Generate landing :
              //      This needs to be the after the tree meta box in this array in order to take the latest saved value of the market areas tree
              array(
                  'id'            => 'market_area_generate_landing',
                  'title'         => '<h4>Generate Landings</h4>',
                  'screen'        => 'market-areas',
                  'context'       => 'normal',
                  'priority'      => 'default',
                  'template'      => 'market_area_landing_generate.php',
                  'need_sanitize' => false,
                  'description'   => 'Check this field if you want to create landing pages out of the locations in the Market areas tree',
              ),
      );
    }
}
