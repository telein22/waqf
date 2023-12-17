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

class Charities extends AdminController
{
    public function index(Request $request, Response $response)
    {
        $userInfo = $this->user->getInfo();

        $charityM = Model::get('\Application\Models\Charity');
        $charities = $charityM->all();

        $view = new View();
        $view->set('Admin/Charities/index', [
            'userInfo' => $userInfo,
            'charities' => $charities
        ]);
        $view->prepend('Admin/header', [
            'title' => "Welcome to telein",
            'currentPage' => 'Charities',
            'userInfo' => $userInfo
        ]);
        $view->append('Admin/footer');

        $response->set($view);
    }

    public function import(Request $request, Response $response)
    {

        $userInfo = $this->user->getInfo();
        $lang = $this->language;

        if (isset($_FILES['csv']) && $_FILES['csv']['error'] == 0) {
            $file = new File();
            $file->set($_FILES['csv']);

            $charityM = Model::get('\Application\Models\Charity');

            if ($file->getExt() != 'csv')
                throw new ResponseJSON(
                    'error',
                    $lang('please_upload_csv')
                );
            $filez = $_FILES['csv']['tmp_name'];

            if (($handle = fopen($filez, "r")) !== FALSE) {
                $i = 0;
                while (($data = fgetcsv($handle)) !== FALSE) {
                    if( $i == 0 ) 
                    {
                        $i++;
                        continue;
                    };

                    $path = ABS_PATH. DS . 'Application/Assets/images/transparent-background-star-115497268824j1ftohfyn.png';

                    $file = new File();
                    $file->set($path);
                    $newName = Strings::random(20) . '_' . time() . '.' . $file->getExt();
                                    // upload the file
                    $file->move('Application/Uploads/' . $newName);
                    
                    $charityM->create([
                        'en_name' => trim($data[0]),
                        'ar_name' => trim($data[1]),
                        'b_name' => trim($data[2]),
                        'b_account_number' => trim($data[3]),
                        'bank_bic_code' => trim($data[4]),
                        'address_line_1' => trim($data[5]),
                        'address_line_2' => trim($data[6]),
                        'address_line_3' => trim($data[7]),
                        'img' => $newName,
                        'created_at' => time()
                    ]);
                    
                }
            }

            throw new Redirect("admin/charities");
        }

        $view = new View();
        $view->set('Admin/Charities/import', [
            'userInfo' => $userInfo
        ]);
        $view->prepend('Admin/header', [
            'title' => "Welcome to telein",
            'currentPage' => 'Import Charities',
            'userInfo' => $userInfo
        ]);
        $view->append('Admin/footer');

        $response->set($view);
    }

    public function addCharity(Request $request, Response $response)
    {
        $userInfo = $this->user->getInfo();

        $lang = $this->language;

        $formValidator = FormValidator::instance("charity");
        $formValidator->setRules([
            'ar_name' => [
                'required' => true,
                'type' => 'string'
            ],
            'en_name' => [
                'required' => true,
                'type' => 'string'
            ],
            'b_name' => [
                'required' => true,
                'type' => 'string'
            ],
            'b_account_number' => [
                'required' => true,
                'type' => 'number'
            ],
            'bank_bic_code' => [
                'required' => true,
                'type' => 'string'
            ],
            'address_line_1' => [
                'type' => 'string'
            ],
            'address_line_2' => [
                'type' => 'string'
            ],
            'address_line_3' => [
                'type' => 'string'
            ],
        ])->setErrors([
            'ar_name.required' => $lang('field_required'),
            'en_name.required' => $lang('field_required'),
            'b_name.required' => $lang('field_required'),
            'b_account_number.required' => $lang('field_required'),
            'bank_bic_code.required' => $lang('field_required'),
        ]);

        $charityM = Model::get('\Application\Models\Charity');

        if ($request->getHTTPMethod() == 'POST' && $isValid = $formValidator->validate()) {
            $en_name = $formValidator->getValue('en_name');
            $ar_name = $formValidator->getValue('ar_name');
            $b_name = $formValidator->getValue('b_name');
            $b_account_number = $formValidator->getValue('b_account_number');
            $bank_bic_code = $formValidator->getValue('bank_bic_code');
            $address_line_1 = $formValidator->getValue('address_line_1');
            $address_line_2 = $formValidator->getValue('address_line_2');
            $address_line_3 = $formValidator->getValue('address_line_3');

            if (isset($_FILES['img']) && $_FILES['img']['error'] == 0) {
                $file = new File();
                $file->set($_FILES['img']);

                // get supported file types
                $supported = Config::get('Website')->images_support;
                $supported = $supported ? $supported : ['image/jpeg'];

                if (!in_array($file->getMime(), $supported))
                    throw new ResponseJSON(
                        'error',
                        $lang('please_upload_jpg_png')
                    );

                $newName = Strings::random(20) . '_' . time() . '.' . $file->getExt();
                // upload the file
                $file->move('Application/Uploads/' . $newName);

                $img = $newName;

                $charityM->create([
                    'en_name' => $en_name,
                    'ar_name' => $ar_name,
                    'b_name' => $b_name,
                    'b_account_number' => $b_account_number,
                    'bank_bic_code' => $bank_bic_code,
                    'address_line_1' => $address_line_1,
                    'address_line_2' => $address_line_2,
                    'address_line_3' => $address_line_3,
                    'img' => $img,
                    'created_at' => time()
                ]);

                // $charityM->create([
                //     'en_name' => $en_name,
                //     'ar_name' => $ar_name,
                //     'img' => '',
                //     'created_at' => time()
                // ]);

                throw new Redirect("admin/charities");
            } else {
                $formValidator->setError('img', $lang('please_select_a_img'));
            }
        }

        $view = new View();
        $view->set('Admin/Charities/add_charities', [
            'userInfo' => $userInfo
        ]);
        $view->prepend('Admin/header', [
            'title' => "Welcome to telein",
            'currentPage' => 'Add Charity',
            'userInfo' => $userInfo
        ]);
        $view->append('Admin/footer');

        $response->set($view);
    }

