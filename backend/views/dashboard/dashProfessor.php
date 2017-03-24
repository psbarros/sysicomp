<?php
/* @var $this yii\web\View */
//use gietos\yii\ionicons\Ion;
use scotthuangzl\googlechart\GoogleChart;
use miloschuman\highcharts\Highcharts;
use yii\helpers\Html; 

?>

<section class="content">
<div class="row">

    <div class="col-sm-4 col-md-4">
        <div class="thumbnail alert alert-success">
            <div class="inner">
                <p><i class="fa fa-graduation-cap fa-4x pull-right"></i></p>
                <h3><?php echo $horasEmEnsino ?> / <?php if(isset($maxHrsGrupos[1])) echo $maxHrsGrupos[1] ?> Horas</h3>
                <h4>Ensino</h4>
            </div>

            <a href="?r=solicitacao/index" class="small-box-footer">Mais detalhes <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-sm-4 col-md-4">
        <div class="thumbnail alert alert-warning">
            <div class="inner">
                <p><i class="fa fa-object-ungroup fa-4x pull-right"></i></p>
                <h3><?php echo $horasEmPesquisa ?> / <?php if(isset($maxHrsGrupos[2])) echo $maxHrsGrupos[2] ?> Horas</h3>
                <h4>Pesquisa</h4>
            </div>

            <a href="?r=solicitacao/index" class="small-box-footer">Mais detalhes <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-sm-4 col-md-4">
        <div class="thumbnail alert alert-info">
            <div class="inner">
                <p><i class="fa fa-paper-plane fa-4x pull-right"></i></p>
                <h3><?php echo $horasEmExtensao ?> / <?php if(isset($maxHrsGrupos[3])) echo $maxHrsGrupos[3] ?> Horas</h3>
                <h4>Extensão</h4>
            </div>

            <a href="?r=solicitacao/index" class="small-box-footer">Mais detalhes <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>

</div>

<div class="row">

    <div class="col-md-12">
        <div class="box box-success">
            <div class="box-header ui-sortable-handle">
              <i class="fa fa-line-chart"></i>

              <h3 class="box-title">Gráfico</h3>
            </div>

            <div class=" box-body">
                <div class="col-xs-2">

                </div>
                <div class="col-xs-8">
                    <?php

                    echo Highcharts::widget([
                        'options' => [
                            'title' => ['text' => 'Total de Horas Concluídas X Total de Horas do Curso'],
                            'series' => [[
                                'type' => 'pie',
                                'name' => 'Horas',
                                'data' => [
                                    ['name' => 'Concluido', 'y' => (float) $totalConcluido ],
                                    ['name' => 'Total', 'y' => (float) $totalGrupos]
                                ]
                            ]]               
                        ]
                    ]);

                    ?>
                </div>

            </div>


        </div><!-- fim div box -->

    </div><!-- fim div col-md-12 -->


</div><!--fim div row-->

</section>