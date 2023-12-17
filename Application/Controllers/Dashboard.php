<?php

namespace Application\Controllers;

use Application\Controllers\Admin\SubSpecialists;
use Application\Helpers\FeedHelper;
use Application\Helpers\UserHelper;
use Application\Main\AuthController;
use Application\Models\Follow;
use Application\Models\Specialty;
use Application\Models\User;
use Application\Models\UserSpecialty;
use Application\Models\UserSubSpecialty;
use System\Core\Config;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Responses\View;

class Dashboard extends AuthController
{
    public function index(Request $request, Response $response)
    {
        $lang = $this->language;
        $userInfo = $this->user->getInfo();
        $selected = null;
        $entitySelected = null;
        $users = null;
        $query = $request->param(0);

        $limit = Config::get('Website')->items_per_page;


        if ($query !== null) {
            if (str_contains($request->getUri(), 'entities')) {
                $entitySelected = $this->user->getEntityByName(urldecode($query));

                $users = $this->user->search('', null, null, 0, $limit, null, false, null, false, $entitySelected['id']);
            } else {
                $specialityObj = Model::get(Specialty::class);
                $selected = $specialityObj->getBySpecialty(urldecode($query));
                $users = $this->user->search('', null, null, 0, $limit, null, false, $selected['id']);
            }
        } else{
            $users = $this->user->search('', null, null, 0, $limit, null, false, null);
        }

        $users = UserHelper::prepare($users);
        $followM = Model::get(Follow::class);
        $followingIds = $followM->getFollowingIds($userInfo['id']);
        $useSpec = Model::get(UserSpecialty::class);
        $sepcs = $useSpec->getTrending(50);


        $entities = $this->user->getEntitiesForDashboard();

        $view = new View();
        $view->set('Dashboard/index', [
            'userInfo' => $userInfo,
            'users' => $users,
            'limit' => $limit,
            'specs' => $sepcs,
            'followingIds' => $followingIds,
            'selected' => $selected,
            'entities' => $entities,
            'entitySelected' => $entitySelected
        ]);

        $view->prepend('header', [
            'title' => $lang('explore')
        ]);
        $view->append('footer');

        $response->set($view);
    }
}
