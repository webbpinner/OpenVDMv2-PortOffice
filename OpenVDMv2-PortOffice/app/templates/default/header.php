<?php

use Core\Error;

use Helpers\Assets;
use Helpers\Url;
use Helpers\Hooks;
use Helpers\Session;

//initialise hooks
$hooks = Hooks::get();

//navigation
$data['tabNames'] = array("Dashboard", "Navigation", "Weather", "Sound Velocity", "Data Quality", "Users", "System");
$data['tabPages'] = array("dashboard", "navigation", "weather", "soundVelocity", "dataQuality", "users", "system");

if(strcmp(Session::get('userRole'), '1') != 0) {
    $data['tabNames'] = array_diff($data['tabNames'], array('Users', 'System'));
    $data['tabPages'] = array_diff($data['tabPages'], array('users', 'system'));
}

$cruisesModel = new \Models\Cruises();
$data['cruiseList'] = $cruisesModel->getCruises();

?>
<!DOCTYPE html>
<html lang="<?php echo LANGUAGE_CODE; ?>">
<head>

	<!-- Site meta -->
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Open Vessel Data Management v2.0 (OpenVDMv2) - Port Office">
    <meta name="author" content="Capable Solutions">
	<title><?php echo $data['title'].' - '.SITETITLE; //SITETITLE defined in app/core/config.php ?></title>

	<!-- CSS -->
<?php

    $cssFileArray = array(
        Url::templatePath() . 'bower_components/bootstrap/dist/css/bootstrap.min.css',
        Url::templatePath() . 'bower_components/metisMenu/dist/metisMenu.min.css',
        //Url::templatePath() . 'bower_components/startbootstrap-sb-admin-2/dist/css/sb-admin-2.css',
        Url::templatePath() . 'bower_components/font-awesome/css/font-awesome.min.css',
        Url::templatePath() . 'css/style.css',
    );

    if (isset($data['css'])){
        foreach ($data['css'] as &$cssFile) {
            if ($cssFile === 'leaflet') {
                array_push($cssFileArray, Url::templatePath() . 'bower_components/leaflet/leaflet.css');
                array_push($cssFileArray, Url::templatePath() . 'bower_components/leaflet/plugins/leaflet-fullscreen/leaflet.fullscreen.css');
            } else if ($cssFile === 'bootstrap-datepicker') {
                array_push($cssFileArray, Url::templatePath() . 'bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css');   
            } else {
                array_push($cssFileArray, Url::templatePath() . "css/" . $cssFile . ".css");
            }
        }
    }

	Assets::css($cssFileArray);

    //hook for plugging in css
	$hooks->run('css');
?>

</head>
<body>

<?php
//hook for running code after body tag
$hooks->run('afterBody');
?>

    <header>
        <nav class="navbar navbar-default">
            <div class="container"><!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="<?php echo DIR;?>"><?php echo SITETITLE;?></a>
                </div> <!-- navbar-header -->
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <form action="<?php echo DIR;?>setCruise" class="navbar-form navbar-right" method="POST">
                        <input type="hidden" name="currentPage" value="<?php echo $data['page']?>"> 
                        <input type="hidden" name="currentTitle" value="<?php echo $data['title']?>"> 
                        <div class="form-group">
<?php
    if(sizeof($data['cruiseList']) > 0) {
?>
                            <select name="cruiseID" onchange="this.form.submit()" class="form-control">
                                <option value="<?php echo $data['cruiseList'][0]; ?>"<?php echo ' ' . (Session::get('cruiseID') == $data['cruiseList'][0] ? 'selected':'');?>>Current Cruise</option>
<?php
        for($i=1;$i<sizeof($data['cruiseList']); $i++){
?>
                                <option value="<?php echo $data['cruiseList'][$i]; ?>"<?php echo ' ' . (Session::get('cruiseID') == $data['cruiseList'][$i] ? 'selected':'');?>><?php echo $data['cruiseList'][$i]; ?></option>
<?php
        }
?>
                            </select>
<?php
    } else {
?>
                            <select class="form-control disabled"></select>
<?php
    }
?>
                        </div> <!-- form-group -->
                    </form>
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-user">
                                <li><a href="<?php echo DIR; ?>editUser/<?php echo Session::get('userID') ?>"><i class="fa fa-user fa-fw"></i>User Settings</a></li>
                                <li><a href="<?php echo DIR; ?>logout"><i class="fa fa-sign-out fa-fw"></i>Logout</a></li>
                            </ul> <!-- dropdown-user -->
                        </li> <!-- dropdown -->
                    </ul> <!-- navbar-right -->
                </div> <!-- navbar-collapse -->
            </div> <!-- container -->
        </nav>           
    </header>
    <!-- Page Content -->
    <div class="container">
        <div class="row"> <!-- success/error message -->
            <div class="col-lg-12"><?php echo Error::display(Session::pull('message'), 'alert alert-success'); ?></div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <ul class="nav nav-tabs">
<?php
    for($i=0; $i<sizeof($data['tabNames']); $i++) {
?>
                    <li role="presentation"<?php echo ($data['page'] == $data['tabPages'][$i] ? ' class="active"':''); ?>><a href="<?php echo DIR . $data['tabPages'][$i]; ?>"><?php echo $data['tabNames'][$i]; ?></a></li>
<?php
    }
?>
                </ul>
            </div>
        </div>