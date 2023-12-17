<?php

namespace Application\Controllers;

use Application\Helpers\AppHelper;
use Application\Helpers\CallHelper;
use Application\Helpers\LiveSessionHelper;
use Application\Helpers\WorkshopHelper;
use Application\Models\CallSlot;
use Application\ThirdParties\Whatsapp\Whatsapp;
use Application\Main\AuthController;
use Application\Models\Call as CallModel;
use Application\Models\Notification as NotificationModel;
use Application\Models\Queue as QueueModel;
use Application\Models\User;
use System\Core\Config;
use System\Core\Exceptions\Error404;
use System\Core\Exceptions\Redirect;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Helpers\URL;
use System\Libs\FormValidator;
use System\Responses\View;

class Calls extends AuthController
{
    public function __construct($modelList)
    {
        $this->_ignore(['calls/find']);
        parent::__construct($modelList);
    }

    public function index(Request $request, Response $response)
    {
        $userInfo = $this->user->getInfo();

        $limit = $request->post('request');
        $limit = empty($limit) ? 8 : $limit;

        $ids = $request->post('id');
        $status = $request->post('status');
        $date = $request->post('date');

        $callM = Model::get(CallModel::class);

        $query = ['is_temp' => 0];
        if (!empty($status)) $query['status'] = $status;
        if (!empty($date)) $query['date'] = $date;

        $calls = $callM->getList($userInfo['id'], $query, 0, $limit);
        $calls = CallHelper::prepare($calls, $userInfo['id']);

        $selectedUsers = [];
        if (!empty($ids)) {
            $selectedUsers = $this->user->getInfoByIds($ids);
        }

        $view = new View();
        $view->set('Calls/index', [
            'calls' => $calls,
            'limit' => $limit,
            'query' => $query,
            'selectedUsers' => $selectedUsers,
        ]);
        $view->prepend('header');
        $view->append('footer');

        $response->set($view);
    }

    public function find(Request $request, Response $response)
    {
        $baseURL = AppHelper::getBaseUrl();
        $serviceProviderUserId = $request->param(0);
        $date = $request->post('date');

        $serviceProviderUser = $this->user->getUser($serviceProviderUserId);
        if ($serviceProviderUser['suspended'] == 1) throw new Error404();

        $from = date('Y-m-d', strtotime('today'));
        $to = $date;
        if ($date) {
            $from = $date;
        }

        //----------------
        $date1 = str_replace("T", " ", $request->post('date1'));
        $date2 = str_replace("T", " ", $request->post('date2'));
        $date3 = str_replace("T", " ", $request->post('date3'));
        $isCallRequestCreated = false;

        $callRequestId = null;
        if ($date1 && $date2 && $date3) {
            $beneficiary = $this->user->getInfo();
            $callRequestId = $this->hooks->dispatch('call.on_customize_call', [
                'serviceProviderUserId' => $serviceProviderUserId,
                'beneficiary' => $beneficiary,
                'date1' => $date1,
                'date2' => $date2,
                'date3' => $date3,
            ])->now();

            if (isset($callRequestId['\Application\Hooks\Call::callRequest'])) {
                $isCallRequestCreated = true;
                $callRequestId = $callRequestId['\Application\Hooks\Call::callRequest'];
            }
        }

        /**
         * @var \Application\Models\CallSlot
         */
        $callSM = Model::get('\Application\Models\CallSlot');
        $slots = $callSM->getSlots($serviceProviderUserId, $from, $to);
        // Filter un wanted slots.
        
        $callM = Model::get(CallModel::class);
        foreach ($slots as $key => $slot) {
            //$time = strtotime($slot['date'] . ' ' . $slot['time']) - 10 * 60;            
            if (WorkshopHelper::orderExpired($slot['date'] . ' ' . $slot['time'])) {
                unset($slots[$key]);
                continue;
            }

            $call = $callM->getBySlotId($slot['id']);
            if (empty($call)) continue;
            if ($call['is_temp'] == 1) continue;
            if ($call['status'] == CallModel::STATUS_CANCELED) continue;

            // else remove the slot
            unset($slots[$key]);
        }

        $slots = CallHelper::prepareCalender($slots);

    if ($isCallRequestCreated) {
        
        $userMessage = <<<MESSAGE
أهلا بك {$beneficiary["name"]}

لقد تم ارسال الاوقات المقترحه للمستخدم {$serviceProviderUser['name']} وفي حال تم التأكيد سوف يرسل لك رابط اكمال عملية الحجز

وفي حال ان الاوقات المقترحه غير مناسبه للمستخدم {$serviceProviderUser['name']} سوف يتم ابلاغك حين يتم جدولة اوقات اخرى متاحه للحجز

شكراً لك 
MESSAGE;


        Whatsapp::sendChat($beneficiary['phone'], $userMessage);

        $ownerMessage = <<<MESSAGE
أهلاً بك {$serviceProviderUser['name']}

 لديك طلب حجز مكالمة مدفوعه من المستخدم {$beneficiary['name']} عبر منصة تيلي ان

الاوقات المقترحه من قبل طالب المكالمه هي:
 
١- {$date1}
٢- {$date2}
٣- {$date3}

الرجاء تحديد الوقت المناسب لك من بينها كي يتم تأكيده من المستخدم طالب المكالمة عن طريق الرابط التالي:

{$baseURL}/calls/request/{$callRequestId}

في حال ان الاوقات المقترحه غير مناسبة لك تستطيع جدولة اوقات مناسبة لك اخرى وسوف يتم تنبيه طالب المكالمه عنها وذلك عن طريق الرابط التالي

{$baseURL}/calls/manage

شكرا لك
MESSAGE;



//            sprintf('%s "%s" %s \n %s \n %s', ' لديك طلب حجز مكالمة مدفوعة من المستخدم ', $beneficiary["name"], 'عبر منصة تيلي ان' , 'الرجاء تحديد الوقت المناسب لك بينها كي يتم تاكيده من المستخدم طالب الخدمة ', 'في حال ان الاوقات المقترحة غير مناسبه لك تستطيع جدولة اوقات مناسبة لك اخري وسوف يتم تنبية طالب المكالمة عنها شكزا لك');

        Whatsapp::sendChat($serviceProviderUser['phone'], $ownerMessage);
    }

        $view = new View();
        $view->set('Calls/find', [
            'slots' => $slots,
            'user' => $serviceProviderUser,
            'date' => $date,
            'isCallRequestCreated' => $isCallRequestCreated
        ]);
        if ($this->user->isLoggedIn()) {
            $view->prepend('header');
            $view->append('footer');
        } else {
            $view->prepend('Outer/header');
            $view->append('Outer/footer');
        }

        $response->set($view);
    }

