<?php

namespace Application\Controllers;

use Application\Helpers\AppHelper;
use Application\Main\MainController;
use Application\Models\Email;
use Application\Models\Language;
use Application\Models\RememberToken;
use Application\Models\User;
use Application\ThirdParties\Whatsapp\Whatsapp;
use Application\Models\UserSettings;
use Application\ThirdParties\Whatsapp\WhatsappMessages;
use Exception;
use System\Core\Config;
use System\Core\Controller;
use System\Core\Exceptions\Redirect;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Helpers\Strings;
use System\Helpers\URL;
use System\Libs\FormValidator;
use System\Models\Cookie;
use System\Responses\View;

class Auth extends MainController
{
    public function blocked(Request $request, Response $response)
    {
        /**
         * @var \Application\Models\User
         */
        $userM = Model::get('\Application\Models\User');
        if (!$userM->isBlocked()) throw new Redirect("");

        $view = new View();
        $view->set('Outer/Blocked/index');
        $view->prepend('Outer/header', [
            'title' => "Welcome to telein"
        ]);
        $view->append('Outer/footer');

        $response->set($view);
    }

    public function login(Request $request, Response $response)
    {
        /**
         * @var \Application\Models\User
         */
        $userM = Model::get('\Application\Models\User');
        if ($userM->isLoggedIn()) throw new Redirect("dashboard");

        $lang = $this->language;

        $formValidator = FormValidator::instance("login");
        $formValidator->setRules([
            'email' => [
                'required' => true,
                'type' => 'string',
                'pattern' => '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'
            ],
            'password' => [
                'required' => true,
                'type' => 'string'
            ]
        ])->setErrors([
            'email.required' => $lang('field_required'),
            'email.pattern' => $lang('email_pattern'),
            'password.required' => $lang('field_required'),
        ]);

        if ($request->getHTTPMethod() == 'POST' && $formValidator->validate()) {
            $result = $userM->login(array(
                'username' => $formValidator->getValue('email'),
                'password' => $formValidator->getValue('password')
            ));

            switch ($result) {
                case User::USER_SUSPENDED:
                    $formValidator->setError('email', $lang('user_suspended'));
                    break;
                case User::USER_DEACTIVATED:
                    $formValidator->setError('email', $lang('user_deactivated'));
                    break;
                case User::INVALID_PASSWORD:
                case User::INVALID_USERNAME:
                    $formValidator->setError('email', $lang('cred_wrong'));
                    $formValidator->setError('password', $lang('cred_wrong'));
                    break;
                default:

                    if (!empty($request->post('rememberMe'))) {
                        $token = strtoupper(Strings::random(10));
                        $user = $userM->getUserByEmail($formValidator->getValue('email'));

                        $rememberM = Model::get(RememberToken::class);
                        $rememberM->create(array(
                            'user_id' => $user['id'],
                            'token' => $token
                        ));

                        $cookieM = Model::get(Cookie::class);
                        $cookieM->setcookie('loginToken', $token, time() + 30 * 24 * 60 * 60, '/');
                    }

                    if ($request->param(0) && $request->param(1)) {
                        $id = $request->param(0);
                        $type = $request->param(1);

                        $session = Model::get("\System\Models\Session");
                        $session->put('bookingInfo', array(
                            'id' => $id,
                            'type' => $type
                        ));

                        throw new Redirect("workshops/checkout");
                    }

                    throw new Redirect("dashboard/");
            }

        }

        $view = new View();
        $view->set('Outer/Auth/login');
        $view->prepend('Outer/header', [
            'title' => "Login"
        ]);
        $view->append('Outer/footer');

        $response->set($view);
    }

    public function logout()
    {
        $userM = Model::get('\Application\Models\User');
        $userM->logout();

        /**
         * @var Cookie
         */
        $cookieM = Model::get(Cookie::class);

        $token = $cookieM->getCookie('loginToken');

        if (!empty($token)) {
            /**
             * @var RememberToken
             */
            $rememberM = Model::get(RememberToken::class);
            $rememberM->removeByToken($token);

            $cookieM->removeCookie('loginToken');
        }

        throw new Redirect(URL::full('login'));
    }

