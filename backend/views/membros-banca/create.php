<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MembrosBanca */

$this->title = 'Criar Membros';
$this->params['breadcrumbs'][] = ['label' => 'Membros Bancas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="membros-banca-create">

    <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ', ['index'], ['class' => 'btn btn-warning']) ?>
	<?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
