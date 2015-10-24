<?php
namespace Controllers;

use Core\View;
use Core\Controller;
use Helpers\Session;
use Helpers\Url;


class Placeholder
{
    public $plotType;
    public $dataType;
    public $id;
    public $heading;
    public $dataTypes;
    public $dataFiles;
}

/*
 * Welcome controller
 *
 * @author David Carr - dave@simplemvcframework.com
 * @version 2.2
 * @date June 27, 2014
 * @date updated May 18 2015
 */
class PortOffice extends Controller
{

    private $_tabNames,
            $_tabPages,
            $_systemModel,
            $_dashboardDataModel;

    /**
     * Call the parent construct
     */
    public function __construct()
    {
        if(!Session::get('loggedin')){
            Url::redirect('');
        }

        parent::__construct();
        
        $this->_systemModel = new \Models\System();

        if(!Session::get('cruiseID')){
            $cruisesModel = new \Models\Cruises(); 
            Session::set('cruiseID', $cruisesModel->getCruises()[0]);
        }
        
        $this->_dashboardDataModel = new \Models\DashboardData(Session::get('cruiseID'));
    }

    /**
     * Define Index page title and load template files
     */
    public function dashboard()
    {

        $data['title'] = 'Dashboard';
        $data['page'] = 'dashboard';
        $data['shoresideDWApacheDir'] = $this->_systemModel->getShoresideDWApacheDir();
        $data['css'] = array('leaflet');
        $data['javascript'] = array('dashboard','highcharts','leaflet');

        $data['dataTypes'] = $this->_dashboardDataModel->getDashboardDataTypes();
        //var_dump($data['dataTypes']);
        
        View::renderTemplate('header', $data);
        if( sizeof($data['dataTypes'])>0){
            View::render('portOffice/dashboard', $data);
        } else {
            View::render('portOffice/noData', $data);
        }
        View::renderTemplate('footer', $data);
    }

    public function navigation()
    {
        
        $data['title'] = 'Navigation';
        $data['page'] = 'navigation';
        $data['shoresideDWApacheDir'] = $this->_systemModel->getShoresideDWApacheDir();
        $data['css'] = array('leaflet');
        $data['javascript'] = array('defaultData','leaflet','highcharts');
        
        $position = new Placeholder();
        $position->plotType = 'map';
        $position->id = 'map';
        $position->heading = 'Position';
        $position->dataTypes = array('geoJSON', 'geoJSON', 'geoJSON', 'geoJSON');
        $position->dataFiles = array(
            $this->_dashboardDataModel->getDashboardObjectsByTypes('gga-furuno'),
            $this->_dashboardDataModel->getDashboardObjectsByTypes('gga-garmin741'),
            $this->_dashboardDataModel->getDashboardObjectsByTypes('gga-nstarwaas'),
            $this->_dashboardDataModel->getDashboardObjectsByTypes('gga-spectracom'),
        );
        
        $hdt_gyro1 = new Placeholder();
        $hdt_gyro1->plotType = 'chart';
        $hdt_gyro1->id = 'hdt-gyro1';
        $hdt_gyro1->heading = 'Heading - Gyro 1';
        $hdt_gyro1->dataTypes = array('json');
        $hdt_gyro1->dataFiles = array(
            $this->_dashboardDataModel->getDashboardObjectsByTypes('hdt-gyro1')
        );
        
        $hdt_gyro2 = new Placeholder();
        $hdt_gyro2->plotType = 'chart';
        $hdt_gyro2->id = 'hdt-gyro2';
        $hdt_gyro2->heading = 'Heading - Gyro 2';
        $hdt_gyro2->dataTypes = array('json');
        $hdt_gyro2->dataFiles = array(
            $this->_dashboardDataModel->getDashboardObjectsByTypes('hdt-gyro2')
        );
        
        $gnss_adu2 = new Placeholder();
        $gnss_adu2->plotType = 'chart';
        $gnss_adu2->id = 'gnss-adu2';
        $gnss_adu2->heading = 'Attitude - ADU2';
        $gnss_adu2->dataTypes = array('json');
        $gnss_adu2->dataFiles = array(
            $this->_dashboardDataModel->getDashboardObjectsByTypes('gnss-adu2')
        );
        
        $gnss_adu5 = new Placeholder();
        $gnss_adu5->plotType = 'chart';
        $gnss_adu5->id = 'gnss-adu5';
        $gnss_adu5->heading = 'Attitude - ADU5';
        $gnss_adu5->dataTypes = array('json');
        $gnss_adu5->dataFiles = array(
            $this->_dashboardDataModel->getDashboardObjectsByTypes('gnss-adu5')
        );

        $data['placeholders'] = array($position, $hdt_gyro1, $hdt_gyro2, $gnss_adu2, $gnss_adu5);
        
        $noDataFiles = true;
        for($i = 0; $i < sizeof($data['placeholders']); $i++) {
            for($j = 0; $j < sizeof($data['placeholders'][$i]->dataFiles); $j++) {
                if(sizeof($data['placeholders'][$i]->dataFiles[$j]) > 0) {
                    $noDataFiles = false;
                    break;
                }
            }
            
            if(!$noDataFiles) {
                break;
            }
        }
        
        View::renderTemplate('header', $data);
        if ($noDataFiles) {
            View::render('portOffice/noData', $data);
        } else {
            View::render('portOffice/default', $data);
        }
        View::renderTemplate('footer', $data);
    }
    
