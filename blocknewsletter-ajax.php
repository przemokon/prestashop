<?php

require_once(dirname(__FILE__).'../../../config/config.inc.php');
require_once(dirname(__FILE__).'../../../init.php');
require_once('blocknewsletter.php');

$newsletter = new Blocknewsletter;

$newsletter->newsletterRegistration();


switch (Tools::getValue('action')) {
  case false:
    die( Tools::jsonEncode( 
	    	array(
	    		'error' => $newsletter->error,
	    		'valid' => $newsletter->valid
	    	)
	    )
	);
    break;
  default:
    exit;
}
 