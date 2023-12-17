<?php

namespace Application\Services;

use Application\Dtos\Order as OrderDto;
use Application\Models\Commission;
use Application\Models\Order;
use Application\Models\ProfitProceed;
use Application\Models\User;
use Application\Models\Wallet;
use Application\Models\WalletTransaction;
use Application\Models\Workshop;
use Application\Models\Call;
use System\Core\Model;

class WalletService extends BaseService
{
    public static function addToWallet(OrderDto $orderDto)
    {
        // check the profits revenue
        $user = Model::get(User::class)->getUser($orderDto->getEntityOwnerId());

        $orderM = Model::get(Order::class);
        $orderObj = $orderM->take($orderDto->getId());

        $entityBalance = 0;
        $advisorBalance = $orderDto->getAdvisorAmount();

        if (in_array($orderObj['entity_type'], [Workshop::ENTITY_TYPE, Call::ENTITY_TYPE])) {
            $entityM = $orderObj['entity_type'] == Workshop::ENTITY_TYPE ? Model::get(Workshop::class) : Model::get(Call::class);
            $entity = $entityM->getById($orderObj['entity_id']);

            $profitProceedM = Model::get(ProfitProceed::class);
            $profitProceed = $profitProceedM->getById($entity['profit_proceed_type_id']);

            if ($profitProceed['code'] = ProfitProceed::TYPE_ENTITY) {
                $commissionM = Model::get(Commission::class);
                $commission = $commissionM->getByEntityAndAdvisor($user['entity_id'], $user['id']);

                if ($commission) {
                    $entityCommission = ((int)$commission['entity_commission']) / 100;
                    $advisorCommission = ((int)$commission['advisor_commission']) / 100;

                    $entityBalance = $orderDto->getAdvisorAmount() - ($orderDto->getAdvisorAmount() * $entityCommission);
                    $advisorBalance = $orderDto->getAdvisorAmount() - ($orderDto->getAdvisorAmount() * $advisorCommission);
                }
            }
        }

        $walletM = Model::get(Wallet::class);
        $walletM->updateBalance($user['id'], (float)$advisorBalance, $orderDto->getId());
        $wallet = $walletM->getByUserId($user['id']);

        $walletTransactionM = Model::get(WalletTransaction::class);
        $walletTransactionM->create([
            'wallet_id' => $wallet['id'],
            'order_id' => $orderDto->getId(),
            'amount' => (float)$advisorBalance,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        // for entity
        if ($entityBalance > 0) {
            $entityId = $user['entity_id'];
            $walletM = Model::get(Wallet::class);
            $walletM->updateBalance($entityId, (float)$entityBalance, $orderDto->getId());
            $wallet = $walletM->getByUserId($entityId);

            $walletTransactionM = Model::get(WalletTransaction::class);
            $walletTransactionM->create([
                'wallet_id' => $wallet['id'],
                'order_id' => $orderDto->getId(),
                'amount' => (float)$entityBalance,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}