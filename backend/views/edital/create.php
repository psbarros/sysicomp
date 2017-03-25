<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Edital */

$this->title = 'Criar Novo Edital de Seleção para o PPGI';
$this->params['breadcrumbs'][] = ['label' => 'Editais', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="edital-create">

	<p>
		<?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span>&nbsp;&nbsp;Voltar','#',['class' => 'btn btn-warning','onclick'=>"history.go(-1);"]); ?>
        <?= Html::a('<span class="fa fa-list"></span>&nbsp;&nbsp;Listar Editais', ['edital/index'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= $this->render('_form', [
        'model' => $model,
        'read' => false,
    ]) ?>

</div>
