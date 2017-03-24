<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ContProjRubricasdeProjetosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$idProjeto = Yii::$app->request->get('idProjeto');


$this->title = "Relatorio Simplificado";
//'Cont Proj Rubricasde Projetos';
$this->params['breadcrumbs'][] = $this->title;
//$this->params['breadcrumbs'][] = ['label' => "$nomeProjeto", 'url' => ['index','idProjeto'=>$idProjeto,'nomeProjeto'=>$nomeProjeto]];
//$this->params['breadcrumbs'][] = $this->title;

?>
<div class="cont-proj-rubricasde-projetos-index">

    <!--<h1><?= Html::encode("Rubricas de projeto - " . $this->title) ?></h1>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ',
            ['cont-proj-projetos/view', 'id' => $idProjeto], ['class' => 'btn btn-warning']) ?>
    </p>
    <?= $this->render('..\cont-proj-projetos\dados.php', [
        'idProjeto' => $idProjeto,
    ]) ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><b>Itens de Custeio</b></h3>
        </div>
        <div class="panel-body">
            <?= GridView::widget([
                'dataProvider' => $dataProviderCusteio,
                //'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    //'projeto_id',
                    //'rubrica_id',
                    'nomerubrica',
                    'descricao',
                    //'nomeprojeto',
                    'valor_total:currency',
                    'valor_gasto:currency',
                    'valor_disponivel:currency',
                ],
            ]); ?>
        </div>
    </div>


    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><b>Itens de capital</b></h3>
        </div>
        <div class="panel-body">
            <?= GridView::widget([
                'dataProvider' => $dataProviderCapital,
                //'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    //'projeto_id',
                    //'rubrica_id',
                    'nomerubrica',
                    'descricao',
                    //'nomeprojeto',
                    'valor_total:currency',
                    'valor_gasto:currency',
                    'valor_disponivel:currency',
                ],
            ]); ?>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><b>Transferências</b></h3>
        </div>
        <div class="panel-body">
            <?= GridView::widget([
                'dataProvider' => $dataProviderTransferencias,
                //'filterModel' => $searchModelTransferencias,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    //'nomeRubricaDestino',
                    //'projeto_id',
                    //'rubrica_destino',
                    [
                        'attribute' => 'data',
                        'format' => ['date', 'php:d/m/Y'],
                    ],
                    'nomeRubricaOrigem',
                    'nomeRubricaDestino',
                    'valor:currency',
                    'autorizacao',
                    // 'autorizacao',
                ],
            ]); ?>
        </div>
    </div>
</div>
