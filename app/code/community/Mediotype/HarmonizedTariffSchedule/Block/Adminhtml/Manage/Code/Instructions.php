<?php
/**
 * HarmonizedTariffSchedule Instructions Block
 *
 * @copyright   Copyright (c) 2014 Mediotype (http://www.mediotype.com)
 *              Copyright, 2014, Mediotype, LLC - US license
 * @license
 * @author      Joel Hart   <joel@mediotype.com>
 *
 *
 *  <p>     For complete module instructions please visit
 *      <a href='http://wiki.mediotype.com/magento/hts'>Mediotype's Magento Harmonized Tarriff Schedule Module Wiki</a>
 *  </p>
 */
class Mediotype_HarmonizedTariffSchedule_Block_Adminhtml_Manage_Code_Instructions extends Mediotype_Core_Block_Instructions{

    protected $_endPoint = "";

    public function __construct(){
        $this->_endPoint = Mage::getStoreConfig('mediotype/instructions/hts_manage');
        parent::_construct();
    }

    protected function _offlineHtml(){
        $html = "";

        $html .= "
            <p>
                    Sometimes HTS codes will be presented as ####.##.#### this should be ####.##.##.##
                    The last two digits in this scenario represent the stat suffix
            </p>
        ";

        return $html;
    }

}