<?php

/**
 * Class Develpr_SQS_Model_Converter_Customer
 */
abstract class Develpr_Sqs_Model_Container_Abstract
{
    protected $messageData;
    protected $model;

    /** @var Develpr_Sqs_Helper_Data $helpre */
    protected $helper;

    public function __construct()
    {
        $this->messageData = new Varien_Object;
        $this->model = null;
        $this->helper = Mage::helper('develprsqs');
    }


    /**
     * I could have used the second parameter in the Mage::model factory method to pass the
     * model into the constructor but I'm not a huge fan of this as it's not totally clear
     * that it's a constructor parameter so requiring a separate method is a bit clearer.
     *
     * @param $model The model that should be converted
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * Process the object as needed to extract information of interest. This is an abstract method
     * to allow child classes to handle their own conversion.
     *
     * @param $toConvert
     * @return mixed
     */
    abstract protected function process();
    abstract protected function getConcreteQueueUrl();

    public function getQueueUrl()
    {
        if(!$this->model)
            throw new Exception("No model set");

        if( ! (bool)$this->helper->getConfig("separateQueues"))
            $queueUrl =  $this->helper->getConfig('mainQueue');

        else
            $queueUrl = $this->getConcreteQueueUrl();

        //The ONLY reason we're doing this is to have slightly shorter event names.
        //todo: this might add confusion, might be clearer just to stick with the name of the model
        $eventName = strtolower(get_class($this->model));
        $eventName = str_replace('mage_', '', $eventName);
        $eventName = str_replace('model_', '', $eventName);

        Mage::dispatchEvent('develpr_sqs_' . $eventName . '_queue_url_before', array('model' => $this->model, 'queueUrl' => $queueUrl));

        return $queueUrl;

    }

    /**
     * Convert an object to a json string
     *
     * @param $toConvert
     */
    public function getMessageData()
    {
        $this->process();

        if(!$this->model)
            throw new Exception("No model set");

        //The ONLY reason we're doing this is to have slightly shorter event names.
        //todo: this might add confusion, might be clearer just to stick with the name of the model
        $eventName = strtolower(get_class($this->model));
        $eventName = str_replace('mage_', '', $eventName);
        $eventName = str_replace('model_', '', $eventName);

        Mage::dispatchEvent('develpr_sqs_' . $eventName . '_message_data_before', array('customer' => $this->model, 'message_data' => $this->messageData));

        //We need to convert the keys to camel case, which is stupid/annoying, but would prefer this to camel case
        $array = $this->transformKeys($this->messageData->toArray());

        return $array;

    }

    /**
     * Convert keys from snake_case to camelCase
     *
     * @param $array
     * @return mixed
     */
    private function transformKeys($array)
    {
        foreach (array_keys($array) as $key)
        {
            $value = $array[$key];
            unset($array[$key]);
            $key = str_replace(' ', '', ucwords(str_replace('_', ' ', ltrim($key, '!'))));
            $key = strtolower(substr($key,0,1)).substr($key,1);
            if (is_array($value)) $this->transformKeys($value);
            $array[$key] = $value;
            unset($value);
        }

        return $array;
    }


}