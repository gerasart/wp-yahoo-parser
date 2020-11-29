<?php
    /**
     * Created by PhpStorm.
     * User: gerasart
     * Date: 16.11.2020
     * Time: 19:44
     */
    
    namespace YahooParser;
    
    class UploadPosts {
        public static function single_post_insert( $data ) {
            if (isset( $data['title']) && isset($data['desc']) && isset($data['thumb']) && isset($data['cat'])) {
                $new_post = array(
                    'post_title'    => esc_html( $data['title'] ),
                    'post_content'  => esc_html( $data['desc'] ),
                    'post_status'   => 'publish',
                    'post_category' => array( $data['cat'] ),
                    'post_type'     => 'post'
                );
                if ( get_page_by_title(  esc_html($data['title']) ) == null ) {
                    $post_id = wp_insert_post( wp_slash( $new_post ) );
                    if ( !empty( $data['thumb'] ) ) {
                        ImageUploader::upload( $data['thumb'] , $post_id);
                    }
                }
            }
        }
        
    }
