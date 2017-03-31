<?php

namespace backend\controllers;

use Yii;
use app\models\Defesa;
use app\models\DefesaSearch;
use app\models\DefesasTipo;
use app\models\BancaControleDefesas;
use app\models\LinhaPesquisa;
use app\models\Banca;
use app\models\BancaSearch;
use app\models\MembrosBanca;
use app\models\MembrosBancaSearch;
use app\models\Aluno;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\db\IntegrityException;
use yii\base\Exception;
use yii\web\UploadedFile;
use mPDF;
use kartik\mpdf\Pdf;

/**
 * DefesaController implements the CRUD actions for Defesa model.
 */
class DefesaController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],

                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Defesa models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DefesaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Defesa model.
     * @param integer $idDefesa
     * @param integer $aluno_id
     * @return mixed
     */
    public function actionView($idDefesa, $aluno_id)
    {

        $model = $this->findModel($idDefesa, $aluno_id);

        $model_banca = new BancaSearch();
        $dataProvider = $model_banca->search(Yii::$app->request->queryParams,$model->banca_id);

        if ($model->load(Yii::$app->request->post() ) ) {
            if($model->banca->status_banca == 1 && $model->save(false))
                $this->mensagens('success', 'Conceito Atribuído', 'Conceito atribuído com sucesso.');
            else
                $this->mensagens('danger', 'Conceito não Atribuído', 'Ocorreu um erro ao atribuir o conceito a defesa. Verifique se a banca foi avaliada.');
        }

        return $this->render('view', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionPendentes()
    {
        $searchModel = new DefesaSearch();
        $dataProvider = $searchModel->searchPendentes(Yii::$app->request->queryParams);

        return $this->render('pendentes', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionLembretependencia($idDefesa, $aluno_id){

        $model = $this->findModel($idDefesa, $aluno_id);
        if($this->enviaNotificacaoPendenciaDefesa($model))
            $this->mensagens('success', 'Lembretes Enviados', 'Os Lembretes de pendência de defesas foram enviados com sucesso.');

        $this->redirect(['defesa/view', 'idDefesa' => $idDefesa, 'aluno_id' => $aluno_id]);
    }

    public function actionGerarrelatoriobanca()
    {
    	try{
			//print_r(Yii::$app->request->Post());
			$idProfessor = Yii::$app->request->Post('idProfessor');
			$mDefesa = new Defesa;
			$mDefesa->load(Yii::$app->request->Post());
			$mDefesa->anoPesq = trim($mDefesa->anoPesq);
			if($mDefesa->tipoRelat==1 && trim($mDefesa->anoPesq)==""){
				$this->mensagens('warning', 'Preenchimento da busca', 'Preencha o Ano de Referência.');
				if(Yii::$app->request->Post('listall') == 'listall')
					return $this->redirect(['defesa/bancasallmembro','idProfessor'=>$idProfessor]);
				else
					return $this->redirect(['defesa/bancasbymembro','idProfessor'=>$idProfessor]);
			}
			if($mDefesa->tipoRelat==0)
				$mDefesa->anoPesq = null;
			$mMembro = Membrosbanca::findOne($idProfessor);
/*
			echo "QueryParams: ".print_r(Yii::$app->request->queryParams);
			echo "<br><br>";
			echo "Post: ".print_r($idProfessor = Yii::$app->request->Post());
			return $this->render('dummy');
*/
			if($mMembro === null){
				$this->mensagens('warning', 'Para gerar relatório:','Use o menu lateral "Gerar Relatório Bancas".');
				return $this->redirect(['site/index']);
			}

			//Gera Relatório de Bancas de um Professor ou Todas de um Professor no Ano especificado
			return $this->generatePdfRelatorioBancas($mMembro, $mDefesa->anoPesq);
    	}catch (Exception $e){
    		$this->mensagens('danger', 'Relatório de Bancas.','Houve algum problema ao gerar o relatório de bancas. Contate o Administrador.');
    		return $this->redirect(['site/index']);
    	}
    }

    public function actionBancasbymembro()
    {	//echo "Professor: ".$idProfessor;

    	//echo "-----".$idProfessor;/*Lembrar de colocar a expressao correta em left.php (User->idProfessor)*/
    	$idProfessor = Yii::$app->request->Get('idProfessor');
    	$mMembro = Membrosbanca::findOne($idProfessor);
        if($mMembro === null){
        	$this->mensagens('warning', 'Não foi possível gerar o relatório.','Professor não encontrado, Contate o Administrador".');
            throw new NotFoundHttpException('Professor Não Encontrado.');
        }
        $bancasId = null;
        if($mMembro!==null)
		foreach ($mMembro->bancas as $banca){
			$bancasId[] = $banca->banca_id;
		}

		$searchModel = new DefesaSearch();
		//echo print_r(Yii::$app->request->queryParams);
        $dataProvider = $searchModel->searchByBancas(Yii::$app->request->queryParams, $bancasId);

        return $this->render('indexrelatoriobancas', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        	'idProfessor' => $idProfessor,
        ]);
    }

    public function actionBancasallmembro()
    {	//echo "Professor: ".$idProfessor;

    //echo "-----".$idProfessor;/*Lembrar de colocar a expressao correta em left.php (User->idProfessor)*/
    //$mMembro = Membrosbanca::findOne($idProfessor);
    //if($mMembro === null){
    //	throw new NotFoundHttpException('Professor Não Encontrado.'.$idProfessor);
    //}
    /*
    	echo "QueryParams: ".print_r(Yii::$app->request->queryParams);
    	echo "<br><br>";
    	echo "Post: ".print_r($idProfessor = Yii::$app->request->Post());
    	return $this->render('dummy');
    	*/
    $bancasId = null;
    //if($mMembro!==null)
    //	foreach ($mMembro->bancas as $banca){
    //		$bancasId[] = $banca->banca_id;
    //}

    $searchModel = new DefesaSearch();
    $dataProvider = $searchModel->searchByBancas(Yii::$app->request->queryParams, $bancasId);

    return $this->render('indexrelatorioallbancas', [
    		'searchModel' => $searchModel,
    		'dataProvider' => $dataProvider,
    		'idProfessor' => "",
    ]);
    }

    public function actionAutocompletemembro($term){
    	$listaMembros = Membrosbanca::find()->where(["like","upper(nome)",strtoupper($term)])->all();

    	$codigos = [];

    	foreach ($listaMembros as $membro)
    	{
    		$codigos[] = ['label'=>$membro['nome'],'value'=>$membro['nome'], 'id'=>$membro['id']
    		]; //build an array
    	}

    	echo json_encode($codigos);
    }
    /**
     * Creates a new Defesa model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($aluno_id)
    {

        $model = new Defesa();
        $aluno = Aluno::findOne($aluno_id);
        $model->aluno_id = $aluno_id;

        // Selecionando os membros de bancas cadastrados
        $membrosBancaInternos = ArrayHelper::map(MembrosBanca::find()->where("filiacao = 'PPGI/UFAM'")->orderBy('nome')->all(), 'id', 'nome','filiacao');
        $membrosBancaExternos = ArrayHelper::map(MembrosBanca::find()->where("filiacao <> 'PPGI/UFAM'")->orderBy('nome')->all(), 'id', 'nome','filiacao');
        $membrosBancaSuplentes = ArrayHelper::map(MembrosBanca::find()->orderBy('nome')->all(), 'id', 'nome','filiacao');

        // Verificando se o aluno possui conceitos pendentes de defesas anteriores
        $conceitoPendente = $model->ConceitoPendente($aluno_id);

        if ($conceitoPendente == true){
            $this->mensagens('danger', 'Defesas c/ Pendências', 'Existem defesas que estão pendentes de conceito ou Bancas pendentes de Deferimento pelo Coordenador.');
            return $this->redirect(['aluno/orientandos']);
        }

        $defesas_aluno = Defesa::find()
            ->where("aluno_id = ".$aluno_id." AND conceito is NOT NULL")
            ->orderBy("id DESC")
            ->all();

        $defesas_aluno_array = [];

        foreach ($defesas_aluno as $defesa) {
            $defesas_aluno_array[$defesa->id] = ['disabled' => true];
        }

        $cont_defesas = Defesa::find()
            ->where("aluno_id = ".$aluno_id." AND conceito is NOT NULL")
            ->count();



        $defesas_tipos = DefesasTipo::find()->where(['curso'=>$aluno->curso])->all();
        $defesas_tipos = ArrayHelper::map($defesas_tipos,'id','nome');

        if ($model->load(Yii::$app->request->post() ) ) {

            $model->auxiliarTipoDefesa = $tipodefesa;
            $model_ControleDefesas = new BancaControleDefesas();

            if($model->tipoDefesa == "Q1" && $aluno->curso == 2) {
                $model_ControleDefesas->status_banca = 1;
                $model_ControleDefesas->justificativa = 'Sem justificativa';
            } else {
                $model_ControleDefesas->status_banca = null;
                $model_ControleDefesas->justificativa = 'Sem justificativa';
            }

            $model_ControleDefesas->save(false);
            $model->banca_id = $model_ControleDefesas->id;

            if (! $model->uploadDocumento(UploadedFile::getInstance($model, 'previa'))){
                $this->mensagens('danger', 'Erro ao salvar defesa', 'Ocorreu um erro ao salvar a defesa. Verifique os campos e tente novamente');
                return $this->redirect(['aluno/orientandos',]);
            }

            try{

                if($model->tipoDefesa == "Q1" && $model->curso == "Doutorado"){

                    if($model->save(false)){
                        $this->mensagens('success', 'Defesa salva', 'A defesa foi salva com sucesso.');
                        return $this->redirect(['view', 'idDefesa' => $model->idDefesa, 'aluno_id' => $model->aluno_id]);
                    }

                } else {

                    $model->salvaMembrosBanca();

                    if($model->save()){
                        $this->mensagens('success', 'Defesa salva', 'A defesa foi salva com sucesso.');
                        return $this->redirect(['passagens', 'banca_id' => $model->banca_id]);
                    } else {
                        $this->mensagens('danger', 'Erro ao salvar defesa', 'Ocorreu um erro ao salvar a defesa. Verifique os campos e tente novamente');
                    }

                }

            } catch(Exception $e){
                $this->mensagens('danger', 'Erro ao salvar Membros da banca', 'Ocorreu um Erro ao salvar os membros da bancas.');
            }

        }

        else if ( ($aluno->curso == 1 && $cont_defesas >= 2) || ($aluno->curso == 2 && $cont_defesas >= 3) ){
            $this->mensagens('danger', 'Solicitar Banca', 'Não foi possível solicitar banca, pois esse aluno já possui '.$cont_defesas.' defesas cadastradas');
            return $this->redirect(['aluno/orientandos',]);
        }

        return $this->render('create', [
            'model' => $model,
            'membrosBancaInternos' => $membrosBancaInternos,
            'membrosBancaExternos' => $membrosBancaExternos,
            'membrosBancaSuplentes' => $membrosBancaSuplentes,
            'defesastipos' => $defesas_tipos,
            'defesas_aluno_array' => $defesas_aluno_array,
            'aluno' => $aluno,
        ]);
    }

    public function actionPassagens($banca_id){


        $banca = Banca::find()->select("j17_banca_has_membrosbanca.* , mb.nome as membro_nome, mb.filiacao as membro_filiacao, mb.*")->leftJoin("j17_membrosbanca as mb","mb.id = j17_banca_has_membrosbanca.membrosbanca_id")
        ->where(["banca_id" => $banca_id , "funcao" => "E"])->all();

        return $this->render('passagens', [
            'model' => $banca,
        ]);



    }

    public function actionPassagens2(){

    $where = "";

    $banca_id = $_POST['banca_id'];

        if(!empty($_POST['check_list'])){
            // Loop to store and display values of individual checked checkbox.

           $arrayChecked = $_POST['check_list'];

            for($i=0; $i<count($arrayChecked)-1; $i++){
                $where = $where."membrosbanca_id = ".$arrayChecked[$i]." OR ";
            }
                $where = $where."membrosbanca_id = ".$arrayChecked[$i];
        }


        if ($where != ""){
            $sqlSim = "UPDATE j17_banca_has_membrosbanca SET passagem = 'S' WHERE ($where) AND banca_id = ".$banca_id;
            //$sqlNao = "UPDATE j17_banca_has_membrosbanca SET passagem = 'N' WHERE $where";

            try{
                echo Yii::$app->db->createCommand($sqlSim)->execute();

              //  echo Yii::$app->db->createCommand($sqlNao)->execute();

                $this->mensagens('success', 'Passagens', 'As alterações das passagens foram salvas com sucesso.');

                return $this->redirect(['aluno/orientandos',]);

            }
            catch(\Exception $e){

                $this->mensagens('danger', 'Erro ao salvar', 'Ocorreu um Erro ao salvar essas alterações no Banco. Tente Novamente.');
            }
        }
        else {
            $this->mensagens('success', 'Passagens', 'As alterações das passagens foram salvas com sucesso.');
            return $this->redirect(['aluno/orientandos',]);
        }



    }


    /**
     * Updates an existing Defesa model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $idDefesa
     * @param integer $aluno_id
     * @return mixed
     */
    public function actionUpdate($idDefesa, $aluno_id)
    {


        //SÓ PODE EDITAR A DEFESA SE ELA NÃO FOI CONCEITUADA! TEM DE CHECAR SE CONCEITO == NULL

        $model_aluno = Aluno::find()->where("id = ".$aluno_id)->one();

        $model = $this->findModel($idDefesa, $aluno_id);

        $model->data = date('d-m-Y', strtotime($model->data));

        if ($model->load(Yii::$app->request->post())) {

            $model->data = date('Y-m-d', strtotime($model->data));
            $model->save(false);


            return $this->redirect(['view', 'idDefesa' => $model->idDefesa, 'aluno_id' => $model->aluno_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'model_aluno' => $model_aluno,

            ]);
        }
    }

    /**
     * Deletes an existing Defesa model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $idDefesa
     * @param integer $aluno_id
     * @return mixed
     */
    public function actionDelete($idDefesa, $aluno_id)
    {

        //SÓ PODE EXCLUIR A DEFESA SE ELA NÃO NÃO POSSUIR BANCA! TEM DE CHECAR SE banca_id == 0
        $model = $this->findModel($idDefesa, $aluno_id);

        $banca = BancaControleDefesas::find()->where(["id" => $model->banca_id])->one();


        if($banca->status_banca != null){
            $this->mensagens('danger', 'Não Excluído', 'Não foi possível excluir, pois essa defesa já possui banca aprovada');
            return $this->redirect(['index']);
        }

        $model->delete();

        return $this->redirect(['index']);
    }

    public function cabecalhoRodape($pdf){
            $pdf->SetHTMLHeader('
                <table style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; ">
                    <tr>
                        <td width="20%" align="center" style="font-family: Helvetica;font-weight: bold; font-size: 175%;"> <img src = "../../frontend/web/img/logo-brasil.jpg" height="90px" width="90px"> </td>
                        <td width="60%" align="center" style="font-family: Helvetica;font-weight: bold; font-size: 135%;">  PODER EXECUTIVO <br> MINISTÉRIO DA EDUCAÇÃO <br> INSTITUTO DE COMPUTAÇÃO <br><br> PROGRAMA DE PÓS-GRADUAÇÃO EM INFORMÁTICA </td>
                        <td width="20%" align="center" style="font-family: Helvetica;font-weight: bold; font-size: 175%;"> <img style="margin-left:8%" src = "../../frontend/web/img/ufam.jpg" height="90px" width="75px"> </td>
                    </tr>
                </table>
                <hr>
            ');

            $pdf->SetHTMLFooter('
				<hr>
                <table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;">
                    <tr>
                        <td  colspan = "3" align="center" ><span style="font-weight: bold"> Av. Rodrigo Otávio, 6.200 - Campus Universitário Senador Arthur Virgílio Filho - CEP 69077-000 - Manaus, AM, Brasil </span></td>
                    </tr>
                    <tr>
                        <td width="33%" align="center" style="font-weight: bold; font-style: italic;">  Tel. (092) 3305-1193/2808/2809</td>
                        <td width="33%" align="center" style="font-weight: bold; font-style: italic;">  E-mail: secretaria@icomp.ufam.edu.br</td>

                        <td width="33%" align="center" style="font-weight: bold; font-style: italic;">  http://www.icomp.ufam.edu.br </td>
                    </tr>
                </table>
            ');

            return $pdf;
    }

    public function actionConvitepdf($idDefesa, $aluno_id){

        $model = $this->findModel($idDefesa, $aluno_id);

        $modelAluno = Aluno::find()->select("u.nome as nome, j17_aluno.curso as curso")->where(["j17_aluno.id" => $aluno_id])->innerJoin("j17_user as u","j17_aluno.orientador = u.id")->one();

        if($modelAluno->curso == 1){
            $curso = "Mestrado";

            if($model->tipoDefesa == "Q1"){
                $tipoDefesa = "Exame de Qualificação de Mestrado";
            }
            else{
                $tipoDefesa = "Dissertação de Mestrado";
            }
        }
        else{
            $curso = "Doutorado";

            if($model->tipoDefesa == "Q1"){
                $tipoDefesa = "Exame de Qualificação de Doutorado";
            }
            else  if($model->tipoDefesa == "Q2"){
                $tipoDefesa = "Exame de Qualificação de Doutorado";
            }
            else{
                $tipoDefesa = "Tese de Doutorado";
            }

        }


        $banca = Banca::find()
        ->select("j17_banca_has_membrosbanca.* , j17_banca_has_membrosbanca.funcao ,mb.nome as membro_nome, mb.filiacao as membro_filiacao, mb.*")->leftJoin("j17_membrosbanca as mb","mb.id = j17_banca_has_membrosbanca.membrosbanca_id")
        ->where(["banca_id" => $model->banca_id])->all();

        $bancacompleta = "";

        foreach ($banca as $rows) {
            if($rows->funcao == "P"){
                $funcao = "(Presidente)";
            }
            else{
                $funcao = "";
            }
            $bancacompleta = $bancacompleta . $rows->membro_nome.' - '.$rows->membro_filiacao.' '.$funcao.'<br>';
        }

        $pdf = new mPDF('utf-8','A4','','','15','15','42','30');

        $pdf = $this->cabecalhoRodape($pdf);

             $pdf->WriteHTML('
                <div style="text-align:center"> <h3>  CONVITE À COMUNIDADE </h3> </div>
                <p style = "text-align: justify;">
                     A Coordenação do Programa de Pós-Graduação em Informática PPGI/UFAM tem o prazer de convidar toda a
                    comunidade para a sessão pública de apresentação de defesa de '.$tipoDefesa.':
                </p>
            ');

             $pdf->WriteHTML('
                <div style="text-align:center"> <h4>'.$model->titulo.'</h4> </div>
                <p style = "text-align: justify;">
                RESUMO: '.$model->resumo.'
                </p>
            ');

             $pdf->WriteHTML('

                    CANDIDATO: '.$model->nome.' <br><br>

                    BANCA EXAMINADORA: <br>
                    <div style="margin-left:15%"> '.$bancacompleta.' </div>

            ');

             $coordenadorppgi = new Defesa();
             $coordenadorppgi = $coordenadorppgi->getCoordenadorPPGI();


             $pdf->WriteHTML('
                <p>
                    LOCAL: '.$model->local.'
                </p>
                <p>
                    DATA: '.$model->data.'
                </p>
                <p>
                    HORÁRIO: '.$model->horario.'
                </p>
				<br><br>
                <div style="text-align:center">
                    <p><font style="font-size:medium">'.$coordenadorppgi.'<br>
					<font style="font-size:small"> Coordenador(a) do Programa de Pós-Graduação em Informática PPGI/UFAM </p>

                </div>
            ');

    $pdfcode = $pdf->output();
    fwrite($arqPDF,$pdfcode);
    fclose($arqPDF);



    }

    public function actionAtadefesapdf($idDefesa, $aluno_id){

    $model = $this->findModel($idDefesa, $aluno_id);

        $arrayMes = array(
            "01" => "Janeiro",
            "02" => "Fevereiro",
            "03" => "Março",
            "04" => "Abril",
            "05" => "Maio",
            "06" => "Junho",
            "07" => "Julho",
            "08" => "Agosto",
            "09" => "Setembro",
            "10" => "Outubro",
            "11" => "Novembro",
            "12" => "Dezembro",
            );

        $modelAlunoLinha = LinhaPesquisa::find()->innerJoin("j17_aluno as a","a.id = ".$aluno_id)->one();

        $modelAluno = Aluno::find()->select("u.nome as nome, j17_aluno.curso as curso")->where(["j17_aluno.id" => $aluno_id])->innerJoin("j17_user as u","j17_aluno.orientador = u.id")->one();

        if($modelAluno->curso == 1){
                $curso = "Mestrado";
                $titulo = "mestre";

                $tipoDefesa = "Dissertação de Mestrado";
                $tipoDefesaUp = "DISSERTAÇÃO DE MESTRADO";

        }
        else{
                $curso = "Doutorado";
                $titulo = "doutor";
                $tipoDefesa = "Tese de Doutorado";
                $tipoDefesaUp = "TESE DE DOUTORADO";
        }

            $banca = Banca::find()
            ->select("j17_banca_has_membrosbanca.* , j17_banca_has_membrosbanca.funcao ,mb.nome as membro_nome, mb.filiacao as membro_filiacao, mb.*")->leftJoin("j17_membrosbanca as mb","mb.id = j17_banca_has_membrosbanca.membrosbanca_id")
            ->where(["banca_id" => $model->banca_id])->orderBy(['funcao'=>SORT_DESC])->all();

            $bancacompleta = "";
            $outrosMembros = "";

            foreach ($banca as $rows) {
                if($rows->funcao == "P"){
                    $funcao = "(Presidente)";
                    $presidente = $rows->membro_nome;
                }
                else{
                    $funcao = "";
                    $outrosMembros = $outrosMembros .', '. $rows->membro_nome;
                }
                $bancacompleta = $bancacompleta . $rows->membro_nome.' - '.$rows->membro_filiacao.' '.$funcao.'<br>';
            }

            $pdf = new mPDF('utf-8','A4','','','15','15','42','30');

            $pdf = $this->cabecalhoRodape($pdf);


             $pdf->WriteHTML('
                <div style="text-align:center;"> <h4>  '.$model->numDefesa.'ª ATA DE DEFESA PÚBLICA DE '.$tipoDefesaUp.' </h4> </div>
            ');

             $dia = date('d', strtotime($model->data));
             $mes = date("m",strtotime($model->data));

             $pdf->WriteHTML('

                <p style = "text-align: justify; font-family: Times New Roman, Arial, serif; font-size: 100%;">
                    Aos '.$dia.' dias do mês de '.$arrayMes[$mes].' do ano de '.date("Y", strtotime($model->data)).', às '.$model->horario.'h, na '.$model->local.' da Universidade Federal do Amazonas, situada na Av. Rodrigo Otávio, 6.200, Campus Universitário, Setor Norte, Coroado, nesta Capital, ocorreu a sessão pública de defesa de '.$tipoDefesa.' intitulada "'.$model->titulo.'" apresentada pelo discente '.$model->nome.' que concluiu todos os pré-requisitos exigidos para a obtenção do título de '.$titulo.' em informática, conforme estabelece o artigo 52 do regimento interno do curso. Os trabalhos foram instalados pelo(a) '.$presidente.', orientador(a) e presidente da Banca Examinadora, que foi constituí­da, ainda, pelos membros convidados: '.$outrosMembros.'. A Banca Examinadora tendo decidido aceitar a dissertação, passou à arguição pública do candidato.
                </p>
            ');


             $pdf->WriteHTML('Encerrados os trabalhos, os examinadores expressaram o parecer abaixo. <br><br>

                A comissão considerou a '.$tipoDefesa.': <br>
                (  &#32;&#32;&#32;  ) Aprovada <br>
                (  &#32;&#32;&#32;  ) Suspensa <br>
                (  &#32;&#32;&#32;  ) Reprovada <br>
                <p style = "text-align: justify;">
                Proclamados os resultados, foram encerrados os trabalhos e, para constar, eu, Elienai Nogueira, Secretária do Programa de Pós-Graduação em Informática, lavrei a presente ata, que assino juntamente com os Membros da Banca Examinadora.
                </p>
                <br>
                ');


            foreach ($banca as $rows) {

                if ($rows->funcao == "P"){
                    $funcao = "Presidente";
                }
                else if($rows->funcao == "E"){
                    $funcao = "Membro Externo";
                }
                else {
                    $funcao = "Membro Interno";
                }
                 $pdf->WriteHTML('

                    <div style="float: right;
					line-height: 2.2;
                                width: 50%;">
                                Assinatura: ............................................................
                    </div>

                    <div style="float: left;
                                width: 50%;
                                text-align:left;
								line-height: 2.2;
                                margin-bottom:3%;">
                            '.$rows->membro_nome.'
                    </div>
                ');
             }

                 $pdf->WriteHTML('

                    <div style="float: left;
                                width: 60%;
                                margin-top:3%;">
                                ____________________________________________________ <br>

                                Secretaria
                    </div>

                    <div style="text-align:right"> <h4>Manaus, '.date("d", strtotime($model->data)).' de '.$arrayMes[$mes].' de '.date("Y", strtotime($model->data)).'</h4> </div>

                ');

    $pdf->addPage();

             $pdf->WriteHTML('
                <div style="text-align:center;"> <h4>  FOLHA DE SUSPENSÃO </h4> </div>
            ');

             $pdf->WriteHTML('
                <p style = "text-align: justify; font-family: Times New Roman, Arial, serif; font-size: 120%; margin-bottom:10%">

                 A Banca Examinadora, com base no Art. 13, da Resolução nº 033/2014 - CONSEPE, de 30 de setembro de 2014, decide suspender a sessão, pelo prazo de ______ dias, respeitando o período máximo de 60 dias estabelecido no § 1º do referido artigo:

                </p>
            ');

            foreach ($banca as $rows) {

                if ($rows->funcao == "P"){
                    $funcao = "Presidente";
                }
                else if($rows->funcao == "E"){
                    $funcao = "Membro Externo";
                }
                else {
                    $funcao = "Membro Interno";
                }
                 $pdf->WriteHTML('

                    <div style="float: right;
                                width: 60%;
                                text-align:right;
                                margin-bottom:5%;
								line-height: 4.0;
                                border-top:solid 1px">
                            '.$rows->membro_nome.' - '.$funcao.'
                    </div>

                ');

             }

             $pdf->WriteHTML('

                    <div style="text-align:center"> <h4>Manaus, '.date("d", strtotime($model->data)).' de '.$arrayMes[$mes].' de '.date("Y", strtotime($model->data)).'</h4> </div>

                ');



    $pdfcode = $pdf->output();
    fwrite($arqPDF,$pdfcode);
    fclose($arqPDF);
}



    public function actionAtapdf($idDefesa, $aluno_id){

    $model = $this->findModel($idDefesa, $aluno_id);

        $modelAlunoLinha = LinhaPesquisa::find()->innerJoin("j17_aluno as a","a.id = ".$aluno_id)->one();

        $modelAluno = Aluno::find()->select("u.nome as nome, j17_aluno.curso as curso")->where(["j17_aluno.id" => $aluno_id])->innerJoin("j17_user as u","j17_aluno.orientador = u.id")->one();

        if($modelAluno->curso == 1){
            $curso = "Mestrado";

            if($model->tipoDefesa == "Q1"){
                $tipoDefesa = "Exame de Qualificação de Mestrado";
            }
            else{
                $tipoDefesa = "Dissertação de Mestrado";
            }
        }
        else{
            $curso = "Doutorado";

            if($model->tipoDefesa == "Q1"){
                $tipoDefesa = "Exame de Qualificação de Doutorado";
            }
            else  if($model->tipoDefesa == "Q2"){
                $tipoDefesa = "Exame de Qualificação de Doutorado";
            }
            else{
                $tipoDefesa = "Tese de Doutorado";
            }

        }

            $banca = Banca::find()
            ->select("j17_banca_has_membrosbanca.* , j17_banca_has_membrosbanca.funcao ,mb.nome as membro_nome, mb.filiacao as membro_filiacao, mb.*")->leftJoin("j17_membrosbanca as mb","mb.id = j17_banca_has_membrosbanca.membrosbanca_id")
            ->where(["banca_id" => $model->banca_id])->orderBy(['funcao'=>SORT_DESC])->all();

            $bancacompleta = "";

            foreach ($banca as $rows) {
                if($rows->funcao == "P"){
                    $funcao = "(Presidente)";
                }
                else{
                    $funcao = "";
                }
                $bancacompleta = $bancacompleta . $rows->membro_nome.' - '.$rows->membro_filiacao.' '.$funcao.'<br>';
            }

            $pdf = new mPDF('utf-8','A4','','','15','15','42','30');

            $pdf = $this->cabecalhoRodape($pdf);

                 $pdf->WriteHTML('
                    <div style="text-align:center"> <h3>  Avaliação de Proposta de '.$tipoDefesa.' </h3> </div>
                    <p style = "font-weight: bold;">
                        DADOS DO(A) ALUNO(A): </p>
                        Nome: '.$model->nome.'  <br><br>
                        Área de Conceitação: Ciência da Computação  <br><br>
                        Linha de Pesquisa: '.$modelAlunoLinha->nome.'  <br><br>
                        Orientador: '.$modelAluno->nome.'  <br><br>
                        <hr>
                    </p>
                ');


                 $pdf->WriteHTML('
                    <p style = "font-weight: bold;">
                        DADOS DA DEFESA:
                    </p>
                    <table style =" margin-bottom:20px;">
                        <tr>
                            <td colspan="4"> Título: '.$model->titulo.' </td>
                        </tr>
                        <tr>
                        <td coslpan="4"> &nbsp;  </td>
                        </tr>
                        <tr>
                            <td> Data: '.date("d-m-Y",  strtotime($model->data)).' </td>
                            <td> Hora: '.$model->horario.' </td>
                            <td colspan="2"> Local: '.$model->local.' </td>

                        </tr>
                    </table>
                    <table style =" margin-bottom:60px; width:100%;">
                    <tr>
                        <td>
                            <h4> Avaliação da Banca Examinadora </h4>
                        </td>

                        <td align="right">
                            <h4> Conceito: _______________________ </h4>
                        </td>
                    </tr>
                    </table>



                ');


            foreach ($banca as $rows) {

                if ($rows->funcao == "P"){
                    $funcao = "Presidente";
                }
                else if($rows->funcao == "E"){
                    $funcao = "Membro Externo";
                }
                else {
                    $funcao = "Membro Interno";
                }
                 $pdf->WriteHTML('

                    <div style="float: right;
								height:40px;
                                width: 60%;
                                text-align:right;
                                margin-bottom:5%;
                                border-top:solid">
                            '.$rows->membro_nome.' - '.$funcao.'
					</div>

                ');

             }

    $pdf->addPage();

    $pdf->WriteHTML('
    <div style="text-align:center"> <h3>  Avaliação de Proposta de '.$tipoDefesa.' </h3> </div>
    <br>
        PARECER:
    ');

     $pdf->WriteHTML('

        <div style="width: 100%;
                    height:65%;
                    text-align:right;
                    margin-top:4%;
                    margin-bottom:8%;
                    border:double 1px">
        </div>

    ');

     $pdf->WriteHTML('

        <table style="width:100%; text-align:center">
            <tr>
                <td>
                _________________________________________
                </td>
                <td>
                _________________________________________
                </td>
            </tr>
            <tr>
                <td>
                Assinatura do(a) Orientador(a)
                </td>
                <td>
                Assinatura do(a) Discente
                </td>
            </tr>
            <tr>
            <td colspan="2"> <br><br> <b> Obs.: Anexar PROPOSTA a ser apresentada  </b> </td>
            </tr>
        </table>

    ');


    $pdfcode = $pdf->output();
    fwrite($arqPDF,$pdfcode);
    fclose($arqPDF);
}


    public function actionFolhapdf($idDefesa, $aluno_id){

        $arrayMes = array(
            "01" => "Janeiro",
            "02" => "Fevereiro",
            "03" => "Março",
            "04" => "Abril",
            "05" => "Maio",
            "06" => "Junho",
            "07" => "Julho",
            "08" => "Agosto",
            "09" => "Setembro",
            "10" => "Outubro",
            "11" => "Novembro",
            "12" => "Dezembro",
            );

        $model = $this->findModel($idDefesa, $aluno_id);

        $modelAluno = Aluno::find()->select("u.nome as nome, j17_aluno.curso as curso")->where(["j17_aluno.id" => $aluno_id])->innerJoin("j17_user as u","j17_aluno.orientador = u.id")->one();

        if($modelAluno->curso == 1){
            $curso = "Mestrado";

            if($model->tipoDefesa == "Q1"){
                $tipoDefesa = "Exame de Qualificação de Mestrado";
            }
            else{
                $tipoDefesa = "Dissertação de Mestrado";
            }
        }
        else{
            $curso = "Doutorado";

            if($model->tipoDefesa == "Q1"){
                $tipoDefesa = "Exame de Qualificação de Doutorado";
            }
            else  if($model->tipoDefesa == "Q2"){
                $tipoDefesa = "Exame de Qualificação de Doutorado";
            }
            else{
                $tipoDefesa = "Tese de Doutorado";
            }

        }

        $banca = Banca::find()
        ->select("j17_banca_has_membrosbanca.* , j17_banca_has_membrosbanca.funcao ,mb.nome as membro_nome, mb.filiacao as membro_filiacao, mb.*")->leftJoin("j17_membrosbanca as mb","mb.id = j17_banca_has_membrosbanca.membrosbanca_id")
        ->where(["banca_id" => $model->banca_id])->orderBy(['funcao'=>SORT_DESC])->all();

        $bancacompleta = "";

        foreach ($banca as $rows) {
            if($rows->funcao == "P"){
                $funcao = "(Presidente)";
            }
            else{
                $funcao = "";
            }
            $bancacompleta = $bancacompleta . $rows->membro_nome.' - '.$rows->membro_filiacao.' '.$funcao.'<br><br><br><br>';
        }

        $pdf = new mPDF('utf-8','A4','','','15','15','42','30');

        $pdf = $this->cabecalhoRodape($pdf);

             $pdf->WriteHTML('
                <div style="text-align:center"> <h1>  FOLHA DE APROVAÇÃO </h1> </div>
            ');

             $pdf->WriteHTML('
                <div style="text-align:center"> <h3>'.$model->titulo.'</h3> </div>
                <div style="text-align:center"> <h3>'.$model->nome.'</h3> </div>
                <p style = "text-align: justify;">
                    '.$tipoDefesa.' defendida e aprovada pela banca examinadora constituída pelos Professores:
                </p>
            ');

             $mes = date("m",strtotime($model->data));


             $pdf->WriteHTML('
                    <br><br>
                    <div style="margin-left:5%"> '.$bancacompleta.' </div>
                    <div style="text-align:center"> <h3>Manaus, '.date("d", strtotime($model->data)).' de '.$arrayMes[$mes].' de '.date("Y", strtotime($model->data)).'</h3> </div>
            ');


    $pdfcode = $pdf->output();
    fwrite($arqPDF,$pdfcode);
    fclose($arqPDF);
}

   public function actionAgradecimentopdf($idDefesa, $aluno_id, $membrosbanca_id){

        $arrayMes = array(
            "01" => "Janeiro",
            "02" => "Fevereiro",
            "03" => "Março",
            "04" => "Abril",
            "05" => "Maio",
            "06" => "Junho",
            "07" => "Julho",
            "08" => "Agosto",
            "09" => "Setembro",
            "10" => "Outubro",
            "11" => "Novembro",
            "12" => "Dezembro",
            );

        $model = $this->findModel($idDefesa, $aluno_id);

        $modelAluno = Aluno::find()->select("u.nome as nome, j17_aluno.curso as curso")->where(["j17_aluno.id" => $aluno_id])->innerJoin("j17_user as u","j17_aluno.orientador = u.id")->one();



        $banca = Banca::find()
        ->select("j17_banca_has_membrosbanca.* , j17_banca_has_membrosbanca.funcao ,mb.nome as membro_nome, mb.filiacao as membro_filiacao, mb.*")->leftJoin("j17_membrosbanca as mb","mb.id = j17_banca_has_membrosbanca.membrosbanca_id")
        ->where(["membrosbanca_id" => $membrosbanca_id])->one();

        if ($banca->funcao == "P"){
                $participacao = "presidente/orientador(a)";
        }
        else if ($banca->funcao == "I"){
                $participacao = "membro interno";
        }
        else if ($banca->funcao == "E"){
                $participacao = "membro externo";
        }

        if($modelAluno->curso == 1){
            $curso = "Mestrado";

            if($model->tipoDefesa == "Q1"){
                $tipoDefesa = "Exame de Qualificação de Mestrado";
            }
            else{
                $tipoDefesa = "Dissertação de Mestrado";
            }
        }
        else{
            $curso = "Doutorado";

            if($model->tipoDefesa == "Q1"){
                $tipoDefesa = "Exame de Qualificação de Doutorado";
            }
            else  if($model->tipoDefesa == "Q2"){
                $tipoDefesa = "Exame de Qualificação de Doutorado";
            }
            else{
                $tipoDefesa = "Tese de Doutorado";
            }

        }

        $pdf = new mPDF('utf-8','A4','','','15','15','32','30');

        $pdf = $this->cabecalhoRodape($pdf);

             $mes = date("m",strtotime($model->data));

             $pdf->WriteHTML('
                <div style="text-align:center; padding:10% 10%;"> <h2>  AGRADECIMENTO </h2> </div>
            ');

             $pdf->WriteHTML('
                <p style = "text-align: justify; line-height: 3.0; font-family: Times New Roman, Arial, serif; font-size: 120%;">
                    AGRADECEMOS a participação do(a) <b>'.$banca->membro_nome.'</b> como
                    '.$participacao.'(a) da banca examinadora referente à apresentação da Defesa de '.$tipoDefesa.'
                    do(a) aluno(a), abaixo especificado(a), do curso de '.$curso.' em Informática do
                    Programa de Pós-Graduação em Informática da Universidade Federal do Amazonas - realizada no dia
                    '.date("d", strtotime($model->data)).' de '.$arrayMes[$mes].' de '.date("Y", strtotime($model->data)).' às '.$model->horario.'.
                </p>
            ');

             $mes = date("m",strtotime($model->data));


             $pdf->WriteHTML('
                    <br><br><br>
                <div style = "text-align: justify; font-family: Times New Roman, Arial, serif; font-size: 120%;"> Título: '.$model->titulo.'</div>
                <br>
                <div style = "text-align: justify; font-family: Times New Roman, Arial, serif; font-size: 120%;"> Aluno(a): '.$model->nome.'</div>
                    <br><br><br><br>
                    <div style="text-align:center"> <h3>Manaus, '.date("d", strtotime($model->data)).' de '.$arrayMes[$mes].' de '.date("Y", strtotime($model->data)).'</h3> </div>
            ');


    $pdfcode = $pdf->output();
    fwrite($arqPDF,$pdfcode);
    fclose($arqPDF);
}

   public function actionDeclaracaopdf($idDefesa, $aluno_id, $membrosbanca_id){

        $arrayMes = array(
            "01" => "Janeiro",
            "02" => "Fevereiro",
            "03" => "Março",
            "04" => "Abril",
            "05" => "Maio",
            "06" => "Junho",
            "07" => "Julho",
            "08" => "Agosto",
            "09" => "Setembro",
            "10" => "Outubro",
            "11" => "Novembro",
            "12" => "Dezembro",
            );

        $model = $this->findModel($idDefesa, $aluno_id);

        $modelAluno = Aluno::find()->select("u.nome as nome, j17_aluno.curso as curso")->where(["j17_aluno.id" => $aluno_id])->innerJoin("j17_user as u","j17_aluno.orientador = u.id")->one();

        $banca = Banca::find()
        ->select("j17_banca_has_membrosbanca.* , j17_banca_has_membrosbanca.funcao ,mb.nome as membro_nome, mb.filiacao as membro_filiacao, mb.*")->leftJoin("j17_membrosbanca as mb","mb.id = j17_banca_has_membrosbanca.membrosbanca_id")
        ->where(["membrosbanca_id" => $membrosbanca_id])->one();

        if ($banca->funcao == "P"){
                $participacao = "presidente/orientador(a)";
        }
        else if ($banca->funcao == "I"){
                $participacao = "membro interno";
        }
        else if ($banca->funcao == "E"){
                $participacao = "membro externo";
        }

        if($modelAluno->curso == 1){
            $curso = "Mestrado";

            if($model->tipoDefesa == "Q1"){
                $tipoDefesa = "Exame de Qualificação de Mestrado";
            }
            else{
                $tipoDefesa = "Dissertação de Mestrado";
            }
        }
        else{
            $curso = "Doutorado";

            if($model->tipoDefesa == "Q1"){
                $tipoDefesa = "Exame de Qualificação de Doutorado";
            }
            else  if($model->tipoDefesa == "Q2"){
                $tipoDefesa = "Exame de Qualificação de Doutorado";
            }
            else{
                $tipoDefesa = "Tese de Doutorado";
            }

        }


        $pdf = new mPDF('utf-8','A4','','','15','15','32','30');

        $pdf = $this->cabecalhoRodape($pdf);

             $pdf->WriteHTML('

                <div style="text-align:center; padding:10% 10%;"> <h2>  DECLARAÇÃO </h2> </div>
            ');

             $mes = date("m",strtotime($model->data));

             $pdf->WriteHTML('
                <p style = "text-align: justify; line-height: 3.0; font-family: Times New Roman, Arial, serif; font-size: 120%;">
                    DECLARAMOS para os devidos fins que o(a) <b> Prof(a) '.$banca->membro_nome.' </b> fez
                    parte, na qualidade de '.$participacao.', da comissão julgadora da defesa de '.$tipoDefesa.'
                    do(a) aluno(a) '.$model->nome.' , intitulada <b>"'.$model->titulo.'    "</b>, do curso de '.$curso.' em Informática do Programa de Pós-Graduação em Informática da Universidade Federal do Amazonas, realizada no dia
                    '.date("d", strtotime($model->data)).' de '.$arrayMes[$mes].' de '.date("Y", strtotime($model->data)).' às '.$model->horario.'.
                </p>
            ');


             $pdf->WriteHTML('
                    <br><br><br><br>
                    <div style="text-align:center"> <h3>Manaus, '.date("d", strtotime($model->data)).' de '.$arrayMes[$mes].' de '.date("Y", strtotime($model->data)).'</h3> </div>
            ');


    $pdfcode = $pdf->output();
    fwrite($arqPDF,$pdfcode);
    fclose($arqPDF);
}

    public function actionGerar_portaria($idDefesa, $aluno_id) {
        $model = $model = $this->findModel($idDefesa, $aluno_id);

        $model->scenario = 'gerar_portaria';

        if ($model->load(Yii::$app->request->post())) {
            $presidente = '';
            $membros = [];
            $suplentes = [];


            foreach ($model->membrosBanca as $membrobanca) {
                if($membrobanca->funcao == "P"){
                    $presidente = $membrobanca->membrosBanca->nome;
                }
                else if($membrobanca->funcao == "I"){
                    array_push($membros, $membrobanca->membrosBanca->nome);
                }
                else if($membrobanca->funcao == "S"){
                    array_push($suplentes, $membrobanca->membrosBanca->nome);
                }
                else{
                    array_push($membros, $membrobanca->membrosBanca->nome);
                }
            }

            $doc = '
                    <p style="text-align: center;">&nbsp;</p>
                            <table style="width: 608px; margin-left: auto; margin-right: auto;">
                            <tbody>
                            <tr style="height: 144px;">
                            <td style="text-align: center; width: 10px; height: 144px;"><img src="'.getcwd().'/img/republica.png" alt="" width="90" height="90" /></td>
                            <td style="text-align: center; width: 573.5px; height: 144px;">
                            <p><strong>PODER</strong> <strong>EXECUTIVO</strong></p>
                            <p><strong>MINIST&Eacute;RIO</strong> <strong>DA</strong> <strong>EDUCA&Ccedil;&Atilde;O</strong></p>
                            <p style="text-align: center;"><strong>UNIVERSIDADE</strong> <strong>FEDERAL</strong> <strong>DO</strong> <strong>AMAZONAS</strong></p>
                            <p><strong>INSTITUTO</strong> <strong>DE</strong> <strong>COMPUTA&Ccedil;&Atilde;O </strong></p>
                            <p><strong>PROGRAMA DE P&Oacute;S-GRADUA&Ccedil;&Atilde;O EM INFORM&Aacute;TICA</strong></p>
                            </td>
                            <td style="width: 101.5px; height: 144px;"><img style="display: block; margin-left: auto; margin-right: auto;" src="'.getcwd().'/img/ufam.jpg" alt="" width="74" height="95" /></td>
                            </tr>
                            </tbody>
                            </table>
                            <p style="text-align: center;"><strong><u>PORTARIA N&ordm;. '.$model->portariaID.'/'.$model->portariaAno.' &ndash; PPGI</u></strong><strong>&nbsp; &nbsp;&nbsp;</strong></p>
                            <p><strong>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </strong></p>
                            <p><strong>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; O COORDENADOR DO PROGRAMA DE P&Oacute;S-GRADUA&Ccedil;&Atilde;O EM INFORM&Aacute;TICA DA UNIVERSIDADE FEDERAL DO AMAZONAS </strong>usando de suas atribui&ccedil;&otilde;es estatut&aacute;rias e regimentais, e</p>
                            <p><strong>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; CONSIDERANDO</strong> o disposto no artigo 10, &sect; 1&ordm;, do Anexo &agrave; Resolu&ccedil;&atilde;o 033/2014, que regulamenta o exame de qualifica&ccedil;&atilde;o e a defesa de teses e disserta&ccedil;&otilde;es na P&oacute;s-Gradua&ccedil;&atilde;o <em>Stricto Sensu</em> da Universidade Federal do Amazonas,</p>
                            <p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <strong>R E S O L V E :</strong></p>
                            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>DESIGNAR</strong> BANCA EXAMINADORA de defesa de mestrado/doutorado do Programa de P&oacute;s-Gradua&ccedil;&atilde;o em Inform&aacute;tica, composta pelos seguintes membros:</p>
                            <p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <u>Presidente:</u></p>
                            <p><strong>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; '.$presidente.'</strong></p>
                            <p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <u>Membros Titulares:</u></p>
                   ';

            foreach ($membros as $membro) {
                $doc .= '<p><strong>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; '.$membro.'</strong></p>';
            }

            $doc .= '<p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <u>Membros Suplentes:</u></p>';

            foreach ($suplentes as $suplente) {
                $doc .= '<p><strong>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; '.$membro.'</strong></p>';
            }

            $doc .= '
                            <p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</p>
                            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; D&ecirc;-se ci&ecirc;ncia e cumpra-se.</p>
                            <p>&nbsp;</p>
                            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>PROGRAMA DE P&Oacute;S-GRADUA&Ccedil;&Atilde;O EM INFORM&Aacute;TICA</strong> <strong>DA</strong> <strong>UNIVERSIDADE</strong><strong> FEDERAL </strong><strong>DO</strong> <strong>AMAZONAS</strong>, em Manaus, 19 de agosto de 2016.</p>
                            <p>&nbsp;</p>
                            <p style="text-align: center;"><strong>EDUARDO LUZEIRO FEITOSA</strong></p>
                            <p style="text-align: center;">Coordenador do PPGI</p>
                    ';

            $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER,
            //'filename' => $filename,
            // your html content input
            'content' => $doc
            ,
            //'cssInline' => '',
             // set mPDF properties on the fly
            'options' => ['title' => 'ppgi_portaria'.$model->portariaID.'_'.$model->portariaAno],
             // call mPDF methods on the fly
            'methods' => [
                'SetFooter'=>[
                '
                    <p style="text-align: center;">&nbsp;</p>
                    <p style="text-align: center;">Av.&nbsp; Rodrigo Ot&aacute;vio Jord&atilde;o Ramos,6.200 &bull; Coroado &bull;&nbsp; CEP.:69077-000&nbsp; Manaus -AM</p>
                    <p style="text-align: center;">Fone/fax: 3305- 193&nbsp; e-mail: secretaria@icomp.ufam.edu.br</p>
                    <p>&nbsp;</p>
                '
                ],
            ]
        ]);

        return $pdf->render();
        }
        else {
            $model->portariaAno = date('Y');

            return $this->render('_gerarPortaria', [
                'model' => $model,
            ]);
        }
    }


    public function actionAprovar($idDefesa, $aluno_id)
    {
        $model = $this->findModel($idDefesa, $aluno_id);

        $model->conceito = "Aprovado";

        if ($model->save(false)) {

             $this->mensagens('success', 'Aluno', 'Aluno Aprovado com sucesso');

            return $this->redirect(['index']);
        } else {
            $this->mensagens('danger', 'Aluno', 'Não foi possível atribuir conceito para este aluno, tente mais tarde');
            return $this->redirect(['index']);
        }
    }


    public function actionReprovar($idDefesa, $aluno_id)
    {
        $model = $this->findModel($idDefesa, $aluno_id);

        $model->conceito = "Reprovado";

        if ($model->save(false)) {

             $this->mensagens('success', 'Aluno', 'Aluno Reprovado com sucesso');

            return $this->redirect(['index']);
        } else {
            $this->mensagens('danger', 'Aluno', 'Não foi possível atribuir conceito para este aluno, tente mais tarde');
            return $this->redirect(['index']);
        }
    }


    /**
     * Finds the Defesa model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $idDefesa
     * @param integer $aluno_id
     * @return Defesa the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($idDefesa, $aluno_id)
    {
        if (($model = Defesa::findOne(['idDefesa' => $idDefesa, 'aluno_id' => $aluno_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    function enviaNotificacaoPendenciaDefesa($model){

        if ($model->tipoDefesa == 'Q1'){
            $tipoexame = "Qualificação I";
        }
        else if ($model->tipoDefesa == 'Q2'){
            $tipoexame = "Qualificação II";
        }
        else if ($model->tipoDefesa == 'D'){
            $tipoexame = "Dissertação";
        }
        else {
            $tipoexame = "Tese";
        }

        $message = "";

        $nome_aluno = $model->modelAluno->nome;
        $emailOrientador = $model->modelAluno->orientador1->email;
        $emailAluno = $model->modelAluno->email;
        $nomeOrientador = $model->modelAluno->orientador1->nome;
        $emails[] = $emailOrientador;
        $emails[] = $emailAluno;
        //$emails[] = "secppgi@ufam.edu.br";
        //$emails[] = "coordenadorppgi@icomp.ufam.edu.br";


        // subject
        $subject  = "[IComp/UFAM] Pendência em relação à Defesa";

        // message
        $message .= "Informamos que há uma pendência de defesa do aluno abaixo relacionado: \r\n\n";
        $message .= "CANDIDATO: ".$nome_aluno."\r\n";
        $message .= "ORIENTADOR: ".$nomeOrientador."\r\n";
        $message .= "EXAME: ".$tipoexame."\r\n\n";
        $message .= "Atenciosamente,\r\n\n";
        $message .= "Secretaria - ICOMP\r\n"  ;

       try{
           Yii::$app->mailer->compose()
            ->setFrom("secretariappgi@icomp.ufam.edu.br")
            ->setTo($emails)
            ->setSubject($subject)
            ->setTextBody($message)
            ->send();
        }catch(Exception $e){
            $this->mensagens('warning', 'Erro ao enviar Email(s)', 'Ocorreu um Erro ao Enviar as Lembres de Pendência de Defesa.
                Tente novamente ou contate o adminstrador do sistema');
            return false;
        }

        return true;
    }

    private function generatePdfRelatorioBancas($model, $ano=null) {
    	$mMembro = $model;
    	$cont;
    	$mesExt = [1=>'Janeiro',2=>'Fevereiro',3=>'Março',4=>'Abril',5=>'Maio',6=>'Junho',7=>'Julho',8=>'Agosto',9=>'Setembro',10=>'Outubro',11=>'Novembro',12=>'Dezembro'];
    	// setup kartik\mpdf\Pdf component
    	$pdf = new Pdf([
    			// set to use core fonts only
    			'mode' => Pdf::MODE_CORE,
    			// A4 paper format
    			'format' => Pdf::FORMAT_A4,
    			// portrait orientation
    			'orientation' => Pdf::ORIENT_PORTRAIT,
    			// stream to browser inline
    			'destination' => Pdf::DEST_BROWSER,
    			'filename' => "relatorioBancas".( ($ano!=null)?$ano:"" ).explode(" ",$mMembro->nome)[0].explode(" ",$mMembro->nome)[count(explode(" ",$mMembro->nome))-1].".pdf",
    			// your html content input
    			'content' => '
                            <p class="western" align="center">&nbsp;</p>
    						<p class="western" align="center">&nbsp;</p>
                            <p class="western" align="center" style="margin-top:6em; margin-bottom:4em;"><span style="font-family: Arial, sans-serif;"><span style="font-size: large;"><strong>DECLARA&Ccedil;&Atilde;O</strong></span></span></p>


			    			<p class="western" style="padding-left:4em;"><span style="font-size: medium;"><span style="font-family: Arial, sans-serif;">DECLARAMOS para os devidos fins que '.((preg_match ("/^Profa/", $mMembro->nome)?"a":"o")).' <b>'.$mMembro->nome.'</b> participou como membro'.(($ano!=null)?", no ano de ".$ano.",":"").' das seguintes bancas:</span></span></p>
							'.
			    			(($cont[]=count($mMembro->getBancasbytipo("Q1", "Mestrado", $ano))>0 )? "<p class=\"western\" align=\"center\"><b><br></br>Qualificação de Mestrado Q1</b></p>":"")
			    			.
			    			((count($mMembro->getBancasbytipo("Q1", "Mestrado", $ano))>0 )?($mMembro->getBancasbytipoEnum("Q1", "Mestrado", $ano)):"")
			    			.
			    			(($cont[]=count($mMembro->getBancasbytipo("D", "Mestrado", $ano))>0 )? "<p class=\"western\" align=\"center\"><b><br></br>Mestrado</b></p>":"")
			    			.
			    			((count($mMembro->getBancasbytipo("D", "Mestrado", $ano))>0 )?($mMembro->getBancasbytipoEnum("D", "Mestrado", $ano)):"")
			    			.
    						(($cont[]=count($mMembro->getBancasbytipo("Q1", "Doutorado", $ano))>0 )? "<p class=\"western\" align=\"center\"><b><br></br>Qualificação de Doutorado Q1</b></p>":"")
							.
    						((count($mMembro->getBancasbytipo("Q1", "Doutorado", $ano))>0 )?($mMembro->getBancasbytipoEnum("Q1", "Doutorado", $ano)):"")
    						.
    						(($cont[]=count($mMembro->getBancasbytipo("Q2", "Doutorado", $ano))>0 )? "<p class=\"western\" align=\"center\"><b><br></br>Qualificação de Doutorado Q2</b></p>":"")
							.
    						((count($mMembro->getBancasbytipo("Q2", "Doutorado", $ano))>0 )?($mMembro->getBancasbytipoEnum("Q2", "Doutorado", $ano)):"")
			    			.
			    			(($cont[]=count($mMembro->getBancasbytipo("T", "Doutorado", $ano))>0 )? "<p class=\"western\" align=\"center\"><b><br></br>Doutorado</b></p>":"")
			    			.
			    			((count($mMembro->getBancasbytipo("T", "Doutorado", $ano))>0 )?($mMembro->getBancasbytipoEnum("T", "Doutorado", $ano)):"")
    						.
    						((array_sum($cont) == 0)?"<p class=\"western\" align=\"center\"><b><br></br>NADA CONSTA.</b></p>":"")
    						.'
                            <p class="western">&nbsp;</p>
                            <p class="western">&nbsp;</p>
    						<p style="text-align: right;"><span style="font-family: Arial, sans-serif;"><span style="font-size: medium;">Manaus, '.date('d').' de '.$mesExt[intval(date('m'))].' de '.date('Y').'.</span></span></p>
	                         '
    			,
    			//'cssInline' => '',
    			// set mPDF properties on the fly
    			'options' => ['title' => "Declação de Participação em Banca"],
    			// call mPDF methods on the fly
    			'methods' => [
    					'SetFooter'=>[
    							'
                   <p align="center"><span style="font-family: Arial, sans-serif;"><span style="font-size: xx-small;">Av. Gal. Rodrigo Oct&aacute;vio Jord&atilde;o Ramos, 6200 CEP.:69077-000 Manaus &ndash;AM</span></span></p>
                    <p align="center"><span style="font-family: Arial, sans-serif;"><span style="font-size: xx-small;"> Fone: 3305 2809 / 2808 / 1193 e-mail: secretariappgi@icomp.ufam.edu.br</span></span></p>
                		'],
    					'SetHeader'=>['
    					<table style="width: 647px;" cellspacing="1" cellpadding="5">
                            <tbody>
                            <tr>
                            <td style="width: 104px;" valign="top" height="89">
    						<p><span style="font-family: \'Arial Narrow\', sans-serif;"><span style="font-size: large;"><strong><img src='.getcwd().'/img/republica.png alt="" width="96" height="104" /></strong></span></span></p>
                            </td>
                            <td style="width: 542px;" valign="top" align="center" height="89">
                            <p><span style="font-family: \'Arial Narrow\', sans-serif;"><span style="font-size: large;"><strong>PODER EXECUTIVO</strong></span></span></p>
    					<p><span style="font-size: small;"><span style="font-family: \'Arial Narrow\', sans-serif;"><span style="font-size: medium;"><strong>MINIST&Eacute;RIO DA EDUCA&Ccedil;&Atilde;O</strong></span></span></span></p>
    					<p><span style="font-size: small;"><span style="font-family: \'Arial Narrow\', sans-serif;"><span style="font-size: medium;"><strong>UNIVERSIDADE FEDERAL DO AMAZONAS</strong></span></span></span></p>
                            <p><span style="font-size: small;"><span style="font-family: \'Arial Narrow\', sans-serif;"><span style="font-size: medium;"><strong>INSTITUTO DE COMPUTA&Ccedil;&Atilde;O</strong></span></span></span></p>
    						<p class="western">&nbsp;</p>
                            <p><span style="font-family: \'Arial Narrow\', sans-serif;"><span style="font-size: large;"><strong>PROGRAMA DE P&Oacute;S-GRADUA&Ccedil;&Atilde;O EM INFORM&Aacute;TICA</strong></span></span></p>
                            </td>
    						<td style="width: 104px;" valign="top" height="89">
    						<p><span style="font-family: \'Arial Narrow\', sans-serif;"><span style="font-size: large;"><strong><img src='.getcwd().'/img/ufam.jpg alt="" width="82" height="106" /></strong></span></span></p>
    						</td>
                            </tr>
                            </tbody>
                            </table>
    					'],
    			]
    	]);

    	return $pdf->render();
    }

            /* Envio de mensagens para views
       Tipo: success, danger, warning*/
    protected function mensagens($tipo, $titulo, $mensagem){
        Yii::$app->session->setFlash($tipo, [
            'type' => $tipo,
            'icon' => 'home',
            'duration' => 5000,
            'message' => $mensagem,
            'title' => $titulo,
            'positonY' => 'top',
            'positonX' => 'center',
            'showProgressbar' => true,
        ]);
    }
}
