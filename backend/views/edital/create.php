<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Edital */

$this->title = 'Criar Edital';
$this->params['breadcrumbs'][] = ['label' => 'Editais', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="edital-create">

	<p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ', ['edital/index', 'id' => $model->numero], ['class' => 'btn btn-warning']) ?>
    </p>

    <?= $this->render('_form', [
        'model' => $model,
        'read' => false,
    ]) ?>

</div>
