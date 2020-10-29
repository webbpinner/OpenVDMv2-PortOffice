<?php

use Core\Error;

use Helpers\Assets;
use Helpers\Url;
use Helpers\Hooks;
use Helpers\Session;

//initialise hooks
$hooks = Hooks::get();

if(strcmp(Session::get('userRole'), '1') != 0) {
    $data['tabNames'] = array_diff($data['tabNames'], array('Users', 'System'));
    $data['tabPages'] = array_diff($data['tabPages'], array('users', 'system'));
}

$warehouseModel = new \Models\Warehouse();
$data['cruiseList'] = $warehouseModel->getCruises();

?>
<!DOCTYPE html>
<html lang="<?php echo LANGUAGE_CODE; ?>">
<head>

	<!-- Site meta -->
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Open Vessel Data Management v2.1 (OpenVDMv2) - Port Office">
    <meta name="author" content="Capable Solutions">
	<title><?php echo $data['title'].' - '.SITETITLE; //SITETITLE defined in app/core/config.php ?></title>

	<!-- CSS -->
<?php

    $cssFileArray = array(
        'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css',
        Url::templatePath() . 'css/style.css',
    );

    if (isset($data['css'])){
        foreach ($data['css'] as &$cssFile) {
            if ($cssFile === 'leaflet') {
                array_push($cssFileArray, 'http://cdn.leafletjs.com/leaflet/v0.7.7/leaflet.css');
                array_push($cssFileArray, 'https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/leaflet.fullscreen.css');
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
<!-- Start of header -->
    <header>
        <nav class="navbar navbar-default">
            <div class="container-fluid" style="max-width:1400px"><!-- Brand and toggle get grouped for better mobile display -->
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
                    <form action="<?php echo DIR;?>setCruise" class="navbar-form pull-right" method="POST">
                        <input type="hidden" name="currentPage" value="<?php echo $data['page']?>"> 
                        <input type="hidden" name="currentTitle" value="<?php echo $data['title']?>"> 
                        <div class="form-group">
<?php
    if(is_array($data['cruiseList']) && count($data['cruiseList']) > 0) {
?>
                            <select name="cruiseID" onchange="this.form.submit()" class="form-control">
<?php
        for($i=0;$i<count($data['cruiseList']); $i++){
?>
                                <option value="<?php echo $data['cruiseList'][$i]; ?>"<?php echo ' ' . (Session::get('cruiseID') == $data['cruiseList'][$i] ? 'selected':'');?>><?php echo $data['cruiseList'][$i]; ?></option>
<?php
        }
    } else {
?>
                            <select name="cruiseID" class="form-control disabled">
                                <option>No Cruises Available</option>
<?php
    }
?>
                            </select>
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
    <div class="container-fluid" style="max-width:1400px">
<!-- End of header -->
