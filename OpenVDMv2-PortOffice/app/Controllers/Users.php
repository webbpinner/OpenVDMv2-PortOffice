<?php

namespace Controllers;
use Core\Controller;
use Core\View;
use Helpers\Password;
use Helpers\Session;
use Helpers\Url;

class Users extends Controller {

    private $_model;

    public function __construct(){
        if(!Session::get('loggedin')){
            Url::redirect('login');
        }

        if(strcmp(Session::get('userRole'), '1') != 0){
            Url::redirect('');
        }
        
        $this->_model = new \Models\Users();
    }
        
    public function index(){
        $data['title'] = 'Users';
        $data['users'] = $this->_model->getUsers();
        $data['javascript'] = array('modals');
        View::rendertemplate('header',$data);
        View::render('users/users',$data);
        View::rendertemplate('footer',$data);
    }

    public function addUser(){
        $data['title'] = 'Users';

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

                $this->_model->insertUser($postdata);
                Session::set('message','User Added');
                Url::redirect('users');
            }
        }

        View::rendertemplate('header',$data);
        View::render('users/addUser',$data,$error);
        View::rendertemplate('footer',$data);
    }
        
    public function editUser($id){
        $data['title'] = 'Users';
        $data['row'] = $this->_model->getUser($id);

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
                $this->_model->updateUser($postdata,$where);
                Session::set('message','User Updated');
                Url::redirect('dashboard');
            }
        }

        View::rendertemplate('header',$data);
        View::render('users/editUser',$data,$error);
        View::rendertemplate('footer',$data);
    }
    
    public function deleteUser($id){
        $where = array('userID' => $id);
        $this->_model->deleteUser($where);
        Session::set('message','User Deleted');
        Url::redirect('users');
    }
}