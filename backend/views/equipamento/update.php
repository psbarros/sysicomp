<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Equipamento */

$this->title = 'Atualizar Equipamento: ' . $model->NomeEquipamento;
$this->params['breadcrumbs'][] = ['label' => 'Equipamentos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->NomeEquipamento, 'url' => ['view', 'id' => $model->NomeEquipamento]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="equipamento-update">

    

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
