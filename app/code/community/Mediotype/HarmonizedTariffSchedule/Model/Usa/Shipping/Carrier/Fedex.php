<?php
/**
 * Designed to handle Harmonized Tariff Schedule Codes and Commercial Invoice Generation via FedEx API
 *
 * @copyright   Copyright (c) 2014 Mediotype (http://www.mediotype.com)
 *              Copyright, 2014, Mediotype, LLC - US license
 * @author      Mediotype (SZ,JH) <diveinto@mediotype.com>
 */
class Mediotype_HarmonizedTariffSchedule_Model_Usa_Shipping_Carrier_Fedex extends Mage_Usa_Model_Shipping_Carrier_Fedex
{

    /**
     * @var $debug bool Turns on and off log output for debugging
     */
    protected $debug;

    /**
     * Set debug flag, then call parent class
     */
    public function __construct()
    {
        $this->debug = ((bool)(int)Mage::getStoreConfig('shipping/harmonized_tariff_schedule/debug')) ? true : false;
        parent::__construct();
    }



    public function getContainerTypes(Varien_Object $params = null)
    {
        $labelForAdminSelect = array("YOUR_PACKAGING" => "Your Packaging");

        $packaging = Mage::getModel('hts/usa_shipping_carrier_fedex_packaging');
        $packageLabels = $packaging->getCustomPackagingLabels();

        array_merge($labelForAdminSelect, $packageLabels);

        return $labelForAdminSelect;
    }

    protected function _setPackageDimensions(&$packageParams)
    {
        $packageType = $packageParams->getData("container");

        $packaging = Mage::getModel('hts/usa_shipping_carrier_fedex_packaging');
        $packageTypes = $packaging->getCustomPackagingData();

        if (array_key_exists($packageType, $packageTypes)) {
            $packageParams->setLength($packageTypes[$packageType]['length']);
            $packageParams->setWidth($packageTypes[$packageType]['width']);
            $packageParams->setHeight($packageTypes[$packageType]['height']);
        }
        //returns nothing, modified by reference
    }

