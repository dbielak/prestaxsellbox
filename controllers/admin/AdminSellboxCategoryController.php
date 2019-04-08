<?php

require_once(dirname(__FILE__) . '/../../classes/SellboxCategories.php');

class AdminSellboxCategoryController extends ModuleAdminController
{
    public $object;

    public $shop_cats = array();

    public function __construct()
    {
        $this->table = 'sellbox_category';
        $this->identifier = 'id';
        $this->className = 'SellboxCategories';
        $this->multiple_fieldsets = true;
        $this->bootstrap = true;
        $this->shop_path[] = 0;
        $this->sellboxFields[] = 0;

        parent::__construct();

        $this->tabAccess = Profile::getProfileAccess($this->context->employee->id_profile, Tab::getIdFromClassName('AdminSellboxMain'));

        $this->fields_list = array(
            'cat_name' => array(
                'title' => $this->l('Kategoria w sellbox')
            ),
            'id_categories' => array(
                'title' => $this->l('Ilość przypisanych kategorii sklepowych'),
                'align' => 'center',
                'search' => false,
                'filter' => false,
                'callback' => 'countAssignedCategories'
            )
        );

        $this->toolbar_title = $this->l('Sellbox - Kategorie');
        $this->tpl_folder = 'sellbox_categories/';
    }

    public function init()
    {
        parent::init();

        $this->loadObject(true);

        $this->sellboxapi = new SellboxCategories;

        if($sellbox_id = Tools::getValue('id_sellbox_category'))
        {
            $this->object->id_sellbox_category = $sellbox_id;
        }
        else if ($cat_id = Tools::getValue('id'))
        {
            $this->object->id_sellbox_category = $this->sellboxapi->getSellboxCatId($cat_id);
        }

        if ($this->object->id_sellbox_category && !$this->sellboxapi->checkIsLastSellboxCat($this->object->id_sellbox_category)) {
            $this->errors[] = $this->l('Wybrana kategoria Sellbox nie jest kategorią najniższego rzędu.');
        }

        if($cat_id = Tools::getValue('id') && !$this->errors)
        {
            $shop_cats = $this->sellboxapi->getAllChosenShopCatFromId($cat_id);

            if(!empty($shop_cats))
            {
                $this->shop_cats = $shop_cats = explode(',', $shop_cats[0]['id_categories']);
            }

            $this->shop_path = $this->sellboxapi->getAllSelectedCategory($this->object->id_sellbox_category);

            if(!empty($this->shop_path))
            {
                $this->sellboxFields = $this->sellboxapi->getAllSellboxFields($this->object->id_sellbox_category);
            }

            $this->object->id_categories = $this->shop_cats;

            if(!$this->object->id_sellbox_category)
            {
                $this->object->id_sellbox_category = false;
            }
        }
    }

