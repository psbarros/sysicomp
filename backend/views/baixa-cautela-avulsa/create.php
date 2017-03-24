<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\BaixaCautelaAvulsa */

$this->title = 'Baixa Cautela Avulsa';
$this->params['breadcrumbs'][] = ['label' => 'Baixa Cautela Avulsas', 'url' => ['cautela-avulsa/view2', 'id'=>$model->idCautelaAvulsa]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="baixa-cautela-avulsa-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
