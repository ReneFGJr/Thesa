<div class="container p-2 rounded-3" style="background-color: #DDD;">
<div class="row">
<?php 
    $title = 'CIENCIA DA INFORMAÇÃO';
    $title = '<span class="p-2 me-2" style="font-family: \'Handel Gothic\', Roboto;">'.$title.'</span>';
    echo bsc($title,6);
    $tela = '
        <form class="d-flex">
            <input class="form-control me-2" type="search" style="width: 300px;" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
    ';
    echo '<div class="col-6 align-items-end">';
    echo $tela;
    echo '</div>';
?>
</div>
</div>