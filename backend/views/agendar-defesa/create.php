<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\AgendarDefesa */

$this->title = 'Agendar Defesa';
$this->params['breadcrumbs'][] = ['label' => 'Agendar Defesas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agendar-defesa-create">

    

    <?= $this->render('_form', [
        'model' => $model,
        'idAluno' => $idAluno,
    ]) ?>

</div>
