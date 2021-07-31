<?php

/**
 *
 * NOTICE OF LICENSE
 *
 *
 *  @author    Fotax <web@fotax.pl>
 *  @copyright 2021 Fotax
 *  @license   Fotax
 */

use PrestaShop\PrestaShop\Adapter\Entity\ObjectModel;

require_once _PS_MODULE_DIR_ . 'narzuty/classes/NarzutyModel.php';

class NarzutyController extends AdminController
{
    private $cache;
    public $id_category;

    public function __construct()
    {
        $this->module = 'narzuty';
        $this->context = Context::getContext();
        $this->bootstrap = true;

        $this->lang = false;
        $this->table = 'specific_price';

        $this->identifier = 'id_specific_price';
        $this->className = 'NarzutyModel';
        $this->required_fields = array('id_specific_price', 'reduction');

        $this->explicitSelect = false;
        $this->allow_export = false;
        $this->delete = false;
        $this->filter_categories = true;
        $this->orderBy = 'company';
        $this->_orderWay = 'ASC';
        $this->shopLinkType = 'shop';
        $this->addRowAction('edit');
        $this->list_no_link = true;
        
        
        parent::__construct();

        $this->_select .= ' a.reduction*100 AS reduction, cu.company AS company, cu.email AS email, cu.firstname AS firstname, cu.lastname AS lastname, c.name AS category'; //GROUP_CONCAT(DISTINCT(c.name) SEPARATOR ", ")
        $this->_join .= ' LEFT JOIN `' . _DB_PREFIX_ . 'customer` cu ON cu.`id_customer` = a.`id_customer`';
        $this->_join .= ' LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pr ON pr.`id_product` = a.`id_product`';
        $this->_join .= ' LEFT JOIN `' . _DB_PREFIX_ . 'product` s ON s.`id_product` = pr.`id_product`';
        $this->_join .= ' LEFT JOIN `' . _DB_PREFIX_ . 'category_lang` c ON c.`id_category` = s.`id_category_default`';
        $this->_group = 'GROUP BY cu.`email`, c.`id_category`';

        $this->_where .= 'AND cu.email <> "" ';
        $this->_where .= 'AND c.name <> "" ';

        $this->_orderBy .= 'company';

        $this->fields_list = [
            'id_customer'       => [
                'title' => $this->l('ID'),
                'type'  => 'text',
                'align' => 'center',
                'class' => 'fixed-width-xs',
                'filter' => false,
                'search' => false
            ],
            'company'     => [
                'title' => $this->l('Klient'),
                'type'  => 'text',
                'filter_key' => 'cu!company',
                'filter_type' => 'text',
            ],
            'email'     => [
                'title' => $this->l('Email'),
                'type'  => 'text',
                'filter_key' => 'cu!email',
                'filter_type' => 'text',
            ],
            'firstname'     => [
                'title' => $this->l('Imię'),
                'type'  => 'text',
                'filter_key' => 'cu!firstname',
                'filter_type' => 'text',
            ],
            'lastname'     => [
                'title' => $this->l('Nazwisko'),
                'type'  => 'text',
                'filter_key' => 'cu!lastname',
                'filter_type' => 'text',
            ],
            // 'id_product'     => [
            //     'title' => $this->l('Produkt'),
            //     'type'  => 'text',
            //     'filter_key' => 'pr!id_product',
            //     'filter_type' => 'text',
            // ],
            'category'     => [
                'title' => $this->l('Category'),
                'type'  => 'text',
                'filter_key' => 'c!name',
                'filter_type' => 'text',

            ],
            'reduction'     => [
                'title' => $this->l('Narzut'),
                'type'  => 'float',
                'suffix' => '%',
                // 'filter_key' => 'a!reduction',
                // 'filter_type' => 'text',
                'filter' => false,
                'search' => false
            ],
        ];
    }

    public function initToolbar()
    {
        // parent::initToolbar();
        // unset( $this->toolbar_btn['new'] );
    }

    public function renderForm()
    {

        if (!($obj = $this->loadObject(true))) {
            return;
        };
        $category_name = NarzutyModel::getCategory($obj->id_product)[0]['category_name'];
        $this->id_category = NarzutyModel::getCategory($obj->id_product)[0]['id_category'];

        $this->fields_form = array(
            'legend' => [
                'title' => 'Kategoria: ' . $category_name,
            ],
            'input'  => array(
                array(
                    'col'     => 4,
                    'type'     => 'hidden',
                    'label'    => $this->l('Kategoria'),
                    'name'     => 'category',
                    'default_value'     => (int)$this->id_category,
                    'required' => true,
                    'lang' => false
                ),
                array(
                    'col'     => 4,
                    'type'     => 'hidden',
                    'label'    => $this->l('Klient'),
                    'name'      => 'id_customer',
                    'required' => true,
                    'lang' => false
                ),
                array(
                    'col'     => 4,
                    'type'     => 'text',
                    'validation' => 'isInt',
                    'label'    => $this->l('Narzut % '),
                    'name'     => 'reduction',
                    'required' => true,
                    'lang' => false
                ),

            ),
            'submit' => [
                'title' => $this->l('Save'),
            ],
        );

        return parent::renderForm($this->fields_form);
    }

    public function initContent()
    {
        parent::initContent();
    }

}
