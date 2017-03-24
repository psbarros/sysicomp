<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\BancaControleDefesas */

$this->title = 'Justificativa do indeferimento';
$this->params['breadcrumbs'][] = ['label' => 'Banca Controle Defesas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banca-controle-defesas-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
