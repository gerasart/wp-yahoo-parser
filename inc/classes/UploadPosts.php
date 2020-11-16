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
                if ( get_page_by_title( $data['title'] ) == null ) {
                    $post_id = wp_insert_post( wp_slash( $new_post ) );
                    if ( !empty( $data['thumb'] ) ) {
                        self::ImgUpload( esc_url( $data['thumb'] ), $post_id );
                    }
                }
            }
        }
        
        public static function ImgUpload( $url, $parent_post_id = null ) {
            
            $response = wp_remote_get( $url );
            
            $t        = mktime();
            $filename = 'name' . date( "Y-m-d" ) . $t;
            
            
            $image = $response['body'];
            
            $dir  = wp_upload_dir();
            $file = $dir['path'] . $filename . ".jpeg";
            
            $fp = fopen( $file, "w" );
            fwrite( $fp, $image );
            fclose( $fp );
            
            $filename = $file;
            
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