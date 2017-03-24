<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Afastamentos */

$this->title = 'Criar Pedido de Afastamento';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="afastamentos-create">

	<p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ', ['afastamentos/index', 'id' => $model->id], ['class' => 'btn btn-warning']) ?>
    </p>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
