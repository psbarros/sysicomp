<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ButtonDropdown;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DisciplinaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Disciplinas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="disciplina-index">

<!--     <h1><?php //Html::encode($this->title); ?></h1> -->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Nova Disciplina', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'codDisciplina',
            'nome',
            'creditos',
            'cargaHoraria',
        	'nomeCurso',
        	'instituicao',
        	[
        		'attribute'=>'obrigatoria',
        		'value'=>'obrigatoriaLabel',
        		'filter'=> Html::dropDownList('DisciplinaSearch[obrigatoria]', $searchModel->obrigatoria, [''=>'',1=>'SIM', 0=>'NAO'],
        				['class'=>"form-control", 'options'=>['width'=>'50%']]),
    		],
			'preRequisito',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
