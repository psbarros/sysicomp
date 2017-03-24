<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\ContProjProrrogacoes */
$idProjeto = Yii::$app->request->get('idProjeto');
$this->title = "Data Final: " . date("d/m/Y", strtotime($model->data_fim_alterada));
$this->params['breadcrumbs'][] = ['label' => 'Prorrogacoes', 'url' => ['index', 'idProjeto' => $idProjeto]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cont-proj-prorrogacoes-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->


    <p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ',
            ['index', 'idProjeto' => $idProjeto], ['class' => 'btn btn-warning']) ?>
        <?= Html::a('Atualizar', ['update', 'id' => $model->id, 'idProjeto' => $idProjeto], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Deletar', ['delete', 'id' => $model->id, 'idProjeto' => $idProjeto], [
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

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><b>Dados</b></h3>
        </div>
        <div class="panel-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    [
                        'attribute' => 'data_fim_alterada',
                        'format' => ['date', 'php:d/m/Y'],
                    ],
                    'descricao:ntext',
                ],
            ]) ?>
        </div>
    </div>
</div>
