<?php

namespace System\Models;

use InvalidArgumentException;
use System\Core\Application;
use System\Core\Exceptions\SystemError;
use System\Core\Model;
use System\Helpers\Strings;
use System\Helpers\URL;
use System\Libs\Storage;
use System\Libs\File;
use System\Libs\Optimizer;

class AutoOptimizer extends Model
{
    /**
     * @var \System\Libs\Storage
     */
    private $_disk;

    /**
     * @var \System\Models\Session
     */
    private $_session;

    /**
     * @var \System\Libs\File
     */
    private $_file;

    private $_params;
    
    private $_config;

    protected function __construct( $options )
    {
        parent::__construct( $options );

        // first work with memory limit
        $this->_config = Application::config();

        if ( ! $this->_config->memory_limit )
            throw new SystemError(
                "Please define a memory limit on application level,
                as auto optimizer may require a lot of memory while live optimizing,
                increase your memory if images are not generating."
            );

        $storage = ABS_PATH . DS . $this->_config->auto_optimizer_storage_directory;

        $this->_disk = new Storage($storage);

        $this->_session = Model::get('\System\Models\Session');
    }

    public function setFile( $file )
    {
        $this->_file = new File();
        $this->_file->set($file);

        return $this;
    }

    public function setParams( $params )
    {
        $this->_params = $params;

        return $this;
    }

    public function link()
    {
        // First try to fetch the relative path of the file
        $rel = $this->_file->getRelative(ABS_PATH);        

        // Of rel is null or non positive that means
        // file provided by the user is not valid so return the provided link
        if ( !$rel ) return $this->_directLink($rel);
        
        $finalPath = md5($rel . $this->_params) . DS .  basename($rel);
        $link = $this->_directLink($finalPath);

        if ( $this->_file->lastModified() > $this->_disk->lastModified($finalPath) )
        {
            // Current file is modified recently 
            // So we need to generate link            
            $arr  = [ 'file' => $this->_file->get(), 'params' => $this->_params, 'final_path' => $finalPath ];
            $param = Strings::random(40);

            // Save data to session
            $this->_session->put($param, $arr);

            $link =  URL::full($this->_config->auto_optimizer_route . DS . '?param=' .$param);
        }

        // else return the file link.
        return $link;
    }

    public function optimize( $param )
    {
        if ( !$param ) throw new InvalidArgumentException(
            "You need to provide a valid param"
        );

        // Get the data from session then delete the session.
        $data = $this->_session->take($param);        
        if ( !$data ) throw new SystemError(
            "Invalid optimizing param"
        );
        $this->_session->delete($param);

        // First check if the file exists.
        if ( !file_exists($data['file']) ) throw new SystemError(
            "The file do not exists anymore: " . $data['file']
        );

        // else start optimizing
        $optimizer = new Optimizer();
        $content = $optimizer->set($data['file'])->optimize($data['params'])->raw();
        
        $this->_disk->put($data['final_path'], $content);

        $file = new File($data['file']);

        return [
            'content' => $content,
            'mime' => $file->getMime()
        ];
    }

    private function _directLink( $path )
    {
        return URL::full($this->_config->auto_optimizer_storage_directory . DS . $path);
    }

}