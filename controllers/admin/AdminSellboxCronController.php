<?php

require_once (dirname(__FILE__) . '/../../sellbox.php');

class AdminSellboxCronController extends ObjectModel
{
    public function __construct()
    {
        if (function_exists('curl_init') == false) {
            return false;
        }
    }

    public function checkRefreshItems()
    {
        $all_items = self::getAllProducts();

        $request = json_encode( $all_items, true);

        if($request)
        {
            $result = self::callAPI($request);
        }

        $request = json_decode($result, true);


        if($request) {
            foreach ($request as $res)
            {
                $all_products = self::getAllProducts();
                $id = $res['product_id'];
                $expired = $res['dt_expiration'];

                if(in_array($id, $all_products[0]))
                {
                    Db::getInstance()->execute('
                UPDATE `'._DB_PREFIX_.'sellbox_product_status` 
                SET `date_expired` = "'. $expired . '" 
                WHERE `id_shop_product` = '.(int) $id.'
                ');
                }
                else
                {
                    Db::getInstance()->execute('
                DELETE from `'._DB_PREFIX_.'sellbox_product_status`
                WHERE `id_shop_product` = '.(int) $id.'
                ');
                }
            }
        } else {
            return 'Brak dodanych produktów';
        }

        return 'OK';
    }

    private function getAllProducts()
    {
        $query = new DbQuery();
        $query->select('*');
        $query->from('sellbox_product_status');

        return Db::getInstance()->executeS($query);
    }

    public function callAPI($data){

        if(!self::isJson($data))
            return false;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'http://sellbox.ebielak.com/api/cron/refresh.php',
            CURLOPT_USERAGENT => 'Sellbox',
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => array(
                'items' => $data,
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

    private function isJson($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}