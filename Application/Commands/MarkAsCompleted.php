<?php

namespace Application\Commands;

use Application\Helpers\QueryHelper;
use Application\Services\WalletService;
use Application\ThirdParties\Whatsapp\Whatsapp;
use Application\Models\Call;
use Application\Models\Conversation;
use Application\Models\Order;
use Application\Dtos\Order as OrderDto;
use Application\Models\User;
use Application\Models\Workshop;
use Application\ThirdParties\Whatsapp\WhatsappMessages;
use System\Core\CLICommand;
use System\Core\Model;


class MarkAsCompleted extends CLICommand
{
    public function run($params)
    {
        $orderM = Model::get(Order::class);
        $approvedOrders = $orderM->getApprovedOrders();
        $ordersIdsToBeMarkAsCompleted = [];
        $entitiesIdsToBeMarkAsCompleted = [
            'call' => [],
            'workshop' => []
        ];

        $completedOrders = [];
        foreach ($approvedOrders as $order) {
            if (!in_array($order['entity_type'], ['call', 'workshop'])) {
                continue;
            }

            if ($order['entity_type'] == 'workshop') {
                $entityM = Model::get(Workshop::class);
                $entity = $entityM->getInfoById($order['entity_id']);

            } elseif ($order['entity_type'] == 'call') {
                $entityM = Model::get(Call::class);
                $entity = $entityM->getById($order['entity_id']);
            }

            $entityDate = strtotime($entity['date'] . ' + ' . $entity['duration'] . ' minute');

            if ($entity['status'] == 'current' && $entityDate < time()) {
                $completedOrders [] = $order;
                $ordersIdsToBeMarkAsCompleted [] = $order['id'];
                $entitiesIdsToBeMarkAsCompleted[$order['entity_type']] [] = $order['entity_id'];

                WalletService::addToWallet(
                    new OrderDto(
                        $order['id'],
                        $order['user_id'],
                        $order['amount'],
                        $order['payable'],
                        $order['final_amount'],
                        $order['advisor_amount'],
                        null,
                        $order['entity_owner_id']
                    ));
            }
        }

        QueryHelper::markAsCompleted('orders', $ordersIdsToBeMarkAsCompleted);

        if (!empty($entitiesIdsToBeMarkAsCompleted['call'])) {
            QueryHelper::markAsCompleted('calls', $entitiesIdsToBeMarkAsCompleted['call']);
        }

        if (!empty($entitiesIdsToBeMarkAsCompleted['workshop'])) {
            QueryHelper::markAsCompleted('workshops', $entitiesIdsToBeMarkAsCompleted['workshop']);
        }


        foreach ($completedOrders as $order) {
            if ($order['entity_type'] == Conversation::ENTITY_TYPE) {
                continue;
            }

            $userM = Model::get(User::class);
            $user = $userM->getUser($order['user_id']);
            $owner = $userM->getUser($order['entity_owner_id']);


            if ($order['entity_type'] == Call::ENTITY_TYPE) {
                $message = WhatsappMessages::confirmCallCompleted($user['name'], $owner['name'], $order['entity_id']);
            } elseif ($order['entity_type'] == Workshop::ENTITY_TYPE) {
                $workshopM = Model::get(Workshop::class);
                $whorkshop = $workshopM->getInfoById($order['entity_id']);
                $message = WhatsappMessages::confirmWorkshopCompleted($user['name'], $whorkshop['name'], $order['entity_id']);
            }

            Whatsapp::sendChat($user['phone'], $message);
        }
    }
}
