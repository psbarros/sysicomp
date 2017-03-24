<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "j17_portaria".
 *
 * @property integer $id
 * @property string $responsavel
 * @property string $descricao
 * @property string $data
 * @property string $documento
 */
class PrazoVencido extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'j17_aluno';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','nome'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Número',
            'nome' => 'Nome',
        ];
    }

    public function getId(){
        $id= $this->find()->select('id')->one();
        
        return $id;
    }
}
