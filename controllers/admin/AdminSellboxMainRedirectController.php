<?php

class AdminSellboxMainRedirectController extends ModuleAdminController
{
    public function init()
    {

        parent::init();

        // Just redirect to the module configuration page
        Tools::redirectAdmin($this->context->link->getAdminLink('AdminSellboxMain'));

    }

}