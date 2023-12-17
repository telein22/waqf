<?php

namespace Application\Controllers;

use Application\Helpers\LiveSessionHelper;
use Application\Helpers\UserHelper;
use Application\ThirdParties\Whatsapp\Whatsapp;
use Application\Main\MainController;
use Application\Models\Specialty;
use Application\Models\User;
use Application\Services\StatisticsService;
use Application\Services\UserService;
use Application\Services\WorkshopService;
use System\Core\Config;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Responses\View;

class Home extends MainController
{
    public function index(Request $request, Response $response)
    {
        try {
            $onlyVerified = true;
            $specialtiesModel = Model::get(Specialty::class);
            $specialties = $specialtiesModel->all();

            $spec = $request->get('spec', null);
            $subSpec = $request->get('sub_spec', null);
            $specialityName = $request->param(0);

            $q = $request->get('q');

            $section = null;
            $limit = 9;

            $spec = $spec == 0 ? null : $spec;
            $subSpec = $subSpec == 0 ? null : $subSpec;
            $users = [];
            if (!empty($q) || $spec) {
                $section = 'search';
                $users = $this->user->search($q, $subSpec, null, 0, $limit, null, false, $spec, $onlyVerified);
            }

            if ($specialityName !== null) {
                $section = 'discover';
                // Handle the extracted string
                $useSpec = Model::get(Specialty::class);
                $selected = $useSpec->getBySpecialty(urldecode($specialityName));

                $users = $this->user->search(null, null, null, 0, $limit, null, false, $selected['id'], $onlyVerified);
            }

            if (!empty($users)) {
                $users = UserHelper::prepare($users);

                foreach ($users as &$user) {
                    $user['avatarUrl'] = UserHelper::getAvatarUrl('fit:300,300', $user['id']);
                }
            }


            $statisticsService = StatisticsService::init();
            $usersCount = $statisticsService->getUsersCount();
            $numberOfMinutesForPerformedWorkshops = $statisticsService->getNumberOfMinutesForPerformedWorkshops();
            $numberOfMinutesForPerformedCalls = $statisticsService->getNumberOfMinutesForPerformedCalls();
            $feedViewsCount = $statisticsService->getNumberOfFeedViewers();


            // Available workshops for booking
            $workshopService = WorkshopService::init();
            $availableWorkshops = $workshopService->getAvailableWorkshops();
            foreach ($availableWorkshops as &$workshop) {
                $user = $this->user->getUser($workshop['user_id']); //TODO it should be eager loading
                $user['avatarUrl'] = UserHelper::getAvatarUrl('fit:300,300', $user['id']);
                $workshop['user'] = $user;

            }

            // High rated users
            $userService = UserService::init();
            $highRatedUsers = $userService->getHighRatedUsers();
            foreach ($highRatedUsers as &$user) {
                $user['avatarUrl'] = UserHelper::getAvatarUrl('fit:300,300', $user['id']);
            }

            $view = new View();
            $view->set('Outer/Home/index', [
                'section' => $section,
                'specialties' => $specialties,
                'users' => $users,
                'isLoggedIn' => $this->user->isLoggedIn(),
                'usersCount' => $usersCount,
                'numberOfMinutesForPerformedWorkshops' => $numberOfMinutesForPerformedWorkshops,
                'numberOfMinutesForPerformedCalls' => $numberOfMinutesForPerformedCalls,
                'feedViewsCount' => $feedViewsCount,
                'availableWorkshops' => $availableWorkshops,
                'highRatedUsers' => $highRatedUsers

            ]);
            $view->prepend('Outer/header', [
                'title' => "Welcome to telein",
                'availableWorkshops' => $availableWorkshops,
            ]);
            $view->append('Outer/footer');

            $response->set($view);
        } catch (\Throwable $e){
            var_dump($e->getMessage());die();
        }
    }
}