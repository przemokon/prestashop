<?php

class Product extends ProductCore
{

	public static function cleanProductEAN($id_product_new)
	{
		return Db::getInstance()->execute('UPDATE '._DB_PREFIX_.'product_attribute SET ean13 = "" WHERE `id_product` = '.$id_product_new);
	}

	public function duplicateWebrotate($id_product_old, $id_product_new) {
        
        $config_url = Db::getInstance()->getValue('SELECT config_file_url FROM '._DB_PREFIX_.'webrotate360 WHERE id_product=' . (int)$id_product_old);
        $root_path = Db::getInstance()->getValue('SELECT root_path FROM '._DB_PREFIX_.'webrotate360 WHERE id_product=' . (int)$id_product_old);

        if($config_url && $root_path)
        	return Db::getInstance()->insert("webrotate360", array("id_product" => (int)$id_product_new, "config_file_url" => pSQL($config_url), "root_path" => pSQL($root_path)));
    }