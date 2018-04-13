<?php
if (!isset($title)) { $title = ':: Title ::';}
?>
<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title><?php echo $title;?></title>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300|Roboto|Titillium+Web" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo base_url('css/bootstrap.css');?>">
  <link rel="stylesheet" href="<?php echo base_url('css/style.css');?>">
  <link rel="stylesheet" href="<?php echo base_url('css/style_thesa.css');?>">  
  <link rel="stylesheet" href="<?php echo base_url('css/style_form_sisdoc.css');?>">
  <link href="<?php echo base_url('css/jquery-ui.css');?>" rel="stylesheet">  
  <script type="text/javascript" src="<?php echo base_url('js/jquery-3.1.1.js');?>"></script>
  <script type="text/javascript" src="<?php echo base_url('js/utils.js');?>"></script>
  <script type="text/javascript" src="<?php echo base_url('js/tether.js');?>"></script>
  <script type="text/javascript" src="<?php echo base_url('js/bootstrap.js');?>"></script>
  <script type="text/javascript" src="<?php echo base_url('js/jquery-ui.js');?>"></script>  
  <script type="text/javascript" src="<?php echo base_url('js/form_sisdoc.js');?>"></script>
  <script type="text/javascript" src="<?php echo base_url('js/clipboard.js');?>"></script>
  
</head>