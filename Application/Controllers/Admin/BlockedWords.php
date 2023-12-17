<?php

namespace Application\Controllers\Admin;

use System\Core\Controller;
use Application\Main\AdminController;
use Application\Main\ResponseJSON;
use System\Core\Config;
use System\Core\Exceptions\Redirect;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Helpers\Strings;
use System\Libs\File;
use System\Libs\FormValidator;
use System\Responses\View;

class BlockedWords extends AdminController
{
    public function index( Request $request, Response $response )
    {   
        $userInfo = $this->user->getInfo();

        $blockedM = Model::get('\Application\Models\BlockedWords');
        $allWords = $blockedM->all();
        
        $view = new View();
        $view->set('Admin/BlockedWords/index', [
            'userInfo' => $userInfo,
            'allWords' => $allWords
        ]);
        $view->prepend('Admin/header', [
            'title' => "Welcome to telein",
            'currentPage' => 'Blocked Words',
            'userInfo' => $userInfo
        ]);
        $view->append('Admin/footer');
        
        $response->set($view);
    }

    public function feeds( Request $request, Response $response )
    {   
        $userInfo = $this->user->getInfo();
        $allUsers = $this->user->all();

        $from = $request->get('from') ? strtotime($request->get('from')) : '';
        $to = $request->get('to') ? strtotime($request->get('to')) : '';

        $view = new View();
        $view->set('Admin/BlockedWords/feed', [
            'userInfo' => $userInfo,
            'from' => $from,
            'to' => $to
        ]);
        $view->prepend('Admin/header', [
            'title' => "Welcome to telein",
            'currentPage' => 'Feeds with blocked words',
            'userInfo' => $userInfo
        ]);
        $view->append('Admin/footer');

        $response->set($view);
    }
    public function editBlocked( Request $request, Response $response )
    {   
        $lang = $this->language;
        $userInfo = $this->user->getInfo();
        
        $blockedM = Model::get('\Application\Models\BlockedWords');
        $id = $request->param(0);
        $blockedInfo = $blockedM->getById( $id );

        $formValidator = FormValidator::instance("blockedWords");
        $formValidator->setRules([
            'word' => [
                'required' => true,
                'type' => 'string'
            ]
        ])->setErrors([
            'word.required' => $lang('field_required'),
        ]);

        if ( $request->getHTTPMethod() == 'POST' && $isValid = $formValidator->validate() )
        {
            $word = $formValidator->getValue('word');

            $blockedM->update([
                'word' => $word
            ], $id);

            throw new Redirect("admin/blocked-words");
        }
        
        $view = new View();
        $view->set('Admin/BlockedWords/edit_blocked', [
            'userInfo' => $userInfo,
            'blockedInfo' => $blockedInfo
        ]);
        $view->prepend('Admin/header', [
            'title' => "Welcome to telein",
            'currentPage' => 'Edit Word',
            'userInfo' => $userInfo
        ]);
        $view->append('Admin/footer');
        
        $response->set($view);
    }

    public function addBlocked(Request $request, Response $response)
    {
        $lang = $this->language ;
        $userInfo = $this->user->getInfo();
        $blockedM = Model::get('\Application\Models\BlockedWords');

        if (isset($_FILES['csv']) && $_FILES['csv']['error'] == 0) {
            $file = new File();
            $file->set($_FILES['csv']);
            
            // get supported file types
            $supported = Config::get('Website')->images_support;
            $supported = $supported ? $supported : ['image/jpeg'];

            if ( $file->getExt() != 'csv' )
            throw new ResponseJSON(
                'error',
                $lang('please_upload_csv')
            );
            $filez = $_FILES['csv']['tmp_name'];

            if (($handle = fopen($filez, "r")) !== FALSE) 
            {
                while (($data = fgetcsv($handle)) !== FALSE) 
                {
                    if( !$blockedM->getByWord( $data[0] ) )
                    {
                        $blockedM->create(array('word' => $data[0]));
                    }
                }
            }

            throw new Redirect("admin/blocked-words");
        }

        $view = new View();
        $view->set('Admin/BlockedWords/upload_blocked', [
            'userInfo' => $userInfo
        ]);
        $view->prepend('Admin/header', [
            'title' => "Welcome to telein",
            'currentPage' => 'Upload CSV',
            'userInfo' => $userInfo
        ]);
        $view->append('Admin/footer');

        $response->set($view);
    }
}
