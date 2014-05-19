<?php
/**
 * @package Develpr_Sqs
 * @author Kevin Mitchell <kevin@develpr.com>
 * @copyright Massachusetts Institute of Technology License (MITL)
 * @license  http://opensource.org/licenses/MIT
 */

$sales_setup = new Mage_Sales_Model_Mysql4_Setup('sales_setup');
$sales_setup->addAttribute('order', 'sqs_status', array('type'=>'varchar', 'default_value'=> null));
