<?php
/**
 * @package Develpr_Sqs
 * @author Kevin Mitchell <kevin@develpr.com>
 * @copyright Massachusetts Institute of Technology License (MITL)
 * @license  http://opensource.org/licenses/MIT
 * Class Develpr_Sqs_Model_Order_Confirmation_Observer
 */
class Develpr_Sqs_Model_Observer {

    /**
      * This method is invoked when an order placement event is fired off
      */
    public function enqueueOrder($observer) {
		
        //Converting observer to a proper order object
        if($observer instanceof Varien_Event_Observer) {
            $order = $observer->getEvent()->getOrder();
        } else if($observer instanceof Varien_Object) {
            $order = $observer;
        }
		
		
		$helper = Mage::helper('develprsqs');
        $configs =$helper->getConfigs();

        if($configs['order_active'] == true) {
			// $helper->publish()
        }
    }
	
}
