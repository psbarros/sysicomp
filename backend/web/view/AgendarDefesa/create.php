<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\AgendarDefesa */

$this->title = 'Create Agendar Defesa';
$this->params['breadcrumbs'][] = ['label' => 'Agendar Defesas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agendar-defesa-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
