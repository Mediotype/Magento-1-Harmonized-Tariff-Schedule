<?php
/**
 * Set Base System Configuration At Time Of Install For Mediotype_HarmonizedTariffSchedule
 *
 * @author  Joel Hart <joel@mediotype.com>
 */
/** @var $this Mage_Eav_Model_Entity_Setup */

$this->startSetup();

$configData = array();
$configData += array('scope' => 'default', 'scope_id' => 0, 'path' => 'shipping/harmonized_tariff_schedule/active', 'value' => '1');
$configData += array('scope' => 'default', 'scope_id' => 0, 'path' => 'shipping/harmonized_tariff_schedule/tariff_payor', 'value' => 'RECIPIENT');
$configData += array('scope' => 'default', 'scope_id' => 0, 'path' => 'shipping/harmonized_tariff_schedule/debug', 'value' => '1');
$configData += array('scope' => 'default', 'scope_id' => 0, 'path' => 'shipping/harmonized_tariff_carrier_fedex/tariff_payor', 'value' => 'RECIPIENT');
$configData += array('scope' => 'default', 'scope_id' => 0, 'path' => 'shipping/harmonized_tariff_carrier_fedex/active', 'value' => '1');
$configData += array('scope' => 'default', 'scope_id' => 0, 'path' => 'shipping/harmonized_tariff_carrier_fedex/retrieve_commercial_invoice', 'value' => '1');

foreach ($configData as $configItem) {
    $config = Mage::getModel('core/config_data');
    $config->setData($configItem);
    $config->save();
    unset($config);
}

$this->endSetup();