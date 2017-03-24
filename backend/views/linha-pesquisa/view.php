<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\LinhaPesquisa */

$this->title = $model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Linhas de Pesquisa', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="linha-pesquisa-view">
    <p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar', ['index'], ['class' => 'btn btn-warning']) ?>
		<?= Html::a('<span class="glyphicon glyphicon-edit"></span> Alterar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-remove"></span> Remover', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Remover Linha de Pesquisa \''. $model->nome.'\'?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'nome',
            'sigla',
            [   'attribute' => 'icone',
                'format' => 'html',
                'value' => "<span class='fa ". $model->icone ." fa-lg'/> ",
            ],
			[   'attribute' => 'cor',
                'format' => 'html',
                'value' => "<div style='width: 100%; height: 30px; background-color: $model->cor'>".$model->cor."</div>",
            ],
        ],
    ]) ?>

</div>
