<?php

require_once(dirname(__FILE__).'../../../config/config.inc.php');
require_once(dirname(__FILE__).'../../../init.php');
require_once('blocknewsletter.php');



$newsletter = new blocknewsletter();

$email = Tools::getValue('unsubsribed_email');


$register_status = $newsletter->isNewsletterRegistered($email);


if(Validate::isEmail($email) && $register_status) {


	die( Tools::jsonEncode( 
		    	array(
		    		'msg' => $newsletter->sendUnsubscribeEmail($email),
		    	)
		    )
	);


} elseif(Validate::isEmail($email)) {

	die( Tools::jsonEncode( 
		    	array(
		    		'msg' => $newsletter->isNotRegistred,
		    	)
		    )
	);

}




