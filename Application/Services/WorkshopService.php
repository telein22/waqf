<?php

namespace Application\Services;

use Application\Dtos\BaseItem;
use Application\Dtos\FirebaseNotification;
use Application\Dtos\FirebaseNotificationData;
use Application\Helpers\AppHelper;
use Application\Models\Meeting;
use Application\Models\ServiceLog;
use Application\Models\User;
use Application\Models\Workshop;
use Application\Models\Call as CallModel;
use Application\Services\Traits\EntityTrait;
use Application\ThirdParties\Firebase\Firebase;
use Application\ThirdParties\MeetingProviders\Zoom\ZoomProvider;
use Pusher\Pusher;
use System\Core\Application;
use System\Core\Model;

class WorkshopService extends BaseService
{
    use EntityTrait;

    public function getAvailableWorkshops()
    {
        $workshopM = Model::get(Workshop::class);
        return $workshopM->findForBooking(-1);
    }
}