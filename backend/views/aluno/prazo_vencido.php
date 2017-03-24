<?php
use app\models\LinhaPesquisa;
use xj\bootbox\BootboxAsset;
use yii\grid\GridView;
use yii\helpers\Html;
$this->title = 'Alunos com prazo vencido';
$this->params['breadcrumbs'][] = $this->title;
BootboxAsset::register($this);
BootboxAsset::registerWithOverride($this);
?>

<?=  Html::a('<span class="fa fa-cloud-download" aria-hidden="true">
</span> Gerar PDF', ['aluno/prazo_vencido_pdf'], ['class' => 'btn btn-success']) ?>

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
        [
            'label' => 'Dias Passados',
            'attribute' => 'diasParaFormar',
            'value' => 'diasParaFormar',
        ],
        /*
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
        */
    ],
]); ?>
