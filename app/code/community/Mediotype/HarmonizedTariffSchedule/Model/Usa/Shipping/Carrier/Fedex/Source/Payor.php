<?php
/**
 * Magento / Mediotype Harmonized Tariff Schedule Module
 * Designed to handle Harmonized Codes and Commercial Invoice Generation
 *
 *
 * @copyright   Copyright (c) 2014 Mediotype (http://www.mediotype.com)
 *              Copyright, 2014, Mediotype, LLC - US license
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author      Joel Hart <joel@mediotype.com>
 */
class Mediotype_HarmonizedTariffSchedule_Model_Usa_Shipping_Carrier_Fedex_Source_Payor
{
    const PAYOR_TYPE_COLLECT = "COLLECT";
    const PAYOR_TYPE_RECIPIENT = "RECIPIENT";
    const PAYOR_TYPE_SENDER = "SENDER";
    const PAYOR_TYPE_THIRD_PARTY = "THIRD_PARTY";

    //todo v 2.0 assess per carrier & provide easier documentation for meaning
    //return ( 'value' => 'label' )
    public function toOptionArray()
    {
        $arr = array(
            'COLLECT' => 'COLLECT',
            'RECIPIENT' => 'RECIPIENT',
            'SENDER' => 'SENDER',
            'THIRD_PARTY' => 'THIRD_PARTY'
        );

        return $arr;
    }

}