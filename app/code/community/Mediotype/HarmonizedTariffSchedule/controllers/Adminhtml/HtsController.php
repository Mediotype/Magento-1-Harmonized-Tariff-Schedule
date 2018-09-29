<?php
/**
 * Handles grid & data for Harmonized Tariff Schedule codes
 *
 * @copyright   Copyright (c) 2014 Mediotype (http://www.mediotype.com)
 *              Copyright, 2014, Mediotype, LLC - US license
 *
 * @author      Joel Hart   <joel@mediotype.com>
 */
class Mediotype_HarmonizedTariffSchedule_Adminhtml_HtsController extends Mage_Adminhtml_Controller_Action
{

    public function indexAction()
    {
        $this->loadLayout()->renderLayout();
    }

    /**
     * Export Csv Action
     *
     */
    public function exportCsvAction()
    {
        $fileName = 'hts_product_data_export.csv';
        /** @var $content  Mediotype_HarmonizedTariffSchedule_Block_Adminhtml_Import_Import_Grid */
        $content = $this->getLayout()
            ->createBlock('hts/adminhtml_import_import_grid')
            ->getCsvFile();

        $this->_prepareDownloadResponse($fileName, $content);
        $this->_redirectReferer();
        return;
    }

    public function postCsvAction()
    {
        if (isset($_FILES['fileinputname']['name']) and (file_exists($_FILES['fileinputname']['tmp_name']))) {

            try {
                $uploader = new Varien_File_Uploader('fileinputname');
                $uploader->setAllowedExtensions(array('csv', 'CSV'));
                $uploader->setAllowRenameFiles(false);
                $uploader->setFilesDispersion(false);
                $path = Mage::getBaseDir('var') . DS . 'import' . DS;
                $uploader->save($path, $_FILES['fileinputname']['name']);

                $fullPathAndFile = $path . $_FILES['fileinputname']['name'];
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addWarning($e->getMessage());
                $this->_redirectReferer();
            }

            $import = Mage::getModel('hts/import_io');
            $import->setImportFile($fullPathAndFile);
            $strategy = Mage::getModel('hts/convert_adapter_hts');

            try {
                $import->importDataByRow($strategy);
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addWarning("No File Was Provided For Processing");
                $this->_redirectReferer();
            }

        } else {
            Mage::getSingleton('adminhtml/session')->addWarning("No File Was Provided For Processing");
            $this->_redirectReferer();
        }

        Mage::getSingleton('adminhtml/session')->addSuccess("Import Process Completed.");
        $this->_redirectReferer('*/*/');
    }
}