<?php
namespace Controllers;
use Core\Controller;
use Core\View;
use Helpers\Session;
use Helpers\Url;

class DataDashboard extends Controller {
    
    private $_warehouseModel;
    private $_dashboardDataModel;
    private $_dataDashboardModel;

    public function __construct(){
        
        if(!Session::get('loggedin')){
            Url::redirect('');
        }

        parent::__construct();
        
        $this->_warehouseModel = new \Models\Warehouse();
        
        if(!Session::get('cruiseID')){
            Session::set('cruiseID', $this->_warehouseModel->getLatestCruise());
        }
        
        $this->_dashboardDataModel = new \Models\DashboardData(Session::get('cruiseID'));
        $this->_dataDashboardModel = new \Models\DataDashboard();
    }

    public function index(){
            
        $data['title'] = 'Data Dashboard';
        $data['page'] = 'main';
        $data['cruiseID'] = Session::get('cruiseID');
        $data['customDataDashboardTabs'] = $this->_dataDashboardModel->getDataDashboardTabs();
        //$data['systemStatus'] = $this->_warehouseModel->getSystemStatus();
        $data['dataWarehouseApacheDir'] = $this->_warehouseModel->getShoresideDataWarehouseApacheDir();
        $data['css'] = array('leaflet');
        $data['javascript'] = array('dataDashboardMain', 'dataDashboardMainCustom', 'leaflet', 'highcharts');
        $data['dataTypes'] = $this->_dashboardDataModel->getDashboardDataTypes();
        $data['geoJSONTypes'] = $this->_dataDashboardModel->getGeoJSONTypes();
        $data['tmsTypes'] = $this->_dataDashboardModel->getTMSTypes();
        $data['jsonTypes'] = $this->_dataDashboardModel->getJSONTypes();
        
        $data['subPages'] = $this->_dataDashboardModel->getSubPages();

        View::renderTemplate('header', $data);
        View::renderTemplate('dataDashboardHeader', $data);
        if( count($data['dataTypes'])>0){
            View::render('DataDashboard/main', $data);
        } else {
            View::render('DataDashboard/noData', $data);
        }
        View::renderTemplate('footer', $data);
    }
    
    public function customTab($tabName) {
        
        $tab = $this->_dataDashboardModel->getDataDashboardTab($tabName);
        $data['title'] = $tab['title'];
        $data['page'] = $tabName;
        $data['cruiseID'] = Session::get('cruiseID');
        $data['customDataDashboardTabs'] = $this->_dataDashboardModel->getDataDashboardTabs();
        //$data['systemStatus'] = $this->_warehouseModel->getSystemStatus();
        $data['dataWarehouseApacheDir'] = $this->_warehouseModel->getShoresideDataWarehouseApacheDir();

        $data['css'] = array();
        if ($tab['cssArray'] && count($tab['cssArray'])>0) {
            foreach ($tab['cssArray'] as $cssFile) {
                array_push($data['css'], $cssFile);
            }
        }
        
        $data['javascript'] = array();
        if ($tab['jsArray'] && count($tab['jsArray'])>0) {
            foreach ($tab['jsArray'] as $jsFile) {
                array_push($data['javascript'], $jsFile);
            }
        }
        
        $data['placeholders'] = array();
        if ($tab['placeholderArray'] && count($tab['placeholderArray'])>0) {
            foreach ($tab['placeholderArray'] as $placeholder) {
                $placeholder['dataFiles'] = array();
                foreach ($placeholder['dataArray'] as $dataObj) {
                    $objects = $this->_dashboardDataModel->getDashboardObjectsByTypes($dataObj['dataType']);
                    array_push($placeholder['dataFiles'], $objects);
                }
                array_push($data['placeholders'], $placeholder);
            }
        }
        
        $noDataFiles = true;
        for($i = 0; $i < count($data['placeholders']); $i++) {
            for($j = 0; $j < count($data['placeholders'][$i]['dataFiles']); $j++) {
                if(count($data['placeholders'][$i]['dataFiles'][$j]) > 0) {
                    $noDataFiles = false;
                    break;
                }
            }
            
            if(!$noDataFiles) {
                break;
            }
        }
        
        View::renderTemplate('header', $data);
        View::renderTemplate('dataDashboardHeader', $data);

        if ($noDataFiles) {
            View::render('DataDashboard/noData', $data);
        } else {
            View::render('DataDashboard/' . $tab['view'], $data);
        }
        View::renderTemplate('footer', $data);
        
    }
    
