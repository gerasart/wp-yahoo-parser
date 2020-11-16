<?php
    /**
     * Created by PhpStorm.
     * User: gerasart
     * Date: 16.11.2020
     * Time: 18:58
     */
    
    namespace YahooParser;
    
    class Settings {
        /**
         * Settings constructor.
         */
        public function __construct() {
            add_action( 'admin_menu', [ $this, 'settingPage' ], 12 );
            add_filter( 'plugin_action_links_' . YH_BASENAME, [ $this, 'plugin_action_links' ] );
        }
        
        
        public function settingPage() {
            add_menu_page(
                'Yahoo parser',
                'Yahoo parsert',
                'edit_posts',
                YH_NAME,
                [ $this, 'view' ],
                plugins_url( 'testproject/inc/img/icon.png' )
            );
        }
        
        public function view() {
            include YH_PATH . 'view/index.php';
        }
        
        /**
         * @param $links
         * @return mixed
         */
        public function plugin_action_links( $links ) {
            $settings_link = '<a href="' . menu_page_url( YH_NAME, false ) . '">' . esc_html( __( 'Settings', 'custom' ) ) . '</a>';
            array_unshift( $links, $settings_link );
            return $links;
        }
    }