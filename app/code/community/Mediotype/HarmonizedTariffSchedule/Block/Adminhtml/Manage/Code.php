<?php
/**
 * Manage Codes Container
 *
 * @copyright   Copyright (c) 2014 Mediotype (http://www.mediotype.com)
 *              Copyright, 2014, Mediotype, LLC - US license
 *
 * @author      Joel Hart   <joel@mediotype.com>
 */
class Mediotype_HarmonizedTariffSchedule_Block_Adminhtml_Manage_Code extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct() {
        $this->_blockGroup = 'mediotype_htc';
        $this->_controller = 'adminhtml_manage_code';
        $this->_headerText = $this->__('Manage HTS');

        parent::__construct();
    }
}