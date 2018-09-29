<?php
/**
 * Necessary for saving generated commercial invoice along with shipping label, and downloading commercial invoice label
 *
 * @desc        OVERWRITE OF ADMINHTML CONTROLLER
 *
 * @author      Joel Hart <joel@mediotype.com>
 */
include_once('Mage/Adminhtml/controllers/Sales/Order/ShipmentController.php');
class Mediotype_HarmonizedTariffSchedule_Adminhtml_Sales_Order_ShipmentController extends Mage_Adminhtml_Sales_Order_ShipmentController {

    /**
     * Create shipping label for specific shipment with validation.
     *
     * @param Mage_Sales_Model_Order_Shipment $shipment
     * @return bool
     */
    protected function _createShippingLabel(Mage_Sales_Model_Order_Shipment $shipment)
    {
        if (!$shipment) {
            return false;
        }
        $carrier = $shipment->getOrder()->getShippingCarrier();
        if (!$carrier->isShippingLabelsAvailable()) {
            return false;
        }
        $shipment->setPackages($this->getRequest()->getParam('packages'));
        $response = Mage::getModel('shipping/shipping')->requestToShipment($shipment);
        if ($response->hasErrors()) {
            Mage::throwException($response->getErrors());
        }
        if (!$response->hasInfo()) {
            return false;
        }
        $labelsContent = array();
        $trackingNumbers = array();
        $commercialInvoice = "";
        $info = $response->getInfo();;
        foreach ($info as $inf) {
            if (!empty($inf['tracking_number']) && !empty($inf['label_content'])) {
                $labelsContent[] = $inf['label_content'];
                $trackingNumbers[] = $inf['tracking_number'];

            }
            if( !empty($inf['commercial_invoice']) ){
                $commercialInvoice = $inf['commercial_invoice'] ; //commercial invoice
                $shipment->setCommercialInvoice($commercialInvoice);
            }
        }
        $outputPdf = $this->_combineLabelsPdf($labelsContent);
        $shipment->setShippingLabel($outputPdf->render());

        $carrierCode = $carrier->getCarrierCode();
        $carrierTitle = Mage::getStoreConfig('carriers/'.$carrierCode.'/title', $shipment->getStoreId());
        if ($trackingNumbers) {
            foreach ($trackingNumbers as $trackingNumber) {
                $track = Mage::getModel('sales/order_shipment_track')
                    ->setNumber($trackingNumber)
                    ->setCarrierCode($carrierCode)
                    ->setTitle($carrierTitle);
                $shipment->addTrack($track);
            }
        }
        return true;
    }

    public function printCommercialAction(){
        try {
            $shipment = $this->_initShipment();
            $labelContent = $shipment->getCommercialInvoice();
            if ($labelContent) {
                $pdfContent = null;
                if (stripos($labelContent, '%PDF-') !== false) {
                    $pdfContent = $labelContent;
                } else {
                    $pdf = new Zend_Pdf();
                    $page = $this->_createPdfPageFromImageString($labelContent);
                    if (!$page) {
                        $this->_getSession()->addError(Mage::helper('sales')->__('File extension not known or unsupported type in the following shipment: %s', $shipment->getIncrementId()));
                    }
                    $pdf->pages[] = $page;
                    $pdfContent = $pdf->render();
                }

                return $this->_prepareDownloadResponse(
                    'ShippingLabel(' . $shipment->getIncrementId() . ').pdf',
                    $pdfContent,
                    'application/pdf'
                );
            }
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            Mage::logException($e);
            $this->_getSession()
                ->addError(Mage::helper('sales')->__('An error occurred while creating shipping label.'));
        }
        $this->_redirect('*/sales_order_shipment/view', array(
            'shipment_id' => $this->getRequest()->getParam('shipment_id')
        ));
    }

}