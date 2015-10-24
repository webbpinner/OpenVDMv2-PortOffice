<?php

namespace Models;
use Core\Model;


class System extends Model {

    public function getShoresideDWBaseDir(){
        return $this->db->select("SELECT value FROM ".PREFIX."CoreVars WHERE name = :name",array(':name' => 'shoresideDWBaseDir '))[0]->value;
    }

    public function getShoresideDWApacheDir(){
        return $this->db->select("SELECT value FROM ".PREFIX."CoreVars WHERE name = :name",array(':name' => 'shoresideDWApacheDir '))[0]->value;
    }

    public function setShoresideDWBaseDir($dirName){
        $data = array('value' => $dirName);
        $where = array('name' => 'shoresideDWBaseDir');
        
        $this->db->update(PREFIX."CoreVars",$data, $where);
    }

    public function setShoresideDWApacheDir($dirName){
        $data = array('value' => $dirName);
        $where = array('name' => 'shoresideDWApacheDir');
        
        $this->db->update(PREFIX."CoreVars",$data, $where);
    }

}