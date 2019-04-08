<?php

if (!defined('_PS_VERSION_'))
    exit;

require_once (dirname(__FILE__) . '/../../sellbox.php');
require_once(dirname(__FILE__) . '/../../classes/SellboxProduct.php');

class AdminSellboxMainController extends ModuleAdminController
{
    public function __construct()
    {
        $this->table = 'product';
        $this->className = 'Product';
        $this->identifier = 'id_product';
        $this->lang = true;
        $this->explicitSelect = true;
        $this->list_no_link = true;
        $this->_defaultOrderBy = 'id_product';
        $this->_defaultOrderWay = 'ASC';
        $this->bootstrap = true;

        parent::__construct();

        $this->fields_list = array(
            'id_product' => array(
                'title' => $this->l('ID'),
                'align' => 'center',
                'class' => 'fixed-width-xs',
                'type' => 'int'
            ),
            'image' => array(
                'title' => $this->l('Zdjęcie'),
                'align' => 'center',
                'image' => 'p',
                'class' => 'fixed-width-sm',
                'orderby' => false,
                'filter' => false,
                'search' => false
            ),
            'name' => array(
                'title' => $this->l('Tytuł'),
                'type' => 'auction_info',
                'filter_key' => 'b!name',
                'class' => 'fixed-width-xxl'
            ),
            'reference' => array(
                'title' => $this->l('Index'),
                'align' => 'left',
                'class' => 'fixed-width-md'
            )
        );

        $this->fields_list = array_merge($this->fields_list, array(
            'name_category' => array(
                'title' => $this->l('Kategoria'),
                'filter_key' => 'cl!name',
                'class' => 'fixed-width-lg'
            )
        ));


        if (Configuration::get('PS_STOCK_MANAGEMENT')) {
            $this->fields_list = array_merge($this->fields_list, array(
                'sav_quantity' => array(
                    'title' => $this->l('Ilość'),
                    'type' => 'int',
                    'align' => 'text-right',
                    'class' => 'fixed-width-sm',
                    'filter_key' => 'sav!quantity',
                    'badge_danger' => true
                )
            ));
        }

        $this->fields_list = array_merge($this->fields_list, array(
            'price' => array(
                'title' => $this->l('Cena'),
                'type' => 'price',
                'align' => 'text-right',
                'class' => 'fixed-width-sm',
                'havingFilter' => true,
                'search' => false
            )
        ));

        $this->assignXFilters();

        $this->sellboxapi = new SellboxProduct;

        $this->tpl_folder = 'sellbox_main/';
        $this->bulk_actions['sellbox'] = array('icon' => 'icon-gavel', 'text' => $this->l('Dodaj zaznaczone'));
    }

    public function initToolbar()
    {
        parent::initToolbar();

        unset($this->toolbar_btn['new']);
    }

    public function renderList()
    {
        $this->addRowAction('sellbox');
        $this->addRowAction('sellboxedit');

        return parent::renderList();
    }

