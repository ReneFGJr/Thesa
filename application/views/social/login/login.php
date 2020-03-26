<!-- Modal -->
<?php
$hide = 'true';
$class = "modal fade";
if (isset($show) and ($show==1))
    {
        $hide = '';
        $class = '';
    }
?>
<div class="<?php echo $class;?>" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="<?php echo $hide;?>">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle"><?php echo msg("user_login");?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="<?php echo base_url(PATH.'social/login_local');?>">
                    <span><?php echo msg("form_user_name");?></span>
                    <br>
                    <input type="text" name="user_login" value="<?php echo get("user_login");?>" class="form-control">
                    <br>
                    <span><?php echo msg("form_user_password");?></span>
                    <br>
                    <input type="password" name="user_password" value="" class="form-control">

                    <br>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <?php echo msg('cancel');?>
                    </button>
                    &nbsp;|&nbsp;
                    <input type="submit" name="action" value="<?php echo msg('user_login');?>" class="btn btn-primary">
                </form>
            </div>
        </div>
    </div>
</div>