<?php

namespace Application\Controllers\Ajax;

use Application\Helpers\UserHelper;
use Application\Main\ResponseJSON;
use System\Core\Controller;
use System\Core\Model;
use System\Core\Request;
use Application\Models\User as UserModel;
use System\Responses\View;

class User extends Controller
{
    public function search( Request $request )
    {
        $term = $request->post('term');
        $spec = $request->post('spec');
        $subSpec = $request->post('subSpec');

        $users = $this->user->search($term, $subSpec, null, 0, 5, null, false, $spec);

        $output = array();
        foreach ( $users as $user )
        {
            $user['avatarUrl'] = UserHelper::getAvatarUrl('fit:300,300', $user['id']);
            $view = new View();
            $view->set('Outer/Home/user', [
                'user' => $user,
            ]);
            $output[] = $view->content();
        }

        throw new ResponseJSON('success', $output);
    }

    public function searchBySpec( Request $request )
    {
        $id = $request->post('id');
        $isLoggedIn = $request->post('isLoggedIn');

        $users = $this->user->searchBySpec( $id, true );
        $users = UserHelper::prepare($users);

        $output = array();
        foreach ( $users as $user )
        {
            $user['avatarUrl'] = UserHelper::getAvatarUrl('fit:300,300', $user['id']);            
            $view = new View();
            $view->set('Outer/Home/user', [
                'user' => $user,
                'isLoggedIn' => $isLoggedIn
            ]);
            $output[] = $view->content();
        }

        throw new ResponseJSON('success', $output);
    }

    public function checkUsernameAvailability(Request $request)
    {
        $username = $request->post('username');

        if (strlen($username) < 4 || strlen($username) > 15) {
            throw new ResponseJSON('error');
        }

        $userM = Model::get(UserModel::class);
        $decision = $userM->checkUsername($username);

        if (!$decision) {
            throw new ResponseJSON('error');
        } else {
            throw new ResponseJSON('success');
        }

    }
}