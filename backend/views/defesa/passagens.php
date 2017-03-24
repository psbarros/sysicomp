
<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Defesa */

$this->title = "Selecione os membros externos que solicitaram passagem";
$this->params['breadcrumbs'][] = $this->title;
?>

<script type="text/javascript">
    
    function toggle(source) {
  checkboxes = document.getElementsByName('check_list[]');
  for(var i=0, n=checkboxes.length;i<n;i++) {
    checkboxes[i].checked = source.checked;
  }
}
    

</script>

<div class="defesa-view">

<table>
    
    <tr>
        <td>
            <input type="checkbox" onClick="toggle(this)" /> <br/>
        </td>
        <td>
            <b> Selecionar Todos </b>
        </td>
    </tr>

    <?php $form = ActiveForm::begin(['action' =>['defesa/passagens2'],'id' => 'forum_post_passagem', 'method' => 'post',]); ?>
    
            <input type="hidden" name= "banca_id" value= <?php echo $_GET['banca_id']; ?> />
    
        <?php
        
        for ($i=0; $i< (count($model)); $i++){
            ?>
            <tr>
              <td style=""> <input <?php if ($model[$i]->passagem == "S"){echo "checked";} ?> type="checkbox" name="check_list[]" value= <?php echo $model[$i]->membrosbanca_id ?>  > </input> </td>
              <td style=""> <?php echo $model[$i]->membro_nome; ?> </td>
            </tr>
            <?php
        }
        ?>
    
    <tr> 
        <td colspan="2">
        <br>
                <?= Html::submitButton("Salvar", ['class' => 'btn btn-success']) ?>
        </td>
    </tr>
    <?php ActiveForm::end(); ?>

</table>


</div>



