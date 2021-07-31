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


class NarzutyModel extends ObjectModel
{

    private $id_category;

    const MODULE_ADMIN_CONTROLLER = 'Narzuty';

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'specific_price',
        'primary' => 'id_specific_price',
        'fields' => array(
            'reduction'       =>  array('type' => self::TYPE_FLOAT, 'validate' => 'isPercentage', 'required' => true),
            'id_product'       =>  array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'id_customer'       =>  array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
        )
    );

    public static function getCategory($id_product)
    {

        $sql = new DbQuery();
        $sql->select('a.id_category AS id_category, a.name AS category_name');
        $sql->from('category_lang', 'a');
        $sql->innerJoin('product', 's', 's.id_product = ' . $id_product);
        $sql->where('a.id_category = s.id_category_default');

        return Db::getInstance()->executeS($sql);

    }

    public function update($null_values = false)
    {
        $result = false;
        $this->clearCache();

        if (Tools::getValue('reduction') !=false && Tools::getValue('id_specific_price') !=false) {
            $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->execute('UPDATE ps_zamspecific_price AS a LEFT JOIN ps_zamproduct AS pr ON pr.id_product = a.id_product LEFT JOIN ps_zamcategory AS cu ON cu.id_category = ' . Tools::getValue('category', 0) . ' SET reduction = ' . Tools::getValue('reduction', 0) . '/100 WHERE a.id_customer = ' . Tools::getValue('id_customer', 0).' AND pr.id_category_default = cu.id_category');
            
        }
        return $result;
        
    }
}
