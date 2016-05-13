<?php
/**
 * Routes - all standard routes are defined here.
 *
 * @author David Carr - dave@daveismyname.com
 *
 * @version 2.2
 * @date updated Sept 19, 2015
 */

/** Create alias for Router. */
use Core\Router;
use Helpers\Hooks;

/* Define routes. */
Router::any('dataDashboard', 'Controllers\DataDashboard@index');
Router::any('dataDashboard/customTab/(:any)', 'Controllers\DataDashboard@customTab');
Router::any('dataDashboard/dataQuality', 'Controllers\DataDashboard@dataQuality');
Router::any('dataDashboard/dataQualityShowFileStats/(:all)', 'Controllers\DataDashboard@dataQualityShowFileStats');
Router::any('dataDashboard/dataQualityShowDataTypeStats/(:any)', 'Controllers\DataDashboard@dataQualityShowDataTypeStats');
Router::any('users', 'Controllers\Users@index');
Router::any('addUser', 'Controllers\Users@addUser');
Router::any('deleteUser/(:num)', 'Controllers\Users@deleteUser');
Router::any('editUser/(:num)', 'Controllers\Users@editUser');
Router::any('logout', 'Controllers\Auth@logout');
Router::any('setCruise', 'Controllers\DataDashboard@setCruise');

Router::any('api/dashboardData/getCruises', 'Controllers\Api\DashboardData@getCruises');
Router::any('api/dashboardData/getLatestCruise', 'Controllers\Api\DashboardData@getLatestCruise');
Router::any('api/dashboardData/getLatestDataObjectByType/(:any)/(:any)', 'Controllers\Api\DashboardData@getLatestDataObjectByType');
Router::any('api/dashboardData/getLatestVisualizerDataByType/(:any)/(:any)', 'Controllers\Api\DashboardData@getLatestVisualizerDataByType');
Router::any('api/dashboardData/getLatestStatsByType/(:any)/(:any)', 'Controllers\Api\DashboardData@getLatestStatsByType');
Router::any('api/dashboardData/getLatestQualityTestsByType/(:any)/(:any)', 'Controllers\Api\DashboardData@getLatestQualityTestsByType');
Router::any('api/dashboardData/getDashboardObjectVisualizerDataByJsonName/(:any)/(:all)', 'Controllers\Api\DashboardData@getDashboardObjectVisualizerDataByJsonName');
Router::any('api/dashboardData/getDashboardObjectVisualizerDataByRawName/(:any)/(:all)', 'Controllers\Api\DashboardData@getDashboardObjectVisualizerDataByRawName');
Router::any('api/dashboardData/getDashboardObjectStatsByJsonName/(:any)/(:all)', 'Controllers\Api\DashboardData@getDashboardObjectStatsByJsonName');
Router::any('api/dashboardData/getDashboardObjectStatsByRawName/(:any)/(:all)', 'Controllers\Api\DashboardData@getDashboardObjectStatsByRawName');
Router::any('api/dashboardData/getDashboardObjectQualityTestsByJsonName/(:any)/(:all)', 'Controllers\Api\DashboardData@getDashboardObjectQualityTestsByJsonName');
Router::any('api/dashboardData/getDashboardObjectQualityTestsByRawName/(:any)/(:all)', 'Controllers\Api\DashboardData@getDashboardObjectQualityTestsByRawName');


Router::any('', 'Controllers\Auth@login');

/* Module routes. */
$hooks = Hooks::get();
$hooks->run('routes');

/* If no route found. */
Router::error('Core\Error@index');

/* Turn on old style routing. */
Router::$fallback = false;

/* Execute matched routes. */
Router::dispatch();
