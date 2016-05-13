<?php

namespace Controllers;
use Core\Controller;
use Core\View;
use Helpers\Password;
use Helpers\Session;
use Helpers\Url;

class Users extends Controller {

    private $_warehouseModel;
    private $_dataDashboardModel;
    private $_usersModel;

    public function __construct(){
        if(!Session::get('loggedin')){
            Url::redirect('login');
        }

        if(strcmp(Session::get('userRole'), '1') != 0){
            Url::redirect('');
        }
        
        $this->_warehouseModel = new \Models\Warehouse();
        
        if(!Session::get('cruiseID')){
            Session::set('cruiseID', $this->_warehouseModel->getLatestCruise());
        }
        
        $this->_dataDashboardModel = new \Models\DataDashboard();
        $this->_usersModel = new \Models\Users();
    }
        
    public function index(){
        $data['title'] = 'Users';
        $data['page'] = 'users';
        $data['users'] = $this->_usersModel->getUsers();
        $data['cruiseID'] = Session::get('cruiseID');
        $data['customDataDashboardTabs'] = $this->_dataDashboardModel->getDataDashboardTabs();
        
        View::rendertemplate('header',$data);
        View::rendertemplate('dataDashboardHeader',$data);
        View::render('users/users',$data);
        View::rendertemplate('footer',$data);
    }

    public function addUser(){
        $data['title'] = 'Users';
        $data['page'] = 'users';
        $data['cruiseID'] = Session::get('cruiseID');
        $data['customDataDashboardTabs'] = $this->_dataDashboardModel->getDataDashboardTabs();

        if(isset($_POST['submit'])){
            $username = $_POST['username'];
            $password = $_POST['password'];

            if($username == ''){
                $error[] = 'Username is required';
            }

            if($password == ''){
                $error[] = 'Password is required';
            } 
                
            if(strcmp($password, $_POST['password2']) !== 0) {
                $error[] = 'Passwords must match';
            }

            if(!$error){
                $postdata = array(
                    'username' => $username,
                    'password' => Password::make($password)//,
                );

                $this->_usersModel->insertUser($postdata);
                Session::set('message','User Added');
                Url::redirect('users');
            }
        }

        View::rendertemplate('header',$data);
        View::rendertemplate('dataDashboardHeader',$data);
        View::render('users/addUser',$data,$error);
        View::rendertemplate('footer',$data);
    }
        
    public function editUser($id){
        $data['title'] = 'Users';
        $data['page'] = 'users';
        $data['row'] = $this->_usersModel->getUser($id);
        $data['cruiseID'] = Session::get('cruiseID');
        $data['customDataDashboardTabs'] = $this->_dataDashboardModel->getDataDashboardTabs();

        if(isset($_POST['submit'])){
            $username = $_POST['username'];
            $password = $_POST['password'];

            if($username == ''){
                $error[] = 'Username is required';
            }

            if($password == ''){
                $error[] = 'Password is required';
            }        

            if(strcmp($password, $_POST['password2']) !== 0) {
                $error[] = 'Passwords must match';
            }
            

                
            if(!$error){
                $postdata = array(
                    'username' => $username,
                    'password' => Password::make($password)
                );
            
                $where = array('userID' => $id);
                $this->_usersModel->updateUser($postdata,$where);
                Session::set('message','User Updated');
                Url::redirect('users');
            }
        }

        View::rendertemplate('header',$data);
        View::rendertemplate('dataDashboardHeader',$data);
        View::render('users/editUser',$data,$error);
        View::rendertemplate('footer',$data);
    }
    
    public function deleteUser($id){
        $where = array('userID' => $id);
        $this->_usersModel->deleteUser($where);
        Session::set('message','User Deleted');
        Url::redirect('users');
    }
}