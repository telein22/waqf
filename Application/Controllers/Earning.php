<?php

namespace Application\Controllers;

use Application\Helpers\EarningLogHelper;
use Application\Helpers\Traits\Exportable;
use Application\Main\AuthController;
use Application\Models\EarningLog;
use Application\Models\Language;
use Application\Models\Order;
use Application\Models\User;
use Application\Models\UserSettings;
use Application\Models\Wallet;
use Application\Models\WalletTransaction;
use Application\Models\WithdrawalRequest;
use Application\ThirdParties\AWS\AWS;
use System\Core\Exceptions\Redirect;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Helpers\URL;
use System\Responses\File;
use System\Responses\View;

class Earning extends AuthController
{
    use Exportable;

    public function index(Request $request, Response $response)
    {
        $userInfo = $this->user->getInfo();
        $userIsEntity = $userInfo['type'] == User::TYPE_ENTITY;

        $walletM = Model::get(Wallet::class);
        $walletTransactionM = Model::get(WalletTransaction::class);
        $wallet = $walletM->getByUserId($userInfo['id']);

        $walletTransactions = [];
        $totalAmount = 0;
        if ($wallet) {
            $walletTransactions = $walletTransactionM->getByWalletId($wallet['id']);
            $totalAmount = $walletTransactionM->getTotalAmount($wallet['id']);
        }

        $userSettingM = Model::get(UserSettings::class);
        $freelanceDocumentUploaded = $userSettingM->take($userInfo['id'], UserSettings::KEY_FREELANCE_DOCUMENT);
        $beneficiaryName = $userSettingM->take($userInfo['id'], UserSettings::KEY_BANK1);
        $iban = $userSettingM->take($userInfo['id'], UserSettings::KEY_BANK2);
        $bankName = $userSettingM->take($userInfo['id'], UserSettings::KEY_BANK3);

        $from = $request->get('from') ? strtotime($request->get('from')) : '';
        $to = $request->get('to') ? strtotime($request->get('to')) : '';

        $limit = 10;
        $logM = Model::get(EarningLog::class);
        $logs = $logM->all($userInfo['id'], $from, $to, null, $limit);
        $logs = EarningLogHelper::prepare($logs);

        $orderM = Model::get(Order::class);


//        $totalAmount = $orderM->calcTotalAmount($userInfo['id'], Order::STATUS_COMPLETED, true);
        $totalPending = $orderM->calcTotalAmount($userInfo['id'], Order::STATUS_APPROVED, true);

        $withdrawalM = Model::get(WithdrawalRequest::class);
        $totalWithdrawn = $withdrawalM->getTotallWithdrawn($userInfo['id']);

        $view = new View();
        $view->set(
            'Earning/index', [
            'userInfo' => $userInfo,
            'wallet' => $wallet,
            'totalAmount' => $totalAmount,
            'currentPending' => $totalPending,
            'totalWithdrawn' => $totalWithdrawn,
            'transactions' => $walletTransactions,
            'freelanceDocumentUploaded' => $freelanceDocumentUploaded,
            'logs' => $logs,
            'from' => $from,
            'to' => $to,
            'limit' => $limit,
            'beneficiaryName' => $beneficiaryName,
            'iban' => $iban,
            'bankName' => $bankName,
            'userIsEntity' => $userIsEntity
        ]);

        $view->prepend('header');
        $view->append('footer');

        $response->set($view);
    }

    public function freelanceDocument(Request $request, Response $response)
    {
        $userInfo = $this->user->getInfo();
        $user = $this->user->getUser($userInfo['id']);

        $userSeetingM = Model::get(UserSettings::class);
        $freelancingDocumentFileName = $userSeetingM->take($user['id'], UserSettings::KEY_FREELANCE_DOCUMENT, null);

        if (!$freelancingDocumentFileName) {
            throw new Redirect(URL::full('earnings'));
        }

        $freelancingDocumentURL = AWS::getFileURL($freelancingDocumentFileName, AWS::FREELANCE_DOCUMENTS_DIRECTORY);
        $headers = get_headers($freelancingDocumentURL, 1);

        $contentType = $headers['Content-Type'];
        $file = new File($contentType);
        $file->set(file_get_contents($freelancingDocumentURL));

        $fileName = $freelancingDocumentFileName;
        $response->setHeaders([
            "Content-Type: {$contentType}; charset=utf-8",
            "content-Disposition: attachment; filename={$fileName}",
        ]);

        $response->set($file);
    }

    public function walletTransactionsCSV(Request $request, Response $response)
    {
        $lang = Model::get(Language::class);
        $userInfo = $this->user->getInfo();
        $walletM = Model::get(Wallet::class);
        $wallet = $walletM->getByUserId($userInfo['id']);

        $transactions = [];
        if ($wallet) {
            $walletTransactionM = Model::get(WalletTransaction::class);
            $transactions = $walletTransactionM->getByWalletId($wallet['id']);
        }

        $data = [];
        $data[] = [
            $lang('service_type'),
            $lang('service_id'),
            $lang('beneficiary'),
            $lang('fees'),
            $lang('created_at'),
        ];

        foreach ($transactions as $transaction) {
            $data[] = [
                htmlentities($transaction['entity_type']),
                htmlentities($transaction['entity_id']),
                htmlentities($transaction['beneficiary']),
                htmlentities($transaction['amount']),
                htmlentities($transaction['created_at']),
            ];
        }

        $file = new File('application/vnd.ms-excel');
        $file->set($this->buildTable($data));

        $response->setHeaders([
            'Content-Type: ' . $file->contentType() . '; charset=utf-8',
            'content-Disposition: attachment; filename=wallets-details.xls'
        ]);

        $response->set($file);
    }

    public function withdrawalRequestsCSV(Request $request, Response $response)
    {
        $lang = Model::get(Language::class);
        $userInfo = $this->user->getInfo();
        $withdrawalRequestM = Model::get(WithdrawalRequest::class);
        $withdrawalRequests = $withdrawalRequestM->all(null, null, null, $userInfo['id']);
        $data = [];
        $data[] = [
            $lang('id'),
            $lang('name'),
            $lang('withdrawal_amount'),
            $lang('wallet_balance'),
            $lang('withdrawal_status'),
            $lang('bank_info'),
            $lang('created_at'),
        ];

        foreach ($withdrawalRequests as $withdrawalRequest) {
            $data[] = [
                $withdrawalRequest['id'],
                $withdrawalRequest['name'],
                $withdrawalRequest['amount'],
                $withdrawalRequest['wallet_balance'],
                $withdrawalRequest['status'],
                $withdrawalRequest['bank_info'],
                date('Y-m-d H:i:s', $withdrawalRequest['created_at']),
            ];
        }

        $file = new File('application/vnd.ms-excel');
        $file->set($this->buildTable($data));

        $date = date('Y-m-d H:i:s');
        $fileName = "withdrawal_requests_{$date}}.xls";
        $response->setHeaders([
            'Content-Type: ' . $file->contentType() . '; charset=utf-8',
            "content-Disposition: attachment; filename={$fileName}"
        ]);

        $response->set($file);
    }
}