<?php
/* @var $this yii\web\View */
//use gietos\yii\ionicons\Ion;
use scotthuangzl\googlechart\GoogleChart;
?>


<section class="content">
<div class="row">

    <div class="col-sm-4 col-md-4">
        <div class="thumbnail alert alert-success">
            <div class="inner">
                <p><i class="fa fa-tasks fa-4x pull-right"></i></p>
                <h3><?php echo $enviadas ?></h3>
                <h4>Aguardando a Secretaria</h4>
            </div>

            <a href="#" class="small-box-footer">Mais detalhes <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>


    <div class="col-sm-4 col-md-4">
        <div class="thumbnail alert alert-danger">
            <div class="inner">
                <p><i class="fa fa-exclamation-triangle fa-4x pull-right"></i></p>
                <h3><?php echo $aberto ?></h3>
                <h4>Solicitações em aberto</h4>
            </div>

            <a href="#" class="small-box-footer">Mais detalhes <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>


    <div class="col-sm-4 col-md-4">
        <div class="thumbnail alert alert-info">
            <div class="inner">
                <p><i class="fa fa-thumbs-up fa-4x pull-right"></i></p>
                <h3><?php echo $arquivadas ?></h3>
                <h4>Solicitações Arquivadas</h4>
            </div>

            <a href="#" class="small-box-footer">Mais detalhes <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>


</div>


</div>
</section>
