<?php

use Helpers\Session;
?>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-7">
                            <table class='table table-striped table-hover table-bordered responsive'>
                                <tr>
                                    <th>System Variables</th>
                                    <th>Action</th>
                                </tr>
                                <tr>
                                    <td>Filesystem Directory containing cruise data: <strong><?php echo $data['shoresideDWBaseDir']; ?></strong></td>
                                    <td>
                                        <a href='<?php echo DIR; ?>editShoresideDWBaseDir'>Edit</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Apache Directory containing cruise data: <strong><?php echo $data['shoresideDWApacheDir']; ?></strong></td>
                                    <td>
                                        <a href='<?php echo DIR; ?>editShoresideDWApacheDir'>Edit</a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-lg-6 col-md-5">
                            <h3>Page Guide</h3>
                            <p>Yeah... still need to write this.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>