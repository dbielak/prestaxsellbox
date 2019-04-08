<?php

if (!defined('_PS_VERSION_'))
    exit;

require_once (dirname(__FILE__) . '/../../sellbox.php');
require_once(dirname(__FILE__) . '/../../classes/SellboxAdd.php');

class AdminSellboxAddController extends ModuleAdminController
{
    protected $_products = array();

    public function __construct()
    {
        $this->table = 'sellbox_product_status';
        $this->identifier = 'id';
        $this->className = 'SellboxAdd';
        $this->multiple_fieldsets = true;
        $this->bootstrap = true;
        parent::__construct();

        $this->tabAccess = Profile::getProfileAccess($this->context->employee->id_profile, Tab::getIdFromClassName('AdminSellboxMain'));

        $this->toolbar_title = $this->l('Sellbox - Dodaj');
        $this->tpl_folder = 'sellbox_add/';

    }

    public function init()
    {

        parent::init();

        $this->loadObject(true);

        if (!isset($this->object))
            $this->object = new stdClass();

        $this->sellboxapi = new SellboxAdd;
        $this->allcategories = $this->sellboxapi->getCategoryList();

        if($id = Tools::getValue('add_products') && $items = Tools::getValue('item'))
        {
            foreach ($items as $key => $item)
            {
                if(!isset($item['enabled']))
                {
                    unset($items[$key]);
                }
                else
                {
                    unset($items[$key]['enabled']);
                }
            }

            $request = json_encode( $items, true);

            if($request)
            {
                $result = $this->sellboxapi->callAPI($request);
            }

            $result = json_decode(trim($result), TRUE);

            $front = new FrontController;

            if (is_array($result) || is_object($result))
            {
                $status_data = array();

                foreach($items as $item)
                {
                    foreach ($result as $key => $res)
                    {
                        if($key == $item['id_product'])
                        {
                            if(isset($res['error']))
                            {
                                $front->errors[] = $res['error'];
                            }
                            else
                            {
                                $front->success[] = $res['success'];
                            }

                            $res['info']['id_shop_product'] = $item['id_product'];

                            $status_data = array(
                                'info' => $res['info']
                            );

                            $this->sellboxapi->addProductStatus($status_data);
                        }
                    }
                }
            }

            $front->redirectWithNotifications(
                $this->context->link->getAdminLink('AdminSellboxMain', false).'&token='.Tools::getAdminTokenLite('AdminSellboxMain')
            );
        }

        if($id = Tools::getValue('id_product'))
        {
            $ids = array_map('intval', explode(',', $id));

            foreach ($ids as $id)
            {
                $categories = false;
                $fields = false;


                $sellbox_cat_id = $this->sellboxapi->getSellboxCatId($id, $this->context->cloneContext());

                if($sellbox_cat_id)
                {
                    $categories = $this->sellboxapi->getAllSelectedCategory($sellbox_cat_id);

                    $fields = $this->sellboxapi->getAllSellboxFields($sellbox_cat_id);
                }

                $this->_products[$id] = $this->sellboxapi->getProductFromId(
                    $id,
                    $this->context->cloneContext()
                );

                $this->_products[$id][0]['categories'] = $categories;
                $this->_products[$id][0]['fields'] = $fields;
            }
        }

        if($sellbox_id = Tools::getValue('id_sellbox_category'))
        {
            $this->object->id_sellbox_category = $sellbox_id;
        }

        if($iteration = Tools::getValue('iteration'))
        {
            $this->object->iteration = $iteration;
        }
    }

    public function renderForm($custom_attr = false, $fields = false, $product_iteration = false)
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
                    'iteration' => $product_iteration,
                    'name' => $categoryField['field_name'],
                    'type' => $categoryField['field_type'],
                    'label' => $categoryField['field_name'],
                    'options' => $categoryField['field_options'],
                    'value' => (isset($categoryField['value']) ? $categoryField['value'] : ''),
                    'class' => ($categoryField['field_type'] == 'TEXT' ? 'text' : 'select')
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

    public function initPageHeaderToolbar()
    {
        $this->page_header_toolbar_btn['sellbox_add'] = array(
            'href' => self::$currentIndex . '&token=' . $this->token . '&add=items',
            'desc' => $this->l('Dodaj wszystkie wybrane'),
            'icon' => 'process-icon-check icon-check',
            'class' => 'sellbox-add'
        );

        parent::initPageHeaderToolbar();
    }

    public function initToolbar()
    {
        $this->toolbar_btn['sellbox_add'] = array(
            'href' => self::$currentIndex . '&token=' . $this->token . '&add=items',
            'desc' => $this->l('Dodaj wszystkie wybrane'),
            'class' => 'sellbox-add fa fa-check'
        );

        parent::initToolbar();

        unset($this->toolbar_btn['new']);
    }

    public function initContent()
    {
        parent::initContent();

        $this->context->smarty->assign(array(
            'products' => $this->_products,
            'allcategories' => $this->allcategories
        ));
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

        $html = $this->renderForm(1, $fields, $this->object->iteration);

        die(Tools::jsonEncode(array(
            'last_node' => $last_node,
            'fields' => $html,
            'categories' => $categories
        )));
    }

}