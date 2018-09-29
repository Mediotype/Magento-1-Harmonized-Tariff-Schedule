<?php
/**
 * Add print commercial invoice button next to print shipping label button
 * 
 * @copyright   Copyright (c) 2014 Mediotype (http://www.mediotype.com)
 *              Copyright, 2014, Mediotype, LLC - US license
 *
 * @author      Joel Hart   <joel@mediotype.com>
 */
 
class Mediotype_HarmonizedTariffSchedule_Block_Adminhtml_Sales_Order_Shipment_View_Form extends Mage_Adminhtml_Block_Sales_Order_Shipment_View_Form {

    public function getPrintCommercialButton()
    {
        $data['shipment_id'] = $this->getShipment()->getId();
        $url = $this->getUrl('*/sales_order_shipment/printCommercial', $data);
        return $this->getLayout()
            ->createBlock('adminhtml/widget_button')
            ->setData(array(
                'label'   => Mage::helper('sales')->__('Print Commercial Invoice'),
                'onclick' => 'setLocation(\'' . $url . '\')'
            ))
            ->toHtml();
    }

    /**
     * @desc !important This is over-ridden so no .phtml has to be modified to
     *       add the commercial invoice button if it is possible
     * @return string
     */
    public function getPrintLabelButton()
    {
        $html = "";
        if($this->_canPrintCommercialInvoice()){
            $html .= $this->getPrintCommercialButton();
        }

        $data['shipment_id'] = $this->getShipment()->getId();
        $url = $this->getUrl('*/sales_order_shipment/printLabel', $data);

        $html .= $this->getLayout()
            ->createBlock('adminhtml/widget_button')
            ->setData(array(
                'label'   => Mage::helper('sales')->__('Print Shipping Label'),
                'onclick' => 'setLocation(\'' . $url . '\')'
            ))
            ->toHtml();

        return $html;
    }

    protected function _canPrintCommercialInvoice()
    {
        $invoice = $this->getShipment()->getCommercialInvoice();
        if(isset($invoice)){
            return true;
        }
        return false;
    }

}