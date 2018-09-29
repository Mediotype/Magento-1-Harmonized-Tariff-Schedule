<?php
/**
 * HTS Code Renderer
 *
 * @author  Joel Hart   <joel@mediotype.com>
 */
class Mediotype_HarmonizedTariffSchedule_Block_Adminhtml_Grid_Renderer_Hts extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $htc_entity_id = $row->getData($this->getColumn()->getIndex());
        $model = Mage::getModel('hts/code')->load($htc_entity_id);
        return $model->getData('hts_no');
    }

    public function renderExport(Varien_Object $row)
    {
        $htc_entity_id = $row->getData($this->getColumn()->getIndex());
        $model = Mage::getModel('hts/code')->load($htc_entity_id);
        return $model->getData('hts_no') . $model->getData('stat_suffix');
    }
}