<?php

use yii\helpers\Html;
use yii\grid\GridView;
use xj\bootbox\BootboxAsset;

BootboxAsset::register($this);
BootboxAsset::registerWithOverride($this);

/* @var $this yii\web\View */
/* @var $searchModel app\models\DefesaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Lista de Defesas';
$this->params['breadcrumbs'][] = $this->title;

if( Yii::$app->user->identity->checarAcesso('coordenador') == 1){
  $action = "{view} {banca} {update} {delete}";
}
else if ( Yii::$app->user->identity->checarAcesso('professor') == 1){
  $action = "{view} {banca} {update} {delete}";
}
else if( Yii::$app->user->identity->checarAcesso('secretaria') == 1){
  $action = "{view}";

}


?>
<div class="defesa-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <div>
           <?php echo Html::a('<span class="glyphicon glyphicon-ok"></span> Pendentes de Defesa ', ['pendentes',], ['class' => 'btn btn-warning']);?>
           
         </div>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            //'idDefesa',
            'nome_aluno',
            [   'label' => 'Curso Desejado',
                'attribute' => 'curso_aluno',
				'filter'=>array (1 => "Mestrado",2 => "Doutorado"),
                'value' => function ($model) {
                     return $model->curso_aluno == 1 ? 'Mestrado' : 'Doutorado';
                },
            ],
            [   'label' => 'Tipo de Defesa',
                'attribute' => 'tipoDefesa',
				'filter'=>array ("Q1" => "Qualificação 1", "Q2" => "Qualificação 2", "D" => "Dissertação", "T" => "Tese"),
                'value' => 'tipoDefesa'
            ],
             //'data',
            [
                'label' => 'Data',
                'attribute' => 'data',
                'filter' => \yii\jui\DatePicker::widget([
                    'model'=>$searchModel,
                    'attribute'=>'data',
                    'language' => 'pt',
                    'dateFormat' => 'yyyy-MM-dd',
                ]),
                'format' => 'html',
                'value' => function ($model) {
                     return date("d-m-Y", strtotime($model->data));
                },
            ],
            [
            'label' => 'Conceito',
            "attribute" => 'conceito',
            'filter'=>array ("Aprovado" => "Aprovado", "Reprovado" => "Reprovado", "Não Julgado" => "Não Julgado" ),
            "value" => function ($model){
                return $model->conceito == null ? "Não Julgado" : $model->conceito;

            },
            ],
             'horario',
             'local',

            ['class' => 'yii\grid\ActionColumn',
              'template'=> $action,
                'buttons'=>[

                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['view', 'idDefesa' => $model->idDefesa , 'aluno_id' => $model->aluno_id], [
                                'title' => Yii::t('yii', 'Visualizar Detalhes'),
                        ]);
                    },
                    'update' => function ($url, $model){
                        if ( $model->conceito == "Não Julgado")
                        {
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update', 'idDefesa' => $model->idDefesa , 'aluno_id' => $model->aluno_id], ['title' => Yii::t('yii', 'Editar Defesa'),
                            ]);
                        }
                    },
                    'delete' => function ($url, $model){
                        return $model->banca->status_banca == null ? Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'idDefesa' => $model->idDefesa , 'aluno_id' => $model->aluno_id], [
                                'data' => [
                                    'confirm' => 'Remover a defesa \''.$model->titulo.'\'?',
                                    'method' => 'post',
                                ], 'title' => Yii::t('yii', 'Remover Defesa'),
                        ]) : "";
                    },
                ]
            ],
        ],
    ]); ?>
</div>
