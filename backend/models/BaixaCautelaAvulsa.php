<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "j17_baixa_cautela_avulsa".
 *
 * @property integer $idBaixaCautelaAvulsa
 * @property integer $idCautelaAvulsa
 * @property string $Recebedor
 * @property string $DataDevolucao
 * @property string $Equipamento
 * @property string $ObservacaoBaixaCautela
 */
class BaixaCautelaAvulsa extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'j17_baixa_cautela_avulsa';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idCautelaAvulsa'], 'integer'],
            [['Recebedor',], 'required'],
            [['Recebedor', 'Equipamento', 'ObservacaoBaixaCautela'], 'string', 'max' => 50],
            //[['idCautelaAvulsa'], 'exist', 'skipOnError' => true, 'targetClass' => CautelaAvulsa::className(), 'targetAttribute' => ['idCautelaAvulsa' => 'idCautelaAvulsa']],
            [['DataDevolucao'], "safe"],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idBaixaCautelaAvulsa' => 'Id Baixa Cautela Avulsa',
            //'idCautelaAvulsa' => 'Id Cautela Avulsa',
            'Recebedor' => 'Recebedor',
            'DataDevolucao' => 'Data Devolucao',
            //'Equipamento' => 'Equipamento',
            'ObservacaoBaixaCautela' => 'Observacao',
        ];
    }
    
    public function  getBaixatemcautela(){
    	return $this->hasOne(CautelaAvulsa::className(),['idCautelaAvulsa'=>'idCautelaAvulsa']);
    }
}