    public function editCharity(Request $request, Response $response)
    {
        $lang = $this->language;
        $userInfo = $this->user->getInfo();
        $id = $request->param(0);

        $charityM = Model::get('\Application\Models\Charity');
        $charityInfo = $charityM->getCharityById($id);

        $formValidator = FormValidator::instance("charity");
        $formValidator->setRules([
            'ar_name' => [
                'required' => true,
                'type' => 'string'
            ],
            'en_name' => [
                'required' => true,
                'type' => 'string'
            ],
            'b_name' => [
                'required' => true,
                'type' => 'string'
            ],
            'b_account_number' => [
                'required' => true,
                'type' => 'number'
            ],
            'bank_bic_code' => [
                'required' => true,
                'type' => 'string'
            ],
            'address_line_1' => [
                'type' => 'string'
            ],
            'address_line_2' => [
                'type' => 'string'
            ],
            'address_line_3' => [
                'type' => 'string'
            ],
        ])->setErrors([
            'ar_name.required' => $lang('field_required'),
            'en_name.required' => $lang('field_required'),
            'b_name.required' => $lang('field_required'),
            'b_account_number.required' => $lang('field_required'),
            'bank_bic_code.required' => $lang('field_required'),
        ]);

        $img = $charityInfo['img'];

        if ($request->getHTTPMethod() == 'POST' && $isValid = $formValidator->validate()) {
            $en_name = $formValidator->getValue('en_name');
            $ar_name = $formValidator->getValue('ar_name');
            $b_account_number = $formValidator->getValue('b_account_number');
            $bank_bic_code = $formValidator->getValue('bank_bic_code');
            $address_line_1 = $formValidator->getValue('address_line_1');
            $address_line_2 = $formValidator->getValue('address_line_2');
            $address_line_3 = $formValidator->getValue('address_line_3');

            if (isset($_FILES['img']) && $_FILES['img']['error'] == 0) {
                $file = new File();
                $file->set($_FILES['img']);

                // get supported file types
                $supported = Config::get('Website')->images_support;
                $supported = $supported ? $supported : ['image/jpeg'];

                if (!in_array($file->getMime(), $supported))
                    throw new ResponseJSON(
                        'error',
                        $lang('please_upload_jpg_png')
                    );

                $newName = Strings::random(20) . '_' . time() . '.' . $file->getExt();
                // upload the file
                $file->move('Application/Uploads/' . $newName);

                $img = $newName;
            }

            $charityM->update([
                'b_account_number' => $b_account_number,
                'bank_bic_code' => $bank_bic_code,
                'address_line_1' => $address_line_1,
                'address_line_2' => $address_line_2,
                'address_line_3' => $address_line_3,
                'en_name' => $en_name,
                'ar_name' => $ar_name,
                'img' => $img,
                'created_at' => time()
            ], $id);

            throw new Redirect("admin/charities");
        }

        $view = new View();
        $view->set('Admin/Charities/edit_charity', [
            'userInfo' => $userInfo,
            'charityInfo' => $charityInfo
        ]);
        $view->prepend('Admin/header', [
            'title' => "Welcome to telein",
            'currentPage' => 'Edit Charity',
            'userInfo' => $userInfo
        ]);
        $view->append('Admin/footer');

        $response->set($view);
    }
}
