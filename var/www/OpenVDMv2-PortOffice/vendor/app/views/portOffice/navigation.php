<?php

use Core\Language;

?>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
<?php
    for($i = 0; $i < count($data['placeholders']); $i++){
        for($j=0; $j < count($data['placeholders'][$i]->dataFiles); $j++){
            $filecount += count($data['placeholders'][$i]->dataFiles[$j]);
        }
?>
                            <div class="panel panel-default">
                                <div class="panel-heading"><?php echo $data['placeholders'][$i]->heading;?></div>                                
                                <div class="panel-body">
                                    <div class="<?php echo $data['placeholders'][$i]->plotType; ?>" id="<?php echo $data['placeholders'][$i]->id;?>_placeholder" style="min-height:<?php echo (strcmp($data['placeholders'][$i]->plotType, 'map') === 0? '493': '200'); ?>px;"><?php echo ($filecount > 0? 'Loading...': 'No Data Found.'); ?></div>
                                </div>
                                <div class="panel-footer">
                                    <div class="objectList" id="<?php echo $data['placeholders'][$i]->id;?>_objectList-placeholder">
                                        <form>                                            
<?php
        for($j = 0; $j < count($data['placeholders'][$i]->dataTypes); $j++){
?>                                     
                                            <div class="row">
                                                <div class="col-lg-12"><strong><?php echo $data['placeholders'][$i]->dataFiles[$j][0]['type']; ?></strong></div>
<?php
            if(count($data['placeholders'][$i]->dataFiles[$j]) > 0){
                if(strcmp($data['placeholders'][$i]->dataTypes[$j], 'geoJSON')===0) {
?>
                                                <div class='col-lg-12'>
                                                    <input class='lp-checkbox' type="checkbox" value="<?php echo $data['placeholders'][$i]->dataFiles[$j][0]['type'];?>" checked> Latest Position
                                                </div></br>
<?php
                    for($k = count($data['placeholders'][$i]->dataFiles[$j])-1; $k >= 0; $k--){
?>                              
                                                <div class='col-lg-4 col-sm-6'>
                                                    <input class='<?php echo $data['placeholders'][$i]->dataTypes[$j]; ?>-checkbox' type="checkbox" value="<?php echo $data['placeholders'][$i]->dataFiles[$j][$k]['dd_json'];?>" checked> <?php echo end(explode('/',$data['placeholders'][$i]->dataFiles[$j][$k]['raw_data']));?>
                                                </div>
<?php
                    }
                } else if(strcmp($data['placeholders'][$i]->dataTypes[$j], 'tms')===0) {
                    for($k = count($data['placeholders'][$i]->dataFiles[$j])-1; $k >= 0; $k--){
?>                              
                                                <div class='col-lg-4 col-sm-6'>
                                                    <input class='<?php echo $data['placeholders'][$i]->dataTypes[$j]; ?>-checkbox' type="checkbox" value="<?php echo $data['placeholders'][$i]->dataFiles[$j][$k]['dd_json'];?>" checked> <?php echo end(explode('/',$data['placeholders'][$i]->dataFiles[$j][$k]['raw_data']));?>
                                                </div>
<?php
                    }
                } else if(strcmp($data['placeholders'][$i]->dataTypes[$j], 'json')===0) {
?>
                                                <div class="form-group">
<?php
                    for($k = count($data['placeholders'][$i]->dataFiles[$j])-1; $k >= 0; $k--){
?>                              
                                                    <div class='col-lg-4 col-sm-6'>
                                                        <input class='<?php echo $data['placeholders'][$i]->dataTypes[$j]; ?>-radio' name="<?php echo $data['placeholders'][$i]->dataFiles[$j][$k]['type'];?>" type="radio" value="<?php echo $data['placeholders'][$i]->dataFiles[$j][$k]['dd_json'];?>"  <?php echo ($k === count($data['placeholders'][$i]->dataFiles[$j])-1? 'checked' : '');   ?>> <?php echo end(explode('/',$data['placeholders'][$i]->dataFiles[$j][$k]['raw_data']));?>
                                                    </div>
<?php
                    }
?>
                                                </div>
<?php
                    }
            } else {
?>
                                                <div class='col-lg-12'>No data found</div>
<?php
            }
?>
                                            </div>
<?php
        }
?>
                                        </form>
                                    </div>
                                </div>
                            </div>
<?php
    }
?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>