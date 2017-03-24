<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\BaixaCautela */

$this->title = 'Dar Baixa em Cautela';
$this->params['breadcrumbs'][] = ['label' => 'Baixa Cautelas', 'url' => ['cautela/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="baixa-cautela-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