    public function manage(Request $request, Response $response)
    {
        /**
         * @var \Application\Models\Charity
         */
        $charityM = Model::get('\Application\Models\Charity');
        $charities = $charityM->all();

        $userInfo = $this->user->getInfo();

        if (!$this->user->canCreateWorkshop()) throw new Error404();

        $isCreated = $this->session->take('call_created') == "1";
        $this->session->delete('call_created');

        $form = $request->get('from');
        $to = $request->get('to');
        $skip = $request->get('skip');
        $skip = !is_numeric($skip) ? 0 : $skip;

        $form = empty($form) ? date('Y-m-d', time()) : $form;
        $to = empty($to) ? date('Y-m-d', strtotime('+7 days')) : $to;

        $limit = 17;

        /**
         * @var \Application\Models\CallSlot
         */
        $callSM = Model::get('\Application\Models\CallSlot');
        $slots = $callSM->getSlots($userInfo['id'], $form, $to, $skip, $limit);
        $dataAvl = $limit == count($slots);
        $slots = CallHelper::prepareCalender($slots);

        $view = new View();
        $view->set('Calls/manage', [
            'isCreated' => $isCreated,
            'from' => $form,
            'to' => $to,
            'slots' => $slots,
            'dataAvl' => $dataAvl,
            'skip' => $skip + $limit,
            'charities' => $charities
        ]);
        $view->append('footer');
        $view->prepend('header');

        $response->set($view);
    }