    public function register(Request $request, Response $response)
    {
        $userM = Model::get('\Application\Models\User');
        if ($userM->isLoggedIn()) throw new Redirect("dashboard");

        $lang = $this->language;

        /**
         * @var \Application\Models\Specialty
         */
        $specialtiesModel = Model::get("\Application\Models\Specialty");
        $specialties = $specialtiesModel->all();

        $sIds = array();
        foreach ($specialties as $specialty) {
            $sIds[] = $specialty['id'];
        }

        /**
         * @var \Application\Models\SubSpecialty
         */
        $subSpecialtiesM = Model::get("\Application\Models\SubSpecialty");
        $subSpecialties = $subSpecialtiesM->all();

        $subSIds = array();
        foreach ($subSpecialties as $specialty) {
            $subSIds[] = $specialty['id'];
        }


        $formValidator = FormValidator::instance("register");
        $formRules = [
            'username' => [
                'required' => true,
                'type' => 'string',
                'unique' => 'users,username',
                'minchar' => 4,
                'maxchar' => 15
            ],
            'name' => [
                'required' => true,
                'type' => 'string'
            ],
            'email' => [
                'required' => true,
                'type' => 'string',
                'unique' => 'users,email',
                'pattern' => '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'
            ],
            'password' => [
                'required' => true,
                'type' => 'string'
            ],
            'confirm_password' => [
                'required' => true,
                'type' => 'string'
            ],
            'specialties' => [
                // 'required' => true,
                'type' => 'multiselect',
                'values' => $sIds
            ],
            'sub_specialties' => [
                // 'required' => true,
                'type' => 'multiselect',
                'values' => $subSIds
            ],
            'checkbox' => [
                'required' => true,
                'type' => 'string',
            ]
        ];

        $validationMessages = [
            'username.required' => $lang('field_required'),
            'username.unique' => $lang('username_unique'),
            'username.minchar' => $lang('username_limit', ['min' => 4, 'max' => 15]),
            'username.maxchar' => $lang('username_limit', ['min' => 4, 'max' => 15]),
            'name.required' => $lang('field_required'),
            'email.required' => $lang('field_required'),
            'email.unique' => $lang('email_unique'),
            'email.pattern' => $lang('email_pattern'),
            'password.required' => $lang('field_required'),
            'confirm_password.required' => $lang('field_required'),
            // 'specialties.required' => $lang('field_required'),
            'specialties.values' => $lang('specialties_values'),
            // 'sub_specialties.required' => $lang('field_required'),
            'sub_specialties.values' => $lang('sub_specialties_values'),
            'checkbox.required' => $lang('field_required'),
        ];

        if ($request->post('phone')) {
            $formRules['phone'] = [
                'required' => true,
                'type' => 'string',
                'unique' => 'users,phone',
                'minchar' => 10,
                'pattern' => '/^\+[0-9]{1,15}$/'
            ];

            $validationMessages = array_merge($validationMessages, [
                'phone.required' => $lang('field_required'),
                'phone.minchar' => $lang('phone_limit', ['min' => 10]),
                'phone.unique' => $lang('phone_unique'),
                'phone.pattern' => $lang('phone_invalid'),
            ]);
        }

        $formValidator->setRules($formRules)->setErrors($validationMessages);
        $subSpecialties = array();
        if ($request->post('specialties')) {
            $subSpecialties = $request->post('specialties');

            $subSpecialties = $subSpecialtiesM->getBySpecialty($subSpecialties);
        }

        if ($request->getHTTPMethod() == 'POST' && $isValid = $formValidator->validate()) {
            $cpass = $formValidator->getValue('confirm_password');
            $pass = $formValidator->getValue('password');

            if ($pass != $cpass) {
                $formValidator->setError('confirm_password', $lang('new_password_not_match'));
                $formValidator->setError('password', $lang('new_password_not_match'));

                $isValid = false;
            }

            if ($isValid) {
                $phone = $formValidator->getValue('phone');
                $name = $formValidator->getValue('name');
                $user = Model::get("\Application\Models\User");
                $userIdUser = $user->create([
                    'username' => $formValidator->getValue('username'),
                    'phone' => $phone,
                    'email' => $formValidator->getValue('email'),
                    'name' => $name,
                    'password' => password_hash($pass, PASSWORD_DEFAULT),
                    'type' => 'subscriber',
                    'joined_at' => time(),
                    'lastactive' => time(),
                    'account_verified' => 0,
                    'email_verified' => 0,
                    'suspended' => 0
                ]);

                // store the lang
                $userSM = Model::get(UserSettings::class);
                if ($userLang = $request->post('lang')) {
                    $userSM->put($userIdUser, UserSettings::KEY_LANGUAGE, $userLang);
                }

                // enable the messaging for free
                $userSM->put($userIdUser, UserSettings::KEY_MESSAGING_PRICE, 0);
                $userSM->put($userIdUser, UserSettings::KEY_MESSAGING_ENABLE, 1);

                Whatsapp::sendChat($phone, WhatsappMessages::greetingAfterRegistration($name));

                $result = $user->login(array(
                    'username' => $formValidator->getValue('email'),
                    'password' => $formValidator->getValue('password')
                ));

                if ($result) {
                    if ($formValidator->getValue('specialties')) {
                        // Saving Specialities
                        $specialties = $formValidator->getValue('specialties');

                        $userSplM = Model::get('\Application\Models\UserSpecialty');

                        // update the specialties
                        $userSplM->delete($userIdUser);
                        $userSplM->create($userIdUser, $specialties);
                    }

                    if ($formValidator->getValue('sub_specialties')) {
                        // Saving Sub Specialities
                        $sub_specialties = $formValidator->getValue('sub_specialties');

                        $userSplM = Model::get('\Application\Models\UserSubSpecialty');

                        // update the specialties
                        $userSplM->delete($userIdUser);
                        $userSplM->create($userIdUser, $sub_specialties);

                    }
                    throw new Redirect("verify-account");
                }
            }

        }

        $view = new View();
        $view->set('Outer/Auth/register', [
            'specialties' => $specialties,
            'subSpecialties' => $subSpecialties
        ]);
        $view->prepend('Outer/header', [
            'title' => "Register"
        ]);
        $view->append('Outer/footer');

        $response->set($view);
    }

