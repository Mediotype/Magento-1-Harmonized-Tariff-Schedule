<?php
/**
 * Attributes for Harmonized Tariff Schedule Data & Behavior
 * @author  Joel Hart <joel@mediotype.com>
 */
$installer = $this;
/* @var $installer Mage_Eav_Model_Entity_Setup */
$installer->startSetup();

//Harmonized Tariff Schedule Code Assigned To A Product
$installer->removeAttribute('catalog_product', 'hts');
$installer->addAttribute('catalog_product', 'hts',
    array(
        'group' => 'HTS',
        'sort_order' => '0',
        'backend_type' => 'int',
        'input' => "select",
        'label' => 'HTS',
        'required' => '0',
        'user_defined' => false,
        'default_value' => '0',
        'unique' => '0',
        'note' => '',
        'global' => '1',
        'visible' => '1',
        'searchable' => '0',
        'filterable' => '0',
        'comparable' => '0',
        'visible_on_front' => '0',
        'html_allowed_on_front' => '1',
        'used_for_price_rules' => '0',
        'filterable_in_search' => '0',
        'used_in_product_listing' => '0',
        'used_for_sort_by' => '0',
        'configurable' => '0',
        'visible_in_advanced_search' => '0',
        'position' => '0',
        'wysiwyg_enabled' => '0',
        'used_for_promo_rules' => true,
        'search_weight' => '1',
    )
);
$installer->updateAttribute('catalog_product', 'hts', 'source_model', 'hts/entity_attribute_source_code');

//Customs Unit Of Measure
$installer->removeAttribute('catalog_product', 'hts_customs_uom');
$installer->addAttribute('catalog_product', 'hts_customs_uom',
    array(
        'group' => 'HTC',
        'sort_order' => '0',
        'backend_type' => 'text',
        'input' => "text",
        'label' => 'HTC Customs Unit Of Measure',
        'required' => '0',
        'user_defined' => false,
        'default_value' => '0',
        'unique' => '0',
        'note' => '',
        'global' => '1',
        'visible' => '1',
        'searchable' => '0',
        'filterable' => '0',
        'comparable' => '0',
        'visible_on_front' => '0',
        'html_allowed_on_front' => '1',
        'used_for_price_rules' => '0',
        'filterable_in_search' => '0',
        'used_in_product_listing' => '0',
        'used_for_sort_by' => '0',
        'configurable' => '0',
        'visible_in_advanced_search' => '0',
        'position' => '0',
        'wysiwyg_enabled' => '0',
        'used_for_promo_rules' => true,
        'search_weight' => '1',
    )
);

//Customs Unit Quantity Value
$installer->removeAttribute('catalog_product', 'hts_customs_unit_quantity');
$installer->addAttribute('catalog_product', 'hts_customs_unit_quantity',
    array(
        'group' => 'HTS',
        'sort_order' => '0',
        'backend_type' => 'int',
        'input' => "text",
        'label' => 'HTS Customs Unit Quantity',
        'required' => '0',
        'user_defined' => false,
        'default_value' => '0',
        'unique' => '0',
        'note' => '',
        'global' => '1',
        'visible' => '1',
        'searchable' => '0',
        'filterable' => '0',
        'comparable' => '0',
        'visible_on_front' => '0',
        'html_allowed_on_front' => '1',
        'used_for_price_rules' => '0',
        'filterable_in_search' => '0',
        'used_in_product_listing' => '0',
        'used_for_sort_by' => '0',
        'configurable' => '0',
        'visible_in_advanced_search' => '0',
        'position' => '0',
        'wysiwyg_enabled' => '0',
        'used_for_promo_rules' => true,
        'search_weight' => '1',
    )
);


$installer->endSetup();
