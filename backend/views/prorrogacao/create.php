<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Prorrogacao */

$this->title = 'Registrar Prorrogação';
$this->params['breadcrumbs'][] = ['label' => 'Gerenciar Prorrogações', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="prorrogacao-create">
	<p> <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar', ['index'], ['class' => 'btn btn-warning']) ?> </p>
    <!--h1><?= Html::encode($this->title) ?></h1-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
