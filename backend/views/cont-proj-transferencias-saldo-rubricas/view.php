<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\ContProjTransferenciasSaldoRubricas */
$idProjeto = Yii::$app->request->get('idProjeto');
$origem = \yii\helpers\ArrayHelper::map(\backend\models\ContProjRubricasdeProjetos::find()->where("projeto_id=$idProjeto")->all(), 'id', 'descricao');
$destino = \yii\helpers\ArrayHelper::map(\backend\models\ContProjRubricasdeProjetos::find()->where("projeto_id=$idProjeto")->all(), 'id', 'descricao');
$modelProjeto = \backend\models\ContProjProjetos::find()->where("id=$idProjeto")->one();
$coordenador = \app\models\User::find()->select("*")->where("id=$modelProjeto->coordenador_id")->one();

$this->title = "Alteração Orçamentária";
$this->params['breadcrumbs'][] = ['label' => 'Alteração Orçamentária', 'url' => ['index','idProjeto'=>$idProjeto]];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="cont-proj-transferencias-saldo-rubricas-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ',
            ['index', 'idProjeto'=>$idProjeto], ['class' => 'btn btn-warning']) ?>
        <!--<?= Html::a('Atualizar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>-->
        <?= Html::a('Excluir', ['delete', 'id' => $model->id,'idProjeto'=>$idProjeto,'detalhe'=>true], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Voccê deseja realmente excluir este item?',
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
            [
                'attribute'=>'data',
                'format' => ['date', 'php:d/m/Y'],
            ],
            [
                'attribute'=>'nomeRubricaOrigem',
                'value'=>$origem[$model->rubrica_origem]
            ],
            [
                'attribute'=>'nomeRubricaDestino',
                'value'=>$origem[$model->rubrica_destino]
            ],
            'valor:currency',
            'autorizacao',
        ],
    ]) ?>

</div>
