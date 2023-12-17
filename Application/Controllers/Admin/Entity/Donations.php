<?php

namespace Application\Controllers\Admin\Entity;

use Application\Helpers\ConversationHelper;
use Application\Helpers\DateHelper;
use Application\Helpers\OrderHelper;
use Application\Helpers\ServiceHelper;
use Application\Helpers\Traits\Exportable;
use Application\Helpers\TransferHelper;
use Application\Helpers\WorkshopHelper;
use Application\Main\EntityController;
use Application\Models\Call;
use Application\Models\Conversation;
use Application\Models\Order;
use Application\Models\UserSettings;
use Application\Models\Workshop;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Responses\File;
use System\Responses\View;

class Donations extends EntityController
{
    use Exportable;

    public function index( Request $request, Response $response )
    {
        $entityInfo = $this->user->getInfo();
        $associates = $this->user->getAssociates($entityInfo['id']);
        $associatesIds = array_column($associates, 'id');
        $orders = [];

        if (!empty($associatesIds)) {
            $from = $request->get('from') ? strtotime($request->get('from') . ' 00:00:00') : strtotime("-30 days");
            $to = $request->get('to') ? strtotime($request->get('to') . ' 23:59:59') : time();

            $status = $request->get('status') ? $request->get('status') : null;

            $type = $request->get('type') ? $request->get('type') : null;


            /**
             * @var \Application\Models\Order
             */
            $orderM = Model::get(Order::class);
            $orders = $orderM->getOrders($associatesIds,$type,null,$status,null,null,null, $from, $to);
            $orders = OrderHelper::prepare( $orders );
        }

        $view = new View();
        $view->set('Admin/Billings/Entity/index', [
            'userInfo' => $entityInfo,
            'orders' => $orders,
            'from' => $from,
            'to' => $to,
            'status' => $status,
            'type' => $type
        ]);
        $view->prepend('Admin/header', [
            'title' => "Welcome to telein",
            'currentPage' => 'Orders',
            'userInfo' => $entityInfo
        ]);
        $view->append('Admin/footer');

        $response->set($view);
    }

