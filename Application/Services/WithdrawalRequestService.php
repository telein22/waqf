<?php

namespace Application\Services;

use Application\Dtos\BankInfo;
use Application\Dtos\Order as OrderDto;
use Application\Main\ResponseJSON;
use Application\Models\Language;
use Application\Models\User;
use Application\Models\UserSettings;
use Application\Models\Wallet;
use Application\Models\WalletTransaction;
use Application\Models\WithdrawalRequest;
use Application\ThirdParties\AWS\AWS;
use Application\ThirdParties\Whatsapp\Whatsapp;
use Application\ThirdParties\Whatsapp\WhatsappMessages;
use BaconQrCode\Common\Mode;
use System\Core\Config;
use System\Core\Model;
use System\Helpers\Strings;

class WithdrawalRequestService extends BaseService
{
    public function add(array $user, float $amount, BankInfo $bankInfo): void
    {
        $lang = Model::get(Language::class);
        $userSettingM = Model::get(UserSettings::class);
        $freelanceDocument = $userSettingM->take($user['id'], UserSettings::KEY_FREELANCE_DOCUMENT, false);

        if ($user['type'] != User::TYPE_ENTITY) {
            if (isset($_FILES['freelance_document']) && !empty($_FILES['freelance_document']['name'])) {
                $fileName = $this->uploadDoc();
                $userSettingM->put($user['id'], UserSettings::KEY_FREELANCE_DOCUMENT, $fileName);
            } else if(!$freelanceDocument) {
                throw new ResponseJSON('error', $lang('cannot_proceed_without_freelance_document'));
            }
        }


        $userSettingM->put($user['id'], UserSettings::KEY_BANK1, $bankInfo->getBeneficiaryName());
        $userSettingM->put($user['id'], UserSettings::KEY_BANK2, $bankInfo->getIban());
        $userSettingM->put($user['id'], UserSettings::KEY_BANK3, $bankInfo->getBankName());

        $walletM = Model::get(Wallet::class);
        $wallet = $walletM->getByUserId($user['id']);

        if ($wallet['balance'] < $amount) {
            throw new ResponseJSON('error', $lang('wallet_balance_exceeded'));
        }

        $withdrawalRequestM = Model::get(WithdrawalRequest::class);
        $withdrawalRequest = $withdrawalRequestM->getActiveRequestByUserId($user['id']);

        if (!empty($withdrawalRequest)) {
            throw new ResponseJSON('error', $lang('already_have_active_request'));
        }

        $withdrawalRequestM->create([
           'user_id' => $user['id'],
           'amount' => $amount,
           'status' => WithdrawalRequest::STATUS_PENDING,
           'created_at' => time(),
           'updated_at' => time(),
        ]);
        
        Whatsapp::sendChat($user['phone'], WhatsappMessages::confirmAddingWithdrawalRequestForAdvisor($user['name'], $amount));
        $adminPhone = Config::get('Website')->whatsapp_number;
        Whatsapp::sendChat($adminPhone, WhatsappMessages::confirmAddingWithdrawalRequestForAdmin($user['name'], $amount));

        throw new ResponseJSON('success', $lang('withdrawal_request_added'));
    }

    public function uploadDoc(): string
    {
        $uploadDir = dirname(__DIR__, 2);
        $fileExtension = pathinfo($_FILES['freelance_document']['name'], PATHINFO_EXTENSION);
        $fileName = Strings::random(20) . '_' . time() . '.' . $fileExtension;
        $uploadFile = "{$uploadDir}\Storage\FreelanceDocs\\{$fileName}";
        move_uploaded_file($_FILES['freelance_document']['tmp_name'], $uploadFile);
        AWS::syncFileWithS3($fileName, $uploadFile, AWS::FREELANCE_DOCUMENTS_DIRECTORY);

        return $fileName;
    }
}