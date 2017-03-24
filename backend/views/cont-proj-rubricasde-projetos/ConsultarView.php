<?php
use yii\helpers\Html;
use \yii\grid\GridView;


$coordenador = \yii\helpers\ArrayHelper::map(\app\models\User::find()->orderBy('nome')->all(),
    'id', mb_strimwidth('nome', 0, 50, "..."));

$agencias = \yii\helpers\ArrayHelper::map(\backend\models\ContProjAgencias::find()->orderBy('nome')->all(),
    'id', 'sigla');


$this->title = "Rubricas Encontradas";
$this->params['breadcrumbs'][] = ['label' => 'Consultar Rubrica', 'url' => ['consultar']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ',
    ['consultar'], ['class' => 'btn btn-warning']) ?>
</br></br>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'data_fim',
            'format' => ['date', 'php:d/m/Y'],
        ],
        [
            'attribute' => 'projeto_id',
            'filter' => \yii\helpers\ArrayHelper::map(\backend\models\ContProjProjetos::find()->asArray()->all(), 'id', mb_strimwidth('nomeprojeto', 0, 20, "...")),
            'value' => 'nomeprojeto',
            'contentOptions' => ['style' => 'width: 23%;'],
        ],
        [
            'attribute' => 'agencia',
            'filter' => $agencias,
            'value' => function ($model) {
                $agencias = \yii\helpers\ArrayHelper::map(\backend\models\ContProjAgencias::find()->orderBy('nome')->all(),
                    'id', 'sigla');
                return $agencias[$model->agencia];
            },
            'contentOptions' => ['style' => 'width: 23%;'],
        ],
        [
            'attribute' => 'coordenador',
            'filter' => $coordenador,
            'value' => function ($model) {
                $coordenador = \yii\helpers\ArrayHelper::map(\app\models\User::find()->orderBy('nome')->all(),
                    'id', mb_strimwidth('nome', 0, 50, "..."));
                return $coordenador[$model->coordenador];
            },
            'contentOptions' => ['style' => 'width: 23%;'],
        ],
        [
            'attribute' => 'valor_disponivel',
            'format' => 'currency',
            'contentOptions' => ['style' => 'width: 50px;'],
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'buttons' => [
                'view' => function ($url, $model) {
                    $url = "http://localhost/novoppgi/backend/web/index.php?r=cont-proj-projetos%2Fview&id=$model->projeto"; //This is where I want to append the $lastAddress variable.
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url,
                        [                                 // link options
                        'title'=>'Ir pra página do projeto!',
                        'target'=>'_blank'
                        ]);
                    //Html::a('Atualizar', ['update', 'id' => $model->id,'idProjeto'=>$idProjeto,'nomeProjeto'=>$nomeProjeto ]);
                },
                'update' => function ($url, $model){
                    return false;
                },

                'delete' => function ($url, $model){
                    return false;
                }
            ]
        ]
    ],
]);
?>