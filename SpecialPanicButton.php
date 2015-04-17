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
  }
  
  /**
   * Processes a panic button press or reset.
   * @param string $anonOverride
   */
  function applyChanges( $anonOverride ) {
    $out = $this->getOutput();
    $conf = PanicButtonConfFile::getInstance();
    
    if ( $anonOverride === '1' ) {
      $conf->setOverride( '*', true );
      $conf->save();
      
      $out->addWikiText( wfMessage( "panic-button-activated" )->text() );
    } elseif ( $anonOverride === '0' ) {
      $conf->setOverride( '*', false );
      $conf->save();
      
      $out->addWikiText( wfMessage( "panic-button-deactivated" )->text() );
    }
  }
 
  /**
   * Processes a request for Special:PanicButton.
   * @param string $par 
  */
  function execute( $par ) {
    $request = $this->getRequest();
    $out = $this->getOutput();
    $user = $this->getUser();
    
    $this->setHeaders();

    if ( $user->isAllowed( "panic" ) ) {
      $anonOverride = $request->getText( 'anonoverride' );
      if ( $anonOverride === "0" || $anonOverride === "1" ) {
        $this->applyChanges( $anonOverride );
      } else {
        $this->showForm( );
      }
    } else {
      $this->displayRestrictionError();
    }
  }
  
  /**
   * Shows the panic button form.
  */
  function showForm( ) {
    $out = $this->getOutput();
    $conf = PanicButtonConfFile::getInstance();
    $scriptPath = $this->getContext()->getConfig()->get("ScriptPath");
    
    if ( !$conf->getPermission( '*' ) ) {
      $out->addWikiText( wfMessage( "anons-cant-edit-anyway" )->text() . "\n\n" );
    }
    
    $value = $button = "";
    if ( $conf->getOverride( '*' ) === true ) {
      $out->addWikiText( wfMessage( "panic-button-active" )->text() . "\n\n" );
      if ( $conf->getPermission( '*' ) ) {
        $out->addWikiText( wfMessage( "reset-panic-button-desc" )->text() . "\n\n" );
      }
      $value = "0";
      $button = wfMessage( "reset-panic-button-text" )->text();
    } else {
      $out->addWikiText( wfMessage( "panic-button-inactive" )->text() . "\n\n" );
      if ( $conf->getPermission( '*' ) ) {
        $out->addWikiText( wfMessage( "press-panic-button-desc" )->text() . "\n\n" );
      }
      $value = "1";
      $button = wfMessage( "press-panic-button-text" )->text();
    }
    
    $form = <<<ENDFORM
<form action="{$scriptPath}/index.php" method="post">
  <input type="hidden" name="title" value="Special:PanicButton">
  <input type="hidden" name="anonoverride" value="{$value}">
  <input type="submit" value="{$button}">
</form>
ENDFORM;
  
    $out->addHTML( $form );
  }
}

?>