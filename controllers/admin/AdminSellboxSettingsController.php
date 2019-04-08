<?php

class AdminSellboxSettingsController extends ModuleAdminController
{
    public function __construct()
    {
        $this->name = 'sellbox';
        $this->tab = 'front_office_features';
        parent::__construct();
        Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name
            .'&token='.Tools::getAdminTokenLite('AdminModules'));
    }
}