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

if (!defined('_PS_VERSION_')) {
    exit;
}


class narzuty extends Module
{

    public function __construct()
    {
        $this->name = 'narzuty';
        $this->tab = 'others';
        $this->version = '1.0.0';
        $this->author = 'Fotax';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.7.0.0',
            'max' => _PS_VERSION_
        ];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Narzuty');
        $this->description = $this->l('Moduł szybkiej zmiany narzutów');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
    }

    public function install()
    {
        return parent::install()
            && $this->installTab();
    }

    public function uninstall()
    {
        return parent::uninstall()
            && $this->uninstallTab();
    }

    private function installTab()
    {

        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = 'Narzuty';

        $tab->name = array();
        foreach (Language::getLanguages() as $lang) {
            $tab->name[$lang['id_lang']] = $this->trans('Narzuty', array(), 'Modules.Narzuty.Admin', $lang['locale']);
        }
        $tab->id_parent = (int) Tab::getIdFromClassName('AdminParentCustomer');
        $tab->module = $this->name;
        return $tab->add();
    }

    private function uninstallTab()
    {
        $tabId = (int) Tab::getIdFromClassName('Narzuty');
        if (!$tabId) {
            return true;
        }

        $tab = new Tab($tabId);

        return $tab->delete();
    }
}
