<?php

namespace Models;
use Core\Model;

class Auth extends Model {
    
    public function getHash($username) {    
        $data = $this->db->select("SELECT password FROM ".PREFIX."Users WHERE username = :username",
            array(':username' => $username));
        return $data[0]->password;
    }
    
    public function getUserID($username) {    
        $data = $this->db->select("SELECT userID FROM ".PREFIX."Users WHERE username = :username",
            array(':username' => $username));
        return $data[0]->userID;
    }
    
    public function getUserRole($username) {    
        $data = $this->db->select("SELECT userRole FROM ".PREFIX."Users WHERE username = :username",
            array(':username' => $username));
        return $data[0]->userRole;
    }
    
    public function updateUser($data, $where) {
        $this->db->update(PREFIX."Users",$data,$where);
    }

}