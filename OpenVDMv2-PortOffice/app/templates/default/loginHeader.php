<?php

use Helpers\Assets;
use Helpers\Url;
use Helpers\Hooks;

//initialise hooks
$hooks = Hooks::get();

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
                    <a class="navbar-brand" href="<?php echo DIR;?>"><?php echo SITETITLE;?></a>
                </div> <!-- navbar-header -->
            </div> <!-- container -->
        </nav>           
    </header>
    <!-- Page Content -->
    <div class="container">