    public function verify(Request $request, Response $response)
    {
        /**
         * @var \Application\Models\User
         */
        $userM = Model::get('\Application\Models\User');
        if (!$userM->isLoggedIn()) throw new Redirect("");

        if ($userM->isVerified()) throw new Redirect("dashboard");

        $userInfo = $userM->getInfo();

        /**
         * @var \Application\Models\UserVerify
         */
        $verifyM = Model::get('\Application\Models\UserVerify');

        if (!$verifyM->hasToken($userInfo['id'], 'email')) {
            // create a new token
            $token = $verifyM->createToken($userInfo['id'], 'email', $userInfo['email']);

            /**
             * @var Language
             */
            $language = Model::get(Language::class);
            $lang = $language->getUserLang($userInfo['id']);

            /**
             * @var Email
             */
            $emailM = Model::get(Email::class);
            $mail = $emailM->new();

            $mail->to([$userInfo['email'], $userInfo['name']]);
            $mail->body('Emails/' . 'verify_account', [
                'otp' => $token,
                'name' => $userInfo['name'],
                'url' => URL::full('')
            ], $lang);
            $mail->subject('verify_account', null, $lang);
            $mail->send();

            if (!empty($userInfo['phone'])) {
                Whatsapp::sendChat($userInfo['phone'], WhatsappMessages::verificationCode($userInfo['name'], $token));
            }
        }

        $lang = $this->language;

        $formValidator = FormValidator::instance("verify");
        $formValidator->setRules([
            'email_token' => [
                'required' => true,
                'type' => 'string',
                'maxchar' => 6,
                'minchar' => 6
            ]
        ])->setErrors([
            'email_token.required' => $lang('field_required'),
            'email_token.maxchar' => $lang("email_token_invalid"),
            'email_token.minchar' => $lang("email_token_invalid")
        ]);

        if ($request->getHTTPMethod() == 'POST' && $formValidator->validate()) {
            // check if the token matches.
            $token = $formValidator->getValue('email_token');

            $result = $verifyM->verifyToken($userInfo['id'], 'email', $token);

            if ($result) {
                // we need to update the user model
                $userM->update([
                    'email_verified' => 1
                ]);

                $twitter = AppHelper::getBaseUrl() . '/share-on-twitter';
                $linkedin = AppHelper::getBaseUrl() . '/share-on-linkedin';
                WhatsApp::sendChat($userInfo['phone'], WhatsappMessages::shareLinksAfterVerification($twitter, $linkedin));

                throw new Redirect("dashboard");
            } else {
                // push error
                $formValidator->setError('email_token', $lang("email_token_invalid"));
            }
        }

        $view = new View();
        $view->set('Outer/Auth/verify');
        $view->prepend('Outer/header', [
            'title' => "Register"
        ]);
        $view->append('Outer/footer');

        $response->set($view);
    }

