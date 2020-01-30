<?php

namespace Models;
use Core\Model;


class Warehouse extends Model {
    
    const CONFIG_FN = 'ovdmConfig.json';
    const MANIFEST_FN = 'manifest.json';
    
    private $_cruises;

    public function getShoresideDataWarehouseBaseDir(){
        return CRUISEDATA_BASEDIR;
    }

    public function getShoresideDataWarehouseApacheDir(){
        return CRUISEDATA_APACHEDIR;
    }
    
    public function getCruises(){
        
        if (count($this->_cruises) == 0) {
        
            $baseDir = $this->getShoresideDataWarehouseBaseDir();
            #var_dump($baseDir);
            //Get the list of directories
            if (is_dir($baseDir)) {
                $rootList = scandir($baseDir);
                #var_dump($rootList);

                foreach ($rootList as $rootKey => $rootValue)
                {
                    if (!in_array($rootValue,array(".","..")))
                    {
                        if (is_dir($baseDir . DIRECTORY_SEPARATOR . $rootValue))
                        {
                            //Check each Directory for ovdmConfig.json
                            $cruiseList = scandir($baseDir . DIRECTORY_SEPARATOR . $rootValue);
                            #var_dump($cruiseList);
                            foreach ($cruiseList as $cruiseKey => $cruiseValue){
                                #var_dump($cruiseValue);
                                if (in_array($cruiseValue,array(self::CONFIG_FN))){
                                    #var_dump($baseDir . DIRECTORY_SEPARATOR . $rootValue . DIRECTORY_SEPARATOR . self::CONFIG_FN);
                                    $ovdmConfigContents = file_get_contents($baseDir . DIRECTORY_SEPARATOR . $rootValue . DIRECTORY_SEPARATOR . self::CONFIG_FN);
                                    $ovdmConfigJSON = json_decode($ovdmConfigContents,true);
                                    #var_dump($ovdmConfigJSON['extraDirectoriesConfig']);
                                    //Get the the directory that holds the DashboardData
                                    for($i = 0; $i < count($ovdmConfigJSON['extraDirectoriesConfig']); $i++){
                                        if(strcmp($ovdmConfigJSON['extraDirectoriesConfig'][$i]['name'], 'Dashboard Data') === 0){
                                            $dataDashboardList = scandir($baseDir . DIRECTORY_SEPARATOR . $rootValue . DIRECTORY_SEPARATOR . $ovdmConfigJSON['extraDirectoriesConfig'][$i]['destDir']);
                                            foreach ($dataDashboardList as $dataDashboardKey => $dataDashboardValue){
                                                //If a manifest file is found, add CruiseID to output
                                                if (in_array($dataDashboardValue,array(self::MANIFEST_FN))){
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
            #var_dump($this->_cruises);

            if(count($this->_cruises) > 0) {
                rsort($this->_cruises);
            }
            return $this->_cruises;
        } else {
            return array("Error"=>"Could not find base directory.");
        }
    }
    
    public function getLatestCruise() {
        return $this->getCruises()[0];
    }

}