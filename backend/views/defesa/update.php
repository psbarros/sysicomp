<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Defesa */

$this->title = 'Editar Defesa';
$this->params['breadcrumbs'][] = ['label' => 'Defesas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->idDefesa, 'url' => ['view', 'idDefesa' => $model->idDefesa, 'aluno_id' => $model->aluno_id]];
$this->params['breadcrumbs'][] = 'Editar';

?>
<div class="defesa-update">

		<p>
		<?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ', ['view', 'idDefesa' => $model->idDefesa, 'aluno_id' => $model->aluno_id], ['class' => 'btn btn-primary']) ?> 

        </p>

    <?= $this->render('_formUpdate', [
        'model' => $model,
        'model_aluno' => $model_aluno,
    ]) ?>

</div>
