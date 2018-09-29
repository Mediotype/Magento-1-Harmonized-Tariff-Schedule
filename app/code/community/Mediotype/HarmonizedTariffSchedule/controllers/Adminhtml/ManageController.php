<?php
/**
 * Admin Form for Harmonized Tariff Schedule Codes
 *
 * @author      Joel Hart   <joel@mediotype.com>
 */
class Mediotype_HarmonizedTariffSchedule_Adminhtml_ManageController extends Mage_Adminhtml_Controller_Action{

    public function indexAction()
    {
        $this->loadLayout()->renderLayout();
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $this->loadLayout();

        // Get id if available
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('hts/code')->load($id);

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError($this->__('This HTS no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
        }

        $this->_title($model->getId() ? $this->__('HTS') : $this->__('New HTS Code'));

        $data = Mage::getSingleton('adminhtml/session')->getHTSData(true);

        if (!empty($data)) {
            $model->setData($data);
        }

        Mage::register('mediotype_hts_code', $model);

        $block = $this->getLayout()->createBlock('hts/adminhtml_manage_code_edit')->setData('action', $this->getUrl('*/*/save'));
        $this->loadLayout()
            ->_addContent($block);
        $this->renderLayout();
    }

    public function saveAction(){
        if ($postData = $this->getRequest()->getPost()) {
            Mage::log($postData);
            $model = Mage::getModel('hts/code');
            $model->setData($postData);

            try {
                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess($this->__('The HTS information has been saved.'));
                $this->_redirect('*/*/');

                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addWarning($e->getMessage());
            }

            Mage::getSingleton('adminhtml/session')->setDealerData($postData);
            $this->_redirectReferer();
        }
    }
}