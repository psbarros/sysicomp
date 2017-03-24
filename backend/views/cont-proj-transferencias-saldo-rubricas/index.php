<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ContProjTransferenciasSaldoRubricasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$idProjeto = Yii::$app->request->get('idProjeto');

$this->title = 'Alterações Orçamentárias';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cont-proj-transferencias-saldo-rubricas-index">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ',
            ['cont-proj-projetos/view', 'id' => $idProjeto], ['class' => 'btn btn-warning']) ?>
        <?= Html::a('Cadastrar Nova Alteração', ['create','idProjeto'=>$idProjeto], ['class' => 'btn btn-success']) ?>
    </p>

    <?= $this->render('..\cont-proj-projetos\dados.php', [
        'idProjeto' => $idProjeto,
    ]) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'nomeRubricaDestino',
            //'projeto_id',
            //'rubrica_destino',
            [
                'attribute'=>'data',
                'format' => ['date', 'php:d/m/Y'],
            ],
            'nomeRubricaOrigem',
            'nomeRubricaDestino',
            'valor:currency',
            'autorizacao',
            // 'autorizacao',
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => function ($url, $model) use ($idProjeto) {
                        $url .= '&idProjeto=' . $idProjeto;
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url);
                    },
                    'update' => function ($url, $model) use ($idProjeto) {
                       return false;
                    },
                    'delete' => function ($url, $model) use ($idProjeto) {
                        return Html::a('<span class="glyphicon glyphicon-remove"></span>', ['delete', 'id' => $model->id,'idProjeto'=>$idProjeto], [
                            'data' => [
                                'confirm' => 'Deseja realmente remover esta alteração?',
                                'method' => 'post',
                            ],
                            'title' => Yii::t('yii', 'Remover Alteração'),
                        ]);
                    },
                ],

            ],
            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
