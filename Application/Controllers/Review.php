<?php

namespace Application\Controllers;

use Application\Helpers\CacheHelper;
use Application\Helpers\UserHelper;
use Application\ThirdParties\Whatsapp\Whatsapp;
use Application\Main\AuthController;
use Application\Models\Call;
use Application\Models\Conversation;
use Application\Models\User;
use Application\Models\Workshop;
use System\Core\Exceptions\Error404;
use System\Core\Exceptions\Redirect;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Responses\View;

class Review extends AuthController
{
    public function index( Request $request, Response $response )
    {
        $entityId = $request->param(0);
        $entityType = $request->param(1);

        switch( $entityType )
        {
            case Conversation::ENTITY_TYPE:
            case Call::ENTITY_TYPE:
            case Workshop::ENTITY_TYPE:
                break;
            default:
                throw new Error404();
        }

        
        $userInfo = $this->user->getInfo();

        
        // check if already reviewed.
        /**
         * @var \Application\Models\Reviews
         */
        $reviewM = Model::get('\Application\Models\Reviews');
        $review = $reviewM->getReview($userInfo['id'], $entityId, $entityType);

        // First we need to validate entity details.
        // Then we can pass those details
        list( $imgUrl, $name, $ownerId ) = $this->_getData($entityId, $entityType);

        $view = new View();
        $view->set('Review/index', [
            'entityId' => $entityId,
            'entityType' => $entityType,
            'ownerId' => $ownerId,
            'imgUrl' => $imgUrl,
            'name' => $name,
            'review' => $review
        ]);
        $view->prepend('header');
        $view->append('footer');

        $response->set($view);
    }

    public function submit( Request $request )
    {
        $entityId = $request->param(0);
        $entityType = $request->param(1);        
        $review = $request->param(2);
        $ownerId = $request->param(3);
        
        if ( !in_array($review, [1, 2, 3, 4, 5]) ) throw new Error404;

        $userInfo = $this->user->getInfo();

        // submit the handler.
        /**
         * @var \Application\Models\Reviews
         */
        $reviewM = Model::get('\Application\Models\Reviews');
        $reviewM->create([
            'entity_owner_id' => $ownerId,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'star' => $review,
            'user_id' => $userInfo['id'],
            'created_at' => time()
        ]);


        $reviewInfo = null;
        switch ($review)
        {
            case 1:  $reviewInfo = 'نجة واحدة';
            break;
            case 2: $reviewInfo = 'نجمتين';
                break;
            case 3: $reviewInfo = 'ثلاث نجوم';
                break;
            case 4: $reviewInfo = 'أربع نجوم';
                break;
            case 5: $reviewInfo = 'خمس نجوم';
                break;
        }


        $userM = Model::get(User::class);
        $owner = $userM->getUser($ownerId);
        $message = <<<MESSAGE
أهلاً بك {$owner['name']}

لقد تم تقييم خدمتك من {$userInfo['name']}

التقييم الذي حصلت عليه هو

{$reviewInfo} 

شكراً لك
MESSAGE;

        Whatsapp::sendChat($owner['phone'], $message);

        CacheHelper::forget(CacheHelper::USER_PROFILE_KEY, $ownerId);

        throw new Redirect('review/' . $entityId . '/' . $entityType);
    }

    private function _getData( $entityId, $entityType )
    {
        $data = [];        
        switch ( $entityType )
        {
            case Conversation::ENTITY_TYPE:

                /**
                 * @var \Application\Models\Conversation
                 */
                $conM = Model::get('\Application\Models\Conversation');
                $con = $conM->getById($entityId);

                $user = $this->user->getUser($con['owner_id']);

                $data[] = UserHelper::getAvatarUrl('fit:300,300', $con['owner_id']);
                $data[] = $user['name'];
                $data[] = $con['owner_id'];
                break;
            case Call::ENTITY_TYPE:
                  /**
                 * @var \Application\Models\Call
                 */
                $callM = Model::get('\Application\Models\Call');
                $call = $callM->getById($entityId);

                $user = $this->user->getUser($call['owner_id']);
                $data[] = UserHelper::getAvatarUrl('fit:300,300', $user['id']);
                $data[] = $user['name'];
                $data[] = $user['id'];
                break;
            case Workshop::ENTITY_TYPE:
                /**
                 * @var \Application\Models\Workshop
                 */
                $workM = Model::get('\Application\Models\Workshop');
                $workshop = $workM->getInfoById($entityId);

                $user = $this->user->getUser($workshop['user_id']);
                $data[] = UserHelper::getAvatarUrl('fit:300,300', $user['id']);
                $data[] = $user['name'];
                $data[] = $user['id'];
                break;
        }

        return $data;
    }
}