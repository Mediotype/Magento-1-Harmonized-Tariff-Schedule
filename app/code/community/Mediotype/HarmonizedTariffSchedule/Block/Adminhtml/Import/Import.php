<?php
/**
 * Admin import grid container
 *
 * @copyright   Copyright (c) 2014 Mediotype (http://www.mediotype.com)
 *              Copyright, 2014, Mediotype, LLC - US license
 *
 * @author      Joel Hart   <joel@mediotype.com>
 */
/**
 * TODO place the export current hts data and import htc data
 */

class Mediotype_HarmonizedTariffSchedule_Block_Adminhtml_Import_Import extends Mage_Adminhtml_Block_Widget_Grid_Container{

    public function __construct() {
        $this->_blockGroup = 'hts';
        $this->_controller = 'adminhtml_import_import';
        $this->_headerText = $this->__('Product / HTS Relationship Manager');

        parent::__construct();
    }
}