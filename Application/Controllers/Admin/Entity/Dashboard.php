<?php

namespace Application\Controllers\Admin\Entity;

use Application\Main\AdminController;
use Application\Main\EntityController;
use Application\Models\Call;
use Application\Models\Charity;
use Application\Models\Conversation;
use Application\Models\Feed;
use Application\Models\Workshop;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Responses\View;

class Dashboard extends EntityController
{
    public function index( Request $request, Response $response )
    {
        $entityInfo = $this->user->getInfo();

        $users = $this->user->getAssociates($entityInfo['id']);

        $feedM = Model::get(Feed::class);
        $feedCount = $feedM->countAllFeeds( true );

        $charityM = Model::get(Charity::class);
        $charities = $charityM->all();

        $convoM = Model::get(Conversation::class);
        $convoCompleteCount = $convoM->listAll( Conversation::STATUS_COMPLETED );
        $convoCurrentCount = $convoM->listAll( Conversation::STATUS_CURRENT );
        $convoCancelledCount = $convoM->listAll( Conversation::STATUS_CANCELED );

        $callM = Model::get(Call::class);
        $callCompleteCount = $callM->getByStatus( Call::STATUS_COMPLETED );
        $callCurrentCount = $callM->getByStatus( Call::STATUS_CURRENT );
        $callCancelledCount = $callM->getByStatus( Call::STATUS_CANCELED );

        $workshopM = Model::get(Workshop::class);
        $workshopCompleteCount = $workshopM->listByStatus( Workshop::STATUS_COMPLETED );
        $workshopCurrentCount = $workshopM->listByStatus( Workshop::STATUS_CURRENT );
        $workshopCancelledCount = $workshopM->listByStatus( Workshop::STATUS_CANCELED );

        // $transferM = Model::get("\Application\Models\Transfer");
        // $adminRevenue = $transferM->listTransferred( Transfer::RECEIVER_ADMIN );
        // $advisorRevenue = $transferM->listTransferred( Transfer::RECEIVER_ADVISOR );
        // $charityRevenue = $transferM->listTransferred( Transfer::RECEIVER_CHARITY );

        $lang = $this->language;


        $view = new View();
        $view->set('Admin/Dashboard/entity/index', [
            'userInfo' => $entityInfo,
            'activeUsersCount' => count($users),
            'activeFeedsCount' => $feedCount,
            'charitiesCount' => count($charities),
            'convoCompleteCount' => $convoCompleteCount,
            'convCurrentCount' => $convoCurrentCount,
            'convoCancelledCount' => $convoCancelledCount,
            'callCompleteCount' => $callCompleteCount,
            'callCurrentCount' => $callCurrentCount,
            'callCancelledCount' => $callCancelledCount,
            'workshopCompleteCount' => $workshopCompleteCount,
            'workshopCurrentCount' => $workshopCurrentCount,
            'workshopCancelledCount' => $workshopCancelledCount,
        ]);
        $view->prepend('Admin/header', [
            'title' => "Welcome to telein",
            'currentPage' => $lang('dashboard'),
            'userInfo' => $entityInfo
        ]);
        $view->append('Admin/footer');

        $response->set($view);
    }
}