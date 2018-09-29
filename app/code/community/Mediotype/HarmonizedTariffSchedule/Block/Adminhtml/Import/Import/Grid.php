<?php
/**
 * Harmonized Tariff Schedule - Import Grid
 *
 * @copyright   Copyright (c) 2014 Mediotype (http://www.mediotype.com)
 *              Copyright, 2014, Mediotype, LLC - US license
 *
 * @author      Joel Hart   <joel@mediotype.com>
 */
class Mediotype_HarmonizedTariffSchedule_Block_Adminhtml_Import_Import_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('product_data_grid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('desc');
        $this->setTemplate('mediotype/core/grid.phtml');
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('catalog/product')->getCollection()
//            ->addAttributeToSelect('*');
            ->addAttributeToSelect('sku')
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('country_of_manufacture')
            ->addAttributeToSelect('hts')
            ->addAttributeToSelect('hts_customs_unit_quantity')
            ->addAttributeToSelect('hts_customs_uom');

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {

        $this->addColumn('entity_id', array(
            'header' => Mage::helper('hts')->__('Product ID'),
            'align' => 'left',
            'index' => 'entity_id',
            'type' => 'text'
        ));

        $this->addColumn('sku', array(
            'header' => Mage::helper('hts')->__('SKU'),
            'align' => 'left',
            'index' => 'sku',
            'type' => 'text'
        ));

        $this->addColumn('name', array(
            'header' => Mage::helper('hts')->__('Name'),
            'align' => 'left',
            'index' => 'name',
            'type' => 'text'
        ));

        $this->addColumn('country_of_manufacture', array(
            'header' => Mage::helper('hts')->__('Country Of Manufacture'),
            'align' => 'left',
            'index' => 'country_of_manufacture',
            'type' => 'text'
        ));


        $this->addColumn('hts', array(
            'header' => Mage::helper('hts')->__('HTS'),
            'align' => 'left',
            'index' => 'htc',
            'type' => 'text',
            'renderer' => "hts/adminhtml_grid_renderer_hts"
        ));

        $this->addColumn('stat_suffix', array(
            'header' => Mage::helper('hts')->__('Stat Suffix'),
            'align' => 'left',
            'index' => 'htc',
            'type' => 'text',
            'renderer' => "hts/adminhtml_grid_renderer_statsuffix"
        ));

        $this->addColumn('hts_customs_uom', array(
            'header' => Mage::helper('hts')->__('Customs Unit Of Measure'),
            'align' => 'left',
            'index' => 'htc_customs_uom',
            'type' => 'text'
        ));

        $this->addColumn('hts_customs_unit_quantity', array(
            'header' => Mage::helper('hts')->__('Customs Unit Quantity'),
            'align' => 'left',
            'index' => 'hts_customs_unit_quantity',
            'type' => 'text'
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('hts')->__('CSV'));


        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('adminhtml/catalog_product/edit', array('id' => $row->getId()));
    }

    public function getTableWidth()
    {
        $total = 0;
        foreach ($this->getColumns() as $colId => $column) {
            $total += (INTEGER)rtrim(trim($column->getData('width')), "px");
        }
        return $total;
    }

    public function getCsvFile()
    {

        $this->_isExport = true;
        $this->_prepareGrid();

        $this->removeColumn('entity_id');
        $this->getColumn('sku')->setData("header", "sku");
        $this->getColumn('name')->setData("header", "name");
        $this->getColumn('country_of_manufacture')->setData("header", "country_of_manufacture");
        $this->getColumn('hts')->setData("header", "hts");
        $this->removeColumn('stat_suffix');
        $this->getColumn('hts_customs_uom')->setData("header", "hts_customs_uom");
        $this->getColumn('hts_customs_unit_quantity')->setData("header", "hts_customs_unit_quantity");

        $io = new Varien_Io_File();

        $path = Mage::getBaseDir('var') . DS . 'export' . DS;
        $name = md5(microtime());
        $file = $path . DS . $name . '.csv';

        $io->setAllowCreateFolders(true);
        $io->open(array('path' => $path));
        $io->streamOpen($file, 'w+');
        $io->streamLock(true);
        $io->streamWriteCsv($this->_getExportHeaders());

        $this->_exportIterateCollection('_exportCsvItem', array($io));

        if ($this->getCountTotals()) {
            $io->streamWriteCsv($this->_getExportTotals());
        }

        $io->streamUnlock();
        $io->streamClose();

        return array(
            'type'  => 'filename',
            'value' => $file,
            'rm'    => true // can delete file after use
        );


    }


}