<?php

namespace Application\Commands;

use Application\Hooks\Service;
use Application\Models\Workshop;
use Application\Services\WorkshopService;
use System\Core\Application;
use System\Core\CLICommand;
use System\Core\Database;
use Pusher\Pusher;
use Application\Models\User;
use Application\ThirdParties\MeetingProviders\BigBlueButton\BigBlueButtonProvider;
use Application\ThirdParties\MeetingProviders\Zoom\ZoomProvider;
use Application\Dtos\Workshop as WorkshopDto;
use Application\Models\Meeting;
use Application\ThirdParties\Firebase\Firebase;
use Application\Dtos\FirebaseNotification;
use Application\Dtos\FirebaseNotificationData;
use Application\Models\ServiceLog;
use System\Core\Model;
use Application\Helpers\AppHelper;
use System\Models\Hooks;

class StartWorkshop extends CLICommand
{
    public function run($params)
    {
        $SQL = "SELECT * FROM `workshops` WHERE `status` = ? AND date <= NOW()";

        $db = Database::get();
        $workshops = $db->query($SQL, [Workshop::STATUS_PREPARING])->getAll();


        $workshopService = new WorkshopService();
        foreach ($workshops as $workshop) {
            $SQL = "UPDATE `workshops` SET `status` = ? WHERE id = ? AND `status` = ?";
            $db->query($SQL, [Workshop::STATUS_CURRENT, $workshop['id'], Workshop::STATUS_PREPARING]);

            $entityDto = new WorkshopDto(
                $workshop['id'],
                $workshop['user_id'],
                $workshop['name'],
                $workshop['desc'],
                $workshop['date'],
                $workshop['duration'],
                $workshop['price']
            );

            $workshopService->notifyOnStart(Workshop::ENTITY_TYPE, $entityDto);
        }
    }
}