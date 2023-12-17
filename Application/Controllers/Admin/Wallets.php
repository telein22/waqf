<?php

namespace Application\Controllers\Admin;

use Application\Helpers\Traits\Exportable;
use Application\Main\AdminController;
use Application\Models\Language;
use Application\Models\Wallet;
use Application\Models\WalletTransaction;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Responses\File;
use System\Responses\View;

class Wallets extends AdminController
{
    use Exportable;

    public function index( Request $request, Response $response )
    {
        $userInfo = $this->user->getInfo();
        $walletM = Model::get(Wallet::class);
        $wallets = $walletM->all();

        $view = new View();
        $view->set('Admin/Wallets/index', [
            'userInfo' => $userInfo,
            'wallets' => $wallets,
        ]);
        $view->prepend('Admin/header', [
            'title' => "Welcome to telein",
            'currentPage' => 'Wallets',
            'userInfo' => $userInfo
        ]);
        $view->append('Admin/footer');

        $response->set($view);
    }

    public function details(Request $request, Response $response)
    {
        $userInfo = $this->user->getInfo();
        $walletId = $request->param(0);
        $walletTransationM = Model::get(WalletTransaction::class);
        $transactions = $walletTransationM->getByWalletId($walletId);

        $view = new View();
        $view->set('Admin/Wallets/details', [
            'userInfo' => $userInfo,
            'wallet_id' => $walletId,
            'transactions' => $transactions,
        ]);
        $view->prepend('Admin/header', [
            'title' => "Welcome to telein",
            'currentPage' => 'Wallets transactions',
            'userInfo' => $userInfo
        ]);
        $view->append('Admin/footer');

        $response->set($view);
    }

    public function csv( Request $request, Response $response )
    {
        $lang = Model::get(Language::class);
        $walletM = Model::get(Wallet::class);
        $wallets = $walletM->all();

        $data = [];
        $data[] = [
            $lang('name'),
            $lang('balance'),
            $lang('bank_info'),
        ];

        foreach( $wallets as $wallet ) {
            $data[] = [
                htmlentities($wallet['name']),
                htmlentities($wallet['balance']),
                htmlentities($wallet['bank_info']),
            ];
        }

        $file = new File('application/vnd.ms-excel');
        $file->set($this->buildTable($data));

        $response->setHeaders([
            'Content-Type: ' . $file->contentType() . '; charset=utf-8',
            'content-Disposition: attachment; filename=wallets.xls'
        ]);

        $response->set($file);
    }

    public function detailsToCsv(Request $request, Response $response)
    {
        $lang = Model::get(Language::class);
        $walletId = $request->param(0);
        $walletTransationM = Model::get(WalletTransaction::class);
        $transactions = $walletTransationM->getByWalletId($walletId);

        $data = [];
        $data[] = [
            $lang('service_type'),
            $lang('service_id'),
            $lang('beneficiary'),
            $lang('fees'),
            $lang('created_at'),
        ];

        foreach( $transactions as $transaction ) {
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
}