    /**
     * Form array with appropriate structure for shipment request
     *
     * @param Varien_Object $request
     * @return array
     */
    protected function _formShipmentRequest(Varien_Object $request)
    {
        if (!$this->_isActive()) {
            return parent::_formShipmentRequest($request);
        }

        if ($request->getReferenceData()) {
            $referenceData = $request->getReferenceData() . $request->getPackageId();
        } else {
            $referenceData = 'Order #'
                . $request->getOrderShipment()->getOrder()->getIncrementId()
                . ' P'
                . $request->getPackageId();
        }

        $packageParams = $request->getPackageParams();

        //Added feature for custom package handling
        $this->_setPackageDimensions($packageParams);

        $height = $packageParams->getHeight();
        $width = $packageParams->getWidth();
        $length = $packageParams->getLength();
        $weightUnits = $packageParams->getWeightUnits() == Zend_Measure_Weight::POUND ? 'LB' : 'KG';
        $dimensionsUnits = $packageParams->getDimensionUnits() == Zend_Measure_Length::INCH ? 'IN' : 'CM';
        $unitPrice = 0;
        $itemsQty = 0;
        $itemsDesc = array();
        $productIds = array();
        $packageItems = $request->getPackageItems();

        $customsValue = 0;
        foreach ($packageItems as $itemShipment) {
            $item = new Varien_Object();
            $item->setData($itemShipment);

            $unitPrice +=
            $itemsQty += $item->getQty();

            $itemsDesc[] = $item->getName();
            $productIds[] = $item->getProductId();
            $customsValue += $item->getPrice();
        }

        // Used for who pays for the shipping not tariffs
        $paymentType = $request->getIsReturn() ? 'RECIPIENT' : 'SENDER';
        $packageType = $packageParams->getData("container");

        $packagingType = "";
        if (array_key_exists($packageType, $this->_customContainerSpecs)) {
            $packagingType = "YOUR_PACKAGING";
        } else {
            $packagingType = $request->getPackagingType();
        }

        //If Enabled Get Commercial Invoice With Print Label
        if ( (bool)(int)Mage::getStoreConfig('shipping/harmonized_tariff_carrier_fedex/retrieve_commercial_invoice')
            && $request->getShipperAddressCountryCode() != $request->getRecipientAddressCountryCode()
        ) {
            $ShippingDocumentSpecification = array();
            $ShippingDocumentSpecification['ShippingDocumentTypes'] = "LABEL";
            $ShippingDocumentSpecification['ShippingDocumentTypes'] = "COMMERCIAL_INVOICE";
            $ShippingDocumentSpecification['CommercialInvoiceDetail'] = array(
                "Format" => array(
                    "ImageType" => "PDF",
                    "StockType" => "PAPER_LETTER"
                )
            );

            $imageString = $this->_getInvoiceImageString();
            if($imageString){
                $uploadImageDetail =  array(
                    "Type"  => "LETTER_HEAD",
                    "Id"    => "IMAGE_1"
//                    "Image" => $imageString,  can not use header image via fedex api, spent 2 days on phone w/ enginers, no one there knows how to
                );
                $ShippingDocumentSpecification['CommercialInvoiceDetail']["CustomerImageUsages"] =  $uploadImageDetail;
            }

        }


        $requestClient = array(
            'RequestedShipment' => array(
                'ShipTimestamp' => time(),
                'DropoffType' => $this->getConfigData('dropoff'),
                'PackagingType' => $packagingType,
                'ServiceType' => $request->getShippingMethod(),
                'Shipper' => array(
                    'Contact' => array(
                        'PersonName' => $request->getShipperContactPersonName(),
                        'CompanyName' => $request->getShipperContactCompanyName(),
                        'PhoneNumber' => $request->getShipperContactPhoneNumber()
                    ),
                    'Address' => array(
                        'StreetLines' => array($request->getShipperAddressStreet()),
                        'City' => $request->getShipperAddressCity(),
                        'StateOrProvinceCode' => $request->getShipperAddressStateOrProvinceCode(),
                        'PostalCode' => $request->getShipperAddressPostalCode(),
                        'CountryCode' => $request->getShipperAddressCountryCode()
                    )
                ),
                'Recipient' => array(
                    'Contact' => array(
                        'PersonName' => $request->getRecipientContactPersonName(),
                        'CompanyName' => $request->getRecipientContactCompanyName(),
                        'PhoneNumber' => $request->getRecipientContactPhoneNumber()
                    ),
                    'Address' => array(
                        'StreetLines' => array($request->getRecipientAddressStreet()),
                        'City' => $request->getRecipientAddressCity(),
                        'StateOrProvinceCode' => $request->getRecipientAddressStateOrProvinceCode(),
                        'PostalCode' => $request->getRecipientAddressPostalCode(),
                        'CountryCode' => $request->getRecipientAddressCountryCode(),
                        'Residential' => (bool)$this->getConfigData('residence_delivery')
                    ),
                ),
                'ShippingChargesPayment' => array(
                    'PaymentType' => $paymentType,
                    'Payor' => array(
                        'AccountNumber' => $this->getConfigData('account'),
                        'CountryCode' => Mage::getStoreConfig(
                            Mage_Shipping_Model_Shipping::XML_PATH_STORE_COUNTRY_ID,
                            $request->getStoreId()
                        ),
                        'Tins' => array(
                            'TinType' => "BUSINESS_NATIONAL",
                            'Number'  => Mage::getStoreConfig('general/store_information/merchant_vat_number')
                        )
                    )
                ),
                'LabelSpecification' => array(
                    'LabelFormatType' => 'COMMON2D',
                    'ImageType' => 'PNG',
                    'LabelStockType' => 'PAPER_8.5X11_TOP_HALF_LABEL',
                ),


                'RateRequestTypes' => array('ACCOUNT'),
                'PackageCount' => 1,
                'RequestedPackageLineItems' => array(
                    'SequenceNumber' => '1',
                    'Weight' => array(
                        'Units' => $weightUnits,
                        'Value' => $request->getPackageWeight()
                    ),
                    'CustomerReferences' => array(
                        'CustomerReferenceType' => 'CUSTOMER_REFERENCE',
                        'Value' => $referenceData
                    ),
                    'SpecialServicesRequested' => array(
                        'SpecialServiceTypes' => 'SIGNATURE_OPTION',
                        'SignatureOptionDetail' => array('OptionType' => $packageParams->getDeliveryConfirmation())
                    ),
                )
            )
        );

        if(isset($ShippingDocumentSpecification)){
            $requestClient['RequestedShipment']['ShippingDocumentSpecification'] = $ShippingDocumentSpecification;
        }

        // for international shipping
        if ($request->getShipperAddressCountryCode() != $request->getRecipientAddressCountryCode()) {
            //Add Duties Payment Node With Module Logic
            $dutiesPayment = array();
            $dutiesPayment['PaymentType'] = Mage::getStoreConfig('shipping/harmonized_tariff_carrier_fedex/tariff_payor');
            switch ($dutiesPayment['PaymentType']) {
                case Mediotype_HarmonizedTariffSchedule_Model_Usa_Shipping_Carrier_Fedex_Source_Payor::PAYOR_TYPE_RECIPIENT:
                    //Add Nothing
                    break;
                case Mediotype_HarmonizedTariffSchedule_Model_Usa_Shipping_Carrier_Fedex_Source_Payor::PAYOR_TYPE_SENDER:
                    //Add Account Number For Payor When Sender Is Responsible For Tariffs
                    $dutiesPayment['Payor'] = array(
                        "AccountNumber" => $this->getConfigData('account'),
                        "CountryCode" => Mage::getStoreConfig(
                            Mage_Shipping_Model_Shipping::XML_PATH_STORE_COUNTRY_ID,
                            $request->getStoreId()
                        )
                    );
                    break;
                case Mediotype_HarmonizedTariffSchedule_Model_Usa_Shipping_Carrier_Fedex_Source_Payor::PAYOR_TYPE_COLLECT:
                    //TODO
                    break;
                case Mediotype_HarmonizedTariffSchedule_Model_Usa_Shipping_Carrier_Fedex_Source_Payor::PAYOR_TYPE_THIRD_PARTY:
                    //TODO
                    break;
                default:
                    throw new Exception("No Valid International Payor Type Provided");
                    break;
            }

            $requestClient['RequestedShipment']['CustomsClearanceDetail'] =
                array(
                    'CustomsValue' => array(
                        'Currency' => $request->getBaseCurrencyCode(),
                        'Amount' => $customsValue, // Sum Of Declared Values
                    ),
                    'DutiesPayment' => $dutiesPayment
                );

            foreach ($packageItems as $item) {
                $product = Mage::getModel('catalog/product')->load($item['product_id']);
                if ($product->hasData('hts_customs_uom')
                    && $product->hasData('hts_customs_unit_quantity')
                    && $product->hasData('hts')
                ) {
                    $harmonizedCode = Mage::getModel('hts/code')->load($product->getData('hts'));
                    $requestClient['RequestedShipment']['CustomsClearanceDetail']['Commodities'][] = array(
                        'Weight' => array(
                            'Units' => $weightUnits,
                            'Value' => $product->getData("weight")
                        ),
                        'NumberOfPieces' => $item['qty'],
                        'CountryOfManufacture' => $product->getCountryOfManufacture(),
                        'Description' => $product->getData('name'),
                        "HarmonizedCode" => $harmonizedCode->getData('hts_no') . "." . $harmonizedCode->getData('stat_suffix'),
                        'Quantity' => ceil($product->getData('hts_customs_unit_quantity')),
                        'QuantityUnits' => $product->getData('hts_customs_uom'),
                        'UnitPrice' => array(
                            'Currency' => $request->getBaseCurrencyCode(),
                            'Amount' => $item['price']
                        ),
                        'CustomsValue' => array(
                            'Currency' => $request->getBaseCurrencyCode(),
                            'Amount' => $item['price']
                        )
                    );
                } else {
                    if ($this->debug) {
                        Mage::log(array("MISSING HTS ELEMENT FOR PRODUCT" => array(
                            "ID" => $product->getId(),
                            "SKU" => $product->getSku(),
                            "NAME" => $product->getName(),
                            "HTS CODE" => $product->getData('hts'),
                            "CUSTOMS VALUE (SOLD FOR PRICE)" => $item['price'],
                            "CUSTOMS UNIT QUANTITY" => $product->getData("hts_customs_unit_quantity"),
                            "CUSTOMS UNIT OF MEASURE" => $product->getData('hts_customs_uom')
                        )
                        ), NULL, 'Mediotype.log');
                    }
                }
            }
        }

        if ($request->getMasterTrackingId()) {
            $requestClient['RequestedShipment']['MasterTrackingId'] = $request->getMasterTrackingId();
        }

        // set dimensions
        if ($length || $width || $height) {
            $requestClient['RequestedShipment']['RequestedPackageLineItems']['Dimensions'] = array();
            $dimenssions = & $requestClient['RequestedShipment']['RequestedPackageLineItems']['Dimensions'];
            $dimenssions['Length'] = $length;
            $dimenssions['Width'] = $width;
            $dimenssions['Height'] = $height;
            $dimenssions['Units'] = $dimensionsUnits;
        }

        return $this->_getAuthDetails() + $requestClient;
    }

