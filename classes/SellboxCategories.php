<?php

class SellboxCategories extends ObjectModel
{
    public $id_sellbox_category;
    public $id_categories;
    public $cat_name;
    public $cat_features;

    public static $definition = array(
        'table' => 'sellbox_category',
        'primary' => 'id',
        'fields' => array(
            'id_sellbox_category' =>    array('type' => ObjectModel :: TYPE_INT),
            'id_categories' =>            array('type' => ObjectModel :: TYPE_STRING),
            'cat_name' =>        array('type' => ObjectModel :: TYPE_STRING),
        ),
    );

    public function __construct($id_sellbox_category = null)
    {
        parent::__construct($id_sellbox_category);
    }

    public function getAllChosenShopCatFromId($cat_id)
    {
        $query = new DbQuery();
        $query->select('id_categories');
        $query->from('sellbox_category', 'sc');
        $query->where('id ='. $cat_id);

        return $shop_cats = Db::getInstance()->executeS($query);
    }

    public function saveField($cat_id, $field_id, $field_value)
    {
        $query = new DbQuery();
        $query->select('id');
        $query->from('sellbox_cat_to_field', 'sctf');
        $query->where('sctf.id_sellbox_category ='. $cat_id);
        $query->where('sctf.id_field ='. $field_id);
        $feat_id = Db::getInstance()->getValue($query);

        if($feat_id)
        {
            Db::getInstance()->execute('
                UPDATE `'._DB_PREFIX_.'sellbox_cat_to_field` 
                SET `value` = "'. $field_value . '" 
                WHERE `id` = '.(int) $feat_id.'
            ');
        }
        else
        {
            Db::getInstance()->execute('
            INSERT INTO `' . _DB_PREFIX_ . 'sellbox_cat_to_field` (`id_sellbox_category`, `id_field`, `value`)
            VALUES (' . (int) $cat_id . ',' . (int) $field_id . ',"' . $field_value . '")');
        }
    }

    public function checkIfExist($cat_id)
    {
        $query = new DbQuery();
        $query->select('id');
        $query->from('sellbox_category', 'sc');
        $query->where('sc.id_sellbox_category = '.$cat_id);
        $exist = Db::getInstance()->getValue($query);

        return $exist;
    }

    public function getAllSelectedCategory($cat_id)
    {
        $fetch = array();

        $query = new DbQuery();
        $query->select('scl.cat_id, scl.cat_name, scl.cat_parent_id');
        $query->from('sellbox_cat_list', 'scl');
        $query->where('scl.cat_id = '.$cat_id);
        $first = Db::getInstance()->executeS($query);
        $first = $first[0];

        $fetch[] = $first;

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

    public function getSellboxCatId($id)
    {
        $query = new DbQuery();
        $query->select('id_sellbox_category');
        $query->from('sellbox_category', 'sc');
        $query->where('id ='. $id);

        $sellbox_cat_id = Db::getInstance()->executeS($query);

        return $sellbox_cat_id[0]['id_sellbox_category'];
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
}