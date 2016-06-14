<?php

use Core\Language;
use Core\Error;
use Helpers\Session;
use Helpers\Url;

$loadingImage = '<img height="50" src="' . Url::templatePath() . 'images/loading.gif"/>';
?>
<!-- Start of dataDashboardHeader -->
        <div class="row">
            <div class="col-lg-12"><?php echo Error::display(Session::pull('message'), 'alert alert-success'); ?></div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <ul class="nav nav-tabs">
                    <li role="presentation" class="<?php echo ($data['page']==='main'? 'active': ''); ?>"><a id="main" href="<?php echo DIR; ?>dataDashboard">Main</a></li>                  
<?php
foreach($data['customDataDashboardTabs'] as $row){
?>
                    <li role="presentation" class="<?php echo ($data['page']===$row['page']? 'active': '');?>"><a id="<?php echo $row['page'];?>" href="<?php echo DIR; ?>dataDashboard/customTab/<?php echo $row['page'];?>"><?php echo $row['title'];?></a></li>
<?php
}
?>
                    <li role="presentation" class="<?php echo ($data['page']==='dataQuality'? 'active': ''); ?>"><a id="dataQuality" href="<?php echo DIR; ?>dataDashboard/dataQuality">Data Quality</a></li>
                    <li role="presentation" class="<?php echo ($data['page']==='users'? 'active': ''); ?>"><a id="dataQuality" href="<?php echo DIR; ?>users">Users</a></li>
                </ul>
            </div>
        </div>
        <br>
<!-- End of dataDashboardHeader -->

