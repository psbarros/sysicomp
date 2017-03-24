<?php
use yii\grid\GridView;
use \yii\helpers\Html;

$idProjeto = Yii::$app->request->get('idProjeto');


$this->title = "Relatorio Detalhado";
//'Cont Proj Rubricasde Projetos';
$this->params['breadcrumbs'][] = $this->title;
?>
<p>
    <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ',
        ['cont-proj-projetos/view', 'id' => $idProjeto], ['class' => 'btn btn-warning']) ?>
</p>
<?= $this->render('..\cont-proj-projetos\dados.php', [
    'idProjeto' => $idProjeto,
]) ?>
<?php
for ($i = 0; $i < count($data); $i++) {

    echo
        '<div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><b>Receitas e Depesas da Rubrica'.$rubricas[$i]->nomerubrica.'</b></h3>
        </div>
        <div class="panel-body"><h4><b>Receita</b></h4>
        <b>Valor Previsto: R$ '.number_format($rubricas[$i]->valor_total,2).'</b>&nbsp|&nbsp&nbsp'.
        '<b>Valor Disponível: R$ '.number_format($rubricas[$i]->valor_disponivel,2).'</b>&nbsp|&nbsp&nbsp'.
        ' <b>Valor Gasto: R$ '.number_format($rubricas[$i]->valor_gasto,2).'</b></br>'.
        GridView::widget([
            'dataProvider' => $data[$i],
            'summary' => '',
            'columns' => [
                [
                    'attribute' => 'data',
                    'format' => ['date', 'php:d/m/Y'],
                    'contentOptions' => ['style' => 'width: 20%;'],
                ],
                [

                    'attribute' => 'descricao',
                    'format' => 'ntext',
                    'contentOptions' => ['style' => 'width: 40%;'],
                ],
                [
                    'attribute' => 'total',
                    'format' => 'currency',
                    'contentOptions' => ['style' => 'width: 20%;'],
                ],
            ],
        ]) . '</div>'
        .'<div class="panel-body"><h4><b>Despesas</b></h4>' .
        GridView::widget([
            'dataProvider' => $data2[$i],
            'summary' => '',
            'columns' => [
                [
                    'attribute' => 'data',
                    'format' => ['date', 'php:d/m/Y'],
                    'contentOptions' => ['style' => 'width: 20%;'],
                ],
                [
                    'attribute' => 'descricao',
                    'format' => 'ntext',
                    'contentOptions' => ['style' => 'width: 40%;'],
                ],
                [
                    'attribute' => 'total',
                    'format' => 'currency',
                    'contentOptions' => ['style' => 'width: 20%;'],
                ],
            ],
        ]) . '</div>
    </div>';
}
?>
