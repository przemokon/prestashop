public function processDuplicate()
    {
        if (Validate::isLoadedObject($product = new Product((int)Tools::getValue('id_product')))) {
            $id_product_old = $product->id;
            if (empty($product->price) && Shop::getContext() == Shop::CONTEXT_GROUP) {
                $shops = ShopGroup::getShopsFromGroup(Shop::getContextShopGroupID());
                foreach ($shops as $shop) {
                    if ($product->isAssociatedToShop($shop['id_shop'])) {
                        $product_price = new Product($id_product_old, false, null, $shop['id_shop']);
                        $product->price = $product_price->price;
                    }
                }
            }
            unset($product->id);
            unset($product->id_product);
            unset($product->meta_title);
            unset($product->meta_description);
            $product->indexed = 0;
            $product->active = 0;
            if ($product->add()
            && Category::duplicateProductCategories($id_product_old, $product->id)
            && Product::duplicateSuppliers($id_product_old, $product->id)
            && ($combination_images = Product::duplicateAttributes($id_product_old, $product->id)) !== false
            && GroupReduction::duplicateReduction($id_product_old, $product->id)
            && Product::duplicateAccessories($id_product_old, $product->id)
            && Product::duplicateFeatures($id_product_old, $product->id)
            && Pack::duplicate($id_product_old, $product->id)
            && Product::duplicateCustomizationFields($id_product_old, $product->id)
            && Product::duplicateTags($id_product_old, $product->id)
            && Product::duplicateDownload($id_product_old, $product->id)) {
                if ($product->hasAttributes()) {
                    Product::updateDefaultAttribute($product->id);
                } else {
                    Product::duplicateSpecificPrices($id_product_old, $product->id);
                }

                if (!Tools::getValue('noimage') && !Image::duplicateProductImages($id_product_old, $product->id, $combination_images)) {
                    $this->errors[] = Tools::displayError('An error occurred while copying images.');
                } else {
                    Hook::exec('actionProductAdd', array('id_product' => (int)$product->id, 'product' => $product));
                    if (in_array($product->visibility, array('both', 'search')) && Configuration::get('PS_SEARCH_INDEXATION')) {
                        Search::indexation(false, $product->id);
                    }
                    $this->redirect_after = self::$currentIndex.(Tools::getIsset('id_category') ? '&id_category='.(int)Tools::getValue('id_category') : '').'&conf=19&token='.$this->token;
                }
            } else {
                $this->errors[] = Tools::displayError('An error occurred while creating an object.');
            }
        }
    }

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