    protected function _doShipmentRequest(Varien_Object $request)
    {
        $this->_prepareShipmentRequest($request);
        $result = new Varien_Object();
        $client = $this->_createShipSoapClient();
        $requestClient = $this->_formShipmentRequest($request);
        $response = $client->processShipment($requestClient);

        if ($response->HighestSeverity != 'FAILURE' && $response->HighestSeverity != 'ERROR') {
            $shippingLabelContent = $response->CompletedShipmentDetail->CompletedPackageDetails->Label->Parts->Image;
            $trackingNumber = $response->CompletedShipmentDetail->CompletedPackageDetails->TrackingIds->TrackingNumber;
            $result->setShippingLabelContent($shippingLabelContent);
            $result->setTrackingNumber($trackingNumber);

            // NEW CODE
            $commercialInvoice = $response->CompletedShipmentDetail->ShipmentDocuments->Parts->Image;
            $result->setCommercialInvoice($commercialInvoice);
            // END NEW CODE

            $debugData = array('request' => $client->__getLastRequest(), 'result' => $client->__getLastResponse());
            if( $this->debug) Mage::log($debugData, NULL, 'Mediotype.log');
            $this->_debug($debugData);
        } else {
            $debugData = array(
                'request' => $client->__getLastRequest(),
                'result' => array(
                    'error' => '',
                    'code' => '',
                    'xml' => $client->__getLastResponse()
                )
            );
            if (is_array($response->Notifications)) {
                foreach ($response->Notifications as $notification) {
                    $debugData['result']['code'] .= $notification->Code . '; ';
                    $debugData['result']['error'] .= $notification->Message . '; ';
                }
            } else {
                $debugData['result']['code'] = $response->Notifications->Code . ' ';
                $debugData['result']['error'] = $response->Notifications->Message . ' ';
            }
            $this->_debug($debugData);
            $result->setErrors($debugData['result']['error']);
        }
        $result->setGatewayResponse($client->__getLastResponse());

        return $result;
    }

