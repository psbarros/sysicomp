<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\LinhaPesquisa */

$this->title = $model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Linhas de Pesquisa', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="linha-pesquisa-view">
    <p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span>&nbsp;&nbsp;Voltar','#',['class' => 'btn btn-warning','onclick'=>"history.go(-1);"]); ?>
        <?= Html::a('<span class="fa fa-list"></span>&nbsp;&nbsp;Listar Linhas de Pesquisa', ['linha-pesquisa/index'], ['class' => 'btn btn-success']) ?>
		<?= Html::a('<span class="glyphicon glyphicon-edit"></span> Editar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-remove"></span> Remover', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Remover Linha de Pesquisa \''. $model->nome.'\'?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="row col-lg-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><b>Dados da Linha de Pesquisa</b></h3>
            </div>
            <div class="panel-body">

                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'nome',
                        'sigla',
                        [   'attribute' => 'icone',
                            'format' => 'html',
                            'value' => "<span class='fa ". $model->icone ." fa-lg'/> ",
                        ],
            			[   'attribute' => 'cor',
                            'format' => 'html',
                            'value' => "<div style='width: 100%; height: 30px; background-color: $model->cor'></div>",
                        ],
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>
