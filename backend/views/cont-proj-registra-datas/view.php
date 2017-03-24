<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use \backend\models\ContProjProjetos;
use \yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\ContProjRegistraDatas */
$idProjeto = Yii::$app->request->get('idProjeto');
$this->title = "Evento: ". $model->evento;
$this->params['breadcrumbs'][] = ['label' => 'Evento do Projeto', 'url' => ['index','idProjeto'=>$idProjeto]];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="cont-proj-registra-datas-view">

    <!--<h1>Evento: <?= Html::encode($this->title) ?></h1>-->
    <p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ',['index', 'idProjeto' => $idProjeto], ['class' => 'btn btn-warning']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-edit"></span> Atualizar', ['update', 'id' => $model->id, 'idProjeto' => $idProjeto], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-remove"></span> Deletar', ['delete', 'id' => $model->id, 'idProjeto' => $idProjeto, 'detalhe' => true], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?= $this->render('..\cont-proj-projetos\dados.php', [
        'idProjeto' => $idProjeto,
    ]) ?>


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'evento',
            [
                'attribute' => 'data',
                'format' => ['date', 'php:d/m/Y'],
            ],
            'observacao',
            'tipo',
        ],
    ]) ?>

</div>
