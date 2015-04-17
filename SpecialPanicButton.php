<?php

/**
 * The PanicButton special page.
 *
 * @author Shawn Bruckner <eyes@aeongarden.com>
 * @copyright Copyright � 2011 Shawn Bruckner
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

class SpecialPanicButton extends SpecialPage {
  
  /**
   * Initializes the special page.
  */
  function __construct() {
    parent::__construct( 'PanicButton', 'panic' );
    wfLoadExtensionMessages( 'PanicButton' );
  }
  
  /**
   * Processes a panic button press or reset.
   * @param string $anonOverride
   * @param PanicButtonConfFile $conf
   */
  function applyChanges( $anonOverride, $conf ) {
    global $wgOut;
    
    if ( $anonOverride === '1' ) {
      $conf->setOverride( '*', true );
      $conf->save();
      
      $wgOut->addWikiText( wfMsg( "panic-button-activated" ) );
    } elseif ( $anonOverride === '0' ) {
      $conf->setOverride( '*', false );
      $conf->save();
      
      $wgOut->addWikiText( wfMsg( "panic-button-deactivated" ) );
    }
  }
 
  /**
   * Processes a request for Special:PanicButton.
   * @global WebRequest $wgRequest
   * @global OutputPage $wgOut
   * @global User $wgUser
   * @global PanicButtonConfFile $wgpbConfFile
   * @param string $par 
  */
  function execute( $par ) {
    global $wgRequest, $wgOut, $wgUser, $wgpbConfFile;
    $this->setHeaders();

    if ( $wgUser->isAllowed( "panic" ) ) {
      $anonOverride = $wgRequest->getText( 'anonoverride' );
      if ( $anonOverride === "0" || $anonOverride === "1" ) {
        $this->applyChanges( $anonOverride, $wgpbConfFile );
      } else {
        $this->showForm( $wgpbConfFile );
      }
    } else {
      $this->displayRestrictionError();
    }
  }
  
  /**
   * Shows the panic button form.
   * @global OutputPage $wgOut
   * @global string $wgScriptPath
   * @param PanicButtonConfFile $conf 
  */
  function showForm( $conf ) {
    global $wgOut, $wgScriptPath;
    
    if ( !$conf->getPermission( '*' ) ) {
      $wgOut->addWikiText( wfMsg( "anons-cant-edit-anyway" ) . "\n\n" );
    }
    
    $value = $button = "";
    if ( $conf->getOverride( '*' ) === true ) {
      $wgOut->addWikiText( wfMsg( "panic-button-active" ) . "\n\n" );
      if ( $conf->getPermission( '*' ) ) {
        $wgOut->addWikiText( wfMsg( "reset-panic-button-desc" ) . "\n\n" );
      }
      $value = "0";
      $button = wfMsg( "reset-panic-button-text" );
    } else {
      $wgOut->addWikiText( wfMsg( "panic-button-inactive" ) . "\n\n" );
      if ( $conf->getPermission( '*' ) ) {
        $wgOut->addWikiText( wfMsg( "press-panic-button-desc" ) . "\n\n" );
      }
      $value = "1";
      $button = wfMsg( "press-panic-button-text" );
    }
    
    $form = <<<ENDFORM
<form action="{$wgScriptPath}/index.php" method="post">
  <input type="hidden" name="title" value="Special:PanicButton">
  <input type="hidden" name="anonoverride" value="{$value}">
  <input type="submit" value="{$button}">
</form>
ENDFORM;
  
    $wgOut->addHTML( $form );
  }
}

?>
