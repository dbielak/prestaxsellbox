<?php

$sql = array();

//KATEGORIE Z SELLBOX
$sql[_DB_PREFIX_.'sellbox_cat_list'] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'sellbox_cat_list` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `cat_id` int(11) NOT NULL,
			  `cat_parent_id` varchar(11) NOT NULL,
			  `cat_name` varchar(255),
			  `field_list` varchar(255),
			  PRIMARY KEY (`id`, `cat_id`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;
			';
include_once 'sellbox_category.php';

//META FIELD KATEGORII SELlBOX
$sql[_DB_PREFIX_.'sellbox_cat_features'] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'sellbox_cat_features` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `field_id` int(11) NOT NULL,
			  `field_name` varchar(32),
			  `field_type` varchar(32),
			  `field_options` varchar(255),
			  PRIMARY KEY (`id`, `field_id`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';
include_once 'sellbox_cat_features.php';

//KATEGORIE ZE SKLEPU POŁĄCZONE Z KATEGORIAMI Z SELLBOX
$sql[_DB_PREFIX_.'sellbox_category'] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'sellbox_category` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `id_sellbox_category` int(11) NOT NULL,
			  `id_categories` char(255) NOT NULL,
			  `cat_name` varchar(255),
			  PRIMARY KEY (`id`, `id_sellbox_category`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

//Powiązania kategorii z wybranymi fieldami i ich value
$sql[_DB_PREFIX_.'sellbox_cat_to_field'] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'sellbox_cat_to_field` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `id_sellbox_category` int(11) NOT NULL,
			  `id_field` int(11) NOT NULL,
			  `value` varchar(255),
			  PRIMARY KEY (`id`, `id_sellbox_category`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

//Ta tabela trzyma status każdego produktu czy jest wystawiony na malito i jak tak to do kiedy.
// Jeżeli nie ma produktu w tej tabeli to znaczy że nie wystawiony.
// Jeżeli jest to sprawdzamy status. 0 - przedmiot zakończony i można aktywować,
// 1 - przedmiot do odświeżenia, 2 - przedmio jest aktualnie wystawiony i jest data od kiedy i do kiedy.
$sql[_DB_PREFIX_.'sellbox_product_status'] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'sellbox_product_status` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `id_shop_product` int(11) NOT NULL,
			  `id_sellbox_ad` int(11) NOT NULL,
			  `status` int(11) NOT NULL,
			  `date_added` datetime NOT NULL ,
			  `date_expired` datetime NOT NULL ,
			  PRIMARY KEY (`id`, `id_shop_product`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';
