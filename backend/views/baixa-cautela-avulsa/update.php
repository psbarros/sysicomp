<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BaixaCautelaAvulsa */

$this->title = 'Update Baixa Cautela Avulsa: ' . $model->idBaixaCautelaAvulsa;
$this->params['breadcrumbs'][] = ['label' => 'Baixa Cautela Avulsas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->idBaixaCautelaAvulsa, 'url' => ['view', 'id' => $model->idBaixaCautelaAvulsa]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="baixa-cautela-avulsa-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