    public function forgotPassword(Request $request, Response $response)
    {
        /**
         * @var \Application\Models\User
         */
        $userM = Model::get('\Application\Models\User');
        if ($userM->isLoggedIn()) throw new Redirect("dashboard");

        $lang = $this->language;

        $formValidator = FormValidator::instance("forgot_password");
        $formValidator->setRules([
            'email' => [
                'required' => true,
                'type' => 'string',
                'pattern' => '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'
            ]
        ])->setErrors(array(
            'email.required' => $lang('field_required'),
            'email.pattern' => $lang('email_pattern'),
        ));

        if ($request->getHTTPMethod() == 'POST' && $formValidator->validate()) {
            $email = $formValidator->getValue('email');
            $userInfo = $this->user->getUserByEmail($email);

            if ($userInfo) {
                $random = strtoupper(Strings::random(6));
                $data = array(
                    'email' => $email,
                    'otp' => $random,
                    'created_at' => time()
                );

                $forgotPM = Model::get('\Application\Models\ForgotPassword');
                if (!$forgotPM->exists($email)) $forgotPM->create($data);
                else $forgotPM->updateByEmail($email, $random);

                $this->hooks->dispatch('auth.on_forget_password_submit', $data)->later();

                throw new Redirect("change-password/" . $email);
            } else {
                $formValidator->setError('email', 'Email you entered does not exist');
            }
        }

        $view = new View();
        $view->set('Outer/Auth/forgot_password');
        $view->prepend('Outer/header', [
            'title' => "Forgot Password"
        ]);
        $view->append('Outer/footer');

        $response->set($view);
    }

    public function changePassword(Request $request, Response $response)
    {
        $error = false;
        /**
         * @var \Application\Models\User
         */
        $userM = Model::get('\Application\Models\User');
        if ($userM->isLoggedIn()) throw new Redirect("dashboard");

        $lang = $this->language;
        $email = $request->param(0);

        $formValidator = FormValidator::instance("change_password");
        $formValidator->setRules([
            'otp' => [
                'required' => true,
                'type' => 'string',
            ],
            'password' => [
                'required' => true,
                'type' => 'string'
            ],
            'confirm_password' => [
                'required' => true,
                'type' => 'string'
            ],
        ])->setErrors([
            'otp.required' => $lang('field_required'),
            'password.required' => $lang("field_required"),
            'confirm_password.required' => $lang("field_required")
        ]);

        if ($request->getHTTPMethod() == 'POST' && $formValidator->validate()) {
            $otp = $formValidator->getValue('otp');
            $password = $formValidator->getValue('password');
            $confirm_password = $formValidator->getValue('confirm_password');

            if ($password != $confirm_password) {
                $formValidator->setError('password', $lang('new_password_not_match'));
                $formValidator->setError('confirm_password', $lang('new_password_not_match'));
                $error = true;
            }

            if (!$error) {
                $forgotPasswordM = Model::get('\Application\Models\ForgotPassword');

                if (!$forgotPasswordM->verify($email, $otp)) {
                    $formValidator->setError('otp', 'Please enter a valid otp');
                    $error = true;
                }
            }

            if (!$error) {
                $userInfo = $userM->getUserByEmail($email);

                $userM->update(array(
                    'password' => password_hash($password, PASSWORD_DEFAULT)
                ), $userInfo['id']);

                throw new Redirect("login");
            }

        }

        $view = new View();
        $view->set('Outer/Auth/change_password', array(
            'email' => $email
        ));
        $view->prepend('Outer/header', [
            'title' => "Change Password"
        ]);
        $view->append('Outer/footer');

        $response->set($view);
    }
}