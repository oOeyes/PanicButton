<?php

/**
 * The PanicButton configuration file processing class.
 *
 * @author Shawn Bruckner <eyes@aeongarden.com>
 * @copyright Copyright � 2011 Shawn Bruckner
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

class PanicButtonConfFile {
  
  /**
   * The configuration object.
   * @var type 
   */
  private $mConfig;
  
  /**
   * The edit permissions from LocalSettings.php.
   * @var Array
  */
  private $mPermissions;
  
  /**
   * The edit permissions overrides from this extension's configuration.
   * @var Array
   */
  private $mOverrides;
  
  /**
   * This class is a singleton. This holds the single instance.
   * @var PanicButtonConfFile 
   */
  private static $mInstance = null;
  
  /**
   * Instantiates the singleton, which triggers initialization steps.
   * @param Title $title Page title instance. Required by the hook, but not used here.
   * @param Article $article Article instance. Required by the hook, but is null is not used here.
   * @param OutputPage $output OutputPage instance. The config is acquired from this per current recommendations.
   * @param User $user User instance. Required by the hook, but not used here.
   * @param WebRequest $request WebRequest instance. Required by the hook, but not used here.
   * @param MediaWiki $mediaWiki The MediaWiki instance. Required by the hook, but not used here.
   */
  public static function onBeforeInitialize( &$title, &$article, &$output, &$user, $request, $mediaWiki ) {
    self::$mInstance = new self( $output->getConfig() );
  }
  
  /**
   * Gets the singleton instance of this class, or null if initilization has not yet been performed.
   * @return PanicButtonConfFile
   */
  public static function getInstance() {
    return self::$mInstance;
  }
  
    /**
   * Initializes variables and loads or creates the conf file.
   * #param Config $config A reference to the main config object.
   */
  function __construct( &$config ) {
    global $wgGroupPermissions;
    
    $this->mConfig = $config;
    $this->mOverrides = Array();

    if ( $this->exists( ) ) {
      $this->load( );
    } else {
      $this->create( );
    }
  }
  
  /**
   * Creates the conf file.
   */
  public function create( ) {
    if ( !is_dir( dirname( __FILE__ ) . "/conf" ) ) {
      mkdir( dirname( __FILE__ ) . "/conf" );
    }
    
    $this->mOverrides['*'] = false;
    $this->save( );
  }
  
  /**
   * Returns true if the conf file exists.
   * @return boolean
  */
  public function exists( ) {
    return file_exists( dirname( __FILE__ ) . "/conf/PanicButton.conf" );
  }
  
  /**
   * Returns whether or not edit permissions for the given are overridden.
   * @param string $group
   * @return boolean
  */
  public function getOverride( $group ) {
    return $this->mOverrides[$group];
  }
  
  /**
   * Returns the current edit permission for the given group as set by LocalSettings.php
   * @param string $group
   * @return boolean
  */
  public function getPermission( $group ) {
    $permissions = $this->mConfig->get('GroupPermissions');
    
    return $permissions[$group]['edit'] ? true : false;
  }
  
  /**
   * Loads the conf file and puts any enabled overrides into effect.
   * @global $wgGroupPermissions
  */
  public function load( ) {
    global $wgGroupPermissions; // still must use the global since we're altering configuration here
    $handle = fopen( dirname( __FILE__ ) . "/conf/PanicButton.conf", "r" );
    
    while ( ( $buffer = fgets( $handle ) ) !== false ) {
      $fields = explode( ' ', trim( $buffer ), 2 );
      
      // @todo should this verify the user group exists first?
      if ( strtolower( $fields[0] ) == "override" ) {
        $wgGroupPermissions[$fields[1]]["edit"] = false;
        $this->mOverrides[$fields[1]] = true;
      } else {
        $this->mOverrides[$fields[1]] = false;
      }
    }
    
    fclose( $handle );
  }
  
  /**
   * Saves the conf file according to the current override status.
  */
  public function save( ) {
    $handle = fopen( dirname( __FILE__ ) . "/conf/PanicButton.conf", "w" );
    
    // @todo should this verify the user group exists first?
    foreach ( $this->mOverrides as $group => $value ) {
      if ( $value ) {
        fwrite( $handle, "override " . $group . "\n" );
      } else {
        fwrite( $handle, "nooverride " . $group . "\n" );
      }
    }
    
    fclose( $handle );
  }
  
  /**
   * Sets whether edit permissions for the specific group should be overridden.
   * @param string $group
   * @param boolean $value 
  */
  public function setOverride( $group, $value ) {
    $this->mOverrides[$group] = $value;
  }
}

?>