    public function csv( Request $request, Response $response )
    {
        /**
         * @var \Application\Models\Language
         */
        $lang = Model::get('\Application\Models\Language');
        $userInfo = $this->user->getInfo();

        $from = $request->get('from') ? $request->get('from') : strtotime("-30 days");
        $to = $request->get('to') ? $request->get('to') : time();

        $status = $request->get('status') ? $request->get('status') : null;

        $type = $request->get('type') ? $request->get('type') : null;

        /**
         * @var \Application\Models\UserSettings
         */
        $userSM = Model::get('\Application\Models\UserSettings');

        /**
         * @var \Application\Models\Order
         */
        $orderM = Model::get("\Application\Models\Order");
        $orders = $orderM->getOrders(null,$type,null,$status,null,null,null, $from, $to);
        $orders = OrderHelper::prepare( $orders );

        $response->setHeaders([
            'Content-Type: text/csv; charset=utf-8',
            'content-Disposition: attachment; filename=orders.csv'
        ]);

        // $mxSize = 500 * 1024 * 1024;

        $data = [];
        $data[] = array(
            $lang('owner'),
            $lang('user'),
            $lang('service_type'),
            $lang('service_name'),
            $lang('service_status'),
            $lang('payment_status'),
            $lang('charity_advisor_amount'),
            $lang('admin_amount'),
            $lang('total_amount'),
            $lang('final_amount'),
            $lang('id'),
            $lang('ref'),
            $lang('entity_id'),
            $lang('transaction_id'),
            $lang('payment_method'),
            $lang('charities'),
            $lang('vat_full'),
            $lang('hold'),
            $lang('beneficiary_name'),
            $lang('account_number'),
            $lang('bank_bic_code'),
            $lang('status'),
            $lang('is_expired'),
            $lang('created_at')
        );

        foreach( $orders as $order )
        {
            $bank1 = $userSM->take($order['user']['id'], UserSettings::KEY_BANK1);
            $bank2 = $userSM->take($order['user']['id'], UserSettings::KEY_BANK2);
            $bank3 = $userSM->take($order['user']['id'], UserSettings::KEY_BANK3);

            $paymentMethod = $lang('free');
            if ( !empty($order['payment']) )
            {
                $paymentStatus = $lang($order['payment']['status']);
                $txnToken = $order['payment']['txn_token'];
                $paymentMethod = $lang($order['payment']['method']);
            } else
            {
                $paymentStatus = $lang('not_paid');
                $txnToken = 'NA';
            }
            $data[] = array(

                htmlentities($order['entity_owner']['name']),
                htmlentities($order['user']['name']),
                $order['entity_type'] == 'conversation' ? $lang('messages') : $lang($order['entity_type']),
                $order['entity']['name'],
                $lang(htmlentities($order['entity']['status'])),
                htmlentities($paymentStatus),
                htmlentities($order['advisor_amount']) . 'SR (' . $lang($order['receiverType']) . ' - #'. $order['entity_owner']['id'] .')',
                htmlentities($order['admin_amount']),
                htmlentities($order['payable']),
                htmlentities($order['final_amount']),
                $order['id'],
                ServiceHelper::generateRef($order['user']['id'], $order['entity_id'], $order['entity_type']),
                'e-' . $order['entity']['id'],
                htmlentities($txnToken),
                htmlentities($paymentMethod),
                htmlentities($order['charities']),
                $lang('c_price', array('p' => htmlentities($order['vat']))),
                $order['in_hold'] == 1 ? $lang('yes') : $lang('no'),
                !empty($bank1) ? $bank1 : '-',
                !empty($bank2) ? $bank2 : '-',
                !empty($bank3) ? $bank3 : '-',
                $lang(htmlentities($order['status'])),
                $this->_getIsExpired($order['entity'], $order['entity_type']),
                htmlentities(DateHelper::butify($order['created_at']))
            );
        }


        $file = new File('application/vnd.ms-excel');
        $file->set($this->buildTable($data));

        $response->setHeaders([
            'Content-Type: ' . $file->contentType() . '; charset=utf-8',
            'content-Disposition: attachment; filename=orders.xls'
        ]);
        $response->set($file);
    }

    public function transfers( Request $request, Response $response )
    {
        $id = $request->param(0);
        $userInfo = $this->user->getInfo();

        $from = $request->get('from') ? strtotime($request->get('from')) : '';
        $to = $request->get('to') ? strtotime($request->get('to')) : '';

        /**
         * @var \Application\Models\Transfer
         */

        $transferM = Model::get("\Application\Models\Transfer");
        $transfers = $transferM->getByOrder( $id, $from, $to );
        $transfers = TransferHelper::prepare( $transfers );

        $view = new View();
        $view->set('Admin/Billings/transfers', [
            'userInfo' => $userInfo,
            'transfers' => $transfers,
            'from' => $from,
            'to' => $to
        ]);
        $view->prepend('Admin/header', [
            'title' => "Welcome to telein",
            'currentPage' => 'Transfers',
            'userInfo' => $userInfo
        ]);
        $view->append('Admin/footer');

        $response->set($view);
    }

    private function _getIsExpired( $entity, $entityType )
    {
        $lang = $this->language;

        $isExpired = false;
        switch( $entityType )
        {
            case Workshop::ENTITY_TYPE:
                $isExpired = (
                    WorkshopHelper::isExpired($entity['date'], $entity['duration']) &&
                    $entity['status'] == Workshop::STATUS_NOT_STARTED
                );
                break;
            case Call::ENTITY_TYPE:
                $isExpired = (
                    WorkshopHelper::isExpired($entity['date'], $entity['duration']) &&
                    $entity['status'] == Call::STATUS_NOT_STARTED
                );
            case Conversation::ENTITY_TYPE:
                $isExpired = (
                    ConversationHelper::isExpired($entity['created_at']) &&
                    $entity['status'] == Conversation::STATUS_CURRENT
                );
        }

        return $isExpired ? $lang('expired') : '-';
    }
}