    public function renderList()
    {
        $this->addRowAction('edit');
        $this->addRowAction('delete');

        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?'),
                'icon' => 'icon-trash'
            )
        );

        return parent::renderList();
    }

    public function renderForm($custom_attr = false, $fields = false)
    {
        if($custom_attr && $fields)
        {
            $this->fields_form[1] = array(
                'input' => array(),
            );

            foreach ($fields as $categoryField)
            {
                $this->fields_form[1]['form']['input'][] = array(
                    'id' => $categoryField['field_id'],
                    'name' => $categoryField['field_name'],
                    'type' => $categoryField['field_type'],
                    'label' => $categoryField['field_name'],
                    'options' => $categoryField['field_options'],
                    'value' => (isset($categoryField['value']) ? $categoryField['value'] : ''),
                    'class' => ($categoryField['field_type'] == 'TEXT' ? 'text' : 'select')
                );
            }
        }
        else
        {
            if (!$this->ajax)
            {
                $this->fields_form[0]['form'] = array(
                    'legend' => array(
                        'title' => $this->l('Kategoria z sellbox')
                    ),
                    'input' => array(
                        array(
                            'type' => 'category',
                            'name' => 'id_sellbox_category',
                            'categories' => $this->sellboxapi->getCategoryList(),
                            'path' => $this->shop_path
                        )
                    ),
                    'submit' => array(
                        'title' => $this->l('Zapisz'),
                    )
                );

                $this->fields_form[1]['form'] = array(
                    'legend' => array(
                        'title' => $this->l('Pola dodatkowe')
                    ),
                    'input' => array(),
                    'submit' => array(
                        'title' => $this->l('Zapisz'),
                    )
                );
            }

            if ($this->sellboxFields)
            {
                foreach ($this->sellboxFields as $categoryField)
                {
                    $this->fields_form[1]['form']['input'][] = array(
                        'id' => $categoryField['field_id'],
                        'name' => 'category_fields[' . $categoryField['field_name'] . ']',
                        'type' => $categoryField['field_type'],
                        'label' => $categoryField['field_name'],
                        'options' => $categoryField['field_options'],
                        'value' => (isset($categoryField['value']) ? $categoryField['value'] : ''),
                        'class' => ($categoryField['field_type'] == 'TEXT' ? 'text' : 'select')
                    );
                }
            }

            if (!$this->ajax)
            {
                $root_category = Category::getRootCategory();
                $root_category = array('id_category' => $root_category->id, 'name' => $root_category->name);

                if (version_compare(_PS_VERSION_, '1.6', '<'))
                {
                    $helper_cat = new Helper();
                    $cat_input = array(
                        'type' => 'categories_select',
                        'label' => $this->l('Kategoria'),
                        'name' => 'categoryBox',
                        'category_tree' => $helper_cat->renderCategoryTree(null, $this->object->id_categories, 'categoryBox', false, true, array(), false, true)
                    );
                }
                else {
                    $cat_input = array(
                        'type' => 'categories',
                        'label' => $this->l('Kategoria'),
                        'name' => 'categoryBox',
                        'tree' => array(
                            'id' => 'categoryBox',
                            'root_category' => $root_category['id_category'],
                            'use_search' => true,
                            'use_checkbox' => true,
                            'use_radio' => false,
                            'selected_categories' => $this->object->id_categories
                        )
                    );
                }

                $this->fields_form[2]['form'] = array(
                    'legend' => array(
                        'title' => $this->l('Kategoria w Twoim sklepie'),
                    ),
                    'input' => array(
                        $cat_input
                    ),
                    'submit' => array(
                        'title' => $this->l('Zapisz'),
                    )
                );
            }
        }

        return parent::renderForm();
    }

    public function setMedia()
    {
        parent::setMedia();

        $this->addCSS(_MODULE_DIR_.$this->module->name.'/views/css/sellbox_style.css');
        $this->addJS(_MODULE_DIR_.$this->module->name.'/views/js/sellbox.js');
    }

    public function postProcess()
    {
        return parent::postProcess();
    }

    public function processSave()
    {
        if($sellbox_id = Tools::getValue('id_sellbox_category'))
        {
            $this->object->id_sellbox_category = $sellbox_id;
        }

        if($cat_name = Tools::getValue('sellbox_cat_name'))
        {
            $this->object->cat_name = $cat_name;
        }

        if (!$this->object->id_sellbox_category) {
            $this->errors[] = $this->l('Nie wybrano kategorii Sellbox.');
        }
        else if (!$this->sellboxapi->checkIsLastSellboxCat($this->object->id_sellbox_category)) {
            $this->errors[] = $this->l('Wybrana kategoria Sellbox nie jest kategorią najniższego rzędu.');
        }

        if($attributes = Tools::getValue('attr'))
        {
            $custom_attr = false;

            foreach ($attributes as $key => $attr)
            {
                if($key == 'custom')
                {
                    $custom_attr = implode(',', $attr);
                }

                $this->sellboxapi->saveField($this->object->id_sellbox_category, ($custom_attr ? 0 : $key), ($custom_attr ? $custom_attr : $attr));
            }
        }

        if(!$this->errors)
            return parent::processSave();
    }

    protected function beforeAdd($object)
    {
        return $this->prepareData($object);
    }

    protected function afterUpdate($object)
    {
        return $this->prepareData($object)->save();
    }

    private function prepareData(ObjectModel $object)
    {
        if($id = Tools::getValue('id'))
        {
            $object->id = $id;
        }

        if($sellbox_cat = Tools::getValue('id_sellbox_category'))
        {
            $object->id_sellbox_category = $sellbox_cat;
        }

        if($sellbox_cat_name = Tools::getValue('sellbox_cat_name'))
        {
            $object->cat_name = $sellbox_cat_name;
        }


        if (!Tools::getValue('categoryBox')) {
            $this->errors[] = $this->l('Nie wybrano kategorii sklepowej.');
        }
        else
        {
            $object->id_categories = trim(implode(',', Tools::getValue('categoryBox')), ',');
        }

        return $object;
    }

    public function countAssignedCategories($echo, $tr)
    {
        return count(explode(',', $echo));
    }

    public function ajaxProcessGetCategories()
    {
        $categories = array();
        $fields = array();
        $last_node = false;

        $last_node = $this->sellboxapi->checkIsLastSellboxCat($this->object->id_sellbox_category);
        $categories = $this->sellboxapi->getCategories($this->object->id_sellbox_category);

        if ($last_node) {
            $fields = $this->sellboxapi->getAllSellboxFields($this->object->id_sellbox_category);
        }


        $html = $this->renderForm(1, $fields);

        die(Tools::jsonEncode(array(
            'last_node' => $last_node,
            'fields' => $html,
            'categories' => $categories
        )));
    }
}