<?php

namespace Application\Controllers\Ajax;

use Application\Controllers\Workshops;
use Application\ThirdParties\Whatsapp\Whatsapp;
use Application\Helpers\LiveSessionHelper;
use Application\Helpers\WorkshopHelper;
use Application\Main\AuthController;
use Application\Main\ResponseJSON;
use Application\Models\Feed;
use Application\Models\Meeting;
use Application\Models\MeetingApi;
use Application\Models\User;
use Application\Models\Workshop as ModelsWorkshop;
use Application\Services\UserService;
use System\Core\Config;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Libs\FormValidator;
use System\Models\Session;
use System\Responses\View;

class Workshop extends AuthController
{

    public function __construct( $modelList )
    {
        $this->_ignore(['find']);
        parent::__construct( $modelList );
    }

    public function create(Request $request, Response $response)
    {
        // Quick hack for multiselect
        if (isset($_POST['charity']) && $_POST['charity'][0] == "") {
            unset($_POST['charity']);
        }

        $lang = $this->language;
        $userInfo = $this->user->getInfo();

        /**
         * @var \Application\Models\Charity
         */
        $charityM = Model::get('\Application\Models\Charity');
        $charities = $charityM->all();
        $charityIds = [];
        foreach ($charities as $charity) {
            $charityIds[] = $charity['id'];
        }

        $formValidator = FormValidator::instance("workshop");

        $formValidator->setRules([
            'name' => [
                'required' => true,
                'type' => 'string',
                'maxchar' => 100
            ],
            'desc' => [
                'required' => true,
                'type' => 'string',
            ],
            'date' => [
                'required' => true,
                'type' => 'string'
            ],
            'time' => [
                'required' => true,
                'type' => 'string'
            ],
            'duration' => [
                'required' => true,
                'type' => 'number',
                'min' => 10,
            ],
            'price' => [
                'required' => true,
                'type' => 'number',
                'min' => 1
            ],
            'capacity' => [
                'required' => true,
                'type' => 'number',
                'min' => 2,
                'max' => 1000
            ],
            'charity' => [
                'type' => 'multiselect',
                'values' => $charityIds
            ],
            'profit_proceed_type_id' => [
                'required' => true,
                'type' => 'number',
            ],
            'invite' => [
                'type' => 'string'
            ]
        ])->setErrors([
            'name.required' => $lang('workshop_name_required'),
            'name.maxchar' => $lang('workshop_name_max', [
                'max' => 100
            ]),
            'desc.required' => $lang('workshop_desc_required'),
            'date.required' => $lang('workshop_date_required'),
            'time.required' => $lang('workshop_time_required'),
            'duration.required' => $lang('workshop_duration_required'),
            'duration.type' => $lang('workshop_duration_should_number'),
            'duration.min' => $lang('workshop_duration_invalid', [
                'min' => 10
            ]),
            'price.required' => $lang('workshop_price_required'),
            'price.type' => $lang('workshop_price_should_number'),
            'price.min' => $lang('workshop_price_invalid'),
            'capacity.required' => $lang('workshop_capacity_required'),
            'capacity.type' => $lang('workshop_capacity_should_number'),
            'capacity.min' => $lang('workshop_capacity_invalid', [
                'min' => 2,
                'max' => 500
            ]),
            'capacity.max' => $lang('workshop_capacity_invalid', [
                'min' => 2,
                'max' => 1000
            ])
        ]);
        // do the workshop code later.

        if (!$formValidator->validate()) {
            // we need to output the errors
            $errors = [];
            if ($formValidator->hasError('name')) {
                $error[] = '<li>' . $formValidator->getError('name') . '</li>';
            }

            if ($formValidator->hasError('desc')) {
                $error[] = '<li>' . $formValidator->getError('desc') . '</li>';
            }

            if ($formValidator->hasError('date')) {
                $error[] = '<li>' . $formValidator->getError('date') . '</li>';
            }

            if ($formValidator->hasError('time')) {
                $error[] = '<li>' . $formValidator->getError('time') . '</li>';
            }

            if ($formValidator->hasError('duration')) {
                $error[] = '<li>' . $formValidator->getError('duration') . '</li>';
            }

            if ($formValidator->hasError('price')) {
                $error[] = '<li>' . $formValidator->getError('price') . '</li>';
            }

            if ($formValidator->hasError('charity')) {
                $error[] = '<li>' . $formValidator->getError('charity') . '</li>';
            }

            if ($formValidator->hasError('capacity')) {
                $error[] = '<li>' . $formValidator->getError('capacity') . '</li>';
            }

            throw new ResponseJSON('error', "
                    <ul>
                    " . implode('', $error) . "
                    </ul>
                ");
        }

        if ( !$formValidator->hasError('date') && !$formValidator->hasError('time')  )
        {
            $date = $formValidator->getValue('date');

            $currentDate = date('Y-m-d');

            if( $date <= $currentDate )
            {
                $selectedTime = strtotime($formValidator->getValue('time'));
                $minTime = strtotime('+5 minutes', strtotime(date("H:i", time())));

                if( $selectedTime < $minTime )
                {
                    throw new ResponseJSON(
                        'error',
                        '<ul><li>'. $lang('workshop_min_time_error', [
                            'min' => date('h:i a', $minTime)
                        ]) .'</li></ul>'
                    );
                }
                
            }

            // check if the user has already
            $datetime = $formValidator->getValue('date') .' ' . $formValidator->getValue('time');
            $workshop = UserService::init()->findWorkshopInGivenPeriod($userInfo['id'], $datetime, $formValidator->getValue('duration'));

            if ($workshop) {
                throw new ResponseJSON(
                    'error',
                    '<ul><li>'. $lang('already_scheduled_workshop', ['date' => $workshop['date']]) .'</li></ul>'
                );
            }
        }

        // now check invite
        $username = $formValidator->getValue('invite');
        if (!empty($username)) {
            $username = str_replace('@', '', $username);
            if ($username == $userInfo['username']) {
                $formValidator->setError('invite', $lang('workshop_invite_error_self'));
            }

            if (!$formValidator->hasError('invite')) {
                // check if user exist with username
                $result = $this->user->find(array('username' => $username));
                if (!$result) {
                    $formValidator->setError('invite', $lang('workshop_invite_invalid'));
                } else {
                    // check if the user is valid for inviting workshop
                    $result = $this->user->canCreateWorkshop($result['id']);
                    if (!$result) $formValidator->setError('invite', $lang('work_invite_cant_create'));
                }
            }
        }

        if ($formValidator->hasError('invite')) {
            throw new ResponseJSON(
                'error',
                '<ul><li>' . $formValidator->getError('invite') . '</li></ul>'
            );
        }

        // else valid invite

        $config = Config::get("Website");
        $startTime = $formValidator->getValue('date') . ' ' . $formValidator->getValue('time');
        $endTime = date('Y-m-d H:i:s', strtotime($startTime) + $formValidator->getValue('duration') * 60);

        // Now validate allowed.
//        $count = LiveSessionHelper::getCountAt($startTime, $endTime);
//        $avl = $config->max_allowed_concurrent_session - $count;
//
//        if ( $avl < $formValidator->getValue('capacity') + 1 )
//        {
//            $avl = $avl < 0 ? 0 : $avl;
//            throw new ResponseJSON(
//                'error',
//                '<ul><li>' . $lang('you_cant_use_this_time_for_workshop', [ 'count' => $avl ]) . '</li></ul>'
//            );
//        }

        /**
         * @var \Application\Models\Workshop
         */
        $workshopM = Model::get('\Application\Models\Workshop');
        $charity = $formValidator->getValue('charity');
        $charity = !empty($charity) ? json_encode($charity) : '[]';
        $result = $workshopM->create(array(
            'user_id' => $userInfo['id'],
            'profit_proceed_type_id' => $formValidator->getValue('profit_proceed_type_id'),
            'name' => $formValidator->getValue('name'),
            'desc' => $formValidator->getValue('desc'),
            'price' => $formValidator->getValue('price'),
            'capacity' => $formValidator->getValue('capacity'),
            'date' => $formValidator->getValue('date') .' ' . $formValidator->getValue('time'),
            'duration' => $formValidator->getValue('duration'),
            'charity' => $charity,
            'invite' => empty($formValidator->getValue('invite')) ? null : $formValidator->getValue('invite'),
            'status' => ModelsWorkshop::STATUS_NOT_STARTED,
            'created_at' => time()
        ));

        if (!$result) throw new ResponseJSON('error', "Cant create workshop, server error.");

        $this->hooks->dispatch('workshop.on_create', [
            'user_id' => $userInfo['id'],
            'type' => Feed::TYPE_USER_STATUS,
            'data' => json_encode([
                'workshop' => $result
            ]),
            'ref' => 'workshop_' . $result,
            'created_at' => time(),
            'deleted' => 0
        ])->now();

        $session = Model::get(Session::class);
        $session->put('toast_body', $lang('workshop_created'));
        $session->put('toast_header', $lang('success'));
        $session->put('toast_type', 'primary');

        // We may need to create a feed to.
        // create a feed when workshop is created.
        // $this->hooks->dispatch('feed.create', [
        //     'user_id' => $userInfo['id'],
        //     'type' => Feed::TYPE_USER_STATUS,
        //     'data' => json_encode([
        //         'workshop' => $result
        //     ]),
        //     'ref' => 'workshop_' . $result,
        //     'created_at' => time(),
        //     'deleted' => 0
        // ])->now();

        throw new ResponseJSON('success');
    }

    public function profileWorkshop(Request $request, Response $response)
    {
        $userId = $request->post('user_id');
        $date = '';

        if (!empty($request->post('date'))) {
            $date = $request->post('date');
        }

        /**
         * @var \Application\Models\Workshop
         */
        $workshopM = Model::get('\Application\Models\Workshop');
        $workshops = $workshopM->findUserWorkshops($userId, 0, null, $date);
        $workshops = WorkshopHelper::prepare($workshops);


        $output = array();

        foreach ($workshops as $workshop) {
            $view = new View();
            $view->set('workshop/book_card', [
                'workshop' => $workshop
            ]);
            $output[] = $view->content();
        }

        if (empty($output)) {
            $output = '<p class="text-center">No data available</p>';
        }

        throw new ResponseJSON('success', $output);
    }

    public function search(Request $request, Response $response)
    {
        $term = $request->post('term');
        $type = $request->post('type');

        $userInfo = $this->user->getInfo();

        $search = array('user_id' => $userInfo['id']);
        if (!empty($term)) {
            $search['name'] = $term;
        }

        $participant = null;
        if ($type == 'b') {
            $participant = $userInfo['id'];

            // We don't need user id for beneficiary
            unset($search['user_id']);
        }

        /**
         * @var \Application\Models\Workshop
         */
        $workshopM = Model::get('\Application\Models\Workshop');
        $workshops = $workshopM->getList($search, 0, 5, $participant);

        throw new ResponseJSON('success', $workshops);
    }

    public function findSearch(Request $request, Response $response)
    {
        $term = $request->post('term');
        $follow = $request->post('follow');

        $userInfo = $this->user->getInfo();

        $terms = null;

        if( !empty($term) )
        {
            $terms = [$term];
        }

        /**
         * @var \Application\Models\Workshop
         */
        $workshopM = Model::get('\Application\Models\Workshop');
        $workshops = $workshopM->findForBooking($userInfo['id'], 0, 5, null , false, $terms);

        throw new ResponseJSON('success', $workshops);
    }

    public function more(Request $request, Response $response)
    {
        $userInfo = $this->user->getInfo();

        $skip = $request->post('skip');
        $limit = $request->post('limit');
        $query = $request->post('query', []);
        $type = $request->post('type');
        $isAdvisor = $type != 'b';

//        if (!$isAdvisor) $participant = $userInfo['id'];

        /**
         * @var \Application\Models\Workshop
         */
        $workshopM = Model::get('\Application\Models\Workshop');
        $workshops = $workshopM->getList($query, $skip, $limit, $userInfo['id']);
        $workshops = WorkshopHelper::prepare($workshops);

        $output = array();
        foreach ($workshops as $workshop) {
            $view = new View();
            $view->set('Workshop/workshop', [
                'workshop' => $workshop,
                'user' => $userInfo,
//                'isAdvisor' => $isAdvisor
            ]);
            $output[] = $view->content();
        }

        throw new ResponseJSON('success', array(
            'skip' => $skip + $limit,
            'dataAvl' => count($output) == $limit,
            'workshops' => $output,
            'isAdvisor' => $isAdvisor
        ));
    }

    public function findMore(Request $request, Response $response)
    {
        $skip = $request->post('skip');
        $limit = $request->post('limit');
        $user = $request->post('user');

        // var_dump($skip, $limit);

        $names = null;
        if( !empty($request->post('names')) )
        {
            $names = $request->post('names');
            $names = json_decode($names, true);
        }

        
        /**
         * @var \Application\Models\Workshop
         */
        $workshopM = Model::get('\Application\Models\Workshop');

        if( !empty($user) )
        {
            $workshops = $workshopM->findUserWorkshops($user, $skip, $limit, null , false);
        } else {            
            $userInfo = $this->user->getInfo();
            $workshops = $workshopM->findForBooking($userInfo['id'], $skip, $limit, null, false, $names);
        }

        $workshops = WorkshopHelper::prepare($workshops);

        $output = [];
        foreach ($workshops as $workshop) {
            $view = new View();
            $view->set('Workshop/book_card', [
                'workshop' => $workshop
            ]);
            $output[] = $view->content();
        }

        throw new ResponseJSON('success', array(
            'dataAvl' => count($output) == $limit,
            'skip' => $skip + $limit,
            'workshops' => $output
        ));
    }

    public function start(Request $request, Response $response)
    {
        $id = $request->post('id');
        if (empty($id)) throw new ResponseJSON('error', "Invalid entry");

        /**
         * @var \Application\Models\Workshop
         */
        $workM = Model::get('\Application\Models\Workshop');
        $workshop = $workM->getInfoByIds($id);
        if (empty($workshop)) throw new ResponseJSON('error', "Invalid workshop");

        $workshop = $workshop[$id];

        // validate if ready to start
        $data = $this->hooks->dispatch('service.start_validation', [
            'type' => ModelsWorkshop::ENTITY_TYPE,
            'item' => $workshop
        ])->now();
        if (!isset($data['\Application\Hooks\Service::validateStart']))
            throw new ResponseJSON('error', "Hook not found");
        $data = $data['\Application\Hooks\Service::validateStart'];

        if (!$data['isValid']) throw new ResponseJSON('error', $data['msg']);

        // Start the workshop
        $workM->update($id, [
            'status' => ModelsWorkshop::STATUS_CURRENT
        ]);

        $this->hooks->dispatch('service.on_start', [
            'type' => ModelsWorkshop::ENTITY_TYPE,
            'item' => $workshop
        ])->now();

        throw new ResponseJSON('success');
    }

    public function delete(Request $request, Response $response)
    {
        $lang = $this->language;
        $id = $request->post('id');

        if (empty($id)) throw new ResponseJSON('error', "Invalid entry");

        /**
         * @var \Application\Models\Order
         */
        $orderM = Model::get('\Application\Models\Order');
        $exists = $orderM->ifOrdered( $id, ModelsWorkshop::ENTITY_TYPE );

        if( !empty($exists) ) throw new ResponseJSON('error', $lang('workshop_already_booked'));

        /**
         * @var \Application\Models\Workshop
         */
        $workM = Model::get('\Application\Models\Workshop');
        $workshop = $workM->getInfoByIds($id);
        if (empty($workshop)) throw new ResponseJSON('error', "Invalid workshop");

        $workshop = $workshop[$id];

        // Start the workshop
        $workM->delete($id);

        $this->hooks->dispatch('workshop.on_delete', $workshop)->later();

        throw new ResponseJSON('success');
    }

    public function cancel(Request $request, Response $response)
    {
        $id = $request->post('id');
        $coupon = $request->post('coupon');

        if (empty($id)) throw new ResponseJSON('error', "Invalid entry");

        /**
         * @var \Application\Models\Workshop
         */
        $workM = Model::get('\Application\Models\Workshop');
        $workshop = $workM->getInfoByIds($id);
        if (empty($workshop)) throw new ResponseJSON('error', "Invalid workshop");

        $workshop = $workshop[$id];
        $workshop['cancel_coupon'] = trim($coupon);

        // validate if ready to start
        $data = $this->hooks->dispatch('service.cancel_validation', [
            'type' => ModelsWorkshop::ENTITY_TYPE,
            'item' => $workshop
        ])->now();
        if (!isset($data['\Application\Hooks\Service::validateCancel']))
            throw new ResponseJSON('error', "Hook not found");
        $data = $data['\Application\Hooks\Service::validateCancel'];

        if (!$data['isValid']) throw new ResponseJSON('error', $data['msg']);

        // Start the workshop
        $workM->update($id, [
            'status' => ModelsWorkshop::STATUS_CANCELED
        ]);

        $this->hooks->dispatch('service.on_cancel', [
            'id' => $workshop['id'],
            'type' => ModelsWorkshop::ENTITY_TYPE,
            'item' => $workshop
        ])->later();

        throw new ResponseJSON('success');
    }

    public function join(Request $request, Response $response)
    {
        $id = $request->post('id');
        if (empty($id)) throw new ResponseJSON('error', "Invalid entry");

        /**
         * @var \Application\Models\Workshop
         */
        $workM = Model::get('\Application\Models\Workshop');
        $workshop = $workM->getInfoByIds($id);
        if (empty($workshop)) throw new ResponseJSON('error', "Invalid workshop");

        $workshop = $workshop[$id];

        // validate if ready to start
        $data = $this->hooks->dispatch('service.join_validation', [
            'type' => ModelsWorkshop::ENTITY_TYPE,
            'item' => $workshop
        ])->now();
        if (!isset($data['\Application\Hooks\Service::validateJoin']))
            throw new ResponseJSON('error', "Hook not found");
        $data = $data['\Application\Hooks\Service::validateJoin'];

        if (!$data['isValid']) throw new ResponseJSON('error', $data);

        $data = $this->hooks->dispatch('service.on_join', [
            'type' => ModelsWorkshop::ENTITY_TYPE,
            'item' => $workshop
        ])->now();
        // Lets join the workshop the workshop
        // $workM->update($id, [
        //     'status' => ModelsWorkshop::STATUS_CURRENT
        // ]);

        if ( empty($data['\Application\Hooks\Service::onJoin']) )
            throw new ResponseJSON('error', "Hook not found");
        $data = json_decode($data['\Application\Hooks\Service::onJoin'], true);

        throw new ResponseJSON('success', $data);
    }

    public function complete(Request $request, Response $response)
    {
        $id = $request->post('id');
        if (empty($id)) throw new ResponseJSON('error', "Invalid entry");

        /**
         * @var \Application\Models\Workshop
         */
        $workM = Model::get('\Application\Models\Workshop');
        $workshop = $workM->getInfoByIds($id);
        if (empty($workshop)) throw new ResponseJSON('error', "Invalid workshop");

        $workshop = $workshop[$id];

        // validate if ready to start
        $data = $this->hooks->dispatch('service.complete_validation', [
            'type' => ModelsWorkshop::ENTITY_TYPE,
            'item' => $workshop
        ])->now();
        if (!isset($data['\Application\Hooks\Service::validateComplete']))
            throw new ResponseJSON('error', "Hook not found");
        $data = $data['\Application\Hooks\Service::validateComplete'];

        if (!$data['isValid']) throw new ResponseJSON('error', $data['msg']);

        $workM->update($workshop['id'], [
            'status' => ModelsWorkshop::STATUS_COMPLETED
        ]);
        
        $this->hooks->dispatch('service.on_complete', [
            'id' => $workshop['id'],
            'type' => ModelsWorkshop::ENTITY_TYPE,
            'item' => $workshop
        ])->later();

        throw new ResponseJSON('success');
    }

    public function startAndJoin(Request $request, Response $response)
    {
        // start workflow
        $id = $request->post('id');
        if (empty($id)) throw new ResponseJSON('error', "Invalid entry");

        $workM = Model::get(ModelsWorkshop::class);
        $workshop = $workM->getInfoById($id);

        if (empty($workshop)) {
            throw new ResponseJSON('error', "Invalid workshop");
        }

        if (in_array($workshop['status'], [ModelsWorkshop::STATUS_NOT_STARTED, ModelsWorkshop::STATUS_PREPARING])) {

            if ((strtotime($workshop['date']) > time()) && (strtotime($workshop['date']) - time()) <= 300) {
                $workM->update($id, [
                    'status' => ModelsWorkshop::STATUS_PREPARING
                ]);

                $this->hooks->dispatch('service.on_start', [
                    'type' => ModelsWorkshop::ENTITY_TYPE,
                    'item' => $workshop
                ])->now();

                $data = $this->hooks->dispatch('service.on_join', [
                    'type' => ModelsWorkshop::ENTITY_TYPE,
                    'item' => $workshop
                ])->now();

                if (empty($data['\Application\Hooks\Service::onJoin'])) {
                    throw new ResponseJSON('error', "Hook not found");
                }

                $data = json_decode($data['\Application\Hooks\Service::onJoin'], true);
                throw new ResponseJSON('success', $data);

            }
            else {
                // validate if ready to start
                $data = $this->hooks->dispatch('service.start_validation', [
                    'type' => ModelsWorkshop::ENTITY_TYPE,
                    'item' => $workshop
                ])->now();
                if (!isset($data['\Application\Hooks\Service::validateStart'])) {
                    throw new ResponseJSON('error', "Hook not found");
                }

                $data = $data['\Application\Hooks\Service::validateStart'];

                if (!$data['isValid']) {
                    throw new ResponseJSON('error', $data);
                }

                // Start the workshop
                $workM->update($id, [
                    'status' => ModelsWorkshop::STATUS_CURRENT
                ]);

                $this->hooks->dispatch('service.on_start', [
                    'type' => ModelsWorkshop::ENTITY_TYPE,
                    'item' => $workshop
                ])->now();

                // modify the status to "current"
                $workshop['status'] = ModelsWorkshop::STATUS_CURRENT;
            }

        }

        // join workflow

        // validate if ready to join
        $data = $this->hooks->dispatch('service.join_validation', [
            'type' => ModelsWorkshop::ENTITY_TYPE,
            'item' => $workshop
        ])->now();

        if (!isset($data['\Application\Hooks\Service::validateJoin'])) {
            throw new ResponseJSON('error', "Hook not found");
        }

        $data = $data['\Application\Hooks\Service::validateJoin'];

        if (!$data['isValid']) {
            throw new ResponseJSON('error', $data);
        }

        $data = $this->hooks->dispatch('service.on_join', [
            'type' => ModelsWorkshop::ENTITY_TYPE,
            'item' => $workshop
        ])->now();

        if (empty($data['\Application\Hooks\Service::onJoin'])) {
            throw new ResponseJSON('error', "Hook not found");
        }

        $data = json_decode($data['\Application\Hooks\Service::onJoin'], true);
        throw new ResponseJSON('success', $data);
    }
}
