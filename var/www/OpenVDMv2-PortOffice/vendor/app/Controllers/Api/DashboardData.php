<?php

namespace Controllers\Api;
use Core\Controller;

class DashboardData extends Controller {

    private $_model;

    public function __construct(){

        $this->_model = new \Models\DashboardData();
    }
    
    public function getCruises() {
        $warehouseModel = new \Models\Warehouse();
        echo json_encode($warehouseModel->getCruises());
    }
    
    public function getLatestCruise() {
        $warehouseModel = new \Models\Warehouse();
        echo json_encode($warehouseModel->getLatestCruise());
    }
        
    public function getLatestDataObjectByType($cruiseID, $dataType){
        $this->_model->setCruiseID($cruiseID);
        $dataObjectList = $this->_model->getDashboardObjectsByTypes($dataType);
        if(count($dataObjectList) > 0) {
            echo json_encode(array($dataObjectList[count($dataObjectList)-1]));
        } else {
            echo json_encode(array());
        }
    }
    
    public function getLatestVisualizerDataByType($cruiseID, $dataType){
        $this->_model->setCruiseID($cruiseID);
        $dataObjectList = $this->_model->getDashboardObjectsByTypes($dataType);
        if(count($dataObjectList) > 0) {
            $lastDataObject = $dataObjectList[count($dataObjectList)-1];
            //echo $lastDataObject['dd_json'];
            echo json_encode($this->_model->getDashboardObjectVisualizerDataByJsonName($lastDataObject['dd_json']));
        } else {
            echo json_encode(array());
        }
    }
    
    public function getLatestStatsByType($cruiseID, $dataType){
        $this->_model->setCruiseID($cruiseID);
        $dataObjectList = $this->_model->getDashboardObjectsByTypes($dataType);
        if(count($dataObjectList) > 0) {
            $lastDataObject = $dataObjectList[count($dataObjectList)-1];
            //echo $lastDataObject['dd_json'];
            echo json_encode($this->_model->getDashboardObjectStatsByName($lastDataObject['dd_json']));
        } else {
            echo json_encode(array());
        }
    }
    
    public function getLatestQualityTestsByType($cruiseID, $dataType){
        $this->_model->setCruiseID($cruiseID);
        $dataObjectList = $this->_model->getDashboardObjectsByTypes($dataType);
        if(count($dataObjectList) > 0) {
            $lastDataObject = $dataObjectList[count($dataObjectList)-1];
            //echo $lastDataObject['dd_json'];
            echo json_encode($this->_model->getDashboardObjectQualityTestsByName($lastDataObject['dd_json']));
        } else {
            echo json_encode(array());
        }
    }
    
    public function getDashboardObjectVisualizerDataByJsonName($cruiseID, $dd_json){
        $this->_model->setCruiseID($cruiseID);
        $dataObjectVisualizerData = $this->_model->getDashboardObjectVisualizerDataByJsonName($dd_json);
        if(count($dataObjectVisualizerData) > 0) {
            echo json_encode($dataObjectVisualizerData);
        } else {
            echo json_encode(array());
        }
    }
    
    public function getDashboardObjectVisualizerDataByRawName($cruiseID, $raw_data){
        $this->_model->setCruiseID($cruiseID);
        $dataObjectVisualizerData = $this->_model->getDashboardObjectVisualizerDataByRawName($raw_data);
        if(count($dataObjectVisualizerData) > 0) {
            echo json_encode($dataObjectVisualizerData);
        } else {
            echo json_encode(array());
        }
    }
    
    public function getDashboardObjectStatsByJsonName($cruiseID, $dd_json){
        $this->_model->setCruiseID($cruiseID);
        $dataObjectStats = $this->_model->getDashboardObjectStatsByJsonName($dd_json);
        if(count($dataObjectStats) > 0) {
            echo json_encode($dataObjectStats);
        } else {
            echo json_encode(array());
        }
    }
    
    public function getDashboardObjectStatsByRawName($cruiseID, $raw_data){
        $this->_model->setCruiseID($cruiseID);
        $dataObjectStats = $this->_model->getDashboardObjectStatsByRawName($raw_data);
        if(count($dataObjectStats) > 0) {
            echo json_encode($dataObjectStats);
        } else {
            echo json_encode(array());
        }
    }
    
    public function getDashboardObjectQualityTestsByJsonName($cruiseID, $dd_json){
        $this->_model->setCruiseID($cruiseID);
        $dataObjectQualityTests = $this->_model->getDashboardObjectQualityTestsByJsonName($dd_json);
        if(count($dataObjectQualityTests) > 0) {
            echo json_encode($dataObjectQualityTests);
        } else {
            echo json_encode(array());
        }
    }
    
    public function getDashboardObjectQualityTestsByRawName($cruiseID, $raw_data){
        $this->_model->setCruiseID($cruiseID);
        $dataObjectQualityTests = $this->_model->getDashboardObjectQualityTestsByRawName($raw_data);
        if(count($dataObjectQualityTests) > 0) {
            echo json_encode($dataObjectQualityTests);
        } else {
            echo json_encode(array());
        }
    }
}