<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\ContProjRubricasdeProjetos */

$idProjeto = Yii::$app->request->get('idProjeto');
$modelProjeto = \backend\models\ContProjProjetos::find()->where("id=$idProjeto")->one();
$coordenador = \app\models\User::find()->select("*")->where("id=$modelProjeto->coordenador_id")->one();


$this->title = $model->descricao;
$this->params['breadcrumbs'][] = ['label' => 'Rubricas do Projeto', 'url' => ['index', 'idProjeto' => $idProjeto]];
$this->params['breadcrumbs'][] = $this->title;

$rubricas = \yii\helpers\ArrayHelper::map(\backend\models\ContProjRubricas::find()->orderBy('id')->all(), 'id', 'nome');
$projetos = \yii\helpers\ArrayHelper::map(\backend\models\ContProjProjetos::find()->orderBy('id')->all(), 'id', 'nomeprojeto');
?>
<div class="cont-proj-rubricasde-projetos-view">

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
            <h3 class="panel-title"><b>Dados da Rubrica</b></h3>
        </div>
        <div class="panel-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    [
                        'attribute' => 'nomerubrica',
                        'value' => $rubricas[$model->rubrica_id],
                    ],
                    [
                        'attribute' => 'nomeProjeto',
                        'value' => $projetos[$model->projeto_id],
                    ],
                    //'nomeprojeto',
                    //'nomerubrica',
                    'descricao',
                    'valor_total:currency',
                    'valor_gasto:currency',
                    'valor_disponivel:currency',
                ],
            ]) ?>
        </div>
    </div>


</div>
