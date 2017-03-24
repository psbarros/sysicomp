<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\LinhaPesquisa;

use xj\bootbox\BootboxAsset;

BootboxAsset::register($this);
BootboxAsset::registerWithOverride($this);

/* @var $this yii\web\View */
/* @var $searchModel app\models\AlunoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Alunos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="aluno-index">

    <p>
        <?= Html::a('<span class="fa fa-plus"></span> Novo Aluno', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('<span class="fa fa-table"></span> Gerar Planilha do Excel', ['gerar_planilha'], ['class' => 'btn btn-primary']) ?>
    </p>
      <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'matricula',
            'nome',
            [   'label' => 'Curso',
                'attribute' => 'curso',
                'filter'=>array (1 => "Mestrado",2 => "Doutorado"),
                'value' => function ($model) {
                    return $model->curso == 1 ? 'Mestrado' : 'Doutorado';
                },
            ],
            [   'label' => 'Status',
                'attribute' => 'status',
                'filter'=>array (0 => "Corrente",1 => "Egresso",2 => "Desistente",3 => "Desligado",4 => "Jubilado",5 => "Matrícula Trancada"),
                'value' => function ($model) {
                    $statusAluno = array (0 => "Corrente",1 => "Egresso",2 => "Desistente",3 => "Desligado",4 => "Jubilado",5 => "Matrícula Trancada");
                    return $statusAluno[$model->status];
                },
            ],
            [   'label' => 'Ingresso',
                'attribute' => 'dataingresso',
                'value' => function ($model) {
                    return date("m-Y", strtotime($model->dataingresso));
                    },
            ],
            'nomeOrientador',
            [   'label' => 'Linha Pesquisa',
                'attribute' => 'siglaLinhaPesquisa',
                'filter' => Html::activeDropDownList($searchModel, 'siglaLinhaPesquisa', \yii\helpers\ArrayHelper::map(LinhaPesquisa::find()->asArray()->all(), 'id', 'sigla'),[  'class'=>'form-control','prompt' => 'Selecione a Linha']),
                'contentOptions' => function ($model){
                  return ['style' => 'background-color: '.$model->linhaPesquisa->cor];
                },
                'format' => 'html',
                'value' => function ($model){
                  return "<span class='fa ". $model->linhaPesquisa->icone ." fa-lg'/> ". $model->siglaLinhaPesquisa;
                }
            ],

            ['class' => 'yii\grid\ActionColumn',
              'template'=>'{view} {delete} {update}',
                'buttons'=>[
                  'delete' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model->id], [
                            'data' => [
                                'confirm' => 'Remover o Aluno \''.$model->nome.'\'?',
                                'method' => 'post',
                            ],
                            'title' => Yii::t('yii', 'Remover Aluno'),
                    ]);   
                  }
              ]                            
            ],
        ],
    ]); ?>
</div>