    public function weather()
    {
        
        $data['title'] = 'Weather';
        $data['page'] = 'weather';
        $data['shoresideDWApacheDir'] = $this->_systemModel->getShoresideDWApacheDir();
        $data['javascript'] = array('defaultData', 'highcharts');
        
        $met = new Placeholder();
        $met->plotType = 'chart';
        $met->id = 'met';
        $met->heading = 'Meterological Sensor';
        $met->dataTypes = array('json');
        $met->dataFiles = array(
            $this->_dashboardDataModel->getDashboardObjectsByTypes('met')
        );
        
        $twind_bow = new Placeholder();
        $twind_bow->plotType = 'chart';
        $twind_bow->id = 'twind-bow';
        $twind_bow->heading = 'Wind Sensor - Bow';
        $twind_bow->dataTypes = array('json');
        $twind_bow->dataFiles = array(
            $this->_dashboardDataModel->getDashboardObjectsByTypes('twind-bow')
        );
        
        $twind_stbd = new Placeholder();
        $twind_stbd->plotType = 'chart';
        $twind_stbd->id = 'twind-stbd';
        $twind_stbd->heading = 'Wind Sensor - Starboard';
        $twind_stbd->dataTypes = array('json');
        $twind_stbd->dataFiles = array(
            $this->_dashboardDataModel->getDashboardObjectsByTypes('twind-stbd')
        );
        
        $twind_port = new Placeholder();
        $twind_port->plotType = 'chart';
        $twind_port->id = 'twind-port';
        $twind_port->heading = 'Wind Sensor - Port';
        $twind_port->dataTypes = array('json');
        $twind_port->dataFiles = array(
            $this->_dashboardDataModel->getDashboardObjectsByTypes('twind-port')
        );
        
        $data['placeholders'] = array($met, $twind_bow, $twind_stbd, $twind_port);
        
        $noDataFiles = true;
        for($i = 0; $i < sizeof($data['placeholders']); $i++) {
            for($j = 0; $j < sizeof($data['placeholders'][$i]->dataFiles); $j++) {
                if(sizeof($data['placeholders'][$i]->dataFiles[$j]) > 0) {
                    $noDataFiles = false;
                    break;
                }
            }
            
            if(!$noDataFiles) {
                break;
            }
        }
        
        View::renderTemplate('header', $data);
        if ($noDataFiles) {
            View::render('portOffice/noData', $data);
        } else {
            View::render('portOffice/default', $data);
        }
        View::renderTemplate('footer', $data);
    }
    
    public function soundVelocity()
    {
        
        $data['title'] = 'Sound Velocity';
        $data['page'] = 'soundVelocity';
        $data['shoresideDWApacheDir'] = $this->_systemModel->getShoresideDWApacheDir();
        $data['javascript'] = array('defaultData', 'highcharts');
        
        $tsg_sbe45 = new Placeholder();
        $tsg_sbe45->plotType = 'chart';
        $tsg_sbe45->id = 'tsg-sbe45';
        $tsg_sbe45->heading = 'Thermosalinograph Sensor';
        $tsg_sbe45->dataTypes = array('json');
        $tsg_sbe45->dataFiles = array(
            $this->_dashboardDataModel->getDashboardObjectsByTypes('tsg-sbe45')
        );
        
        $data['placeholders'] = array($tsg_sbe45);
        
        $noDataFiles = true;
        for($i = 0; $i < sizeof($data['placeholders']); $i++) {
            for($j = 0; $j < sizeof($data['placeholders'][$i]->dataFiles); $j++) {
                if(sizeof($data['placeholders'][$i]->dataFiles[$j]) > 0) {
                    $noDataFiles = false;
                    break;
                }
            }
            
            if(!$noDataFiles) {
                break;
            }
        }
        
        View::renderTemplate('header', $data);
        if ($noDataFiles) {
            View::render('portOffice/noData', $data);
        } else {
            View::render('portOffice/default', $data);
        }
        View::renderTemplate('footer', $data);
    }
    
