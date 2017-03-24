<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\ContProjReceitas */

$idProjeto = Yii::$app->request->get('idProjeto');

$this->title = $model->tipo ." ". $model->descricao;
$this->params['breadcrumbs'][] = ['label' => 'Receitas', 'url' => ['index','idProjeto'=>$idProjeto,'nomeProjeto'=>$nomeProjeto]];
$this->params['breadcrumbs'][] = $this->title;
$rub = \backend\models\ContProjRubricasdeProjetos::find()->orderBy('id')->all();
$rubricas = \yii\helpers\ArrayHelper::map($rub, 'id', 'descricao');
?>
<div class="cont-proj-receitas-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ',
            ['index', 'idProjeto'=>$idProjeto], ['class' => 'btn btn-warning']) ?>
        <!--<?= Html::a('Update', ['update', 'id' => $model->id,'idProjeto'=>$idProjeto], ['class' => 'btn btn-primary']) ?>-->
        <?= Html::a('Delete', ['delete', 'id' => $model->id,'idProjeto'=>$idProjeto, 'detalhe'=>true], [
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
            <h3 class="panel-title"><b>Dados da Receita</b></h3>
        </div>
        <div class="panel-body">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            //'rubricasdeprojetos_id',
            [
                'attribute' => 'rubricasdeprojetos_id',
                'value' => $rubricas[$model->rubricasdeprojetos_id],
            ],
            'descricao',
            'valor_receita:currency',
            [
                'attribute'=>'data',
                'format' => ['date', 'php:d/m/Y'],
            ],
            'ordem_bancaria',
        ],
    ]) ?>
        </div>
    </div>

</div>
