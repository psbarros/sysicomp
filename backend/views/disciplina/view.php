<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Disciplina */

$this->title = 'Disciplina: '.strtoupper($model->codDisciplina).' - '.$model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Disciplinas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="disciplina-view">

    <!-- <h1><?php //Html::encode($this->title); ?></h1> -->

    <p>
    	<?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar', ['index'], ['class' => 'btn btn-warning']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-edit"></span> Editar  ', ['update', 'id' => $model->codDisciplina], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('<span class="fa fa-trash-o"></span> Excluir', ['delete', 'id' => $model->codDisciplina], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Remover a Disciplina \''.$model->codDisciplina.'?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'codDisciplina',
            'nome',
            'creditos',
            'nomeCurso',
            'cargaHoraria',
            'instituicao',
            'preRequisito',
            [
            	'attribute'=>'obrigatoria',
            	'value'=>"$model->obrigatoriaLabel"
    		]
        ],
    ]) ?>

</div>