    public function dataQuality(){
        
        $data['title'] = 'Data Quality';
        $data['page'] = 'dataQuality';
        $data['cruiseID'] = Session::get('cruiseID');
        $data['customDataDashboardTabs'] = $this->_dataDashboardModel->getDataDashboardTabs();
        //$data['systemStatus'] = $this->_warehouseModel->getSystemStatus();
        $data['dataWarehouseApacheDir'] = $this->_warehouseModel->getShoresideDataWarehouseApacheDir();
        $data['javascript'] = array('dataDashboardQuality');
        $data['dataTypes'] = $this->_dashboardDataModel->getDashboardDataTypes();
        $data['dataObjects'] = array();
        $data['dataObjectsQualityTests'] = array();
        $data['dataObjectsStats'] = array();
        
        for($i = 0; $i < count($data['dataTypes']); $i++) {
            array_push($data['dataObjects'], $this->_dashboardDataModel->getDashboardObjectsByTypes($data['dataTypes'][$i]));
            array_push($data['dataObjectsQualityTests'], array());
            array_push($data['dataObjectsStats'], array());
            for($j = 0; $j < count($data['dataObjects'][$i]); $j++) {
                //var_dump($dashboardDataModel->getDashboardObjectQualityTestsByJsonName($data['dataObjects'][$i][$j]['dd_json']));
                array_push($data['dataObjectsQualityTests'][$i], $this->_dashboardDataModel->getDashboardObjectQualityTestsByJsonName($data['dataObjects'][$i][$j]['dd_json']));
                array_push($data['dataObjectsStats'][$i], $this->_dashboardDataModel->getDashboardObjectStatsByJsonName($data['dataObjects'][$i][$j]['dd_json']));
            }
        }
        
        View::renderTemplate('header', $data);
        View::renderTemplate('dataDashboardHeader', $data);

        if( count($data['dataTypes'])>0){
            View::render('DataDashboard/dataQuality', $data);
        } else {
            View::render('DataDashboard/noData', $data);
        }
        View::renderTemplate('footer', $data);
    }
    
    public function dataQualityShowFileStats($raw_data){
        
        $data['title'] = 'Data Quality';
        $data['page'] = 'dataQuality';
        $data['cruiseID'] = Session::get('cruiseID');
        $data['customDataDashboardTabs'] = $this->_dataDashboardModel->getDataDashboardTabs();
        //$data['systemStatus'] = $this->_warehouseModel->getSystemStatus();
        $data['javascript'] = array('dataDashboardQuality');
        $data['dataTypes'] = $this->_dashboardDataModel->getDashboardDataTypes();
        $data['dataObjects'] = array();
        $data['dataObjectsQualityTests'] = array();
        $data['dataObjectsStats'] = array();
        
        for($i = 0; $i < count($data['dataTypes']); $i++) {
            array_push($data['dataObjects'], $this->_dashboardDataModel->getDashboardObjectsByTypes($data['dataTypes'][$i]));
            array_push($data['dataObjectsQualityTests'], array());
            array_push($data['dataObjectsStats'], array());
            for($j = 0; $j < count($data['dataObjects'][$i]); $j++) {
                //var_dump($dashboardDataModel->getDashboardObjectQualityTestsByJsonName($data['dataObjects'][$i][$j]['dd_json']));
                array_push($data['dataObjectsQualityTests'][$i], $this->_dashboardDataModel->getDashboardObjectQualityTestsByJsonName($data['dataObjects'][$i][$j]['dd_json']));
                array_push($data['dataObjectsStats'][$i], $this->_dashboardDataModel->getDashboardObjectStatsByJsonName($data['dataObjects'][$i][$j]['dd_json']));
            }
        }
        
        $data['statsTitle'] = array_pop(explode("/", $raw_data));
        $data['statsDataType'] = $this->_dashboardDataModel->getDashboardObjectDataTypeByRawName($raw_data);        
        $data['stats'] = $this->_dashboardDataModel->getDashboardObjectStatsByRawName($raw_data);
        
        View::renderTemplate('header', $data);
        View::renderTemplate('dataDashboardHeader', $data);

        View::render('DataDashboard/dataQuality', $data);
        View::renderTemplate('footer', $data);
    }
    
    public function dataQualityShowDataTypeStats($dataType){
        
        $data['title'] = 'Data Quality';
        $data['page'] = 'dataQuality';
        $data['cruiseID'] = Session::get('cruiseID');
        $data['customDataDashboardTabs'] = $this->_dataDashboardModel->getDataDashboardTabs();
        //$data['systemStatus'] = $this->_warehouseModel->getSystemStatus();
        $data['javascript'] = array('dataDashboardQuality');
        $data['dataTypes'] = $this->_dashboardDataModel->getDashboardDataTypes();
        $data['dataObjects'] = array();
        $data['dataObjectsQualityTests'] = array();
        $data['dataObjectsStats'] = array();
        
        for($i = 0; $i < count($data['dataTypes']); $i++) {
            array_push($data['dataObjects'], $this->_dashboardDataModel->getDashboardObjectsByTypes($data['dataTypes'][$i]));
            array_push($data['dataObjectsQualityTests'], array());
            array_push($data['dataObjectsStats'], array());
            for($j = 0; $j < count($data['dataObjects'][$i]); $j++) {
                //var_dump($dashboardDataModel->getDashboardObjectQualityTestsByJsonName($data['dataObjects'][$i][$j]['dd_json']));
                array_push($data['dataObjectsQualityTests'][$i], $this->_dashboardDataModel->getDashboardObjectQualityTestsByJsonName($data['dataObjects'][$i][$j]['dd_json']));
                array_push($data['dataObjectsStats'][$i], $this->_dashboardDataModel->getDashboardObjectStatsByJsonName($data['dataObjects'][$i][$j]['dd_json']));
            }
        }
        
        $data['statsTitle'] = $dataType;
        $data['statsDataType'] = $dataType;   
        $data['stats'] = $this->_dashboardDataModel->getDataTypeStats($dataType);
        
        View::renderTemplate('header', $data);
        View::renderTemplate('dataDashboardHeader', $data);

        View::render('DataDashboard/dataQuality', $data);
        View::renderTemplate('footer', $data);
    }
    
    public function setCruise()
    {
        if(isset($_POST['cruiseID'])){
            Session::set('cruiseID', $_POST['cruiseID']);
        }
        
        Url::previous();
    }

}
