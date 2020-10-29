<?php

use Helpers\Assets;
use Helpers\Url;
use Helpers\Hooks;
use Helpers\Session;

//initialise hooks
$hooks = Hooks::get();
?>

<!-- Start of footer -->
    </div>
    <footer class="footer">
        <div class="container-fluid" style="max-width:1400px">
            <div class="pull-right text-muted" style="padding-top:15px;padding-right:15px">
                <a href="https://github.com/webbpinner/OpenVDMv2-PortOffice" target="_blank">OpenVDMv2 - PortOffice</a> is licensed under the <a href="http://www.gnu.org/licenses/gpl-3.0.html">GPLv3</a> public license.
            </div>
        </div>
    </footer>


<!-- JS -->
<script type="text/javascript">
    var siteRoot = "<?php echo DIR ?>";
    
    <?php echo (isset($data['cruiseID']) ? 'var cruiseID = "' . $data['cruiseID'] . '";' : ''); ?>
    
    <?php echo (isset($data['dataWarehouseApacheDir']) ? 'var cruiseDataDir = "' . $data['dataWarehouseApacheDir'] . '";' : ''); ?>
    
    <?php echo (isset($data['geoJSONTypes']) ? 'var geoJSONTypes = [\'' . join('\', \'', $data['geoJSONTypes']) . '\'];' : ''); ?>
    
    <?php echo (isset($data['tmsTypes']) ? 'var tmsTypes = [\'' . join('\', \'', $data['tmsTypes']) . '\'];' : ''); ?>
    
    <?php echo (isset($data['jsonTypes']) ? 'var jsonTypes = [\'' . join('\', \'', $data['jsonTypes']) . '\'];' : ''); ?>
        
<?php
    if(isset($data['subPages'])) {
        echo '    var subPages = [];' . "\n";
        
        foreach ($data['subPages'] as $key => $subPage) {
            echo '    subPages[\'' . $key . '\'] = \'' . $subPage . '\';' . "\n";
            
        }
    }
?>

</script>

<?php

$jsFileArray = array(
    'https://code.jquery.com/jquery-2.2.3.min.js',
    'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js',
    'https://use.fontawesome.com/3830781cff.js',
);

if (isset($data['javascript'])){
    foreach ($data['javascript'] as &$jsFile) {
        if ($jsFile === 'leaflet') {
            array_push($jsFileArray, 'http://cdn.leafletjs.com/leaflet/v0.7.7/leaflet.js');
            array_push($jsFileArray, 'https://cdn.jsdelivr.net/leaflet.esri/1.0.3/esri-leaflet.js');
            array_push($jsFileArray, 'https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/Leaflet.fullscreen.min.js');
            array_push($jsFileArray, 'https://cdn.jsdelivr.net/npm/leaflet-easyprint@2.1.9/dist/bundle.min.js');
        } else if ($jsFile === 'highcharts') {
            array_push($jsFileArray, 'https://code.highcharts.com/highcharts.js');
        } else if ($jsFile === 'highcharts-exporting') {
            array_push($jsFileArray, 'https://code.highcharts.com/modules/exporting.js');
        } else {
            array_push($jsFileArray, Url::templatePath() . "js/" . $jsFile . ".js");
        }
    }
}

Assets::js($jsFileArray);

//hook for plugging in javascript
$hooks->run('js');

//hook for plugging in code into the footer
$hooks->run('footer');
?>

</body>
</html>
