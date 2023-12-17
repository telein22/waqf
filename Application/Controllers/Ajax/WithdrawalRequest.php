<?php

namespace Application\Controllers\Ajax;

use Application\Dtos\BankInfo;
use Application\Services\WithdrawalRequestService;
use Application\Main\AuthController;
use Application\Main\ResponseJSON;
use Application\Models\WithdrawalRequest as WithdrawalRequestModel;
use System\Core\Config;
use System\Core\Model;
use Application\Models\Language;
use System\Core\Request;
use System\core\Response;

class WithdrawalRequest extends AuthController
{
    public function index(Request $request, Response $response)
    {
        $skip = $request->get('start');
        $limit = $request->get('length');
        $lang = Model::get(\Application\Models\Language::class);
        $userInfo = $this->user->getInfo() ;
        $withdrawalRequestM = Model::get(WithdrawalRequestModel::class);
        $withdrawalRequests = $withdrawalRequestM->getAllByUserId($userInfo['id']);
        $withdrawalRequests= array_map(function($withdrawalRequest) use ($lang) {
            $withdrawalRequest['status'] = $lang("withdrawal_status_{$withdrawalRequest['status']}");
            $withdrawalRequest['created_at'] = date('Y-m-d H:i:s', $withdrawalRequest['created_at']);
            $withdrawalRequest['updated_at'] = date('Y-m-d H:i:s', $withdrawalRequest['updated_at']);

            return $withdrawalRequest;
        }, $withdrawalRequests);

        throw new ResponseJSON("success", [
            "draw"            => intval($_GET['draw']), // Echo back the draw parameter from the request
            "recordsTotal"    => 1,
            "recordsFiltered" => 0,
            "data"            => $withdrawalRequests,
        ]);
    }
    public function create(Request $request, Response $response)
    {
        $user = $this->user->getInfo();
        $amount = $request->post('withdrawal_amount');
        $lang = Model::get(Language::class);
        $withdrawalAmountLimit = Config::get('Website')->minimumWithdrawalAmount;

        if ($amount < $withdrawalAmountLimit) {
            throw new ResponseJSON("error", $lang('profits_withdrawal_note1', ['min' => $withdrawalAmountLimit]));
        }

        WithdrawalRequestService::init()->add($user, $amount, new BankInfo(
            $request->post('beneficiary_name'),
            $request->post('iban'),
            $request->post('bank_name'),
            ));

        throw new ResponseJSON("success");
    }
}