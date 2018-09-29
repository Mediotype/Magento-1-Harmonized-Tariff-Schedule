<?php
/**
 * Harmonized Tariff Schedule - Import Edit Container
 *
 * @copyright   Copyright (c) 2014 Mediotype (http://www.mediotype.com)
 *              Copyright, 2014, Mediotype, LLC - US license
 *
 * @author      Joel Hart   <joel@mediotype.com
 */
class Mediotype_HarmonizedTariffSchedule_Block_Adminhtml_Import_Import_Edit extends Mage_Adminhtml_Block_Widget_Form_Container{

    public function __construct() {
        $this->_blockGroup = 'hts';
        $this->_controller = 'adminhtml_import_import';

        parent::__construct();

        $this->_updateButton('save', 'label', $this->__('Process CSV'));
    }

    public function getHeaderText(){
        return $this->__("Import HTS data by product SKU CSV File");
    }
}