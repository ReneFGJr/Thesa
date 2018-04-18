<?php
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
                    <a class="nav-link active" href="<?php echo base_url('index.php/thesa/terms/' . $th); ?>"><?php echo msg('glossario'); ?></a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                  </li>
                  <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><?php echo msg('rules'); ?></a>
                    <div class="dropdown-menu">
                      <a class="dropdown-item" href="<?php echo base_url('index.php/thesa/check_all/'); ?>"><?php echo msg('check_all'); ?></a>
                      <a class="dropdown-item" href="#">Separated link</a>
                      <a class="dropdown-item" href="<?php echo base_url('index.php/thesa/th_edit/'.$th.'/'.checkpost_link($th)); ?>"><?php echo msg('preferences'); ?></a>
                      <a class="dropdown-item" href="<?php echo base_url('index.php/thesa/terms_list/');?>"><?php echo msg('terms_list'); ?></a>
                      <a class="dropdown-item" href="<?php echo base_url('index.php/thesa/terms_from_to/');?>"><?php echo msg('remissiva_de_para'); ?></a>
                      
                    <a class="dropdown-item" href="<?php echo base_url('index.php/thesa/concept_add/'); ?>"><?php echo msg('terms'); ?></a>
                    <a class="dropdown-item" href="<?php echo base_url('index.php/thesa/collaborators'); ?>"><?php echo msg('collaborators'); ?></a>                                      
                    </div>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link disabled" href="#">Disabled</a>
                  </li>
                </ul>                
            </div>
            <div class="col-md-4 text-right">    
                <form class="navbar-form navbar-left">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="<?php echo msg('term_search');?>" aria-label="<?php echo msg('term_search');?>" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button"><?php echo msg('search'); ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

