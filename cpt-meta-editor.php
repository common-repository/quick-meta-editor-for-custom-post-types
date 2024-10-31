<?php

/**
* Plugin Name: Quick Meta Editor For Custom Post Types
* Description: This is the description
* Version: 1.0
* Author: Irato99
* Author URI: https://www.irato99.com
* Text Domain: cpt-meta-editor
* License: GPLv2 or later
* License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/

add_action('admin_menu', 'cpt_meta_editor_add_admin_menu');
function cpt_meta_editor_add_admin_menu() {
    add_menu_page( 
        'Quick Meta Editor', 
        'Quick Meta Editor', 
        'manage_options', 
        plugin_dir_path(__FILE__).'cpt-meta-editor-admin-view.php', 
        null, 
        null, 
        null 
    );
}

add_action('admin_enqueue_scripts', 'cpt_meta_editor_register_scripts');
function cpt_meta_editor_register_scripts() {
    wp_register_script( 'cpt-meta-editor-js', plugin_dir_url(__FILE__).'cpt-meta-editor.js' );
    wp_enqueue_script( 'cpt-meta-editor-js' );

    wp_register_style( 'bootstrap_css', plugin_dir_url(__FILE__).'Bootstrap/bootstrap.min.css' );
    wp_enqueue_style( 'bootstrap_css' );

    wp_register_script( 'bootstrap_js', plugin_dir_url(__FILE__).'Bootstrap/bootstrap.min.js' );
    wp_enqueue_script( 'bootstrap_js' );
}


add_action('wp_ajax_cpt_meta_editor_meta', 'ajax_cpt_meta_editor_meta');
function ajax_cpt_meta_editor_meta() {
    if(!wp_verify_nonce($_POST['nonce'], 'plugin-nonce' )) exit;

    $post_id = sanitize_text_field($_POST['id']);
    $meta_key = sanitize_text_field($_POST['meta']);
    $meta_value = sanitize_text_field($_POST['value']);
    if($meta_key == 'title') {
        wp_update_post( array(
            'ID' => $post_id,
            'post_title' => $meta_value
        ) );
    } else {
        update_post_meta( $post_id, $meta_key, $meta_value );
    }
    wp_die();
}

add_action('wp_ajax_cpt_meta_editor_main', 'ajax_cpt_meta_editor_main');
function ajax_cpt_meta_editor_main()
{
    $main_object = array(
        'post_types' => array(),
        'data' => array()
    );

    $post_types = get_post_types(array('_builtin' => false));

    foreach($post_types as $type) {
        if(substr($type, 0, 1) != '_') {
            array_push($main_object['post_types'], array(
                'name' => $type,
                'label' => get_post_type_object($type)->label
            ));
            $main_object['data'][$type] = array(
                'meta' => array(),
                'posts' => array()
            );
    
            $type_ar = new WP_Query(
                array(
                'post_type' => $type, 
                'orderby' => 'post_title', 
                'order' => 'ASC',
                'posts_per_page' => 1000
            ));
            $posts = $type_ar->posts;
    
            foreach($posts as $post) {
                $p = array();
                $p['title'] = $post->post_title;
                $p['ID'] = $post->ID;
                $meta = get_post_meta( $post->ID );
    
                foreach($meta as $key => $value) {
                    if($key != '_edit_last' && $key != '_edit_lock') {
                        if(!in_array($key, $main_object['data'][$type]['meta'])) array_push($main_object['data'][$type]['meta'], $key);
                        $p[$key] = $value[0];
                    }
                }
    
                array_push($main_object['data'][$type]['posts'], $p);
            }
        }
        
    }

    echo json_encode($main_object);
    wp_die();
}