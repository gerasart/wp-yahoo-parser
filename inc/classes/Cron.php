<?php
    
    namespace YahooParser;
    
    class Cron {
        public function __construct() {
            self::declaration();
            add_filter( 'cron_schedules', array( __CLASS__, 'cronTimes' ) );
            add_action( 'wp', array( __CLASS__, 'registration' ) );
            //wp_clear_scheduled_hook( 'resetRegular' );
        }
        
        /**
         * @param $schedules {
         * weekly
         * bimonthly
         * one_hour
         * one_min
         * every_custom
         * }
         * @return mixed
         */
        public static function cronTimes( $schedules ) {
            $interval = get_field( 'user_interval', 'options' );
            
            $schedules['one_min']      = array(
                'interval' => 60,
                'display'  => 'Every 1 min'
            );
            $schedules['two_hour']     = array(
                'interval' => 7200,
                'display'  => 'Every 2 hour'
            );
            $schedules['one_day']      = array(
                'interval' => 43200,
                'display'  => 'Every 1 day'
            );
            $schedules['every_custom'] = array(
                'interval' => 43200 * (int)$interval,
                'display'  => 'Every custom'
            );
            
            return $schedules;
        }
        
        public static function registration() {
            if ( !wp_next_scheduled( 'EveryTwoHours' ) ) {
                wp_schedule_event( time(), 'two_hour', 'EveryMonth' );
            }
        }
        
        public static function declaration() {
            $pref          = 'action_';
            $class_methods = get_class_methods( get_called_class() );
            foreach ( $class_methods as $name ) {
                
                $need  = strpos( $name, $pref );
                $short = str_replace( $pref, '', $name );
                
                if ( $need === 0 ) {
                    add_action( $short, array( get_called_class(), $name ) );
                }
            }
        }
        
        public static function action_EveryTwoHours() {
            $links = [
                [
                    'url' => 'https://finance.yahoo.com/',
                    'cat' => '171' //finance
                ],
                [
                    'url' => 'https://www.yahoo.com/entertainment/',
                    'cat' => '172' //entertainment
                ]
            ];
            $data  = PhpParse::parseLinks( $links );
            foreach ( $data as $item ) {
                UploadPosts::single_post_insert( $item );
            }
        }
    }