public function ajaxProcessCheckEAN13()
    {
        $ean = Tools::getValue('ean');

        $sql = 'SELECT id_product FROM '._DB_PREFIX_.'product_attribute WHERE ean13 = '.(int)$ean.' AND id_product != '.(int)Tools::getValue('id_product').'';

        $result = Db::getInstance()->executeS($sql);

        foreach ($result as $key => $product) {

            $products .= $product['id_product'].(count($result) > 1 && $key+1 != count($result) ? ", " : "");

        }

        $json = array(
                'ean13' => $ean,
                'count' => count($result),
                'products' => $products,
            );

        die(Tools::jsonEncode($json));
    }
