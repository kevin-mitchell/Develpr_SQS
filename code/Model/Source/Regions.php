<?php


/**
 * A list of valid regions as of May 16, 2014
 *
 */
class Develpr_Sqs_Model_Source_Regions
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 'us-east-1', 'label'=>Mage::helper('develprsqs')->__('US East (Northern Virginia)')),
            array('value' => 'us-west-1', 'label'=>Mage::helper('develprsqs')->__('US West (Northern California)')),
            array('value' => 'us-west-2', 'label'=>Mage::helper('develprsqs')->__('US West (Oregon)')),
            array('value' => 'eu-west-1', 'label'=>Mage::helper('develprsqs')->__('EU (Ireland)')),
            array('value' => 'ap-southeast-1', 'label'=>Mage::helper('develprsqs')->__('Asia Pacific (Singapore)')),
            array('value' => 'ap-southeast-2', 'label'=>Mage::helper('develprsqs')->__('Asia Pacific (Sydney)')),
            array('value' => 'ap-northeast-1', 'label'=>Mage::helper('develprsqs')->__('Asia Pacific (Tokyo)')),
            array('value' => 'sa-east-1', 'label'=>Mage::helper('develprsqs')->__('South America (Sao Paulo)'))
        );
    }

}
