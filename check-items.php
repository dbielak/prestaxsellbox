<?php

    define('_PRESTA_DIR_', dirname(dirname(dirname($_SERVER['SCRIPT_FILENAME']))));
    require_once(_PRESTA_DIR_ . '/config/config.inc.php');
    require_once(_PRESTA_DIR_ . '/init.php');
    require_once(dirname(__FILE__) . '/controllers/admin/AdminSellboxCronController.php');

    $sellbox = new AdminSellboxCronController();
    $result = $sellbox->checkRefreshItems();

    die($result);