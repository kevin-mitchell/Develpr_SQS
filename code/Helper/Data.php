<?php
/**
 * @package Develpr_Sqs
 * @author Kevin Mitchell <kevin@develpr.com>
 * @copyright Massachusetts Institute of Technology License (MITL)
 * @license  http://opensource.org/licenses/MIT
 */

//require(Mage::getBaseDir('lib') . '/aws/build/aws-autoloader.php');
require(Mage::getBaseDir('lib') . '/develpr_sqs/aws.phar');

$autoloadFuncs = spl_autoload_functions();
$awsCallback = false;
foreach ($autoloadFuncs as $callback) {
    if (is_array($callback) && get_class($callback[0]) == 'Symfony\Component\ClassLoader\UniversalClassLoader') {
        $awsCallback = $callback;
        break;
    }
}
if ($awsCallback) {
    spl_autoload_unregister($awsCallback);
    spl_autoload_register($awsCallback, true, true);
}
$autoloadFuncs = spl_autoload_functions();


class Develpr_Sqs_Helper_Data extends Mage_Core_Helper_Abstract {


    public function getSqsClient()
    {
        $configs = $this->getConfigs();

        $sqsClient = Aws\Sqs\SqsClient::factory(array(
            'region' => $configs['region'],
            'key'    => $configs['key'],
            'secret' => $configs['secret'],
        ));

        return $sqsClient;
    }
	
	/**
	 *	
	 */
	public function isEnabled()
	{
		return (bool)Mage::getStoreConfig('develprsqs/setup/active');
	}


    /**
     * Get configs from Magento Admin for the MQ module as an array
     *
     * @return array
     */
    public function getConfigs() {
        $configs = array(
            'active' => Mage::getStoreConfig('develprsqs/setup/active'),
            'region' => Mage::getStoreConfig('develprsqs/setup/region'),
            'key' => Mage::getStoreConfig('develprsqs/setup/key'),
            'secret' => Mage::getStoreConfig('develprsqs/setup/secret'),
            'queueUrl' => Mage::getStoreConfig('develprsqs/setup/queueUrl'),

            'orderActive' => Mage::getStoreConfig('develprsqs/triggers/order_active'),
            'customerActive' => Mage::getStoreConfig('develprsqs/triggers/customer_active'),
            'customerAddressActive' => Mage::getStoreConfig('develprsqs/triggers/customer_address_active'),
            'invoiceActive' => Mage::getStoreConfig('develprsqs/triggers/invoice_active'),
            'shipmentActive' => Mage::getStoreConfig('develprsqs/triggers/shipment_active'),
            'creditMemoActive' => Mage::getStoreConfig('develprsqs/triggers/credit_memo_active'),
            'productActive' => Mage::getStoreConfig('develprsqs/triggers/product_active'),
			
            'orderQueue' => Mage::getStoreConfig('develprsqs/queues/order'),
            'customerQueue' => Mage::getStoreConfig('develprsqs/queues/customer'),
            'customerAddressQueue' => Mage::getStoreConfig('develprsqs/queues/customer_address'),
            'invoiceQueue' => Mage::getStoreConfig('develprsqs/queues/invoice'),
            'shipmentQueue' => Mage::getStoreConfig('develprsqs/queues/shipment'),
            'creditMemoQueue' => Mage::getStoreConfig('develprsqs/queues/credit_memo'),
            'productQueue' => Mage::getStoreConfig('develprsqs/queues/product'),
        );

        return $configs;
    }


}