<?php
/**
 * Form for a new or edited HTS code
 *
 * @copyright   Copyright (c) 2014 Mediotype (http://www.mediotype.com)
 *              Copyright, 2014, Mediotype, LLC - US license
 *
 * @author      Joel Hart   <joel@mediotype.com>
 */
class Mediotype_HarmonizedTariffSchedule_Block_Adminhtml_Manage_Code_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    public function __construct()
    {
        parent::__construct();
        $this->setId("mediotype_hts_manage_code_form");
        $this->setTitle($this->__("HTS Code"));
    }

    protected function _prepareForm()
    {
        $model = Mage::registry('mediotype_hts_code');

        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
            'method' => 'post'
        ));

        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend' => Mage::helper('hts')->__('HTS Information'),
            'class' => 'fieldset-wide',
        ));

        if ($model->getId()) {
            $fieldset->addField('entity_id', 'hidden', array(
                'name' => 'entity_id',
            ));
        }

        $fieldset->addField('hts_no', 'text', array(
            'name' => 'hts_no',
            'label' => Mage::helper('hts')->__('HTS No'),
            'title' => Mage::helper('hts')->__('HTS No'),
            'required' => true
        ));

        $fieldset->addField('stat_suffix', 'text', array(
            'name' => 'stat_suffix',
            'label' => Mage::helper('hts')->__('Stat Suffix'),
            'title' => Mage::helper('hts')->__('Stat Suffix'),
            'required' => false
        ));

        $fieldset->addField('level', 'text', array(
            'name' => 'level',
            'label' => Mage::helper('hts')->__('Level'),
            'title' => Mage::helper('hts')->__('Level'),
            'required' => false
        ));

        $fieldset->addField('description', 'text', array(
            'name' => 'description',
            'label' => Mage::helper('hts')->__('Description'),
            'title' => Mage::helper('hts')->__('Description'),
            'required' => false
        ));

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();

    }
}