<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Breadcrumbs;
use xj\bootbox\BootboxAsset;
use yii\bootstrap\Collapse;

BootboxAsset::register($this);
BootboxAsset::registerWithOverride($this);


/* @var $this yii\web\View */
/* @var $searchModel app\models\AlunoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Acompanhar Orientandos';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="orientandos-index">

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-print"></span> Relatório de Orientandos Correntes', ['relatorio', 'status' => 0], ['class' => 'btn btn-success']) ?>
		<?= Html::a('<span class="glyphicon glyphicon-print"></span> Relatório de Orientandos Passados', ['relatorio', 'status' => 1], ['class' => 'btn btn-success']) ?>
    </p>
	<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'matricula',
	     'nome',
	     [   'label' => 'Status',
       	   'attribute' => 'status',
	          'filter'=>array (0 => "Aluno Corrente",1 => "Aluno Egresso",2 => "Aluno Desistente",3 => "Aluno Desligado",4 => "Aluno Jubilado",5 => "Aluno com Matrícula Trancada"),
	          'value' => function ($model) {
       	   $statusAluno = array (0 => "Aluno Corrente",1 => "Aluno Egresso",2 => "Aluno Desistente",3 => "Aluno Desligado",4 => "Aluno Jubilado",5 => "Aluno com Matrícula Trancada");
	          return $statusAluno[$model->status];
                },
            ],
      [   'label' => 'Curso',
                'attribute' => 'curso',
        'filter'=>array("1"=>"Mestrado","2"=>"Doutorado"),
                'value' => function ($model) {
          return $model->curso == 1 ? "Mestrado" : "Doutorado";
                },
            ],
          'email:email',
          'telresidencial',
            ['class' => 'yii\grid\ActionColumn',
              'template'=> "{view} {banca}",
                'buttons'=>[
        
                  'view' => function ($url, $model) {  

                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['view_orientado', 'id' => $model->id], [
                            'title' => Yii::t('yii', 'Visualizar Detalhes'),
                    ]);                                

                  },

                  'banca' => function ($url, $model) {  

                    return Html::a('<span class="glyphicon glyphicon-check"></span>', ['defesa/create', 'aluno_id' => $model->id], [
                            'title' => Yii::t('yii', 'Solicitar Banca'),
                    ]);                                

                  },
              ]                            
            ],
        ],
    ]); ?>
    
</div>