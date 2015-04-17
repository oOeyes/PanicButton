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
   * The edit permissions from LocalSettings.php.
   * @var Array
  */
  var $mPermissions;
  
  /**
   * The edit permissions overrides from this extension's configuration.
   * @var Array
   */
  var $mOverrides;
  
  /**
   * Initializes variables and loads or creates the conf file.
   * @global Array $wgGroupPermissions
  */
  function __construct( ) {
    global $wgGroupPermissions;
    
    $this->mPermissions = Array();
    $this->mOverrides = Array();

    // this is intended to be expanded in future versions
    // but for now, we just check and record the edit permission for anons
    $this->mPermissions['*'] = $wgGroupPermissions['*']['edit'];
    if ( $this->exists( ) ) {
      $this->load( );
    } else {
      $this->create( );
    }
  }
  
  /**
   * Creates the conf file.
  */
  function create( ) {
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
  function exists( ) {
    return file_exists( dirname( __FILE__ ) . "/conf/PanicButton.conf" );
  }
  
  /**
   * Returns whether or not edit permissions for the given are overridden.
   * @param string $group
   * @return boolean
  */
  function getOverride( $group ) {
    return $this->mOverrides[$group];
  }
  
  /**
   * Returns the current edit permission for the given group as set by LocalSettings.php
   * @param string $group
   * @return boolean
  */
  function getPermission( $group ) {
    return $this->mPermissions[$group];
  }
  
  /**
   * Loads the conf file and puts any enabled overrides into effect.
   * @global boolean $wgGroupPermissions 
  */
  function load( ) {
    global $wgGroupPermissions;
    
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
  function save( ) {
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
  function setOverride( $group, $value ) {
    $this->mOverrides[$group] = $value;
  }
}

?>
