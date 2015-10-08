<?php

namespace Controllers;
use Core\Controller;
use Core\View;
use Helpers\Password;
use Helpers\Session;
use Helpers\Url;

class System extends Controller {

    private $_model;

    public function __construct(){
        if(!Session::get('loggedin')){
            Url::redirect('login');
        }
        
        if(strcmp(Session::get('userRole'), '1') != 0){
            Url::redirect('');
        }  

        $this->_model = new \Models\System();
    }
        
    public function index(){
        $data['title'] = 'System';
        $data['page'] = 'system';
        $data['shoresideDWBaseDir'] = $this->_model->getShoresideDWBaseDir();
        $data['shoresideDWApacheDir'] = $this->_model->getShoresideDWApacheDir();
        //$data['javascript'] = array('modals');
        View::rendertemplate('header',$data);
        View::render('system/system',$data);
        View::rendertemplate('footer',$data);
    }
        
    public function editShoresideDWBaseDir(){
        $data['title'] = 'System';
        $data['page'] = 'system';
        $data['shoresideDWBaseDir'] = $this->_model->getShoresideDWBaseDir();

        if(isset($_POST['submit'])){
            $shoresideDWBaseDir = $_POST['shoresideDWBaseDir'];

            if($shoresideDWBaseDir == ''){
                $error[] = 'Base Directory is required';
            }
                
            if(!$error){
                $postdata = array(
                    'shoresideDWBaseDir' => $shoresideDWBaseDir
                );
            
                $this->_model->setShoresideDWBaseDir($shoresideDWBaseDir);
                Session::set('message','Base Directory Updated');
                Url::redirect('system');
            }
        }

        View::rendertemplate('header',$data);
        View::render('system/editShoresideDWBaseDir',$data,$error);
        View::rendertemplate('footer',$data);
    }
    
    public function editShoresideDWApacheDir(){
        $data['title'] = 'System';
        $data['page'] = 'system';
        $data['shoresideDWApacheDir'] = $this->_model->getShoresideDWApacheDir();

        if(isset($_POST['submit'])){
            $shoresideDWApacheDir = $_POST['shoresideDWApacheDir'];

            if($shoresideDWApacheDir == ''){
                $error[] = 'Apache Directory is required';
            }
                
            if(!$error){
                $postdata = array(
                    'shoresideDWApacheDir' => $shoresideDWApacheDir
                );
            
                $this->_model->setShoresideDWApacheDir($shoresideDWApacheDir);
                Session::set('message','Apache Directory Updated');
                Url::redirect('system');
            }
        }

        View::rendertemplate('header',$data);
        View::render('system/editShoresideDWApacheDir',$data,$error);
        View::rendertemplate('footer',$data);
    }
}