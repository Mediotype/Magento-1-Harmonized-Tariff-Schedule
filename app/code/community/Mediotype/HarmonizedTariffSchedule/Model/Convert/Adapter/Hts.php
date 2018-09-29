<?php
/**
 * Import adapter for importing HTS codes to associate with product data
 *
 * @copyright   Copyright (c) 2014 Mediotype (http://www.mediotype.com)
 *              Copyright, 2014, Mediotype, LLC - US license
 * @author      Joel Hart <joel@mediotype.com>
 */
class Mediotype_HarmonizedTariffSchedule_Model_Convert_Adapter_Hts extends Mediotype_Import_Model_Convert_Adapter_Abstract
{
    protected $validator = null;

    public function __construct(){
        $this->validator = Mage::helper('mediotype_core/mschema');
        $this->validator->LoadSchema(Mage::getModuleDir('', 'Mediotype_HarmonizedTariffSchedule') . "/Schema/products_harmoized_tariff_schedule.json");
        $this->validator->addValidator('hts/mschemavalidator_htscheck');

        $this->_dataLoader = Mage::helper('mediotype_import/dataLoader');
        $this->_dataLoader->addStrategy('mediotype_import/strategy_cache');
        $this->_dataLoader->addStrategy('mediotype_import/strategy_lookup');
    }

    public function saveRow($rowData)
    {
        Mage::app()->setCurrentStore(0);
        $this->_currentRecord = null;
        $_funcResponse = new Mediotype_Core_Model_Response(__METHOD__);
        $_funcResponse->startTimer();

        try {
            // VALIDATE DATA
            if (!is_array($rowData)) {
                throw new Exception("No data supplied");
            }

            $this->_currentRecord = json_decode(json_encode($rowData));
            $this->_handleResponse($this->validator->Validate($this->_currentRecord));

            $data = array();
            $data['sku'] = $this->_currentRecord->sku;
            $response = $this->_handleResponse($this->_dataLoader->getProduct($data));
            /** @var $product Mage_Catalog_Model_Product */
            $product = $response->data;
            $data = array(
                "hts_customs_uom" => $this->_currentRecord->hts_customs_uom,
                "hts" => $this->_currentRecord->hts,
                "country_of_manufacture" => $this->_currentRecord->country_of_manufacture
            );

            if(isset($this->_currentRecord->hts_customs_unit_quantity)){
                $data["hts_customs_unit_quantity"] = $this->_currentRecord->hts_customs_unit_quantity;
            } else {
                $data["hts_customs_unit_quantity"] = 1;
            }

            foreach($data as $index => $value){
                $product->setData($index, $value);
            }

            $product->save();


        } catch (Exception $e) {
            Mediotype_Core_Helper_Debugger::log("ERROR RECORD", $this->_currentRecord);

            $_funcResponse->data = $this->_currentRecord;
            $_funcResponse->disposition = Mediotype_Core_Model_Response::FATAL;
            $_funcResponse->description = $e->getMessage();
        }
        $_funcResponse->stopTimer();
        Mediotype_Core_Helper_Debugger::log($_funcResponse);

        return TRUE;
    }

    public function load()
    {
        return NULL;
    }

    public function save()
    {
        return NULL;
    }
}