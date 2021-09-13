<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?php echo base_url('index.php');?>"><img src="<?php echo base_url('img/logo/logo_thesa.png');?>" height="50"></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="active"><a href="<?php echo base_url('index.php');?>">Home <span class="sr-only">(current)</span></a></li>
        <li><a href="<?php echo base_url('index.php');?>">Catalogo de Livros Infantins</a></li>
        <li><a href="<?php echo base_url('index.php/skos');?>">Tesauros e Vocabularios</a></li>       
      </ul>
      <ul class="nav navbar-nav navbar-right">
     	<?php
          if (isset($_SESSION['check']))
          {
            echo '<li style="background-color: orange;">'.$_SESSION['nome'].'</li>';
          } else {
            echo '<li style="background-color: orange;"><a href="'.base_url('index.php/login').'" style="color: white;">Acesso2</a></li>';		
          }                 
      ?>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>