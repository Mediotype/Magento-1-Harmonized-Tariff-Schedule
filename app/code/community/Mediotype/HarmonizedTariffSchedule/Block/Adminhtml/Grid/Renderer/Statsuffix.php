<?php
/**
 * Stats Suffix Renderer
 *
 * @author  Joel Hart   <joel@mediotype.com>
 */
class Mediotype_HarmonizedTariffSchedule_Block_Adminhtml_Grid_Renderer_Statsuffix extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $htsEntityId = $row->getData($this->getColumn()->getIndex());
        $model = Mage::getModel('hts/code')->load($htsEntityId);
        return $model->getData('stat_suffix');
    }
}