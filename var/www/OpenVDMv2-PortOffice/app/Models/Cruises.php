<?php

namespace Models;
use Core\Model;


class Cruises extends Model {
    
    private $_cruiseDataDir;
    private $_cruises;
    private $_systemModel;

    public function __construct(){
        $this->_systemModel = new \Models\System();
        $this->_cruiseDataDir = $this->_systemModel->getShoresideDWBaseDir();
        $this->_cruises = array();
    }

    public function getCruises(){
        
        if (sizeof($this->_cruises) == 0) {
        
            //Get the list of directories
            if (is_dir($this->_cruiseDataDir . DIRECTORY_SEPARATOR . $rootValue)) {
                $rootList = scandir($this->_cruiseDataDir);
                foreach ($rootList as $rootKey => $rootValue)
                {
                    if (!in_array($rootValue,array(".","..")))
                    {
                        if (is_dir($this->_cruiseDataDir . DIRECTORY_SEPARATOR . $rootValue))
                        {
                            //Check each Directory for ovdmConfig.json
                            $cruiseList = scandir($this->_cruiseDataDir . DIRECTORY_SEPARATOR . $rootValue);
                            foreach ($cruiseList as $cruiseKey => $cruiseValue){
                                if (in_array($cruiseValue,array("ovdmConfig.json"))){
                                    $ovdmConfigContents = file_get_contents($this->_cruiseDataDir . DIRECTORY_SEPARATOR . $rootValue . DIRECTORY_SEPARATOR . "ovdmConfig.json");
                                    $ovdmConfigJSON = json_decode($ovdmConfigContents,true);
                                    //Get the the directory that holds the DashboardData
                                    for($i = 0; $i < sizeof($ovdmConfigJSON['extraDirectoriesConfig']); $i++){
                                        if(strcmp($ovdmConfigJSON['extraDirectoriesConfig'][$i]['name'], 'Dashboard Data') === 0){
                                            $dataDashboardList = scandir($this->_cruiseDataDir . DIRECTORY_SEPARATOR . $rootValue . DIRECTORY_SEPARATOR . $ovdmConfigJSON['extraDirectoriesConfig'][$i]['destDir']);
                                            foreach ($dataDashboardList as $dataDashboardKey => $dataDashboardValue){
                                                //If a manifest file is found, add CruiseID to output
                                                if (in_array($dataDashboardValue,array("manifest.json"))){
                                                    $this->_cruises[] = $rootValue;
                                                    break;
                                                }
                                            }
                                            break;
                                        }
                                    }
                                    break;
                                }
                            }
                        }
                    }
                }
            }
            rsort($this->_cruises);
            return $this->_cruises;
        } else {
            return array("Error"=>"Could not find base directory.");
        }
    }
}