    /**
     * Do request to shipment
     * @param Mage_Shipping_Model_Shipment_Request $request
     * @return array
     */
    public function requestToShipment(Mage_Shipping_Model_Shipment_Request $request)
    {
        $packages = $request->getPackages();
        if (!is_array($packages) || !$packages) {
            Mage::throwException(Mage::helper('usa')->__('No packages for request'));
        }
        if ($request->getStoreId() != null) {
            $this->setStore($request->getStoreId());
        }
        $data = array();
        foreach ($packages as $packageId => $package) {
            $request->setPackageId($packageId);
            $request->setPackagingType($package['params']['container']);
            $request->setPackageWeight($package['params']['weight']);
            $request->setPackageParams(new Varien_Object($package['params']));
            $request->setPackageItems($package['items']);
            $result = $this->_doShipmentRequest($request);

            if ($result->hasErrors()) {
                $this->rollBack($data);
                break;
            } else {
                $data[] = array(
                    'tracking_number' => $result->getTrackingNumber(),
                    'label_content'   => $result->getShippingLabelContent(),
                    'commercial_invoice'   => $result->getCommercialInvoice()
                );
            }
            if (!isset($isFirstRequest)) {
                $request->setMasterTrackingId($result->getTrackingNumber());
                $isFirstRequest = false;
            }
        }

        $response = new Varien_Object(array(
            'info'   => $data
        ));
        if ($result->getErrors()) {
            $response->setErrors($result->getErrors());
        }
        return $response;
    }

    /**
     * @return bool|string
     */
    protected function _getInvoiceImageString(){
        $invoiceImage = Mage::getStoreConfig('shipping/harmonized_tariff_carrier_fedex/logo');
        if(isset( $invoiceImage )){
            $mediaPath = Mage::getBaseDir('media') . DS . "email" . DS . "logo" . DS;
            $fh = fopen( $mediaPath . $invoiceImage, 'r');
            $string = fread($fh, filesize($mediaPath . $invoiceImage) );
            if ($string) {
                return base64_encode($string);
            }
        }
        return false;
    }

    /**
     * @return  bool
     * @desc    Easy check to resume as parent or run module
     */
    protected function _isActive()
    {
        return (Mage::getStoreConfig('shipping/harmonized_tariff_schedule/active') && Mage::getStoreConfig('shipping/harmonized_tariff_carrier_fedex/active')) ? true : false;
    }
}