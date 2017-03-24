<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Ferias */

$this->title = 'Editar Férias';
$this->params['breadcrumbs'][] = ['label' => 'Ferias', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ferias-update">

<p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ', ['listar', "ano" => $_GET["ano"] ], ['class' => 'btn btn-warning']) ?>  
</p>

    <?= $this->render('_formUpdate', [
        'model' => $model,
    ]) ?>

</div>
