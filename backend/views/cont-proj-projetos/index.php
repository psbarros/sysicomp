<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ContProjProjetosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Projetos de Pesquisa e desenvolvimento';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="cont-proj-projetos-index">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Cadastrar Novo Projeto', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'coordenador',
			'nomeprojeto',
            'orcamento:currency',
            'saldo:currency',
            [
                'attribute'=>'data_inicio',
                'format' => ['date', 'php:d/m/Y'],
            ],
            [
                'attribute'=>'data_fim',
                'format' => ['date', 'php:d/m/Y'],
            ],
            // 'data_fim_alterada',
            // 'agencia_id',
            // 'banco_id',
            // 'agencia',
            // 'conta',
            // 'edital',
            // 'proposta',
			[   'label' => 'Status',
                'attribute' => 'status',
				'filter'=>array ("Cadastrado" => "Cadastrado", "Ativo" => "Ativo", "Prorrogado" => "Prorrogado", "Encerrado" => "Encerrado"),
                'value' => 'status'
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
