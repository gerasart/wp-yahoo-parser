<?php
    /**
     * Created by PhpStorm.
     * User: gerasart
     * Date: 18.11.2020
     * Time: 13:00
     */
    
    namespace YahooParser;
    
    
    class ImageUploader {
        
        public static function upload( $url, $post_id ) {
            $filename = $url;
            $parent_post_id = $post_id;
            $filetype = wp_check_filetype( basename( $filename ), null );
            $wp_upload_dir = wp_upload_dir();
            $attachment = array(
                'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ),
                'post_mime_type' => $filetype['type'],
                'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
                'post_content'   => '',
                'post_status'    => 'inherit'
            );

            $attach_id = wp_insert_attachment( $attachment, $filename, $parent_post_id );
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            $attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
            wp_update_attachment_metadata( $attach_id, $attach_data );
            set_post_thumbnail( $parent_post_id, $attach_id );
        }
        
    }