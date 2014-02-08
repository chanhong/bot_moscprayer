<?php
/**
* @version 
* @package mosprayersbot
* @copyright (C) 2009 ongetc.com
* @info ongetc@ongetc.com http://ongetc.com
* @license GNU/GPL http://ongetc.com/gpl.html.
*/
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

include_once($GLOBALS['mosConfig_absolute_path'] .'/administrator/components/com_mosprayer/mosprayer.main.class.php');
global $mosprayer;
if (!isset($mosprayer)) $mosprayer = new MosPrayerMain();
global $published;

$ttype = $mosprayer->mambotParm("where");

if (!$ttype) $ttype = "onPrepareContent"; // set default
if (strtolower($ttype)=='onpreparecontent') {
	($mosprayer->ut->isJ15())
  ? $whichfunc = "callback_mosprayersbot_J15_in_content"
	: $whichfunc = "callback_legacy_mosprayersbot_in_content";
} else {
	$whichfunc="callback_mosprayersbot_out_content";
}

$_MAMBOTS->registerFunction( $ttype, $whichfunc ) ;

// wrapper to work around J1.5 legacy issue
function callback_mosprayersbot_J15_in_content( &$row, &$params, $page=0 ) {  // call back function
  global $mosprayer;
	$row->text = $row->text . $mosprayer->mcMain($row->id, "bot");
	return true;
}

function callback_legacy_mosprayersbot_in_content( $published, &$row, &$params, $page=0 ) {  // call back function
  global $mosprayer;
  $id="";
	if (!$published) return false;
	if (isset($row->id)) $id=$row->id;	// work around Joomla 1.0.15 issue
  
	$row->text = $row->text . $mosprayer->mcMain($id, "bot");
	return true;
}

function callback_mosprayersbot_out_content( &$row, &$params, $page=0 ) { 
  global $mosprayer;
  return $mosprayer->mcMain($row->id, "bot"); 
}

?>