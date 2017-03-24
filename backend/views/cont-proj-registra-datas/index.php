<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ContProjRegistraDatasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$idProjeto = Yii::$app->request->get('idProjeto');
$this->title = 'Eventos do Projeto';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cont-proj-registra-datas-index">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>



    <p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ',
            ['cont-proj-projetos/view', 'id' => $idProjeto], ['class' => 'btn btn-warning']) ?>
        <?= Html::a('Registrar Novo Evento do Projeto', ['create', 'idProjeto' => $idProjeto], ['class' => 'btn btn-success']) ?>
    </p>

    <?= $this->render('..\cont-proj-projetos\dados.php', [
        'idProjeto' => $idProjeto,
    ]) ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'dataProvider' => $projetos,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute'=>'data',
                'format' => ['date', 'php:d/m/Y'],
            ],
            'evento',
            /*[
                'attribute' => 'projeto_id',
                'value' => function ($model){
                    $projeto = \backend\models\ContProjProjetos::find()->select("*")->where("id=$model->projeto_id")->one();
                    return $projeto->nomeprojeto;
                },
                 'contentOptions'=>['style'=>'width: 30%;'],
            ],*/
            [
                'attribute' =>  'observacao',
                'contentOptions'=>['style'=>'width: 30%;'],
            ],
            //// 'tipo',
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => function ($url, $model) use ($idProjeto) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['view', 'id' => $model->id,'idProjeto'=>$idProjeto]);
                    },
                    'update' => function ($url, $model) use ($idProjeto) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update', 'id' => $model->id,'idProjeto'=>$idProjeto]);
                    },
                    'delete' => function ($url, $model) use ($idProjeto) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model->id,'idProjeto'=>$idProjeto], [
                            'data' => [
                                'confirm' => 'Deseja realmente remover o lembrete?',
                                'method' => 'post',
                            ],
                            'title' => Yii::t('yii', 'Remover'),
                        ]);
                    }
                ],

            ],
        ],
    ]); ?>
</div>
