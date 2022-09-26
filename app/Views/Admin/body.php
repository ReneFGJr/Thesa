<div class="container">
    <div class="row py-3">
        <div class="col-3 order-2" id="sticky-sidebar">
            <div class="sticky-top">
                <div class="nav flex-column">
                    <?php
                    for ($r=0;$r < count($link);$r++)
                        {
                            echo $link[$r];
                        }
                    ?>
                </div>
            </div>
        </div>
        <div class="col" id="main">
            <?=$body;?>
        </div>
    </div>
</div>