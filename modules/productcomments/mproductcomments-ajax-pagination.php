<?php

	require(dirname(__FILE__).'/../../config/config.inc.php');
	include(dirname(__FILE__).'/../../init.php');

	global $cookie, $smarty;

	require_once(dirname(__FILE__).'/MProductComment.php'); 
	require_once(dirname(__FILE__).'/MProductCommentCriterion.php'); // enabling classes to work with comments 

	$n = abs((int)(Tools::getValue('n', Configuration::get('M_PRODUCT_COMMENTS_COMMENTS_PER_PAGE'))));
	$p = abs((int)(Tools::getValue('p', 1)));

	$id_product = abs((int)(Tools::getValue('id_product')));
	$id_guest = (!$id_customer = (int)$cookie->id_customer) ? (int)$cookie->id_guest : false;
	$customerComment = MProductComment::getByCustomer($id_product, (int)$cookie->id_customer, true, (int)$id_guest);

	$nbComments = count(MProductComment::getByProduct((int)($id_product)));
	$range = 2; /* how many pages around page selected */
	if ($p > (($nbComments / $n) + 1))
		Tools::redirect(preg_replace('/[&?]p=\d+/', '', $_SERVER['REQUEST_URI']));
	$pages_nb = ceil($nbComments / (int)($n));
	$start = (int)($p - $range);
	if ($start < 1)
		$start = 1;
	$stop = (int)($p + $range);
	if ($stop > $pages_nb)
		$stop = (int)($pages_nb);

	$smarty->assign(array(
		'allow_guests' => (int)Configuration::get('PRODUCT_COMMENTS_ALLOW_GUESTS'),
		'comments' => MProductComment::getByProduct((int)($id_product), $p, $n),
		'p' => (int)$p,
		'n' => (int)$n,
		'criterions' => MProductCommentCriterion::getByProduct((int)($id_product), (int)($cookie->id_lang)),
		'nbComments' => (int)(MProductComment::getCommentNumber((int)($id_product))),
		'action_url' => '',
		'too_early' => ($customerComment AND (strtotime($customerComment['date_add']) + Configuration::get('PRODUCT_COMMENTS_MINIMAL_TIME')) > time()),
		'range' => $range,
		'start' => $start,
		'stop' => $stop,
		'pages_nb' => $pages_nb,
	));

	$rendered_content = $smarty->fetch(_PS_ROOT_DIR_.'/modules/mproductcomments/views/templates/front/ajax_load_content.tpl');
	echo $rendered_content;

?>