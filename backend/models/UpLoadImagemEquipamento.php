<?php
/**
 * Created by PhpStorm.
 * User: barros
 * Date: 01/12/16
 * Time: 14:03
 */

namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;


/**
 * UploadForm is the model behind the upload form.
 */
class UpLoadImagensEquipamento extends Model
{
    /**
     * @var UploadedFile
     */
    public $imageFile;

    public function rules()
    {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $this->imageFile->saveAs('uploads/' . $this->imageFile->baseName . '.' . $this->imageFile->extension);
            return true;
        } else {
            return false;
        }
    }

}
?>