public function ajaxProcessCheckEAN13()
    {
        $ean = Tools::getValue('ean');
        $id_attribute = Tools::getValue('id_attribute');
        if(!empty($ean)){
                $sql = 'SELECT DISTINCT id_product FROM '._DB_PREFIX_.'product_attribute WHERE ean13 = '.(int)$ean.' AND id_product != '.(int)Tools::getValue('id_product').' AND id_product != 0';
                $duplicate = 'SELECT DISTINCT id_product_attribute FROM '._DB_PREFIX_.'product_attribute WHERE ean13 = '.(int)$ean.' AND id_product = '.(int)Tools::getValue('id_product').' AND id_product_attribute != '.(int)$id_attribute.' AND id_product != 0';
                $result = Db::getInstance()->executeS($sql);
                $resultD = Db::getInstance()->executeS($duplicate);
                $duplicateCount = count($resultD); 
                foreach ($result as $key => $product) { 
                    $productsIds[] = $product['id_product'];
                }
                $json = array(
                        'count' => count($result),
                        'products' => $productsIds,
                        'duplicates' => $duplicateCount,
                    );
                die(Tools::jsonEncode($json));
        } else {
            return false;
        } 
    }