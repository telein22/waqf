<?php

namespace Application\Controllers\Admin;

use Application\Models\User;
use System\Core\Controller;
use Application\Main\AdminController;
use Application\Models\UserSettings;
use System\Core\Exceptions\Redirect;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Responses\File;
use System\Libs\FormValidator;
use System\Responses\View;

class Users extends AdminController
{
    public function index(Request $request, Response $response)
    {
        $userInfo = $this->user->getInfo();
        $allUsers = $this->user->all();

        $lang = $this->language;

        $view = new View();
        $view->set('Admin/Users/index', [
            'userInfo' => $userInfo,
            'allUsers' => $allUsers,
        ]);
        $view->prepend('Admin/header', [
            'title' => "Welcome to telein",
            'currentPage' => $lang('users'),
            'userInfo' => $userInfo
        ]);
        $view->append('Admin/footer');

        $response->set($view);
    }

    public function editUser(Request $request, Response $response)
    {
        $lang = $this->language;
        $userInfo = $this->user->getInfo();

        /**
         * @var \Application\Models\Country
         */
        $countryM = Model::get('\Application\Models\Country');
        $countries = $countryM->getAll();

        /**
         * @var \Application\Models\Specialty
         */
        $specialtiesModel = Model::get("\Application\Models\Specialty");
        $specialties = $specialtiesModel->all();

        $id = $request->param(0);
        $editInfo = $this->user->getUser($id);

        $sIds = array();
        foreach ($specialties as $specialty) {
            $sIds[] = $specialty['id'];
        }

        $rules = [
            'name' => [
                'required' => true,
                'type' => 'string'
            ],
            'email' => [
                'required' => true,
                'type' => 'string',
                'unique' => 'users,email,' . $editInfo['email'],
                'pattern' => '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'
            ],
            'country' => [
                'required' => true,
                'type' => 'string'
            ],
            'city' => [
                'required' => true,
                'type' => 'string'
            ],
            'spl' => [
                'required' => true,
                'type' => 'multiselect',
                'values' => $sIds
            ],
            'snapchat' => [
                'type' => 'string',
                'pattern' => '/^https:\/\/www\.snapchat\.com/'
            ],
            'linkedin' => [
                'type' => 'string',
                'pattern' => '/^https:\/\/www\.linkedin\.com/'
            ],
            'insta' => [
                'type' => 'string',
                'pattern' => '/^https:\/\/www\.instagram\.com/'
            ],
            'youtube' => [
                'type' => 'string',
                'pattern' => '/^https:\/\/www\.youtube\.com/'
            ],
            'website' => [
                'type' => 'string',
                'pattern' => '/^https:\/\//'
            ],
            'type' => [
                'type' => 'string',
            ]
        ];

        if ($editInfo['type'] != User::TYPE_ENTITY) {
            $rules = array_merge($rules, [
                'phone' => [
                    'required' => true,
                    'type' => 'number'
                ],
                'gender' => [
                    'required' => true,
                    'type' => 'select',
                    'values' => [1, 2]
                ],
                'dob' => [
                    'required' => true,
                    'type' => 'string'
                ],
            ]);
        }
//dd($rules);
        $formValidator = FormValidator::instance("user");
        $formValidator->setRules($rules)->setErrors([
            'name.required' => $lang('field_required'),
            'email.required' => $lang('field_required'),
            'email.unique' => $lang('email_unique'),
            'email.pattern' => $lang('email_pattern'),
            'phone.required' => $lang('field_required'),
            'phone.type' => $lang('phone_invalid'),
            'gender.required' => $lang('field_required'),
            'dob.required' => $lang('field_required'),
            'country' => $lang('field_required'),
            'city.required' => $lang('field_required'),
            'spl.required' => $lang('field_required'),
            'snapchat.pattern' => $lang('snapchat_invalid'),
            'linkedin.pattern' => $lang('linkedin_invalid'),
            'insta.pattern' => $lang('insta_invalid'),
            'youtube.pattern' => $lang('youtube_invalid'),
            'website.pattern' => $lang('website_invalid'),

        ]);

        /**
         * @var \Application\Models\UserSettings
         */
        $userSM = Model::get('\Application\Models\UserSettings');
        /**
         * @var \Application\Models\UserSpecialty
         */
        $userSplM = Model::get('\Application\Models\UserSpecialty');

        $isSaved = false;


        if ($request->getHTTPMethod() == 'POST' && $isValid = $formValidator->validate()) {
            $snapchat = $formValidator->getValue('snapchat');
            $linkedIn = $formValidator->getValue('linkedin');
            $insta = $formValidator->getValue('insta');
            $youtube = $formValidator->getValue('youtube');
            $website = $formValidator->getValue('website');

            $phone = $formValidator->getValue('phone');
            $gender = $formValidator->getValue('gender');
            $dob = $formValidator->getValue('dob');
            $country = $formValidator->getValue('country');
            $city = $formValidator->getValue('city');
            $uspl = $formValidator->getValue('spl');
            $username = $formValidator->getValue('username');
            $email = $formValidator->getValue('email');
            $name = $formValidator->getValue('name');
            $password = $formValidator->getValue('password');
            $type = $formValidator->getValue('type');

            $userData = array(                
                'email' => $email,
                'name' => $name,
                'phone' => $phone,
                'type' => $type
            );

            if ($email != $editInfo['email']) {
                $userData['email_verified']  = 0;
            }

            if (!empty($password)) {
                $userData['password'] = password_hash($password, PASSWORD_DEFAULT);
            }

            $this->user->update($userData, $id);

//            $userSM->put($id, UserSettings::KEY_PHONE, $phone);
            if ($type != User::TYPE_ENTITY) {
                $userSM->put($id, UserSettings::KEY_GENDER, $gender);
                $userSM->put($id, UserSettings::KEY_DOB, $dob);
            }

            $userSM->put($id, UserSettings::KEY_COUNTRY, $country);
            $userSM->put($id, UserSettings::KEY_CITY, $city);

            $userSM->put($id, UserSettings::KEY_SOCIAL_SNAPCHAT, $snapchat);
            $userSM->put($id, UserSettings::KEY_SOCIAL_LINKEDIN, $linkedIn);
            $userSM->put($id, UserSettings::KEY_SOCIAL_INSTAGRAM, $insta);
            $userSM->put($id, UserSettings::KEY_SOCIAL_YOUTUBE, $youtube);
            $userSM->put($id, UserSettings::KEY_SOCIAL_WEBSITE, $website);

            // update the specialties
            $userSplM->delete($id);
            $userSplM->create($id, $uspl);

            // all saves are done
            $isSaved = true;

            throw new Redirect("admin/users");
        }

        $snapchat = $userSM->take($id, UserSettings::KEY_SOCIAL_SNAPCHAT);
        $linkedIn = $userSM->take($id, UserSettings::KEY_SOCIAL_LINKEDIN);
        $insta = $userSM->take($id, UserSettings::KEY_SOCIAL_INSTAGRAM);
        $youtube = $userSM->take($id, UserSettings::KEY_SOCIAL_YOUTUBE);
        $website = $userSM->take($id, UserSettings::KEY_SOCIAL_WEBSITE);
        $gender = $userSM->take($id, UserSettings::KEY_GENDER);
        $phone = $editInfo['phone'];
        $dob = $userSM->take($id, UserSettings::KEY_DOB);
        $country = $userSM->take($id, UserSettings::KEY_COUNTRY);
        $city = $userSM->take($id, UserSettings::KEY_CITY);
        $uspl = $userSplM->getSpl($id);

        $cities = array();
        if ($country = $formValidator->getValue('country', $country)) {
            /**
             * @var \Application\Models\City
             */
            $cityM = Model::get('\Application\Models\City');
            $cities = $cityM->getByCountry($country);
        }

        $view = new View();
        $view->set('Admin/Users/edit_user', [
            'userInfo' => $userInfo,
            'editInfo' => $editInfo,
            'countries' => $countries,
            'gender' => $gender,
            'phone' => $phone,
            'dob' => $dob,
            'country' => $country,
            'cities' => $cities,
            'city' => $city,
            'specialties' => $specialties,
            'uspl' => $uspl,
            'snapchat' => $snapchat,
            'linkedIn' => $linkedIn,
            'insta' => $insta,
            'youtube' => $youtube,
            'website' => $website,
            'isSaved' => $isSaved,
        ]);
        $view->prepend('Admin/header', [
            'title' => "Welcome to telein",
            'currentPage' => $lang('users'),
            'userInfo' => $userInfo
        ]);
        $view->append('Admin/footer');

        $response->set($view);
    }

