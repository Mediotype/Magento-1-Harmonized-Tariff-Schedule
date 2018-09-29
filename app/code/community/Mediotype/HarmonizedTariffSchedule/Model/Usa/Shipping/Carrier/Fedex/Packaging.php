<?php

/**
 * Custom packaging data to use for shipping. TODO Build an admin view and store these in the database
 *
 * @author  Joel Hart   <joel@mediotype.com>
 */
class Mediotype_HarmonizedTariffSchedule_Model_Usa_Shipping_Carrier_Fedex_Packaging
{

    /** @var array Label for your packaging if selecting one of the packages below */
    protected $_customizableContainerTypes = array('YOUR_PACKAGING');

    /** @var array Array of package specifications */
    protected $_customContainerSpecs = array(
        "SINGLE FRAME" => array(
            "length" => 9,
            "width" => 47,
            "height" => 24),
        "KORVU" => array(
            "length" => 12,
            "width" => 46,
            "height" => 29),
        "KIT BOX" => array("length" => 10,
            "width" => 29,
            "height" => 28),
        "TRI PAK" => array("length" => 15,
            "width" => 47,
            "height" => 23),
        "REAR TRI BOX" => array("length" => 8,
            "width" => 20,
            "height" => 12),
        "FRONT TRI BOX" => array("length" => 7,
            "width" => 36,
            "height" => 25),
        "SMALL PADDED ENVELOPE" => array("length" => 1,
            "width" => 10,
            "height" => 6),
        "MEDIUM ENVELOPE (WHITE)" => array("length" => 2,
            "width" => 15,
            "height" => 15),
        "SMALL PARTS BOX" => array("length" => 6,
            "width" => 8,
            "height" => 8),
        "LARGE PADDED ENVELOPE" => array("length" => 1,
            "width" => 19,
            "height" => 13),
        "MEDIUM PARTS BOX" => array("length" => 4,
            "width" => 14,
            "height" => 12),
        "LARGE PARTS BOX" => array("length" => 8,
            "width" => 14,
            "height" => 10),
        "XL PARTS BOX" => array("length" => 8,
            "width" => 20,
            "height" => 12),
        "WHITE BAG SMALL" => array("length" => 1,
            "width" => 12,
            "height" => 9),
        "WHITE BAG LARGE" => array("length" => 1,
            "width" => 16,
            "height" => 12)
    );

    public function getCustomPackagingLabels()
    {
        $labelForAdminSelect = array();
        foreach ($this->_customContainerSpecs as $key => $value) {
            $labelForAdminSelect[$key] = ucwords(strtolower($key));
        }

        return $labelForAdminSelect;
    }

    public function getCustomPackagingData(){
        return $this->_customContainerSpecs;
    }

}