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
        $configs = $helper->getConfigs();

        if($configs['orderActive'] == true) {
			// $helper->publish()
        }
    }

    public function enqueueCustomer($observer) {

        /** @var Develpr_Sqs_Helper_Data $helper */
        $helper = Mage::helper('develprsqs');
        $configs = $helper->getConfigs();

        if(!$configs['customerActive'])
            return;

        //Converting observer to a proper customer object
        if($observer instanceof Varien_Event_Observer) {
            $customer = $observer->getEvent()->getCustomer();
        } else if($observer instanceof Varien_Object) {
            $customer = $observer;
        }
        /** @var Mage_Customer_Model_Customer $customer */

        //Convert Customer object to flatter array
        /** @var Develpr_Sqs_Model_Container_Customer $container */
        $container = Mage::getModel('develprsqs/container_customer');

        $container->setModel($customer);

        $helper->publish($container);

    }

}
