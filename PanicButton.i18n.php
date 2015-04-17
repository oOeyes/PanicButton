<?php

/**
 * Internationalization file.  Only contains English for now.
 *
 * @author Shawn Bruckner <eyes@aeongarden.com>
 * @copyright Copyright � 2011 Shawn Bruckner
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */
 
$messages = array();
$messages['en'] = array(
  'panicbutton'              => 'Panic button',
  'panicbutton-desc'         => 'Provides a special page that allows specified users to shut off ' . 
                                'editing permissions for anonymous users.',
  'right-panic'              => "Press the [[Special:PanicButton|panic button]] to disable anonymous edits.",
  'anons-cant-edit-anyway'   => "'''Warning.''' Anonymous editing is currently deactivated on this wiki. The " .
                                "panic button setting will have no effect unless anonymous editing is enabled " .
                                "in <tt>LocalSettings.php</tt>.",
  'panic-button-active'      => "The panic button is currently '''<big>On</big>''', disabling anonymous editing.",
  'reset-panic-button-desc'  => "Resetting the panic button will stop preventing anonymous editing.",
  'reset-panic-button-text'  => 'Reset the panic button',
  'panic-button-inactive'    => "The panic button is currently <big>Off</big>.",
  'press-panic-button-desc'  => "Pressing the panic button will prevent anonymous editing. This is intended as a " .
                                "temporary measure against heavy vandalism, spamming, or abuse by anonymous " .
                                "users. To disable anonymous editing permanently, settings in " .
                                "<tt>LocalSettings.php</tt> should be changed instead.",
  'press-panic-button-text'  => 'Press the panic button',
  'panic-button-activated'   => "The panic button has been pressed and is now '''<big>On</big>'''. Only registered " .
                                "users can now edit.",
  'panic-button-deactivated' => "The panic button has been reset and is now <big>off</big>.",
);
?>
