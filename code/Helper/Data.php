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

    public function publish($type, $message)
    {
        //todo: check type to see if a separate specific queue should be used
        $client = $this->getSqsClient();

        if(!is_string($message))
        {
            $message = json_encode($message);
        }

        $client->sendMessage(array(
            'QueueUrl' => $this->getConfig('queueUrl'),
            'MessageBody' => $message
        ));
    }

	/**
	 *	Check to make sure messages should be sent to SQS
	 */
	public function isEnabled()
	{
		return (bool)Mage::getStoreConfig('develprsqs/setup/active');
	}

    public function getConfig($configKey)
    {
        $configs = $this->getConfigs();
        if(!array_key_exists($configKey, $configs))
            return false;

        return $configs[$configKey];
    }


    /**
     * Get configs from Magento Admin for the SQSb module as an array so we have them
     *
     * todo: maybe don't do this? not really saving much time..
     * @return array
     */
    public function getConfigs() {

        if(Mage::registry('develpr_sqs_configs'))
            return Mage::registry('develpr_sqs_configs');

        $configs = array(
            'active' => Mage::getStoreConfig('develprsqs/setup/active'),
            'region' => Mage::getStoreConfig('develprsqs/setup/region'),
            'key' => Mage::getStoreConfig('develprsqs/setup/key'),
            'secret' => Mage::getStoreConfig('develprsqs/setup/secret'),
            'queueUrl' => Mage::getStoreConfig('develprsqs/queues/main'),

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

        Mage::register('develpr_sqs_configs', $configs);

        return $configs;
    }

}