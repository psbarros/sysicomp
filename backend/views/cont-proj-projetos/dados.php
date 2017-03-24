<?php

use \backend\models\ContProjProjetos;
use app\models\User;

$idProjeto = Yii::$app->request->get('idProjeto');
$modelProjeto = ContProjProjetos::find()->select("*")->where("id=$idProjeto")->one();
$coordenador = User::find()->select("*")->where("id=$modelProjeto->coordenador_id")->one();

?>


<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><b>Dados do Projeto</b></h3>
    </div>
    <div class="panel-body">
        <?= \yii\widgets\DetailView::widget([
            'model' => $modelProjeto,
            'attributes' => [
                //'coordenador',
                'nomeprojeto',
                [
                    'attribute' => 'coordenador_id',
                    'value' => $coordenador->nome,
                ],
                'orcamento:currency',
                'saldo:currency',
                [
                    'attribute' => 'data_inicio',
                    'value' => date("d/m/Y", strtotime($modelProjeto->data_inicio)),

                ],
                [
                    'attribute' => 'data_fim',
                    'format' => ['date', 'php:d/m/Y'],
                    'value' => $modelProjeto->dataMaior(),

                ],
            ],
        ]) ?>
    </div>
</div>
