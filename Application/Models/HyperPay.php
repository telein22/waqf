<?php

namespace Application\Models;

use System\Core\Config;
use System\Core\Model;

class HyperPay extends Model
{

    private $_config;

    public function __construct( $options )
    {
        parent::__construct( $options );

        $this->_config = Config::get("HyperPay");

    }

    public function prepareCheckout( $amount, $merchantTransId,  $user, $type = null )
    {
        $token = $this->_config->access_token;
        $entityId = $this->_config->visa_entity_id;
        $baseURL = $this->_config->baseURL;

        switch ($type) {
            case Payment::METHOD_MADA:
                $entityId = $this->_config->mada_entity_id;
                break;
            case Payment::METHOD_APPLE_PAY:
                $entityId = $this->_config->apple_entity_id;
        }

        $url = "$baseURL/v1/checkouts";
        $data = "entityId=$entityId" .
                    "&amount=" . number_format($amount, 2) .
                    "&currency=SAR" .
                    "&paymentType=DB" .
                    "&merchantTransactionId=" . $merchantTransId .
                    "&customer.email=" . $user['email'];
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                       'Authorization:Bearer ' . $token));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);

        if(curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        return $responseData;
    }

    public function request( $id, $type = null )
    {
        $token = $this->_config->access_token;
        $entityId = $this->_config->visa_entity_id;
        $baseURL = $this->_config->baseURL;

        switch ($type) {
            case Payment::METHOD_MADA:
                $entityId = $this->_config->mada_entity_id;
                break;
            case Payment::METHOD_APPLE_PAY:
                $entityId = $this->_config->apple_entity_id;
        }

        $url = "$baseURL/v1/checkouts/{$id}/payment";
        $url .= "?entityId=$entityId";
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                       'Authorization:Bearer ' . $token));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if(curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        return $responseData;
    }

    public function refund( $id, $amount )
    {
        $baseURL = $this->_config->baseURL;

        $url = "$baseURL/v1/payments/{$id}" ;
        $data = "entityId=8a8294174b7ecb28014b9699220015ca" .
                    "&amount=" .  number_format($amount, 2) .
                    "&currency=EUR" .
                    "&paymentType=RF";
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Authorization:Bearer OGE4Mjk0MTc0YjdlY2IyODAxNGI5Njk5MjIwMDE1Y2N8c3k2S0pzVDg='));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if(curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        return $responseData;
    }


}