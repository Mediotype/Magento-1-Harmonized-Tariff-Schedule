<?php
/**
 * Import Grid Instructions
 *
 * @copyright   Copyright (c) 2014 Mediotype (http://www.mediotype.com)
 *              Copyright, 2014, Mediotype, LLC - US license
 *
 * @author      Joel Hart   <joel@mediotype.com>
 *
 *  <p>     For complete module instructions please visit
 *      <a href='http://wiki.mediotype.com/magento/hts'>Mediotype's Magento Harmonized Tariff Schedule Module Wiki</a>
 *  </p>
 */
class Mediotype_HarmonizedTariffSchedule_Block_Adminhtml_Import_Import_Instructions extends Mediotype_Core_Block_Instructions{

    protected $_endPoint = "";
    protected $_jsonRequest = "";

    public function __construct(){
        $this->_endPoint = Mage::getStoreConfig('mediotype/instructions/hts_import');
        parent::_construct();
    }

    protected function _offlineHtml(){
        $html = "";

        $html .= "
            <p>
                    Export this grid to CSV. Populate missing HTS parameters (completely) and then import the appended CSV through this interface.
            </p>
        ";

        return $html;
    }

}