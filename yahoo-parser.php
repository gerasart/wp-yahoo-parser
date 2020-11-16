<?php
    /*
     * Plugin Name: YahooParser
     * Version: 1.0
     * Plugin URI: #
     * Description: #
     * Author: Gerasart
     * Author URI: #
     */
    
    if ( !defined( 'ABSPATH' ) ) exit;
    
    define( 'YH_BASENAME', plugin_basename( __FILE__ ) );
    define( 'YH_PATH', plugin_dir_path( __FILE__ ) );
    define( 'YH_NAME', 'yahoo' );
    require_once dirname( __FILE__ ) . '/vendor/autoload.php';
    require_once dirname( __FILE__ ) . '/libs/simple_html_dom.php';
    
    use HaydenPierce\ClassFinder\ClassFinder;
    
    class YahooParserInit {
        
        private static $basedir;
        private static $namespace = 'YahooParser';
        
        public function __construct() {
            self::$basedir = YH_PATH . '/inc/classes/';
            
            self::cc_autoload();
            
        }
        
        private static function cc_autoload() {
            foreach ( glob( self::$basedir . '*.*' ) as $file ) {
                include_once(self::$basedir . basename( $file ));
            }
            
            $namespaces = self::getDefinedNamespaces();
            foreach ( $namespaces as $namespace => $path ) {
                $clear = str_replace( '\\', '', $namespace );
                
                ClassFinder::setAppRoot( YH_PATH );
                $level   = error_reporting( E_ERROR );
                $classes = ClassFinder::getClassesInNamespace( $clear );
                error_reporting( $level );
                
                foreach ( $classes as $class ) {
                    new $class();
                }
            }
        }
        
        private static function getDefinedNamespaces() {
            $composerJsonPath = dirname( __FILE__ ) . '/composer.json';
            $composerConfig   = json_decode( file_get_contents( $composerJsonPath ) );
            
            $psr4 = "psr-4";
            return (array)$composerConfig->autoload->$psr4;
        }
    }
    
new YahooParserInit();