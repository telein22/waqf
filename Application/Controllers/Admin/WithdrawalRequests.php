<?php

namespace Application\Controllers\Admin;

use Application\Helpers\Traits\Exportable;
use Application\Main\AdminController;
use Application\Models\Language;
use Application\Models\User;
use Application\Models\UserSettings;
use Application\Models\Wallet;
use Application\Models\WithdrawalRequest;
use Application\ThirdParties\AWS\AWS;
use Application\ThirdParties\Whatsapp\Whatsapp;
use Application\ThirdParties\Whatsapp\WhatsappMessages;
use System\Core\Exceptions\Redirect;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Helpers\URL;
use System\Models\Session;
use System\Responses\File;
use System\Responses\View;

class WithdrawalRequests extends AdminController
{
    use Exportable;

    public function index(Request $request, Response $response)
    {
        $status = $request->get('status', null);
        $from = $request->get('from') ? strtotime($request->get('from') . ' 00:00:00') : strtotime("-30 days");
        $to = $request->get('to') ? strtotime($request->get('to') . ' 23:59:59') : time();
        $userInfo = $this->user->getInfo();
        $withdrawalRequestM = Model::get(WithdrawalRequest::class);
        $withdrawalRequests = $withdrawalRequestM->all($from, $to, $status);

        $view = new View();
        $view->set('Admin/WithdrawalRequests/index', [
            'userInfo' => $userInfo,
            'withdrawalRequests' => $withdrawalRequests,
            'status' => $status,
            'from' => $from,
            'to' => $to
        ]);
        $view->prepend('Admin/header', [
            'title' => "Welcome to telein",
            'currentPage' => 'Wallets',
            'userInfo' => $userInfo,
        ]);
        $view->append('Admin/footer');

        $response->set($view);
    }

    public function csv(Request $request, Response $response)
    {
        $lang = Model::get(Language::class);
        $status = $request->get('status', null);
        $from = $request->get('from') ? strtotime($request->get('from') . ' 00:00:00') : strtotime("-30 days");
        $to = $request->get('to') ? strtotime($request->get('to') . ' 23:59:59') : time();
        $userInfo = $this->user->getInfo();
        $withdrawalRequestM = Model::get(WithdrawalRequest::class);
        $withdrawalRequests = $withdrawalRequestM->all($from, $to, $status);

        $data = [];
        $data[] = [
            $lang('id'),
            $lang('name'),
            $lang('withdrawal_amount'),
            $lang('wallet_balance'),
            $lang('status'),
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

    public function freelanceDocument(Request $request, Response $response)
    {
        $withdrawalRequestId = $request->param(0);
        $withdrawalRequestM = Model::get(WithdrawalRequest::class);
        $withdrawalRequest = $withdrawalRequestM->getById($withdrawalRequestId);

        if (!$withdrawalRequest) {
            throw new Redirect(URL::full('admin/withdrawal-requests'));
        }

        $user = $this->user->getUser($withdrawalRequest['user_id']);
        $userSeetingM = Model::get(UserSettings::class);
        $freelancingDocumentFileName = $userSeetingM->take($user['id'], UserSettings::KEY_FREELANCE_DOCUMENT, null);

        if (!$freelancingDocumentFileName) {
            throw new Redirect(URL::full('admin/withdrawal-requests'));
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

    public function changeStatus(Request $request, Response $response)
    {
        $status = $request->post('status');
        $withdrawalRequestId = $request->post('withdrawal_request_id');
        $walletM = Model::get(Wallet::class);
        $withdrawalRequestM = Model::get(WithdrawalRequest::class);
        $withdrawalRequest = $withdrawalRequestM->getById($withdrawalRequestId);
        $sessionM = Model::get(Session::class);

        if ($withdrawalRequest['status'] == WithdrawalRequest::STATUS_COMPLETED) {
            $session = $sessionM->put('changing_status_impossible', 1);
            throw new Redirect(URL::full('admin/withdrawal-requests'));
        }

        $userM = Model::get(User::class);
        $user = $userM->getUser($withdrawalRequest['user_id']);

        $wallet = $walletM->getByUserId($user['id']);
        if ($wallet['balance'] < $withdrawalRequest['amount']) {
            $session = $sessionM->put('wallet_balance_insufficient', 1);
            throw new Redirect(URL::full('admin/withdrawal-requests'));
        }

        $withdrawalRequestM->changeStatus($withdrawalRequestId, $status);

        if ($status == WithdrawalRequest::STATUS_COMPLETED) {
            $walletM->deductFromBalance($user['id'], $withdrawalRequest['amount']);
        }

        $currentWalletBalance = $wallet['balance'] - $withdrawalRequest['amount'];
        $funName = ucfirst($status);
        $funName = "markWithdrawalRequestAs{$funName}";
        $message = WhatsappMessages::$funName($user['name'], $currentWalletBalance);
        Whatsapp::sendChat($user['phone'], $message);

        throw new Redirect(URL::full('admin/withdrawal-requests'));
    }
}