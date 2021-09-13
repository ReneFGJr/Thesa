<?php
if (!isset($ed)) { $ed = ''; }
if (!isset($_SESSION['skos'])) {
    $th = 0;
    if (isset($id_pa)) { $th = $id_pa;
        $_SESSION['skos'] = $th;
    }
} else {
    $th = $_SESSION['skos'];
}

if (!isset($_SESSION['id'])) {
    $id = 0;
} else {
    $id = $_SESSION['id'];
}
?>
<style>
    .menu_tool {
        border-bottom: 1px solid #000000;
        background-color: #fff;
        padding: 0px;
    }
</style>
<div class="container-fluid menu_tool">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <ul class="nav">
                  <li class="nav-item">
                    <a class="nav-link active" href="<?php echo base_url('index.php/thesa/terms/' . $th); ?>"><?php echo msg('glossario_cap'); ?></a>
                  </li>
                  <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><?php echo msg('configuration'); ?></a>
                    <div class="dropdown-menu">
                      <a class="dropdown-item" href="<?php echo base_url('index.php/thesa/th_edit/'.$th.'/'.checkpost_link($th)); ?>"><?php echo msg('edit_th'); ?></a>
                      <a class="dropdown-item" href="<?php echo base_url('index.php/thesa/collaborators'); ?>"><?php echo msg('collaborators'); ?></a>
                      <a class="dropdown-item" href="<?php echo base_url('index.php/thesa/languages'); ?>"><?php echo msg('languages_config'); ?></a>
                      <a class="dropdown-item" href="<?php echo base_url('index.php/thesa/apis'); ?>"><?php echo msg('api'); ?></a>                                                          
                    </div>
                  </li>
                  <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><?php echo msg('rules'); ?></a>
                    <div class="dropdown-menu">
                      <a class="dropdown-item" href="<?php echo base_url('index.php/thesa/reports/'.$th.'/'); ?>"><?php echo msg('management_reporting'); ?></a>
                      <a class="dropdown-item" href="<?php echo base_url('index.php/thesa/check_all/'); ?>"><?php echo msg('check_all'); ?></a>
                      <a class="dropdown-item" href="<?php echo base_url('index.php/thesa/file_import'); ?>"><?php echo msg('file_import'); ?></a>
                      <a class="dropdown-item" href="<?php echo base_url('index.php/thesa/term_grapho/'.$th.'/'); ?>"><?php echo msg('grapho'); ?></a>
                      <a class="dropdown-item" href="<?php echo base_url('index.php/thesa/terms_list/');?>"><?php echo msg('terms_list'); ?></a>
                      <a class="dropdown-item" href="<?php echo base_url('index.php/thesa/terms_from_to/');?>"><?php echo msg('remissiva_de_para'); ?></a>
                    
                    <a class="dropdown-item" href="<?php echo base_url('index.php/thesa/tools/'); ?>"><?php echo msg('text_processing'); ?></a>
                                                          
                    </div>
                  </li>
                  <?php if (($ed == 1) or ($this -> skoses -> autho('', $th))) { ?>
                  <li class="nav-item2 btn " style="background-color: #c0c0ff;" title="'.msg('add_new_terms').'" >
                    <a class="" href="<?php echo base_url('index.php/thesa/concept_add/'.$th);?>" title="<?php echo msg('add_term_title');?>"><?php echo msg('add');?></a>
                  </li>
                  <?php } ?>
                </ul>                
            </div>
            <div class="col-md-4 text-right">    
                <form class="navbar-form navbar-left" menthod="get" action="<?php echo base_url('index.php/thesa/search');?>">
                    <div class="input-group mb-3">
                        <input type="text" name="form_search" class="form-control" placeholder="<?php echo msg('term_search');?>" aria-label="<?php echo msg('term_search');?>" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <input type="submit" class="btn btn-outline-secondary" type="button" value="<?php echo msg('search'); ?>">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

