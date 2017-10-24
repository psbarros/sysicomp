<?php

use yii\helpers\Html;
use yii\grid\GridView;
use xj\bootbox\BootboxAsset;

BootboxAsset::register($this);
BootboxAsset::registerWithOverride($this);

/* @var $this yii\web\View */
/* @var $searchModel app\models\DefesaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$action = "{view} {lembrete}";

$this->title = 'Lista de Defesas Pendentes';

$this->params['breadcrumbs'][] = $this->title;

?>
<div class="defesa-index">

<h4 style="font-weight: bold; text-align:center"> Listagem de defesas que estão pendentes de CONCEITO </h4>
<br>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
<?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ', ['defesa/index',], ['class' => 'btn btn-warning']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'idDefesa',
            'nome_aluno',
            [   'label' => 'Curso Desejado',
                'attribute' => 'curso_aluno',
                'value' => function ($model) {
                     return $model->curso_aluno == 1 ? 'Mestrado' : 'Doutorado';
                },
            ],
            //'titulo',
            'tipoDefesa',
            'data',
            [
            "attribute" => 'conceito',

            "value" => function ($model){
                return $model->conceito == null ? "Não Julgado" : $model->conceito;

            },
            ],
             'horario',
             'local',
            ['class' => 'yii\grid\ActionColumn',
              'template'=> $action,
                'buttons'=>[
                    'lembrete' => function ($url, $model) {
                         if($model->conceito== null && $model->banca->status_banca == 1) return  Html::a('<span class="glyphicon glyphicon-ok"></span>', ['lembretependencia', 'idDefesa' => $model->idDefesa , 'aluno_id' => $model->aluno_id], [
                                'title' => Yii::t('yii', 'Enviar Lembrete'),
                        ]);
                    },
                        'view' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['view', 'idDefesa' => $model->idDefesa , 'aluno_id' => $model->aluno_id], [
                                    'title' => Yii::t('yii', 'Visualizar Detalhes'),
                            ]);
                        },
                ]
            ],
        ],
    ]); ?>
</div>
