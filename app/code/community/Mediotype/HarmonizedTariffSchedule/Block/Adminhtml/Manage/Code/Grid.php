<?php
/**
 * Grid of all Harmonized Tariff Schedule Codes
 *
 * @copyright   Copyright (c) 2014 Mediotype (http://www.mediotype.com)
 *              Copyright, 2014, Mediotype, LLC - US license
 *
 * @author      Joel Hart   <joel@mediotype.com>
 */
class Mediotype_HarmonizedTariffSchedule_Block_Adminhtml_Manage_Code_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('code_grid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('desc');
        $this->setTemplate('mediotype/core/grid.phtml');
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('hts/code')
            ->getCollection();

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {

        $this->addColumn('entity_id', array(
            'header' => Mage::helper('hts')->__('Entity ID'),
            'align' => 'left',
            'index' => 'entity_id',
            'type' => 'text'
        ));

        $this->addColumn('hts_no', array(
            'header' => Mage::helper('hts')->__('HTS No'),
            'align' => 'left',
            'index' => 'hts_no',
            'type' => 'text'
        ));

        $this->addColumn('stat_suffix', array(
            'header' => Mage::helper('hts')->__('Stat Suffix'),
            'align' => 'left',
            'index' => 'stat_suffix',
            'type' => 'text'
        ));

        $this->addColumn('level', array(
            'header' => Mage::helper('hts')->__('Level'),
            'align' => 'left',
            'index' => 'level',
            'type' => 'text'
        ));

        $this->addColumn('description', array(
            'header' => Mage::helper('hts')->__('Description'),
            'align' => 'left',
            'index' => 'description',
            'type' => 'text'
        ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    public function getTableWidth()
    {
        $total = 0;
        foreach ($this->getColumns() as $colId => $column) {
            $total += (INTEGER)rtrim(trim($column->getData('width')), "px");
        }
        return $total;
    }
}