    public function csv(Request $request, Response $response)
    {
        /**
         * @var \Application\Models\Language
         */
        $lang = Model::get('\Application\Models\Language');

        $userInfo = $this->user->getInfo();
        $allUsers = $this->user->all();
       
        $response->setHeaders([
            'Content-Type: text/csv; charset=utf-8',
            'content-Disposition: attachment; filename=orders.csv'
        ]);

        $output = fopen("php://output", "w");

        fputcsv($output, array(
            $lang('id'),
            $lang('email_verification'),
            $lang('account_verification'),
            $lang('name'),
            $lang('email'),
            $lang('username'),
            $lang('type'),
            $lang('status'),
            $lang('joined_at'),
            $lang('last_active'),
        ));

        foreach ($allUsers as $user) 
        {
            $emailVerification = $lang('verified');
            $accountVerification = $lang('verified');
            $status = $lang('active');

            if( $user['account_verified'] == 0 )
            {
                $accountVerification = $lang('not_verified');
            }

            if( $user['email_verified'] == 0 )
            {
                $emailVerification = $lang('not_verified');
            }

            if( $user['suspended'] == 1 )
            {
                $status = $lang('suspended');
            }

            
            $list = array(
                $user['id'],
                $accountVerification,
                $emailVerification,
                htmlentities($user['name']),
                htmlentities($user['email']),
                htmlentities($user['username']),
                htmlentities($user['type']),
                $status,
                date('d-m-Y H:i', $user['joined_at']),
                date('d-m-Y H:i', $user['lastactive'])
            );
            fputcsv($output, $list);
        }
        
        fclose($output);
        
        $file = new File('text/csv');
        $file->set($output);
        return $response->set($file);
    }
}
