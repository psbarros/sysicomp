<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Breadcrumbs;
use xj\bootbox\BootboxAsset;
use yii\bootstrap\Collapse;
use app\models\LinhaPesquisa;

BootboxAsset::register($this);
BootboxAsset::registerWithOverride($this);


/* @var $this yii\web\View */
/* @var $searchModel app\models\CandidatosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Candidatos';

$this->params['breadcrumbs'][] = ['label' => 'Editais', 'url' => ['edital/index']];
$this->params['breadcrumbs'][] = ['label' => 'Número: '.Yii::$app->request->get('id'), 
    'url' => ['edital/view','id' => Yii::$app->request->get('id') ]];
$this->params['breadcrumbs'][] = $this->title;

//var_dump($dataProvider->getModels());

?>
<div class="candidato-index">

<script>
function goBack() {
    window.history.back();
}
</script>

<?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar', ['edital/view', 'id' => Yii::$app->request->get('id')], ['class' => 'btn btn-warning']) ?>

<?= Html::a(' <span class="glyphicon glyphicon-download"></span> Baixar Documentação ', ['candidatos/downloadscompletos', 'id' => Yii::$app->request->get('id')], ['class' => 'btn btn-success']) ?>

<h2> Inscrições Finalizadas </h2>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
   'filterModel' => $searchModel,

            'rowOptions'=> function($model){
                    if($model->resultado === 2) {
                        return ['class' => 'info'];
                    }
                    else if($model->resultado === 1) {
                        return ['class' => 'danger'];
                    }
                    else if($model->cartas_respondidas < 2 && $model->carta_recomendacao == 1){
                        return ['class' => 'warning'];
                    }
                    else{
                        return ['class' => 'success'];
                    }
            },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [   'label' => 'Nº de Inscrição',
                'attribute' => 'id',
                'value' => function ($model) {
                     return $model->idEdital.'-'.str_pad($model->posicaoEdital, 3, "0", STR_PAD_LEFT);
                },
            ],
             'nome',
              ['attribute' => 'qtd_cartas',
              'label' => 'Cartas',
              'visible' => $cartarecomendacao,
              'value' => function ($model){
                if(!isset($model->qtd_cartas ))
                  return "0/0";
                else if(!isset($model->cartas_respondidas))
                  return "0/".$model->qtd_cartas;
                else
                  return $model->cartas_respondidas."/".$model->qtd_cartas;

              }
             ],
            [   'label' => 'Curso Desejado',
                'attribute' => 'cursodesejado',
      'filter'=>array("1"=>"Mestrado","2"=>"Doutorado"),
                'value' => function ($model) {
                     return $model->cursodesejado == 1 ? 'Mestrado' : 'Doutorado';
                },
            ],
            [   'label' => 'Linha Pesquisa',
                'attribute' => 'siglaLinhaPesquisa',
      'filter' => Html::activeDropDownList($searchModel, 'siglaLinhaPesquisa', \yii\helpers\ArrayHelper::map(LinhaPesquisa::find()->asArray()->all(), 'id', 'sigla'),['class'=>'form-control','prompt' => 'Selecione a Linha']),
                'contentOptions' => function ($model){
                  return ['style' => 'background-color: '.$model->linhaPesquisa->cor];
                },
                'format' => 'html',
                'value' => function ($model){
                  return "<span class='fa ". $model->linhaPesquisa->icone ." fa-lg'/> ". $model->siglaLinhaPesquisa;
                }
            ],
            [   'label' => 'Fase',
                'attribute' => 'fase',
      'filter'=>array("0"=>"Não Julgado","1"=>"Reprovado","2"=>"Aprovado"),

                'format' => 'html',
                'value' => function ($model) {

                    if($model->resultado === 2){
                        return "<span class='fa fa-thumbs-up' /> Aprovado";
                    }
                    else if($model->resultado === 1){

                        return "<span class='fa fa-thumbs-down'/> Reprovado";
                    }
                    else{
                        return "<span class='fa fa-hand-stop-o'/> Não Julgado";
                    }
                },
            ],

            ['class' => 'yii\grid\ActionColumn',
              'template'=>'{download} {view} {aprovar} {reprovar} {reenviar}',
                'buttons'=>[
                  'download' => function ($url, $model) {  

                    return Html::a('<span class="glyphicon glyphicon-download"></span>', ['candidatos/downloads', 'id' => $model->id, 'idEdital' => $model->idEdital], [
                            'target' => '_blank','title' => Yii::t('yii', 'Download da Documentação'),
                    ]);                                

                  },
                  'view' => function ($url, $model) {  

                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['candidatos/view', 'id' => $model->id, 'idEdital' => $model->idEdital], [
                            'title' => Yii::t('yii', 'Visualizar Detalhes'),
                    ]);                                

                  },
                  'aprovar' => function ($url, $model) {  

                    return $model->resultado === null ? Html::a('<span class="glyphicon glyphicon-ok-sign"></span>', ['candidatos/aprovar', 'id' => $model->id, 'idEdital' => $model->idEdital], [
                            'title' => Yii::t('yii', 'Aprovar Aluno'),
                            'data-confirm' => \Yii::t('yii', 'Você deseja APROVAR este candidato?'),
                    ]) : '';                               

                  },
                  'reprovar' => function ($url, $model) {  

                    return $model->resultado === null ? Html::a('<span class="glyphicon glyphicon-remove-sign"></span>', ['candidatos/reprovar', 'id' => $model->id, 'idEdital' => $model->idEdital], [
                            'title' => Yii::t('yii', 'Reprovar Aluno'),
                            'data-confirm' => \Yii::t('yii', 'Você deseja REPROVAR este candidato?'),
                    ]) : '';                   

                  },
                  'reenviar' => function ($url, $model) {  

                    return $model->carta_recomendacao == 1 && $model->qtd_cartas > $model->cartas_respondidas && !($model->resultado === 1 || $model->resultado === 0) && $model->cartasPrazo ? Html::a('<span class="glyphicon glyphicon-envelope"></span>', ['candidatos/reenviarcartas', 'id' => $model->id, 'idEdital' => $model->idEdital], [
                            'title' => Yii::t('yii', 'Reenviar Cartas'),
                            'data-confirm' => \Yii::t('yii', 'Você deseja Reenviar cartas de recomendação deste candidato?'),
                    ]) : '';                   

                  }
              ]                            
            ],
        ],
    ]); ?>
    
<?php
echo Collapse::widget([
    'items' => [
        // equivalent to the above
        [
            'label' => 'Inscrições em Andamento: '. $dataProvider2->totalcount,
            'content' => GridView::widget([
            'dataProvider' => $dataProvider2,
            'emptyText' => '-',
            'rowOptions'=> ['class' => 'warning'],
            'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [   'label' => 'Nº de Inscrição',
                'attribute' => 'id',
                'value' => function ($model) {
                     return $model->idEdital.'-'.str_pad($model->posicaoEdital, 3, "0", STR_PAD_LEFT);;
                },
            ],
             'nome',
             'email',
            [   'label' => 'Curso Desejado',
                'attribute' => 'cursodesejado',
                'value' => function ($model) {
                     return $model->cursodesejado == 1 ? 'Mestrado' : 'Doutorado';
                },
            ],
            [   'label' => 'Linha Pesquisa',
                'attribute' => 'siglaLinhaPesquisa',
                'contentOptions' => function ($model){
                  return ['style' => $model->linhaPesquisa == null ? "-" : 'background-color: '.$model->linhaPesquisa->cor];
                },
                'format' => 'html',
                'value' => function ($model){
                  return $model->linhaPesquisa == null ? "-" : "<span class='fa ". $model->linhaPesquisa->icone ." fa-lg'/> ". $model->siglaLinhaPesquisa;
                }
            ],
            [   'label' => 'Etapa da Inscrição',
                'attribute' => 'passoatual',
                'value' => function ($model) {
                     return $model->passoatual."/4";
                },
            ],

            ['class' => 'yii\grid\ActionColumn',
              'template'=>'{download} {view} {aprovar} {reprovar} {reenviar}',
                'buttons'=>[
                  'download' => function ($url, $model) {  

                    return Html::a('<span class="glyphicon glyphicon-download"></span>', ['candidatos/downloads', 'id' => $model->id, 'idEdital' => $model->idEdital], [
                            'target' => '_blank','title' => Yii::t('yii', 'Download da Documentação'),
                    ]);                                

                  },
                  'view' => function ($url, $model) {  

                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['candidatos/view', 'id' => $model->id, 'idEdital' => $model->idEdital], [
                            'title' => Yii::t('yii', 'Visualizar Detalhes'),
                    ]);                                

                  },
              ]                            
            ],
        ],
    ]),
            // open its content by default
            'contentOptions' => ['class' => 'in']
        ],
    ]
]);

?>
</div>
