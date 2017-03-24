<?php

namespace app\models;

use Yii;
use backend\models\Cautela;


/**
 * This is the model class for table "j17_baixa_cautela".
 *
 * @property integer $idBaixaCautela
 * @property integer $idCautela
 * @property integer $idCautelaAvulsa
 * @property string $Recebedor
 * @property string $DataDevolucao
 * @property string $Equipamento
 * @property string $ObservacaoBaixaCautela
 */
class BaixaCautela extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'j17_baixa_cautela';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idCautela',], 'integer'],
            [['Recebedor',], 'required'],
            [['Recebedor', 'DataDevolucao', 'ObservacaoBaixaCautela'], 'string', 'max' => 50],
            [['idCautela'], 'exist', 'skipOnError' => true, 'targetClass' => Cautela::className(), 'targetAttribute' => ['idCautela' => 'idCautela']],
//            [['idCautelaAvulsa'], 'exist', 'skipOnError' => true, 'targetClass' => CautelaAvulsa::className(), 'targetAttribute' => ['idCautelaAvulsa' => 'idCautelaAvulsa']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idBaixaCautela' => 'Id Baixa Cautela',
            'idCautela' => 'Id Cautela',
//            'idCautelaAvulsa' => 'Id Cautela Avulsa',
            'Recebedor' => 'Recebedor',
            'DataDevolucao' => 'Data Devolucao',
            //'Equipamento' => 'Equipamento',
            'ObservacaoBaixaCautela' => 'Observacao Baixa Cautela',
        ];
    }
}
