<?php

class Develpr_Sqs_Model_Container_Customer extends Develpr_Sqs_Model_Container_Abstract
{
    /**
     * Process the object as needed to extract information of interest. This is an method
     * to allow child classes to handle their own conversion.
     *
     * @param $this->model
     * @return mixed
     */
    protected function process()
    {
        /** @var $this->model Mage_Customer_Model_Customer */

        $this->messageData->setId($this->model->getId());

        $this->messageData->setFirstName($this->model->getFirstname());
        $this->messageData->setLastName($this->model->getLastname());
        $this->messageData->setName($this->model->getName());

        $this->messageData->setGroupId($this->model->getGroupId());
        $this->messageData->setStoreId($this->model->getStoreId());
        $this->messageData->setActive($this->model->getIsActive());

        $this->messageData->setEmail($this->model->getEmail());

        //todo: possibly include the shipping/billing address or address ids?

        //fin.

    }

    protected function getConcreteQueueUrl()
    {
        return $this->helper->getConfig('customerQueue');
    }


}