<?php
/**
 * Edit Container
 *
 * @copyright   Copyright (c) 2014 Mediotype (http://www.mediotype.com)
 *              Copyright, 2014, Mediotype, LLC - US license
 *
 * @author      Joel Hart   <joel@mediotype.com>
 */
class Mediotype_HarmonizedTariffSchedule_Block_Adminhtml_Manage_Code_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {
        $this->_blockGroup = 'hts';
        $this->_controller = 'adminhtml_manage_code';

        parent::__construct();

        $this->_updateButton('save', 'label', $this->__('Save HTS'));
        $this->_updateButton('delete', 'label', $this->__('Delete HTS'));
    }

    public function getHeaderText(){
        return $this->__("HTS Information");
    }
}