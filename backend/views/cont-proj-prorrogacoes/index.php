<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ContProjProrrogacoesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$idProjeto = Yii::$app->request->get('idProjeto');
$this->title = 'Prorrogações do Projeto';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cont-proj-prorrogacoes-index">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ',
            ['cont-proj-projetos/view', 'id' => $idProjeto], ['class' => 'btn btn-warning']) ?>
        <?= Html::a('Prorrogar data final', ['create','idProjeto'=>$idProjeto], ['class' => 'btn btn-success']) ?>
    </p>

    <?= $this->render('..\cont-proj-projetos\dados.php', [
        'idProjeto' => $idProjeto,
    ]) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'data_fim_alterada',
                'format' => ['date', 'php:d/m/Y'],
                //'contentOptions'=>['style'=>'width: 20%;'],
            ],
            'descricao:ntext',
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => function ($url, $model) use ($idProjeto) {
                        $url .= '&idProjeto=' . $idProjeto;
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url);
                    },
                    'update' => function ($url, $model) use ($idProjeto) {
                        $url .= '&idProjeto=' . $idProjeto;
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url);
                    },
                    'delete' => function ($url, $model) use ($idProjeto) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model->id,'idProjeto'=>$idProjeto,], [
                            'data' => [
                                'confirm' => 'Deseja realmente remover esta data?',
                                'method' => 'post',
                            ],
                            'title' => Yii::t('yii', 'Remover Data'),
                        ]);
                    }
                ],
                //['class' => 'yii\grid\ActionColumn'],
            ],
        ],
    ]); ?>
</div>
