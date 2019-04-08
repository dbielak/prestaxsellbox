<?php
var_dump('test');die;
if (!defined('_PS_VERSION_'))
{
    exit;
}

class sellbox extends Module
{
    public $update = null;

    public $config;

    public function __construct()
    {
        $this->name = 'sellbox';
        $this->tab = 'market_place';
        $this->version = '1.0.0';
        $this->author = 'sellbox.pl';
        $this->controllers = array('main');
        $this->need_instance = 1;
        $this->secure_key = Tools::encrypt($this->name);
        $this->bootstrap = true;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => '1.7.99.9999');

        parent::__construct();

        $this->displayName = $this->l('Sellbox.pl');
        $this->description = $this->l('Integracja z Sellbox.pl');
    }

    public function installModuleTab()
    {
        $tab = new Tab;
        $langs = Language::getLanguages();
        foreach ($langs as $lang)
            $tab->name[$lang['id_lang']] = 'Sellbox';
        $tab->module = $this->name;
        $tab->id_parent = 2;
        $tab->icon = 'gavel';
        $tab->class_name = 'AdminSellboxMain';
        $tab->save();

        $parent_tab = new Tab;
        $parent_tab_id = $parent_tab->getIdFromClassName('AdminSellboxMain');

        $tab_add = new Tab;
        foreach ($langs as $lang)
            $tab_add->name[$lang['id_lang']] = 'Dodaj ogłoszenia';
        $tab_add->module = $this->name;
        $tab_add->id_parent = $parent_tab_id;
        $tab_add->icon = 'gavel';
        $tab_add->class_name = 'AdminSellboxMainRedirect';
        $tab_add->save();

        $tab_category = new Tab;
        foreach ($langs as $lang)
            $tab_category->name[$lang['id_lang']] = 'Kategorie';
        $tab_category->module = $this->name;
        $tab_category->id_parent = $parent_tab_id;
        $tab_category->icon = 'gavel';
        $tab_category->class_name = 'AdminSellboxCategory';
        $tab_category->save();

        $tab_settings = new Tab;
        foreach ($langs as $lang)
            $tab_settings->name[$lang['id_lang']] = 'Ustawienia';
        $tab_settings->module = $this->name;
        $tab_settings->id_parent = $parent_tab_id;
        $tab_settings->icon = 'gavel';
        $tab_settings->class_name = 'AdminSellboxSettings';
        $tab_settings->save();

        return true;
    }

    public function uninstallModuleTab()
    {
        $id_tab = Tab::getIdFromClassName('AdminSellboxMain');
        if($id_tab)
        {
            $tab = new Tab($id_tab);
            return $tab->delete();
        }

        $tab_settings = Tab::getIdFromClassName('AdminSellboxSettings');
        if($tab_settings)
        {
            $tab = new Tab($tab_settings);
            return $tab->delete();
        }

        $tab_add = Tab::getIdFromClassName('AdminSellboxMainRedirect');
        if($tab_add)
        {
            $tab = new Tab($tab_add);
            return $tab->delete();
        }

        $tab_category = Tab::getIdFromClassName('AdminSellboxCategory');
        if($tab_category)
        {
            $tab = new Tab($tab_category);
            return $tab->delete();
        }

        return true;
    }

    public function installDb()
    {
        $return = true;
        include(dirname(__FILE__).'/install/sql_install.php');
        foreach ($sql as $s) {
            $return &= Db::getInstance()->execute($s);
        }
        return $return;
    }

    public function uninstallDb()
    {
        include(dirname(__FILE__).'/install/sql_install.php');
        foreach ($sql as $name => $v) {
            Db::getInstance()->execute('DROP TABLE '.$name);
        }
        return true;
    }

    public function install()
    {
        if (
            !parent::install() ||
            !$this->registerHook('displayBackOfficeHeader') ||
            !$this->installDb()
        )
            return false;


        $this->installModuleTab();
        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall() || !$this->uninstallDb() || !$this->uninstallModuleTab())
            return false;

        return true;
    }

    public function hookDisplayBackOfficeHeader()
    {
        if (Tools::isSubmit('ajax')) {
            return null;
        }

        $output = '
            <script type="text/javascript">
                var sellbox_token = \'' . Tools::getValue('token') . '\';
                var sellbox_bootstrap = ' . (version_compare(_PS_VERSION_, '1.6', '<') ? 0 : 1) . ';
            </script>';

        return $output;
    }

    public function getContent()
    {
        $output = null;

        if (Tools::isSubmit('submit'.$this->name))
        {
            Configuration::updateValue('APIKEY_SELLBOX', Tools::getValue('APIKEY_SELLBOX'));
            Configuration::updateValue('LOGIN_SELLBOX', Tools::getValue('LOGIN_SELLBOX'));
            $output .= $this->displayConfirmation($this->l('Settings updated'));
        }

        $output = $output.$this->displayForm();

        $output = $output.$this->context->smarty->fetch($this->local_path.'views/templates/admin/_configure/configure_header.tpl');
        $output = $output.$this->context->smarty->fetch($this->local_path.'views/templates/admin/_configure/configure_footer.tpl');
        $output = $output.$this->context->smarty->fetch($this->local_path.'views/templates/admin/_configure/configure_author.tpl');

        return $output;
    }

    public function displayForm()
    {
        // Get default language
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

        $fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('Ustawienia konta'),
            ),
            'input' =>	array(
                array(
                    'label' => $this->l('API key'),
                    'type' => 'text',
                    'class' => 'fixed-width-lg',
                    'size' => 70,
                    'value' => Configuration::get('APIKEY_SELLBOX'),
                    'name' => 'APIKEY_SELLBOX',
                    'desc' => $this->l('Aby uzyskać api key załóż konto na sellbox.pl, wejdź na swoje konto i wygeneruj api key. Pamiętaj, że aby wygenerować klucz, trzeba wybrać lokalizację.'),
                    'required' => true
                ),
                array(
                    'label' => $this->l('LOGIN Sellbox'),
                    'type' => 'text',
                    'class' => 'fixed-width-lg',
                    'size' => 70,
                    'value' => Configuration::get('LOGIN_SELLBOX'),
                    'name' => 'LOGIN_SELLBOX',
                    'desc' => $this->l('Użyj loginu do serwisu sellbox.pl'),
                    'required' => true
                )
            ),
            'submit' => array('title' => $this->l('Zapisz'))
        );

        $helper = new HelperForm();

        // Module, token and currentIndex
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;

        // Language
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;

        // Title and toolbar
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;        // false -> remove toolbar
        $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
        $helper->submit_action = 'submit'.$this->name;
        $helper->toolbar_btn = array(
            'save' =>
                array(
                    'desc' => $this->l('Save'),
                    'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.
                        '&token='.Tools::getAdminTokenLite('AdminModules'),
                ),
            'back' => array(
                'href' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list')
            )
        );

        // Load current value
        $helper->fields_value['MYMODULE_NAME'] = Configuration::get('MYMODULE_NAME');

        return $helper->generateForm($fields_form);
    }
}