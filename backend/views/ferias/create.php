<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Ferias */

$this->title = 'Registrar Férias';
$this->params['breadcrumbs'][] = ['label' => 'Minhas Solicitações de Férias', 'url' => ['listar' , "ano" => $_GET["ano"]]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ferias-create">

	<p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ', ['listar', "ano" => $_GET["ano"], ], ['class' => 'btn btn-warning']) ?> 
    </p>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
