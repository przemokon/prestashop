public static function cleanProductEAN($id_product_new)
	{
		return Db::getInstance()->execute('UPDATE '._DB_PREFIX_.'product_attribute SET ean13 = "" WHERE `id_product` = '.$id_product_new);
	}