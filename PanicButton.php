<?php

/**
 * PanicButton entry point, performing initialization steps.
 *
 * @author Shawn Bruckner <eyes@aeongarden.com>
 * @copyright Copyright � 2011 Shawn Bruckner
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

// If this is run directly from the web die as this is not a valid entry point.
if( !defined( 'MEDIAWIKI' ) ) die( 'Invalid entry point.' );

$wgExtensionCredits['specialpage'][] = array(
  'name'           => 'PanicButton',
  'url'            => 'http://sw.aeongarden.com/', 
  'author'         => '[http://www.mediawiki.org/wiki/User:OoEyes Shawn Bruckner]', 
  'description'    => 'Provides a special page that allows specified users to shut off ' . 
                      'editing permissions for anonymous users.',
  'descriptionmsg' => 'panicbutton-desc',
  'version'        => 0.5,
);

$wgAvailableRights[] = 'panic';

require_once( dirname ( __FILE__ ) . "/PanicButtonConfFile.php" );
$wgpbConfFile = new PanicButtonConfFile();

$wgAutoloadClasses['SpecialPanicButton'] = dirname ( __FILE__ ) . '/SpecialPanicButton.php';
$wgExtensionMessagesFiles['PanicButton'] = dirname ( __FILE__ ) . '/PanicButton.i18n.php';
$wgSpecialPages['PanicButton'] = 'SpecialPanicButton'; 
$wgSpecialPageGroups['PanicButton'] = 'users';

?>
