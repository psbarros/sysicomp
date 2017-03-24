<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Disciplina */

$this->title = 'Disciplina: '.strtoupper($model->codDisciplina).' - '.$model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Disciplinas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->codDisciplina, 'url' => ['view', 'id' => $model->codDisciplina]];
$this->params['breadcrumbs'][] = 'Editar';
?>
<?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar', ['index'], ['class' => 'btn btn-warning']) ?>
<br></br>
<div class="disciplina-update">

    <!-- <h1><?php //Html::encode($this->title); ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