    public function getList($id_lang, $orderBy = null, $orderWay = null, $start = 0, $limit = null, $id_lang_shop = null)
    {
        $id_shop = Shop::isFeatureActive() && Shop::getContext() == Shop::CONTEXT_SHOP ? (int)$this->context->shop->id : 'a.id_shop_default';

        if (version_compare(_PS_VERSION_, '1.6.1.0', '<'))
        {
            $select_image = 'MAX(image_shop.`id_image`) AS id_image';
            $join_image = '
                LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = a.`id_product`)
                LEFT JOIN `'._DB_PREFIX_.'image_shop` image_shop ON (image_shop.`id_image` = i.`id_image` AND image_shop.`cover` = 1 AND image_shop.id_shop = ' . $id_shop . ')';
        }
        else {
            $select_image = 'image_shop.`id_image` AS `id_image`';
            $join_image = '
                LEFT JOIN `'._DB_PREFIX_.'image_shop` image_shop ON (image_shop.`id_product` = a.`id_product` AND image_shop.`cover` = 1 AND image_shop.id_shop = ' . $id_shop . ')
                LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_image` = image_shop.`id_image`)';
        }

        $this->_select .= 'shop.`name` AS `shopname`, a.`id_shop_default`, ' . $select_image . ',
            cl.`name` AS `name_category`, sa.`price`, 0 AS `price_final`, sav.`quantity` AS `sav_quantity`, sa.`active`';

        $this->_join .= ' JOIN `'._DB_PREFIX_.'product_shop` sa ON (a.`id_product` = sa.`id_product` AND sa.id_shop = ' . $id_shop . ')
            LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON (sa.`id_category_default` = cl.`id_category` AND b.`id_lang` = cl.`id_lang` AND cl.id_shop = ' . $id_shop . ')
            LEFT JOIN `'._DB_PREFIX_.'shop` shop ON (shop.id_shop = ' . $id_shop . ')' .
            $join_image .'
            LEFT JOIN `'._DB_PREFIX_.'product_download` pd ON (pd.`id_product` = a.`id_product` AND pd.`active` = 1)
            LEFT JOIN `'._DB_PREFIX_.'stock_available` sav ON (sav.`id_product` = a.`id_product` AND sav.`id_product_attribute` = 0' . StockAvailable::addSqlShopRestriction(null, null, 'sav') . ')
            LEFT JOIN `'._DB_PREFIX_.'tax_rule` tr ON (sa.`id_tax_rules_group` = tr.`id_tax_rules_group` AND tr.`id_country` = ' . (int)Configuration::get('PS_COUNTRY_DEFAULT') . ' AND tr.`id_state` = 0)
            LEFT JOIN `'._DB_PREFIX_.'tax` t ON (t.`id_tax` = tr.`id_tax`)';

        if ($this->context->cookie->xFilterCategory) {
            $this->_join .= ' INNER JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.`id_product` = a.`id_product` AND cp.`id_category` IN (' . implode(',', unserialize($this->context->cookie->xFilterCategory)) . ')) ';
            $this->_select .= ' , cp.`position`, ';
        }

        if ($this->context->cookie->xFilterManufacturer) {
            $this->_join .= ' INNER JOIN `'._DB_PREFIX_.'manufacturer` pm ON (a.`id_manufacturer` = pm.`id_manufacturer` AND pm.`active` = 1 AND pm.`id_manufacturer` = ' . (int)$this->context->cookie->xFilterManufacturer . ')';
        }

        if ($this->context->cookie->xFilterSupplier) {
            $this->_join .= ' INNER JOIN `'._DB_PREFIX_.'supplier` ps ON (a.`id_supplier` = ps.`id_supplier` AND ps.`active` = 1 AND ps.`id_supplier` = ' . (int)$this->context->cookie->xFilterSupplier . ')';
        }

        if ($this->context->cookie->xFilterPriceFrom > 0) {
            $this->_where .= ' AND ((sa.`price` * IF(t.`rate`, ((100 + (t.`rate`))/100), 1)) >= "' . pSQL((float)$this->context->cookie->xFilterPriceFrom) . '")';
        }
        if ($this->context->cookie->xFilterPriceTo > 0) {
            $this->_where .= ' AND ((sa.`price` * IF(t.`rate`, ((100 + (t.`rate`))/100), 1)) <= "' . pSQL((float)$this->context->cookie->xFilterPriceTo) . '")';
        }

        if ($this->context->cookie->xFilterQtyFrom > 0) {
            $this->_where .= ' AND sav.`quantity` >= ' . (int)$this->context->cookie->xFilterQtyFrom;
        }
        if ($this->context->cookie->xFilterQtyTo > 0) {
            $this->_where .= ' AND sav.`quantity` <= ' . (int)$this->context->cookie->xFilterQtyTo;
        }

        if ($this->context->cookie->xFilterActive) {
            $this->_where .= ' AND sa.`active` = ' . ((int)$this->context->cookie->xFilterActive - 1);
        }


        $this->_group = 'GROUP BY a.`id_product`';

        $orderByPriceFinal = (empty($orderBy) ? (Tools::getValue($this->table . 'Orderby') ? Tools::getValue($this->table . 'Orderby') : 'id_' . $this->table) : $orderBy);
        $orderWayPriceFinal = (empty($orderWay) ? (Tools::getValue($this->table . 'Orderway') ? Tools::getValue($this->table . 'Orderway') : 'ASC') : $orderWay);

        parent::getList($id_lang, $orderBy, $orderWay, $start, $limit, $this->context->shop->id);

        /* update product quantity with attributes */
        $nb = count($this->_list);
        if ($this->_list)
        {
            $context = $this->context->cloneContext();
            $context->shop = clone($context->shop);

            /* update product final price */
            for ($i = 0; $i < $nb; $i++)
            {
                if (Context::getContext()->shop->getContext() != Shop::CONTEXT_SHOP) {
                    $context->shop = new Shop((int)$this->_list[$i]['id_shop_default']);
                }

                // convert price with the currency from context

                $this->_list[$i]['price'] = Product::getPriceStatic($this->_list[$i]['id_product'], true, null,
                    (int)Configuration::get('PS_PRICE_DISPLAY_PRECISION'), null, false, true, 1, true, null, null, null, $nothing, true, true, $context);
                $this->_list[$i]['price_tmp'] = Product::getPriceStatic($this->_list[$i]['id_product'], true, null,
                    (int)Configuration::get('PS_PRICE_DISPLAY_PRECISION'), null, false, true, 1, true, null, null, null, $nothing, true, true, $context);
            }
        }

        if ($orderByPriceFinal == 'price_final')
        {
            if (strtolower($orderWayPriceFinal) == 'desc') {
                uasort($this->_list, 'cmpPriceDesc');
            } else {
                uasort($this->_list, 'cmpPriceAsc');
            }
        }

        for ($i = 0; $this->_list && $i < $nb; $i++)
        {
            $this->_list[$i]['price_final'] = $this->_list[$i]['price_tmp'];
            unset($this->_list[$i]['price_tmp']);
        }
    }

    private function assignXFilters()
    {
        if (Tools::getValue('reset_xFilter'))
        {
            $this->context->cookie->xFilterCategory =
            $this->context->cookie->xFilterPriceFrom =
            $this->context->cookie->xFilterPriceTo =
            $this->context->cookie->xFilterQtyFrom =
            $this->context->cookie->xFilterQtyTo =
            $this->context->cookie->xFilterManufacturer =
            $this->context->cookie->xFilterSupplier =
            $this->context->cookie->xFilterPerformed =
            $this->context->cookie->xFilterActive = false;
        }
        else if (Tools::isSubmit('submit_xFilter'))
        {
            if (is_numeric(Tools::getValue('productFilter_cl!name')))
            {
                $category = new Category((int)Tools::getValue('productFilter_cl!name'));

                if (Validate::isLoadedObject($category) && $category->inShop($this->context->shop)) {
                    $_POST['productFilter_cl!name'] = $category->name[$this->context->language->id];
                }
            }
            else {
                if (Tools::getValue('xFilterCategory')) {
                    $xFilterCategory = Tools::getValue('xFilterCategory');
                } else if ($this->context->cookie->xFilterCategory) {
                    $xFilterCategory = unserialize($this->context->cookie->xFilterCategory);
                } else {
                    $xFilterCategory = false;
                }

                // sprawdzamy czy wybrane kategorie wystepuja w aktywnym sklepie
                if ($xFilterCategory) {
                    $xFilterCategory = $this->existsInShop($xFilterCategory, $this->context->shop->id);
                }
            }

            $this->context->cookie->xFilterCategory = (isset($xFilterCategory) && is_array($xFilterCategory) ? serialize($xFilterCategory) : false);
            $this->context->cookie->xFilterPriceFrom = (float)Tools::getValue('xFilterPriceFrom');
            $this->context->cookie->xFilterPriceTo = (float)Tools::getValue('xFilterPriceTo');
            $this->context->cookie->xFilterQtyFrom = (int)Tools::getValue('xFilterQtyFrom');
            $this->context->cookie->xFilterQtyTo = (int)Tools::getValue('xFilterQtyTo');
            $this->context->cookie->xFilterManufacturer = (int)Tools::getValue('xFilterManufacturer');
            $this->context->cookie->xFilterSupplier = (int)Tools::getValue('xFilterSupplier');
            $this->context->cookie->xFilterActive = (int)Tools::getValue('xFilterActive');
            $this->context->cookie->xFilterPerformed = (int)Tools::getValue('xFilterPerformed');
        }
    }

    public function setMedia()
    {
        $this->addJS(_PS_BO_ALL_THEMES_DIR_.'default/js/tree.js');
        $this->addCSS(_MODULE_DIR_.$this->module->name.'/views/css/sellbox_style.css');
        $this->addJS(_MODULE_DIR_.$this->module->name.'/views/js/sellbox.js');
        parent::setMedia();
    }

    public function initContent($token = null)
    {
        $selected_categories = array((int)Configuration::get('PS_ROOT_CATEGORY'));

        if (version_compare(_PS_VERSION_, '1.6', '<'))
        {
            $helper_cat = new Helper();
            $category_tree = $helper_cat->renderCategoryTree(null, $selected_categories, 'xFilterCategory', false, false, array(), false, true);
        }
        else {
            $tree = new HelperTreeCategories('xFilterCategories', $this->l('Kategorie'));
            $category_tree = $tree->setInputName('xFilterCategory')
                ->setUseCheckBox(true)
                ->setRootCategory(Category::getRootCategory()->id)
                ->setSelectedCategories($selected_categories)
                ->render();
        }

        if(isset($_SESSION['notifications']) && $notif = $_SESSION['notifications'])
        {
            $notif = json_decode($notif);

            if($notif->error)
            {
                foreach ($notif->error as $error)
                {
                    $this->displayWarning($error);
                }
            }

            if($notif->success)
            {
                foreach ($notif->success as $success)
                {
                    $this->displayInformation($success);
                }
            }
        }

        if(isset($_GET['id_product']) && isset($_GET['action']) && $_GET['action'] == 'refresh_product' && $refresh_id = $_GET['id_product'])
        {
            $status = $this->sellboxapi->refreshProduct((int)$refresh_id);

            if($status == 1)
                $shop_refresh = $this->sellboxapi->refreshShopProduct((int)$refresh_id);

            $front = new FrontController;

            $front->success[] = 'Ogłoszenie zostało odświeżone';

            $front->redirectWithNotifications(
                $this->context->link->getAdminLink('AdminSellboxMain', false).'&token='.Tools::getAdminTokenLite('AdminSellboxMain')
            );
        }

        if(isset($_GET['id_product']) && isset($_GET['action']) && $_GET['action'] == 'remove_product' && $remove_id = $_GET['id_product'])
        {
            $status = $this->sellboxapi->removeProduct((int)$remove_id);

            if($status == 'OK')
                $shop_remove = $this->sellboxapi->removeShopProduct((int)$remove_id);

            $front = new FrontController;

            $front->errors[] = 'Ogłoszenie zostało usunięte';

            $front->redirectWithNotifications(
                $this->context->link->getAdminLink('AdminSellboxMain', false).'&token='.Tools::getAdminTokenLite('AdminSellboxMain')
            );
        }

        $this->tpl_list_vars['category_tree'] = $category_tree;
        $this->tpl_list_vars['manufacturers'] = Manufacturer::getManufacturers(false, $this->context->language->id);
        $this->tpl_list_vars['suppliers'] = Supplier::getSuppliers(false, $this->context->language->id);
        $this->tpl_list_vars['xFilterPriceFrom'] = (float)$this->context->cookie->xFilterPriceFrom;
        $this->tpl_list_vars['xFilterPriceTo'] = (float)$this->context->cookie->xFilterPriceTo;
        $this->tpl_list_vars['xFilterQtyFrom'] = (int)$this->context->cookie->xFilterQtyFrom;
        $this->tpl_list_vars['xFilterQtyTo'] = (int)$this->context->cookie->xFilterQtyTo;
        $this->tpl_list_vars['xFilterManufacturer'] = (int)$this->context->cookie->xFilterManufacturer;
        $this->tpl_list_vars['xFilterSupplier'] = (int)$this->context->cookie->xFilterSupplier;
        $this->tpl_list_vars['xFilterActive'] = (int)$this->context->cookie->xFilterActive;
        $this->tpl_list_vars['xFilterPerformed'] = (int)$this->context->cookie->xFilterPerformed;
        $this->tpl_list_vars['bootstrap'] = true;

        parent::initContent();
    }

    public function displaySellboxLink($token = null, $id, $name = null)
    {
        $status = $this->sellboxapi->checkProductStatus($id);

        $tpl = $this->context->smarty->createTemplate($this->module->getLocalPath() . 'views/templates/admin/' . $this->tpl_folder . 'helpers/list/action_sellbox.tpl');

        if($status['status'] == 1)
        {
            $txt = $this->l('Usuń ogłoszenie');
            $class = 'remove-ad';
            $href = $this->context->link->getAdminLink('AdminSellboxMain') . '&id_product=' . $id .'&action=remove_product';

            $expired = $status['date_expired'];

            $now_dt = Datetime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
            $item_dt = new DateTime($expired);
            $now_dt = $now_dt->modify('+4 days');
            $diff = $now_dt->diff($item_dt);
            $daysleft = $diff->format('%a'); // %a modifier means total days

            if($daysleft < 5 || $diff->invert == 1)
            {
                $txt = $this->l('Odświeź ogłoszenie');
                $class = 'refresh-ad';
                $href = $this->context->link->getAdminLink('AdminSellboxMain') . '&id_product=' . $id .'&action=refresh_product';
            }

            $tpl->assign(array(
                'href' => $href,
                'button_txt' => $txt,
                'title' => $txt,
                'class' => $class,
                'id' => $id
            ));
        }
        else
        {
            $txt = $this->l('Dodaj ogłoszenie');

            $tpl->assign(array(
                'href' => $this->context->link->getAdminLink('AdminSellboxAdd') . '&id_product=' . $id .'&action=add_product',
                'button_txt' => $txt,
                'title' => $this->l('Dodaj ogłoszenie w sellbox.pl'),
                'class' => '',
                'id' => $id
            ));
        }

        return $tpl->fetch();
    }

    public function processBulkSellbox()
    {
        if (is_array($this->boxes) && !empty($this->boxes)) {
            $this->redirect_after = $this->context->link->getAdminLink('AdminSellboxAdd') . '&id_product=' . implode(',', array_map('intval', $this->boxes));
        }
    }

    private function existsInShop(array $categories, $id_shop)
    {
        if (empty($categories)) {
            return array();
        }

        $result =  Db::getInstance()->executeS('
            SELECT `id_category`
            FROM `'._DB_PREFIX_.'category_shop`
            WHERE `id_category` IN (' . implode(',', $categories) . ')
                AND `id_shop` = ' . (int) $id_shop
        );

        $cats = array();
        if (!$result) {
            return $cats;
        }

        foreach ($result as $row) {
            $cats[] = $row['id_category'];
        }

        return $cats;
    }
}