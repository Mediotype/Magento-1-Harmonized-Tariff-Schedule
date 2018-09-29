<?php
/**
 * Harmonized Tariff Schedule - Import Edit Form
 *
 * @copyright   Copyright (c) 2014 Mediotype (http://www.mediotype.com)
 *              Copyright, 2014, Mediotype, LLC - US license
 *
 * @author      Joel Hart   <joel@mediotype.com>
 */
class Mediotype_HarmonizedTariffSchedule_Block_Adminhtml_Import_Import_Edit_Form extends Mage_Adminhtml_Block_Widget_Form{

    public function __construct()
    {
        parent::__construct();
        $this->setId("harmonized_tariff_product_upload");
        $this->setDestElementId('harmonized_tariff_product_upload');
        $this->setTitle($this->__("HTS"));
    }

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getUrl('hts/adminhtml_htc/postCsv'),
            'method' => 'post',
            'enctype' => 'multipart/form-data'
        ));

        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend' => Mage::helper('hts')->__('HTS Information'),
            'class' => 'fieldset-wide'
        ));

        $fieldset->addField('fileinputname', 'image', array(
            'label'     => Mage::helper('hts')->__('CSV To Upload'),
            'required'  => false,
            'name'      => 'fileinputname'
        ));

        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }

}