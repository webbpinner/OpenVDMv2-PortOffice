<?php

use Helpers\Session;

?>
                    <div class="row">
                        <div class="col-lg-4 col-md-6">
                            <div class="panel">
                                <div class="panel-body">
                            
                                    <table class='table table-striped table-hover table-bordered responsive'>
                                        <tr>
                                            <th>Name</th>
                                            <th>Action</th>
                                        </tr>
<?php
    if($data['users']){
        foreach($data['users'] as $row){
?>
                                        <tr>
                                            <td><?php echo $row->username; ?></td>
                                            <td>
                                                <a href='<?php echo DIR; ?>editUser/<?php echo $row->userID; ?>'>Edit</a>
<?php
            if (strcmp(Session::get('userID'), $row->userID) !== 0 ) {
?>
                                        / 
                                                <a href='#confirmDeleteModal' data-toggle="modal" data-item-name="User" data-delete-url="<?php echo DIR; ?>deleteUser/<?php echo $row->userID; ?>">Delete</a>
<?php
            }
?>
                                            </td>
                                        </tr>
<?php
        }
    }
?>
                                    </table>
                                    <a class="btn btn-sm btn-primary" href="<?php echo DIR; ?>addUser">Add New User</a>
                                </div>
                            </div>
                        </div>
                    </div>

    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="Delete Confirmation" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Delete Confirmation</h4>
                </div>
                <div class="modal-body">Are you sure you want to delete this <span id="modelDeleteItemName"></span>?  This cannot be undone!</div>
                <div class="modal-footer">
                    <a href='' class="btn btn-danger" data-dismiss="modal">Cancel</a>
                    <a href='doesnotexist' class="btn btn-primary" id="modalDeleteLink">Yup!</a>
                </div>
            </div> <!-- /.modal-content -->
        </div> <!-- /.modal-dialog -->
    </div> <!-- /.modal -->