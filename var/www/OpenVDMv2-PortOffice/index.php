<?php
if (file_exists('vendor/autoload.php')) {
    require 'vendor/autoload.php';
} else {
    echo "<h1>Please install via composer.json</h1>";
    echo "<p>Install Composer instructions: <a href='https://getcomposer.org/doc/00-intro.md#globally'>https://getcomposer.org/doc/00-intro.md#globally</a></p>";
    echo "<p>Once composer is installed navigate to the working directory in your terminal/command promt and enter 'composer install'</p>";
    exit;
}

if (!is_readable('app/Core/Config.php')) {
    die('No Config.php found, configure and rename Config.example.php to Config.php in app/Core.');
}

/*
 *---------------------------------------------------------------
 * APPLICATION ENVIRONMENT
 *---------------------------------------------------------------
 *
 * You can load different configurations depending on your
 * current environment. Setting the environment also influences
 * things like logging and error reporting.
 *
 * This can be set to anything, but default usage is:
 *
 *     development
 *     production
 *
 * NOTE: If you change these, also change the error_reporting() code below
 *
 */
    define('ENVIRONMENT', 'development');
/*
 *---------------------------------------------------------------
 * ERROR REPORTING
 *---------------------------------------------------------------
 *
 * Different environments will require different levels of error reporting.
 * By default development will show errors but production will hide them.
 */

if (defined('ENVIRONMENT')) {
    switch (ENVIRONMENT) {
        case 'development':
            error_reporting(E_ALL);
            break;
        case 'production':
            error_reporting(0);
            break;
        default:
            exit('The application environment is not set correctly.');
    }

}

//initiate config
new Core\Config();

//create alias for Router
use Core\Router;
use Helpers\Hooks;

//define routes
Router::any('dashboard', 'Controllers\PortOffice@dashboard');
Router::any('navigation', 'Controllers\PortOffice@navigation');
Router::any('weather', 'Controllers\PortOffice@weather');
Router::any('soundVelocity', 'Controllers\PortOffice@soundVelocity');
Router::any('dataQuality', 'Controllers\PortOffice@dataQuality');
Router::any('dataQualityShowFileStats/(:all)', 'Controllers\PortOffice@dataQualityShowFileStats');
Router::any('dataQualityShowDataTypeStats/(:all)', 'Controllers\PortOffice@dataQualityShowDataTypeStats');
Router::any('users', 'Controllers\Users@index');
Router::any('addUser', 'Controllers\Users@addUser');
Router::any('deleteUser/(:num)', 'Controllers\Users@deleteUser');
Router::any('editUser/(:num)', 'Controllers\Users@editUser');
Router::any('logout', 'Controllers\Auth@logout');
Router::any('system', 'Controllers\System@index');
Router::any('editShoresideDWBaseDir', 'Controllers\System@editShoresideDWBaseDir');
Router::any('editShoresideDWApacheDir', 'Controllers\System@editShoresideDWApacheDir');
Router::any('setCruise', 'Controllers\PortOffice@setCruise');

Router::any('api/getCruises', 'Controllers\Api\DashboardData@getCruises');
Router::any('api/getLatestDataObjectByType/(:any)/(:any)', 'Controllers\Api\DashboardData@getLatestDataObjectByType');
Router::any('api/getLatestVisualizerDataByType/(:any)/(:any)', 'Controllers\Api\DashboardData@getLatestVisualizerDataByType');
Router::any('api/getLatestStatsByType/(:any)/(:any)', 'Controllers\Api\DashboardData@getLatestStatsByType');
Router::any('api/getLatestQualityTestsByType/(:any)/(:any)', 'Controllers\Api\DashboardData@getLatestQualityTestsByType');
Router::any('api/getDashboardObjectVisualizerDataByJsonName/(:any)/(:all)', 'Controllers\Api\DashboardData@getDashboardObjectVisualizerDataByJsonName');
Router::any('api/getDashboardObjectVisualizerDataByRawName/(:any)/(:all)', 'Controllers\Api\DashboardData@getDashboardObjectVisualizerDataByRawName');
Router::any('api/getDashboardObjectStatsByJsonName/(:any)/(:all)', 'Controllers\Api\DashboardData@getDashboardObjectStatsByJsonName');
Router::any('api/getDashboardObjectStatsByRawName/(:any)/(:all)', 'Controllers\Api\DashboardData@getDashboardObjectStatsByRawName');
Router::any('api/getDashboardObjectQualityTestsByJsonName/(:any)/(:all)', 'Controllers\Api\DashboardData@getDashboardObjectQualityTestsByJsonName');
Router::any('api/getDashboardObjectQualityTestsByRawName/(:any)/(:all)', 'Controllers\Api\DashboardData@getDashboardObjectQualityTestsByRawName');

Router::any('', 'Controllers\Auth@login');

//module routes
$hooks = Hooks::get();
$hooks->run('routes');

//if no route found
Router::error('Core\Error@index');

//turn on old style routing
Router::$fallback = false;

//execute matched routes
Router::dispatch();
