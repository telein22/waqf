<?php

namespace Application\Commands;

use Application\Models\Queue as ModelsQueue;
use Exception;
use System\Core\CLICommand;
use System\Core\Model;
use Application\Models\Order;
use Application\Models\Call;
use Application\Models\Conversation;
use Application\Models\HyperPay;
use Application\Models\Message;
use Application\Models\Notification;
use Application\Models\Payment;
use Application\Models\Workshop;

class Refund extends CLICommand
{
    public function run( $params )
    {
       /**
         * @var \Application\Models\Payment
         */
        $paymentM = Model::get('\Application\Models\Payment');
        $payments = $paymentM->all(null, Payment::STATUS_REFUND_INITIATED);

        foreach( $payments as $payment )
        {
            $this->_update( $payment );
        }

    }

    public function _update( $payment )
    {
        $gatewayData = json_decode($payment['gateway_data'], true);        

        /**
         * @var \Application\Models\Payment
         */
        $paymentM = Model::get('\Application\Models\Payment');

        if ( $payment['paid'] == 0 ) {
            $paymentM->update($payment['id'], [
                'status' => Payment::STATUS_REFUNDED
            ]);
        }

        if ( !isset($gatewayData['id']) ) return;

        $id = $gatewayData['id'];
        /**
         * @var HyperPay
         */
        $hyperPay = Model::get(HyperPay::class);
        $result = $hyperPay->refund($id, $payment['paid']);
        $result = json_decode($result, true);

        if ( isset($result['result']) && $result['result']['code'] === "000.100.110" )
        {
            $paymentM->update($payment['id'], [
                'status' => Payment::STATUS_REFUNDED
            ]);
        }
    }
}