<?php
/**
 * Display customs data on the shipment packaging area based on module config
 *
 * @copyright   Copyright (c) 2014 Mediotype (http://www.mediotype.com)
 *              Copyright, 2014, Mediotype, LLC - US license
 *
 * @author      Joel Hart <joel@mediotype.com>
 */
 
class Mediotype_HarmonizedTariffSchedule_Block_Adminhtml_Sales_Order_Shipment_Packaging extends Mage_Adminhtml_Block_Sales_Order_Shipment_Packaging {


    /**
     * Can display customs value
     * @desc !important OVERRIDDEN to use module configuration since the module
     *       drives customs value declarations from product attributes instead of
     *       a manually input field
     * @return bool
     */
    public function displayCustomsValue()
    {
        $carrier = $shippingCarrier = $this->getShipment()->getOrder()->getShippingCarrier();
        $carrierCode = $carrier->getCarrierCode();
        $carriers = Mage::getStoreConfig('mediotype/hts/carriers');

        if( array_key_exists( $carrierCode, $carriers)
            && $carriers[$carrierCode] == 1
            && (bool)(int)Mage::getStoreConfig('shipping/harmonized_tariff_carrier_fedex/active')
            && (bool)(int)Mage::getStoreConfig('shipping/harmonized_tariff_carrier_fedex/retrieve_commercial_invoice')
        ) {
            return false;
        }

        return parent::displayCustomsValue();
    }
}