<?php
/**
 * Magento / Mediotype Module
 *
 *
 * @author      Joel Hart   <joel@mediotype.com>
 */
 
class Mediotype_HarmonizedTariffSchedule_Block_Adminhtml_Sales_Order_Shipment_Packaging_Grid extends Mage_Adminhtml_Block_Sales_Order_Shipment_Packaging_Grid {

    /**
     * Can display customs value
     *
     * @return bool
     */
    public function displayCustomsValue()
    {
        return false;
    }

}