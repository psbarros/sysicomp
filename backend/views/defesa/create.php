<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Defesa */

$this->title = 'Cadastrar Defesa - ' . $model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Defesas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="defesa-create">

	<p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ', ['aluno/view_orientado', 'id' => $_GET["aluno_id"] ], ['class' => 'btn btn-warning']) ?>
	</p>

    <?= $this->render('_form', [
        'model' => $model,
        'membrosBancaInternos' => $membrosBancaInternos,
        'membrosBancaExternos' => $membrosBancaExternos,
        'membrosBancaSuplentes' => $membrosBancaSuplentes,
        'defesastipos' => $defesastipos,
        'defesas_aluno_array' => $defesas_aluno_array,		
        'aluno' => $aluno,
    ]) ?>

</div>
