<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Disciplina */

$this->title = 'Nova Disciplina';
$this->params['breadcrumbs'][] = ['label' => 'Disciplinas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar', ['index'], ['class' => 'btn btn-warning']) ?>
<br></br>
<div class="disciplina-create">

    <!-- <h1><?php //Html::encode($this->title); ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