    public function addSlot(Request $request, Response $response)
    {
        // Quick hack for multiselect
        if (isset($_POST['charities']) && $_POST['charities'][0] == "") {
            unset($_POST['charities']);
        }

        $userInfo = $this->user->getInfo();
        $lang = $this->language;

        if (!$this->user->canCreateWorkshop()) throw new Error404();

        /**
         * @var \Application\Models\Charity
         */
        $charityM = Model::get('\Application\Models\Charity');
        $charities = $charityM->all();
        foreach ($charities as $charity) {
            $charityIds[] = $charity['id'];
        }

        $formValidator = FormValidator::instance("call_modify");
        $formValidator->setRules([
            'date' => [
                'required' => true,
                'type' => 'string'
            ],
            'time' => [
                'required' => true,
                'type' => 'string'
            ],
            'to_time' => [
                'required' => false,
                'type' => 'string'
            ],
            'price' => [
                'required' => true,
                'type' => 'number',
                'min' => 1
            ],
            'charities' => [
                'type' => 'multiselect',
                'values' => $charityIds
            ],
            'profit_proceed_type_id' => [
                'required' => true,
                'type' => 'string'
            ]
        ])->setErrors([
            'date.required' => $lang('field_required'),
            'time.required' => $lang('field_required'),
            'price.required' => $lang('field_required'),
            'price.type' => $lang('workshop_price_should_number'),
            'price.min' => $lang('workshop_price_invalid')
        ]);

        if ($request->getHTTPMethod() == "POST" && $formValidator->validate()) {
            $config = Config::get("Website");
            $creationType = $request->post('creation_type');

            if ($creationType == 'many_slots') {
                $endTime = 0;
                $startTime = $formValidator->getValue('date') . ' ' . $formValidator->getValue('time') . ':00';
                $toTime = $formValidator->getValue('date') . ' ' . $formValidator->getValue('to_time') . ':00';

                while(strtotime($endTime) <= strtotime($toTime)) {
                    $endTime = date('Y-m-d H:i:s', strtotime($startTime) + $config->call_duration * 60);

                    $count = LiveSessionHelper::getCountAt($startTime, $endTime);
                    $avl = $config->max_allowed_concurrent_session - $count;

                    $isValid = true;
                    if ($avl < 2) {
                        $formValidator->setError('date', $lang('you_cant_use_this_time_for_call'));
                        $formValidator->setError('time', $lang('you_cant_use_this_time_for_call'));

                        $isValid = false;
                    }

                    if ($isValid) {
                        $charities = $formValidator->getValue('charities');
                        $charities = empty($charities) ? "[]" : json_encode($charities);


                        $callSM = Model::get(CallSlot::class);
                        $callId = $callSM->create([
                            'user_id' => $userInfo['id'],
                            'profit_proceed_type_id' => $formValidator->getValue('profit_proceed_type_id'),
                            'date' => $formValidator->getValue('date'),
                            'time' => $startTime,
                            'price' => $formValidator->getValue('price'),
                            'charity' => $charities,
                            'created_at' => time()
                        ]);

                        $this->hooks->dispatch('call_slot.on_create', $callId)->now();

                        $this->session->put('call_created', 1);
                    }

                    $startTime = $endTime;
                }

                throw new Redirect(URL::full('calls/manage'));
            }

            $startTime = $formValidator->getValue('date') . ' ' . $formValidator->getValue('time') . ':00';
            $endTime = date('Y-m-d H:i:s', strtotime($startTime) + $config->call_duration * 60);


            // Now validate allowed.
            $count = LiveSessionHelper::getCountAt($startTime, $endTime);
            $avl = $config->max_allowed_concurrent_session - $count;

            $isValid = true;
            if ($avl < 2) {
                $formValidator->setError('date', $lang('you_cant_use_this_time_for_call'));
                $formValidator->setError('time', $lang('you_cant_use_this_time_for_call'));

                $isValid = false;
            }

            if ($isValid) {

                $charities = $formValidator->getValue('charities');
                $charities = empty($charities) ? "[]" : json_encode($charities);


                $callSM = Model::get('\Application\Models\CallSlot');
                $callId = $callSM->create([
                    'user_id' => $userInfo['id'],
                    'profit_proceed_type_id' => $formValidator->getValue('profit_proceed_type_id'),
                    'date' => $formValidator->getValue('date'),
                    'time' => $formValidator->getValue('time'),
                    'price' => $formValidator->getValue('price'),
                    'charity' => $charities,
                    'created_at' => time()
                ]);

                $this->hooks->dispatch('call_slot.on_create', $callId)->now();

                $this->session->put('call_created', 1);
                throw new Redirect(URL::full('calls/manage'));
            }

        }


        $view = new View();
        $view->set('Calls/add', [
            'charities' => $charities
        ]);
        $view->append('footer');
        $view->prepend('header');

        $response->set($view);
    }

    public function checkout(Request $request, Response $response)
    {

        $id = $request->post('slot');
        if (empty($id)) throw new Error404;

        $userInfo = $this->user->getInfo();

        // First get the slot details 
        /**
         * @var \Application\Models\CallSlot
         */
        $callSM = Model::get('\Application\Models\CallSlot');
        $slot = $callSM->getById($id);
        if (empty($slot)) throw new Error404;

        $config = Config::get("Website");

        // Create a call
        /**
         * @var \Application\Models\Call
         */
        $callM = Model::get('\Application\Models\Call');
        $id = $callM->create([
            'created_by' => $userInfo['id'],
            'owner_id' => $slot['user_id'],
            'profit_proceed_type_id' => $slot['profit_proceed_type_id'],
            'date' => $slot['date'] . ' ' . $slot['time'],
            'duration' => $config->call_duration,
            'price' => $slot['price'],
            'slot_id' => $id,
            'charity' => $slot['charity'],
            'status' => CallModel::STATUS_NOT_STARTED,
            'is_temp' => 1, // Default is in temp
            'created_at' => time()
        ]);

        $view = new View();
        $view->set('Checkout/prepare_page', [
            'id' => $id,
            'type' => CallModel::ENTITY_TYPE
        ]);
        $view->append('footer');
        $view->prepend('header');

        $response->set($view);
    }
}