<?php
    /**
     * Created by PhpStorm.
     * User: gerasart
     * Date: 16.11.2020
     * Time: 18:33
     */
    
    namespace YahooParser;
    
    class PhpParse {
        
        
        public static function parseSite( $url, $cat_id ) {
            
            $html  = file_get_html( $url );
            $items = [];
            foreach ( $html->find( 'li.js-stream-content' ) as $article ) {
                $items[] = [
                    'title' => isset( $article->find( 'h3', 0 )->plaintext ) ? $article->find( 'h3', 0 )->plaintext : '',
                    'desc'  => isset( $article->find( 'p', 0 )->plaintext ) ? $article->find( 'p', 0 )->plaintext : '',
                    'thumb' => isset( $article->find( 'img', 0 )->src ) ? $article->find( 'img', 0 )->src : '',
                    'cat'   => !empty( $cat_id ) ? $cat_id : 0
                ];
            }
            
            return $items;
        }
        
        
        public static function parseLinks( $links ) {
            $result = [];
            foreach ( $links as $link ) {
                $items = self::parseSite( $link['url'], $link['cat'] );
                foreach ( $items as $item ) {
                    $result[] = $item;
                }
            }
            return $result;
        }
        
    }