<?php

namespace Application\Controllers\Admin;

use Application\Helpers\CallHelper;
use Application\Helpers\ParticipantHelper;
use Application\Helpers\ServiceLogHelper;
use Application\Helpers\WorkshopHelper;
use Application\Main\AdminController;
use Application\Models\Call;
use Application\Models\CallSlot;
use Application\Models\Participant;
use Application\Models\ServiceLog as ModelsServiceLog;
use Application\Models\Workshop;
use System\Core\Exceptions\Error404;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Responses\View;

class ServiceLog extends AdminController
{
    public function index( Request $request, Response $response )
    {
        $entityType = $request->param(0);
        $entityId = $request->param(1);

        $entity = $this->_getEntity($entityType, $entityId);

        $lang = $this->language;

        if ( empty($entity) ) throw new Error404();

        /**
         * @var ModelsServiceLog
         */
        $serviceLM = Model::get(ModelsServiceLog::class);
        $logs = $serviceLM->getByEntity($entityId, $entityType);
        $logs = ServiceLogHelper::prepare($logs);

        // $finalLogs = $this->_prepare($entity['participants'], $logs);

        $view = new View();
        $view->set('Admin/ServiceLog/index', [
            // 'finalLogs' => $finalLogs,
            'entityId' => $entityId,
            'entityType' => $entityType,
            'entity' => $entity,
            'logs' => $logs
        ]);
        $view->prepend('Admin/header', [
            'title' => "Admin",
            'currentPage' => $lang('log'),
            'userInfo' => $this->user->getInfo()
        ]);
        $view->append('Admin/footer');

        $response->set($view);
    }

    private function _getEntity( $entityType, $entityId )
    {
        $entity = null;
        switch( $entityType )
        {
            case Workshop::ENTITY_TYPE:
                /**
                 * @var Workshop
                 */
                $workM = Model::get(Workshop::class);
                $entity = $workM->getInfoById($entityId);
                if ( $entity ) {
                    $entity = WorkshopHelper::prepare([$entity]);
                    $entity = $entity[0];

                    $entity['participants'] = [];
                    // get participants
                    /**
                     * @var Participant
                     */
                    $partiM = Model::get(Participant::class);
                    $entity['participants'] = $partiM->getByEntities([ Workshop::ENTITY_TYPE => [$entity['id']] ]);
                    $entity['participants'] = ParticipantHelper::prepare($entity['participants']);

                }
                break;
            case Call::ENTITY_TYPE:
                /**
                 * @var CallSlot
                 */
                $callM = Model::get(CallSlot::class);
                $entity = $callM->getById($entityId);
                if ( $entity ) {
                    $entity = CallHelper::prepareSlots([$entity]);
                    $entity = $entity[0];

                    $entity['participants'] = [];

                    if ( $entity['call'] )
                    {
                        // get participants
                        /**
                         * @var Participant
                         */
                        $partiM = Model::get(Participant::class);
                        $entity['participants'] = $partiM->getByEntities([ Call::ENTITY_TYPE => [$entity['call']['id']] ]);
                        $entity['participants'] = ParticipantHelper::prepare($entity['participants']);
                    }
                }
                break;
        }

        return $entity;
    }

    private function _prepare( $participants, $serviceLogs )
    {
        $output = [];
        foreach ( $participants as $parti )
        {
            $user = $parti['user'];
            
            $arr = [
                'user' => $user,
                'log' => null
            ];

            foreach ( $serviceLogs as $log )
            {
                if ( $log['action_by']['id'] == $user['id'] )
                {                    
                    $arr['log'] = $log;
                }
            }

            $output[] = $arr;
        }

        return $output;
    }
}