    public function dataQuality()
    {
        
        $data['title'] = 'Data Quality';
        $data['page'] = 'dataQuality';
        $data['javascript'] = array('dataQuality');
        $data['dataTypes'] = $this->_dashboardDataModel->getDashboardDataTypes();
        $data['dataObjects'] = array();
        $data['dataObjectsQualityTests'] = array();
        $data['dataObjectsStats'] = array();
        
        for($i = 0; $i < sizeof($data['dataTypes']); $i++) {
            array_push($data['dataObjects'], $this->_dashboardDataModel->getDashboardObjectsByTypes($data['dataTypes'][$i]));
            array_push($data['dataObjectsQualityTests'], array());
            array_push($data['dataObjectsStats'], array());
            for($j = 0; $j < sizeof($data['dataObjects'][$i]); $j++) {
                //var_dump($dashboardDataModel->getDashboardObjectQualityTestsByJsonName($data['dataObjects'][$i][$j]['dd_json']));
                array_push($data['dataObjectsQualityTests'][$i], $this->_dashboardDataModel->getDashboardObjectQualityTestsByJsonName($data['dataObjects'][$i][$j]['dd_json']));
                array_push($data['dataObjectsStats'][$i], $this->_dashboardDataModel->getDashboardObjectStatsByJsonName($data['dataObjects'][$i][$j]['dd_json']));
            }
        }
        
        View::renderTemplate('header', $data);
        if( sizeof($data['dataTypes'])>0){
            View::render('portOffice/dataQuality', $data);
        } else {
            View::render('portOffice/noData', $data);
        }
        View::renderTemplate('footer', $data);
    }
    
    public function dataQualityShowFileStats($raw_data){
        
        $data['title'] = 'Data Quality';
        $data['page'] = 'dataQuality';
        $data['javascript'] = array('dataQuality');
        $data['dataTypes'] = $this->_dashboardDataModel->getDashboardDataTypes();
        $data['dataObjects'] = array();
        $data['dataObjectsQualityTests'] = array();
        $data['dataObjectsStats'] = array();
        
        for($i = 0; $i < sizeof($data['dataTypes']); $i++) {
            array_push($data['dataObjects'], $this->_dashboardDataModel->getDashboardObjectsByTypes($data['dataTypes'][$i]));
            array_push($data['dataObjectsQualityTests'], array());
            array_push($data['dataObjectsStats'], array());
            for($j = 0; $j < sizeof($data['dataObjects'][$i]); $j++) {
                //var_dump($dashboardDataModel->getDashboardObjectQualityTestsByJsonName($data['dataObjects'][$i][$j]['dd_json']));
                array_push($data['dataObjectsQualityTests'][$i], $this->_dashboardDataModel->getDashboardObjectQualityTestsByJsonName($data['dataObjects'][$i][$j]['dd_json']));
                array_push($data['dataObjectsStats'][$i], $this->_dashboardDataModel->getDashboardObjectStatsByJsonName($data['dataObjects'][$i][$j]['dd_json']));
            }
        }
        
        $data['statsTitle'] = array_pop(explode("/", $raw_data));
        $data['stats'] = $this->_dashboardDataModel->getDashboardObjectStatsByRawName($raw_data);
        
        View::renderTemplate('header', $data);
        View::render('portOffice/dataQuality', $data);
        View::renderTemplate('footer', $data);
    }
    
    public function dataQualityShowDataTypeStats($dataType){
        
        $data['title'] = 'Data Quality';
        $data['page'] = 'dataQuality';
        $data['javascript'] = array('dataQuality');
        $data['dataTypes'] = $this->_dashboardDataModel->getDashboardDataTypes();
        $data['dataObjects'] = array();
        $data['dataObjectsQualityTests'] = array();
        $data['dataObjectsStats'] = array();
        
        for($i = 0; $i < sizeof($data['dataTypes']); $i++) {
            array_push($data['dataObjects'], $this->_dashboardDataModel->getDashboardObjectsByTypes($data['dataTypes'][$i]));
            array_push($data['dataObjectsQualityTests'], array());
            array_push($data['dataObjectsStats'], array());
            for($j = 0; $j < sizeof($data['dataObjects'][$i]); $j++) {
                //var_dump($dashboardDataModel->getDashboardObjectQualityTestsByJsonName($data['dataObjects'][$i][$j]['dd_json']));
                array_push($data['dataObjectsQualityTests'][$i], $this->_dashboardDataModel->getDashboardObjectQualityTestsByJsonName($data['dataObjects'][$i][$j]['dd_json']));
                array_push($data['dataObjectsStats'][$i], $this->_dashboardDataModel->getDashboardObjectStatsByJsonName($data['dataObjects'][$i][$j]['dd_json']));
            }
        }
        
        $data['statsTitle'] = $dataType;
        $data['stats'] = $this->_dashboardDataModel->getDataTypeStats($dataType);
        
        View::renderTemplate('header', $data);
        View::render('portOffice/dataQuality', $data);
        View::renderTemplate('footer', $data);
    }

    
    public function setCruise()
    {
        if(isset($_POST['cruiseID'])){
            Session::set('cruiseID', $_POST['cruiseID']);   
            $data['page'] = $_POST['currentPage'];
        } else {
            $data['page'] = $_POST['dashboard'];
        }
        
        Url::redirect($data['page']);
    }
}
