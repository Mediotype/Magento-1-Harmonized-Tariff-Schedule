<?php
/**
 * Harmonized Tariff Schedule Codes Model -> harmonized_tariff_schedule_codes table
 *
 *
 * @copyright   Copyright (c) 2014 Mediotype (http://www.mediotype.com)
 *              Copyright, 2014, Mediotype, LLC - US license
 * @author      Joel Hart   <joel@mediotype.com>
 */
class Mediotype_HarmonizedTariffSchedule_Model_Code extends Mage_Core_Model_Abstract {

    public function _construct()
    {
        $this->_init('hts/code');
    }

}