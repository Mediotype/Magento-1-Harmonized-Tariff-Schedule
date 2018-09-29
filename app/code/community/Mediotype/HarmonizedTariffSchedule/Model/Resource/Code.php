<?php
/**
 * Magento / Mediotype Harmonized Tariff Schedule Module
 * Designed to handle Harmonized Codes and Commercial Invoice Generation
 *
 *
 * @category    Mediotype
 * @package     Htc
 * @class       Mediotype_Htc_Model_Resource_Code
 * @copyright   Copyright (c) 2013 Mediotype (http://www.mediotype.com)
 *              Copyright, 2013, Mediotype, LLC - US license
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author      Mediotype (SZ,JH) <diveinto@mediotype.com>
 */
class Mediotype_HarmonizedTariffSchedule_Model_Resource_Code extends Mage_Core_Model_Resource_Db_Abstract {

    public function _construct()
    {
        $this->_init('hts/code', 'entity_id');
    }

}