<?php

use System\Core\Config;

$hyperpay = Config::get("HyperPay");
$hyperpay->set([

    // STC ENTITY ID WILL BE SAME AS VISA ENTITY ID
     'visa_entity_id' => $_ENV['HYPERPAY_VISA_ENTITY_ID'],
     'mada_entity_id' => $_ENV['HYPERPAY_MADA_ENTITY_ID'],
     'apple_entity_id' => $_ENV['HYPERPAY_APPLE_ENTITY_ID'],
     'access_token' => $_ENV['HYPERPAY_ACCESS_TOKEN'],

     'baseURL' => $_ENV['HYPERPAY_BASE_URL'],
]);