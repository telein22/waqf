<?php

namespace Application\Controllers\Ajax;

use Application\Helpers\FeedHelper;
use Application\Main\AuthController;
use Application\Main\ResponseJSON;
use System\Core\Config;
use System\Core\Model;
use System\Core\Request as CoreRequest;
use System\core\Response;
use System\Responses\View;

class Suggestion extends AuthController
{
    public function more(CoreRequest $request)
    {
        $q = $request->post('q');
        $userInfo = $this->user->getInfo();

        $skip = $request->post('skip');

        $limit = Config::get('Website')->items_per_page;

        $users = $this->user->Search($q, null, null, $skip, $limit, $userInfo['id']);

        $output = [];
        foreach ($users as $user) {
            $view = new View();
            $view->set('Dashboard/user', ['user' => $user]);

            $output[] = $view->content();
        }

        throw new ResponseJSON('success', [
            'skip' => $skip + $limit,
            'list' => $output,
            'dataAvl' => count($output) == $limit
        ]);
    }

    public function dashboardSearch(CoreRequest $request, Response $response)
    {
        $q = $request->post('q');
        $limit = Config::get('Website')->items_per_page;
        $skip = 0;
        $output = [
            'data' => [],
            'dataAvl' => false
        ];

        $users = $this->user->search($q, null, null, $skip, $limit);

        foreach ($users as $user) {
            $view = new View();
            $view->set('Dashboard/user', ['user' => $user]);
            $output['data'][] = $view->content();
        }

        $output['dataAvl'] = count($output['data']) == $limit;

        throw new ResponseJSON('success', $output);
    }
}