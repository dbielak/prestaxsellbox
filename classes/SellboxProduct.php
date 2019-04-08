<?php

require_once (dirname(__FILE__) . '/../sellbox.php');

class SellboxProduct extends ObjectModel
{

    public function __construct()
    {
        if (function_exists('curl_init') == false) {
            return false;
        }
    }

    private function isJson($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    public function callAPI($data, $action){

        if(!self::isJson($data))
            return false;

        if($action != 'refresh' && $action != 'remove')
            return false;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'http://sellbox.ebielak.com/api/item/'.$action.'.php',
            CURLOPT_USERAGENT => 'Sellbox',
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => array(
                $action.'_item_id' => $data,
                'client_login' => Configuration::get("LOGIN_SELLBOX"),
                'client_secret' => Configuration::get("APIKEY_SELLBOX")
            ),
            CURLOPT_REFERER => Tools::getHttpHost(true).__PS_BASE_URI__,
            CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            )
        ));

        $result = curl_exec($curl);

        curl_close($curl);

        return $result;
    }

    public function refreshProduct($refresh_id)
    {
        $product_status = self::checkProductStatus($refresh_id);
        $data = $product_status['id_sellbox_ad'];

        $refresh_status = self::callAPI($data, 'refresh');
        return (int)$refresh_status;
    }

    public function refreshShopProduct($refresh_id)
    {
        return Db::getInstance()->execute('
                UPDATE `'._DB_PREFIX_.'sellbox_product_status` 
                SET `date_expired` = "'. date('Y-m-d H:i:s', strtotime("+30 days")) . '" 
                WHERE `id_shop_product` = '.(int) $refresh_id.'
            ');
    }

    public function removeProduct($remove_id)
    {
        $product_status = self::checkProductStatus($remove_id);
        $data = $product_status['id_sellbox_ad'];

        $remove_status = self::callAPI($data, 'remove');
        return $remove_status;
    }

    public function removeShopProduct($remove_id)
    {
        return Db::getInstance()->execute('
                DELETE from `'._DB_PREFIX_.'sellbox_product_status`
                WHERE `id_shop_product` = '.(int) $remove_id.'
            ');
    }

    public function getProductFromId($products_id, $context)
    {
        $products = array();

        if ($products_id)
        {
            $product = new Product($products_id);
            $image_ids = self::getProductImages($products_id);

            $images = [];
            foreach ($image_ids as $image_id) {
                $image = new Image($image_id['id_image']);
                $images[] = _PS_BASE_URL_._THEME_PROD_DIR_.$image->getExistingImgPath().".jpg";
            }

            $manufacturer = new Manufacturer($product->id_manufacturer, $context->language->id);

            $price = Product::getPriceStatic($products_id, true, null,
                (int)Configuration::get('PS_PRICE_DISPLAY_PRECISION'), null, false, true, 1, true, null, null, null, $nothing, true, true, $context);

            $product->manufacturer_name = $manufacturer->name;
            $product->price = $price;
            $product->images = $images;

            $products[] = $product;
        }

        return json_decode(json_encode($products), true);
    }

    public function checkProductStatus($product_id)
    {
        $query = new DbQuery();
        $query->select('*');
        $query->from('sellbox_product_status');
        $query->where('id_shop_product = '.$product_id);
        $result = Db::getInstance()->executeS($query);

        if($result)
            return $result[0];
    }

    public function checkIsLastSellboxCat($sellbox_cat_id)
    {
        $query = new DbQuery();
        $query->select('cat_id');
        $query->from('sellbox_cat_list', 'sc');
        $query->where('cat_parent_id ='. $sellbox_cat_id);

        $sellbox_cats = Db::getInstance()->executeS($query);

        return count($sellbox_cats) > 0 ? false : true;
    }

    public function getCategories($sellbox_cat_id)
    {
        $query = new DbQuery();
        $query->select('*');
        $query->from('sellbox_cat_list', 'sct');
        $query->where('cat_parent_id ='. $sellbox_cat_id);

        return Db::getInstance()->executeS($query);
    }

    public function getCategoryList()
    {
        $query = new DbQuery();
        $query->select('*');
        $query->from('sellbox_cat_list', 'sct');

        return Db::getInstance()->executeS($query);
    }

    public function getSellboxCatId($cat_id, $context)
    {
        $result = false;
        $product = new Product($cat_id);
        $category = new Category((int)$product->id_category_default, $context->language->id);

        $query = new DbQuery();
        $query->select('id_categories, id_sellbox_category');
        $query->from('sellbox_category', 'sc');
        $categories = Db::getInstance()->executeS($query);

        foreach ($categories as $cat)
        {
            $shop_cats = explode(',', $cat['id_categories']);

            foreach ($shop_cats as $scat)
            {
                if($scat == $category->id)
                {
                    $result = $cat['id_sellbox_category'];
                }
            }
        }

        return $result;
    }

    public function getAllSelectedCategory($selected_cat_id)
    {
        $fetch = array();

        $query = new DbQuery();
        $query->select('scl.cat_id, scl.cat_name, scl.cat_parent_id');
        $query->from('sellbox_cat_list', 'scl');
        $query->where('scl.cat_id = '.$selected_cat_id);
        $first = Db::getInstance()->executeS($query);

        $fetch[] = $first[0];
        $first = $first[0];

        if($first['cat_parent_id'])
        {
            $query = new DbQuery();
            $query->select('scl.cat_id, scl.cat_name, scl.cat_parent_id');
            $query->from('sellbox_cat_list', 'scl');
            $query->where('scl.cat_id = '.$first['cat_parent_id']);
            $second = Db::getInstance()->executeS($query);
            $second = $second[0];

            $fetch[] = $second;
        }


        if($second['cat_parent_id'])
        {
            $query = new DbQuery();
            $query->select('scl.cat_id, scl.cat_name, scl.cat_parent_id');
            $query->from('sellbox_cat_list', 'scl');
            $query->where('scl.cat_id = '.$second['cat_parent_id']);
            $third = Db::getInstance()->executeS($query);
            $third = $third[0];

            $fetch[] = $third;
        }

        $result = (!empty($fetch) ? array_reverse($fetch) : '');

        return $result;
    }

    public function getAllSellboxFields($cat_id)
    {
        $query = new DbQuery();
        $query->select('field_list');
        $query->from('sellbox_cat_list', 'scf');
        $query->where('scf.cat_id ='. $cat_id);
        $feature_ids = Db::getInstance()->getValue($query);

        $query = new DbQuery();
        $query->select('*');
        $query->from('sellbox_cat_features', 'scf');
        $query->where('scf.field_id IN ('. $feature_ids . ')');
        $result = Db::getInstance()->executeS($query);

        $query = new DbQuery();
        $query->select('value, id_field');
        $query->from('sellbox_cat_to_field', 'sctf');
        $query->where('sctf.id_sellbox_category ='. $cat_id);
        $fields_value = Db::getInstance()->executeS($query);

        foreach ($result as $key => $res)
        {
            $result[$key]['value'] = '';

            foreach ($fields_value as $field)
            {
                if($field['value'] && (($field['id_field'] === $res['field_id']) || ($res['field_type'] === 'CUSTOMATTR' && $field['id_field'] == 0)))
                {
                    $result[$key]['value'] = $field['value'];
                }
            }
        }

        return $result;
    }

    public static function getProductImages($id_product){
        $id_image = Db::getInstance()->ExecuteS('SELECT `id_image` FROM `'._DB_PREFIX_.'image` WHERE `id_product` = '.(int)($id_product));
        return $id_image;
    }
}