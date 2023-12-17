<?php

namespace Application\Controllers;

use Application\Main\MainController;
use Application\ThirdParties\AWS\AWS;
use System\Core\Exceptions\Redirect;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Models\AutoOptimizer;
use System\Models\Session;
use System\Responses\File;

class Optimize extends MainController
{
    public function index(Request $request, Response $response)
    {
        $fileInfo = $this->getFileInfoFromSession($request->get('param'));
        $autoM = Model::get(AutoOptimizer::class);
        $data = $autoM->optimize($request->get('param'));

        $file = new File($data['mime']);
        $file->set($data['content']);

        $response->set($file);

        AWS::syncFileWithS3($fileInfo['fileName'], "Application/Cache/{$fileInfo['filePath']}");
    }

    private function getFileInfoFromSession(string $key)
    {
        $session = Model::get(Session::class);
        $data = $session->take($key);

        if (!$data) {
            throw new Redirect('dashboard');
        }

        $filePath = $data['final_path'];
        $res = explode('/', $filePath);

        return [
            'fileName' => $res[1],
            'filePath' => $filePath
        ];
    }
}