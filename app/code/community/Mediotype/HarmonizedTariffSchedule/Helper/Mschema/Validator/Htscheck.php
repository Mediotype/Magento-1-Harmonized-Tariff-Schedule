<?php
/**
 * MSchema Validator for HTS import rows associated with products
 *
 * @author Steven Zurek    <steven@mediotype.com>
 */
class Mediotype_HarmonizedTariffSchedule_Helper_Mschema_Validator_Htscheck extends Mediotype_Core_Helper_Mschema_Validator_Abstract
{
    public function Validate($validationObject, &$data)
    {
        $response = new Mediotype_Core_Model_Response(__METHOD__);
        $response->disposition = Mediotype_Core_Model_Response::OK;
        $response->description = "PASSED VALIDATION";

        if ($this->CanRead($validationObject)) {

            if (trim($data) == '') {
                // IF DATA IS BLANK
                $response->description = "FAILED HTS VALIDATION - BLANK DATA PROVIDED";
            } else {
                $code = substr($data, 0, strlen($data) - 2);
                $suffix = substr($data, -2, 2);

                // IF DATA IS NOT BLANK
                $collection = Mage::getModel('mediotype_hts/code')
                    ->getCollection()
                    ->addFieldToFilter("hts_no", $code)
                    ->addFieldToFilter("stat_suffix", $suffix);

                $collection->load();

                if (count($collection) > 0) {
                    $model = $collection->getFirstItem();
                    $data = $model->getId();
                } else {
                    $data = null;
                    $response->description = "FAILED HTS VALIDATION - FAILED LOOKUP";
                }
                unset($model);

            }
        }
        return $response;
    }

    public function getKeyword()
    {
        return "htscheck";
    }
}