<?php

namespace backend\controllers;

use Yii;
use app\models\Edital;
use common\models\User;
use common\models\Recomendacoes;
use common\models\LinhaPesquisa;
use app\models\Candidato;
use app\models\SearchEdital;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\UploadForm;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;
/**
 * EditalController implements the CRUD actions for Edital model.
 */
class EditalController extends Controller
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
                        'matchCallback' => function ($rule, $action) {
                               return Yii::$app->user->identity->checarAcesso('coordenador') || Yii::$app->user->identity->checarAcesso('secretaria');
                        }
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
     * Lists all Edital models.
     * @return mixed
     */
    public function actionIndex()
    {

        $searchModel = new SearchEdital();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Edital model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {

        $model = $this->findModel($id);

        $model->datainicio = date("d-M-Y", strtotime($model->datainicio));
        $model->datafim = date("d-M-Y", strtotime($model->datafim));

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function linhaNaoHaCandidatos($objPHPExcel,$i,$intervalo){

            $objPHPExcel->mergeCells($intervalo);
            $objPHPExcel->setCellValueByColumnAndRow(0, $i+2, "Não há candidatos");
            $objPHPExcel->getStyle($intervalo)->getFont()->setBold(true);
            $objPHPExcel->getStyle($intervalo)->getFont()->getColor()->setRGB('FF0000');
            $objPHPExcel->getRowDimension($i+2)->setRowHeight(30);
    }

    public function planilhaCandidatoFormatacao($objPHPExcel,$intervalo_tamanho){

    //definindo a altura das linhas

    $qtd_linhas = $objPHPExcel->getActiveSheet()->getHighestRow();

    for ($i=1; $i<=$qtd_linhas; $i++){
        $objPHPExcel->getActiveSheet()->getRowDimension(''.$i.'')->setRowHeight(20);
    }

    // Centralizando o valor nas colunas

        $objPHPExcel->getActiveSheet()->getStyle( $intervalo_tamanho )->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle( $intervalo_tamanho )->getAlignment()->setVertical(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    //auto break line

        $objPHPExcel->getActiveSheet()
            ->getStyle($intervalo_tamanho)
            ->getAlignment()
            ->setWrapText(true);


          $BStyle = array(
              'borders' => array(
                  'allborders' => array(
                      'style' => \PHPExcel_Style_Border::BORDER_THIN
                  )
              )
          );
        $objPHPExcel->getActiveSheet()->getStyle($intervalo_tamanho)->applyFromArray($BStyle);

    // Configurando diferentes larguras para as colunas
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(40);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(18);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(14);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(14);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12);
    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(14);
    $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(40);

    }

    //método responsável por preencher na planilha os títulos: NOME/INSCRIÇÃO/LINHA/NÍVEL/COMPROVANTE/ ETC.

    public function planilhaHeaderCandidato ($objPHPExcel,$arrayColunas,$curso,$intervaloHeader){

        // Criamos as colunas
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue($arrayColunas[0], $curso )
                ->setCellValue($arrayColunas[1], "Nome" )
                ->setCellValue($arrayColunas[2], "Inscrição" )
                ->setCellValue($arrayColunas[3], "Linha" )
                ->setCellValue($arrayColunas[4], "Nível" )
                ->setCellValue($arrayColunas[5], "Comprovante" )
                ->setCellValue($arrayColunas[6], "Curriculum" )
                ->setCellValue($arrayColunas[7], "Histórico" )
                ->setCellValue($arrayColunas[8], "Proposta" )
                ->setCellValue($arrayColunas[9], "Cartas \n(2 no mínimo)" )
                ->setCellValue($arrayColunas[10], "Homologado" )
                ->setCellValue($arrayColunas[11], "Observações");

        //mesclando celulas

        $objPHPExcel->getActiveSheet()->mergeCells($intervaloHeader);

        //colocando os títulos em Negrito

        $objPHPExcel->getActiveSheet()->getStyle($intervaloHeader)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle($arrayColunas[1].":".$arrayColunas[11])->getFont()->setBold(true);

        $objPHPExcel->getActiveSheet()
            ->getStyle($intervaloHeader)
            ->getFill()
            ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('FF808080');

        $objPHPExcel->getActiveSheet()->getStyle($intervaloHeader)->getFont()->getColor()->setRGB('FFFFFF');



    }

    //método responsável por preencher na planilha dados provenientes do banco: NOME/INSCRIÇÃO/LINHA/NÍVEL

    public function planilhaCandidatoPreencherDados($objPHPExcel,$model_candidato,$linhasPesquisas,$arrayCurso,$i,$j){


        for($j=0; $j<count($model_candidato); $j++){
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $i+3, $model_candidato[$j]->nome);

            $objPHPExcel->getActiveSheet()
                ->setCellValueByColumnAndRow(1, $i+3, ($model_candidato[$j]->idEdital.'-'.str_pad($model_candidato[$j]->posicaoEdital, 3, "0", STR_PAD_LEFT)));

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $i+3, $linhasPesquisas[$model_candidato[$j]->idLinhaPesquisa]);

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $i+3, $arrayCurso[$model_candidato[$j]->cursodesejado]);

            $i++;
        }

        if($model_candidato == NULL){
            $intervalo = "A".($i+2).":K".($i+2);
            $this->linhaNaoHaCandidatos($objPHPExcel->getActiveSheet(),$i,$intervalo);

        }

        return $i;
    }

    public function planilhaProvasFormatacao($objWorkSheet,$intervalo_tamanho){


        $objWorkSheet->mergeCells("A1:C1");

        $objWorkSheet->getStyle("A1:C2")->getFont()->setBold(true);


        $objWorkSheet->getStyle( $intervalo_tamanho )->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objWorkSheet->getStyle( $intervalo_tamanho )->getAlignment()->setVertical(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


        $objWorkSheet->getColumnDimension('A')->setWidth(40);
        $objWorkSheet->getColumnDimension('B')->setWidth(15);
        $objWorkSheet->getColumnDimension('C')->setWidth(18);

          $BStyle = array(
              'borders' => array(
                  'allborders' => array(
                      'style' => \PHPExcel_Style_Border::BORDER_THIN
                  )
              )
          );
        $objWorkSheet->getStyle($intervalo_tamanho)->applyFromArray($BStyle);
    }

    public function planilhaProvas($objWorkSheet,$linhaAtual,$ultimaLinha){

        //definindo altura da linha do header
        $objWorkSheet->getRowDimension(2)->setRowHeight(40);


        $objWorkSheet
                ->setCellValue("A1", "Mestrado" )
                ->setCellValue("A2", "Nome" )
                ->setCellValue("B2", "Inscrição" )
                ->setCellValue("C2", "Nota Final" );

        //Write cells
        for ($i=0; $i< $linhaAtual; $i++){

            $objWorkSheet
                ->setCellValue('A'.($i+3), "='Candidato'!A".($i+3))
                ->setCellValue('B'.($i+3), "='Candidato'!B".($i+3));
        }

        if($linhaAtual == 0){
            $intervalo = "A".($i+2).":C".($i+2);
            $this->linhaNaoHaCandidatos($objWorkSheet,$linhaAtual, $intervalo);
        }


        $i = $i+4;


        $objWorkSheet
                ->setCellValue("A".($i-1), "Doutorado" )
                ->setCellValue("A".($i), "Nome" )
                ->setCellValue("B".($i), "Inscrição" )
                ->setCellValue("C".($i), "Nota Final" );


        $objWorkSheet->getStyle("A".($i-1).":C".$i)->getFont()->setBold(true);

        $objWorkSheet->mergeCells("A".($i-1).":C".($i-1));

        //definindo a cor de fundo e cor da fonte do título do header: mestrado

        $objWorkSheet
            ->getStyle("A1:C1")
            ->getFill()
            ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('FF808080');

        $objWorkSheet->getStyle("A1:C1")->getFont()->getColor()->setRGB('FFFFFF');

        //definindo a cor de fundo e cor da fonte do título do header: doutorado


        $objWorkSheet
            ->getStyle("A".($i-1).":C".($i-1))
            ->getFill()
            ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('FF808080');

        $objWorkSheet->getStyle("A".($i-1).":C".($i-1))->getFont()->getColor()->setRGB('FFFFFF');

        //definindo a linha do segundo header!
        $linhaSegundoHeader = $i;


        if($i+1 == $ultimaLinha+3){
            $intervalo = "A".($i).":C".($i+1);
            $this->linhaNaoHaCandidatos($objWorkSheet,$ultimaLinha, $intervalo);
        }

        //Write cells
        for ($i=$i+1; $i< $ultimaLinha+3; $i++){

            $objWorkSheet
                ->setCellValue('A'.($i), "='Candidato'!A".($i))
                ->setCellValue('B'.($i), "='Candidato'!B".($i));
        }

        //definindo tamanho das linhas
        $qtd_linhas = $objWorkSheet->getHighestRow();
        for ($k=1; $k<=$qtd_linhas; $k++){
            $objWorkSheet->getRowDimension(''.$k.'')->setRowHeight(20);

        }

        //definindo altura da linha do header
        $objWorkSheet->getRowDimension(2)->setRowHeight(40);
        $objWorkSheet->getRowDimension($linhaSegundoHeader)->setRowHeight(40);

        // Rename sheet
        $objWorkSheet->setTitle("Provas");

    }

    public function planilhaPropostasFormatacao($planilhaPropostas,$intervalo_tamanho){

        //define a página como formato em RETRATO

        $planilhaPropostas->getPageSetup()
            ->setOrientation(\PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

        $planilhaPropostas->mergeCells("A1:E1");

        $planilhaPropostas->getStyle("A1:E2")->getFont()->setBold(true);


        //definindo altura da linha do header
        $planilhaPropostas->getRowDimension(1)->setRowHeight(20);
        $planilhaPropostas->getRowDimension(2)->setRowHeight(40);

        $planilhaPropostas->getStyle( $intervalo_tamanho )->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $planilhaPropostas->getStyle( $intervalo_tamanho )->getAlignment()->setVertical(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


        $planilhaPropostas->getColumnDimension('A')->setWidth(40);
        $planilhaPropostas->getColumnDimension('B')->setWidth(13);
        $planilhaPropostas->getColumnDimension('C')->setWidth(13);
        $planilhaPropostas->getColumnDimension('D')->setWidth(13);
        $planilhaPropostas->getColumnDimension('E')->setWidth(13);

          $BStyle = array(
              'borders' => array(
                  'allborders' => array(
                      'style' => \PHPExcel_Style_Border::BORDER_THIN
                  )
              )
          );
        $planilhaPropostas->getStyle($intervalo_tamanho)->applyFromArray($BStyle);

    }

    public function planilhaPropostas($planilhaPropostas,$linhaAtual,$ultimaLinha){

        $planilhaPropostas
                ->setCellValue("A1", "Mestrado" )
                ->setCellValue("A2", "Nome" )
                ->setCellValue("B2", "Avaliador 1" )
                ->setCellValue("C2", "Avaliador 2" )
                ->setCellValue("D2", "Avaliador 3" )
                ->setCellValue("E2", "Média Final" );


        //Write cells
        for ($i=0; $i< $linhaAtual; $i++){

            $planilhaPropostas
                ->setCellValue('A'.($i+3), "='Candidato'!A".($i+3))
                ->setCellValue('E'.($i+3), '=AVERAGE(B'.($i+3).':D'.($i+3).')');
        }

        if($linhaAtual == 0){
            $intervalo = "A".($i+2).":E".($i+2);
            $this->linhaNaoHaCandidatos($planilhaPropostas,$linhaAtual, $intervalo);
        }


        $i = $i+4;

        $planilhaPropostas
                ->setCellValue("A".($i-1), "Doutorado" )
                ->setCellValue("A".($i), "Nome" )
                ->setCellValue("B".($i), "Avaliador 1" )
                ->setCellValue("C".($i), "Avaliador 2" )
                ->setCellValue("D".($i), "Avaliador 3" )
                ->setCellValue("E".($i), "Média Final" );


        $planilhaPropostas->getStyle("A".($i-1).":E".$i)->getFont()->setBold(true);

        $planilhaPropostas->mergeCells("A".($i-1).":E".($i-1));

        //definindo a cor de fundo e cor da fonte do título do header: mestrado

        $planilhaPropostas
            ->getStyle("A1:C1")
            ->getFill()
            ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('FF808080');

        $planilhaPropostas->getStyle("A1:C1")->getFont()->getColor()->setRGB('FFFFFF');

        //definindo a cor de fundo e cor da fonte do título do header: doutorado


        $planilhaPropostas
            ->getStyle("A".($i-1).":C".($i-1))
            ->getFill()
            ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('FF808080');

        $planilhaPropostas->getStyle("A".($i-1).":C".($i-1))->getFont()->getColor()->setRGB('FFFFFF');

        $linhaSegundoHeader = $i;


        if($i+1 == $ultimaLinha+3){
            $intervalo = "A".($i).":E".($i+1);
            $this->linhaNaoHaCandidatos($planilhaPropostas,$ultimaLinha, $intervalo);
        }

        //Write cells
        for ($i=$i+1; $i< $ultimaLinha+3; $i++){

            $planilhaPropostas
                ->setCellValue('A'.($i), "='Candidato'!A".($i))
                ->setCellValue('E'.($i), '=AVERAGE(B'.($i).':D'.($i).')');
        }

        //definindo tamanho das linhas
        $qtd_linhas = $planilhaPropostas->getHighestRow();
        for ($k=1; $k<=$qtd_linhas; $k++){
            $planilhaPropostas->getRowDimension(''.$k.'')->setRowHeight(20);
        }

        //definindo altura da linha do header

        $planilhaPropostas->getRowDimension($linhaSegundoHeader)->setRowHeight(40);

        $planilhaPropostas->setTitle("Propostas");

    }

    public function planilhaTitulosFormatacao($planilhaTitulos,$intervalo_tamanho){


        $planilhaTitulos->getStyle("A1:J2")->getFont()->setBold(true);


        $planilhaTitulos->getRowDimension(3)->setRowHeight(40);

        //definindo altura da linha do header
        $planilhaTitulos->getRowDimension(1)->setRowHeight(20);
        $planilhaTitulos->getRowDimension(2)->setRowHeight(20);

        $planilhaTitulos->getStyle( $intervalo_tamanho )->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $planilhaTitulos->getStyle( $intervalo_tamanho )->getAlignment()->setVertical(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        //auto break line

        $planilhaTitulos
            ->getStyle( $intervalo_tamanho )
            ->getAlignment()
            ->setWrapText(true);


        $planilhaTitulos->getColumnDimension('A')->setWidth(40);
        $planilhaTitulos->getColumnDimension('B')->setWidth(15);
        $planilhaTitulos->getColumnDimension('C')->setWidth(18);


          $BStyle = array(
              'borders' => array(
                  'allborders' => array(
                      'style' => \PHPExcel_Style_Border::BORDER_THIN
                  )
              )
          );
        $planilhaTitulos->getStyle($intervalo_tamanho)->applyFromArray($BStyle);

    }

    public function planilhaTitulos($planilhaTitulos,$linhaAtual,$ultimaLinha){


        $planilhaTitulos
                ->setCellValue("A1", "Mestrado" )
                ->setCellValue("A2", "Nome" )
                ->setCellValue("B2", "Atividades Curriculares e Extracurriculares (30 pontos)" )
                ->setCellValue("F2", "Publicações (70 pontos)" )
                ->setCellValue("B3", "Mestrado" )
                ->setCellValue("C3", "Estágio, Extensão e monitoria" )
                ->setCellValue("D3", "Docência" )
                ->setCellValue("E3", "IC, IT, ID" )
                ->setCellValue("F3", "A" )
                ->setCellValue("G3", "B1 a B2" )
                ->setCellValue("H3", "B3 a B5" )
                ->setCellValue("I2", "Nota" )
                ->setCellValue("J2", "NAC" );

            $planilhaTitulos->mergeCells("A1:J1");
            $planilhaTitulos->mergeCells("A2:A3");

            $planilhaTitulos->mergeCells("B2:E2");
            $planilhaTitulos->mergeCells("F2:H2");

            $planilhaTitulos->mergeCells("I2:I3");
            $planilhaTitulos->mergeCells("J2:J3");

        //Write cells
        for ($i=0; $i< $linhaAtual; $i++){

            $soma1 = "SUM(".'B'.($i+4).':E'.($i+4).")";
            $soma2 = "SUM(".'F'.($i+4).':H'.($i+4).")";

            $planilhaTitulos
                ->setCellValue('A'.($i+4), "='Candidato'!A".($i+3))
                ->setCellValue('J'.($i+4), '=5+((5 * I'.($i+4).')/100)')
                ->setCellValue('I'.($i+4), '=IF('.$soma1.'>30,30,'.$soma1.')'.' + IF('.$soma2.'>70,70,'.$soma2.')');

        }

        if($linhaAtual == 0){
            $intervalo = "A".($i+2).":J".($i+3);
            $this->linhaNaoHaCandidatos($planilhaTitulos,$linhaAtual, $intervalo);
        }

        $i = $i+5;

        $planilhaTitulos->mergeCells("I".($i).":"."I".($i+1));
        $planilhaTitulos->mergeCells("J".($i).":"."J".($i+1));


        $planilhaTitulos
                ->setCellValue("A".($i-1), "Doutorado" )
                ->setCellValue("A".($i), "Nome" )
                ->setCellValue("B".($i), "Atividades Curriculares e Extracurriculares (30 pontos)" )
                ->setCellValue("F".($i), "Publicações (70 pontos)" )
                ->setCellValue("B".($i+1), "Mestrado" )
                ->setCellValue("C".($i+1), "Estágio, Extensão e monitoria" )
                ->setCellValue("D".($i+1), "Docência" )
                ->setCellValue("E".($i+1), "IC, IT, ID" )
                ->setCellValue("F".($i+1), "A" )
                ->setCellValue("G".($i+1), "B1 a B2" )
                ->setCellValue("H".($i+1), "B3 a B5" )
                ->setCellValue("I".($i), "Nota" )
                ->setCellValue("J".($i), "NAC" );

        $planilhaTitulos->getStyle("A".($i-1).":J".$i)->getFont()->setBold(true);

        $planilhaTitulos->mergeCells("A".($i-1).":J".($i-1));

        $planilhaTitulos->mergeCells("A".($i).":A".($i+1));

        $planilhaTitulos->mergeCells("B".($i).":E".($i));

        $planilhaTitulos->mergeCells("F".($i).":H".($i));

        //definindo qual linha da planilha se encontra o proximo header

        $linhaSegundoHeader = $i+1;

        //definindo a cor de fundo e cor da fonte do título do header: mestrado

        $planilhaTitulos
            ->getStyle("A1:C1")
            ->getFill()
            ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('FF808080');

        $planilhaTitulos->getStyle("A1:C1")->getFont()->getColor()->setRGB('FFFFFF');

        //definindo a cor de fundo e cor da fonte do título do header: doutorado



        $planilhaTitulos
            ->getStyle("A".($i-1).":C".($i-1))
            ->getFill()
            ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('FF808080');

        $planilhaTitulos->getStyle("A".($i-1).":C".($i-1))->getFont()->getColor()->setRGB('FFFFFF');


        if($i == $ultimaLinha+3){
            $intervalo = "A".($i).":J".($i+1);
            $this->linhaNaoHaCandidatos($planilhaTitulos,$ultimaLinha+1, $intervalo);
        }

        //Write cells
        for ($i; $i< $ultimaLinha+3; $i++){

            $soma1 = "SUM(".'B'.($i+2).':E'.($i+2).")";
            $soma2 = "SUM(".'F'.($i+2).':H'.($i+2).")";

            $planilhaTitulos
                ->setCellValue('A'.($i+2), "='Candidato'!A".($i))
                ->setCellValue('I'.($i+2), '=IF('.$soma1.'>30,30,'.$soma1.')'.' + IF('.$soma2.'>70,70,'.$soma2.')')
                ->setCellValue('J'.($i+2), '=5+((5 * I'.($i+2).')/100)');
        }

        $qtd_linhas = $planilhaTitulos->getHighestRow();


        for ($k=1; $k<=$qtd_linhas; $k++){
            $planilhaTitulos->getRowDimension(''.$k.'')->setRowHeight(20);
        }

        //definindo altura da linha do header
        $planilhaTitulos->getRowDimension($linhaSegundoHeader)->setRowHeight(40);


        // Rename sheet
        $planilhaTitulos->setTitle("Títulos");

    }

    public function planilhaCartasFormatacao($planilhaCartas,$intervalo_tamanho){

        $planilhaCartas->mergeCells("B1:L1");
        $planilhaCartas->mergeCells("M1:W1");

        $planilhaCartas->getColumnDimension('A')->setWidth(40);
        $planilhaCartas->getColumnDimension('W')->setWidth(20);


        $planilhaCartas->getStyle("A1:X2")->getFont()->setBold(true);

        $planilhaCartas->getStyle( $intervalo_tamanho )->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $planilhaCartas->getStyle( $intervalo_tamanho )->getAlignment()->setVertical(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


          $BStyle = array(
              'borders' => array(
                  'allborders' => array(
                      'style' => \PHPExcel_Style_Border::BORDER_THIN
                  )
              )
          );
        $planilhaCartas->getStyle($intervalo_tamanho)->applyFromArray($BStyle);

    }

    public function inserirAtributosCartas($planilhaCartas,$dominio,$aprendizado,$assiduidade,$relacionamento,$iniciativa,$expressao,$RA,$classificacao,$n){

                        $planilhaCartas
                        ->setCellValue('B'.($n+3), $dominio[0])
                        ->setCellValue('L'.($n+3), $dominio[1] )
                        ->setCellValue('C'.($n+3), $aprendizado[0] )
                        ->setCellValue('M'.($n+3), $aprendizado[1] )
                        ->setCellValue('D'.($n+3), $assiduidade[0] )
                        ->setCellValue('N'.($n+3), $assiduidade[1] )
                        ->setCellValue('E'.($n+3), $relacionamento[0] )
                        ->setCellValue('O'.($n+3), $relacionamento[1] )
                        ->setCellValue('F'.($n+3), $iniciativa[0] )
                        ->setCellValue('P'.($n+3), $iniciativa[1] )
                        ->setCellValue('G'.($n+3), $expressao[0] )
                        ->setCellValue('Q'.($n+3), $expressao[1] )

                        ->setCellValue('I'.($n+3), $RA[0] )
                        ->setCellValue('S'.($n+3), $RA[1] )

                        ->setCellValue('J'.($n+3), $classificacao[0] )
                        ->setCellValue('T'.($n+3), $classificacao[1] );

    }



    public function obterAtributosCartas($planilhaCartas,$model_recomendacoes,$model_candidato,$qtd_linhas,$titulo){

        for($n=0; $n<count($model_candidato); $n++){

            $cont = 0;

            for ($p=0; $p<count($model_recomendacoes)  ;$p++){

                    if($model_candidato[$n]->id == $model_recomendacoes[$p]->idCandidato){

                                    $dominio[$cont] = $model_recomendacoes[$p]->dominio;
                                    $aprendizado[$cont] = $model_recomendacoes[$p]->aprendizado;
                                    $assiduidade[$cont] = $model_recomendacoes[$p]->assiduidade;
                                    $relacionamento[$cont] = $model_recomendacoes[$p]->relacionamento;
                                    $iniciativa[$cont] = $model_recomendacoes[$p]->iniciativa;
                                    $expressao[$cont] = $model_recomendacoes[$p]->expressao;

                                    $classificacao[$cont] = $model_recomendacoes[$p]->classificacao; // PI

                                    if($model_recomendacoes[$p]->orientador == 1){
                                        $RA[$cont] = 1;
                                    }
                                    else if ($model_recomendacoes[$p]->professor == 1 && $model_recomendacoes[$p]->titulacao == 2){

                                        $RA[$cont] = 0.85;
                                    }
                                    else if ($model_recomendacoes[$p]->professor == 1 && $model_recomendacoes[$p]->titulacao == 1){

                                        $RA[$cont] = 0.70;
                                    }
                                    else if ($model_recomendacoes[$p]->coordenador == 1 && $model_recomendacoes[$p]->titulacao == 2){

                                        $RA[$cont] = 0.70;
                                    }
                                    else {
                                        $RA[$cont] = 0.50;
                                    }

                                    $AC[$cont] = $dominio[$cont] + $aprendizado[$cont] + $assiduidade[$cont] + $relacionamento[$cont] +
                                                 $iniciativa[$cont] + $expressao[$cont];

                                    $NICR[$cont] = (($AC[$cont] * $RA[$cont])/10) + $classificacao[$cont];


                            $cont++;
                    }

            }

                array_multisort($NICR, SORT_DESC, $dominio,$aprendizado,$assiduidade,$relacionamento,$iniciativa,$expressao,$classificacao,$RA,$AC);


                if($titulo == 1){
                    $this->inserirAtributosCartas(
                        $planilhaCartas,$dominio,$aprendizado,$assiduidade,$relacionamento,$iniciativa,$expressao,$RA,$classificacao,$n
                    );
                }
                else{
                    $this->inserirAtributosCartas(
                        $planilhaCartas,$dominio,$aprendizado,$assiduidade,$relacionamento,$iniciativa,$expressao,$RA,$classificacao,$n+$qtd_linhas
                    );
                }

        }



    }


    public function planilhaCartas($planilhaCartas,$model_recomendacoes,$model_candidato_mestrado,
        $model_candidato_doutorado,$linhaAtual,$ultimaLinha){

        $planilhaCartas
                ->setCellValue("A1", "Mestrado" )
                ->setCellValue("B1", "Avaliador 1" )
                ->setCellValue("M1", "Avaliador 2" )
                ->setCellValue("A2", "Candidato" )
                ->setCellValue("B2", "Dom" )
                ->setCellValue("C2", "Facil" )
                ->setCellValue("D2", "Assid" )
                ->setCellValue("E2", "Relac" )
                ->setCellValue("F2", "Iniciativa" )
                ->setCellValue("G2", "Escrita" )

                ->setCellValue("H2", "AC" )
                ->setCellValue("I2", "RA" )
                ->setCellValue("J2", "PI" )
                ->setCellValue("K2", "NICR" )
                ->setCellValue("L2", "Dom" )
                ->setCellValue("M2", "Facil" )
                ->setCellValue("N2", "Assid" )
                ->setCellValue("O2", "Relac" )
                ->setCellValue("P2", "Iniciativa" )
                ->setCellValue("Q2", "Escrita" )
                ->setCellValue("R2", "AC" )
                ->setCellValue("S2", "RA" )
                ->setCellValue("T2", "PI" )
                ->setCellValue("U2", "NICR" )
                ->setCellValue("V2", "Total" )
                ->setCellValue("W2", "Nota Ponderada" );

        //Write cells
        for ($i=0; $i< $linhaAtual; $i++){

            $formulaAC = "=SUM(".'B'.($i+3).':G'.($i+3).")";
            $formulaAC2 = "=SUM(".'L'.($i+3).':Q'.($i+3).")";
            $formulaNICR = "=((H".($i+3)." * I".($i+3).")/10)+J".($i+3);
            $formulaNICR2 = "=((R".($i+3)." * S".($i+3).")/10)+T".($i+3);
            $formulaTotal = "=SUM(".'K'.($i+3).',U'.($i+3).")";

            $planilhaCartas
                ->setCellValue('A'.($i+3), "='Candidato'!A".($i+3))
                ->setCellValue('H'.($i+3), $formulaAC)
                ->setCellValue('K'.($i+3), $formulaNICR)
                ->setCellValue('R'.($i+3), $formulaAC2)
                ->setCellValue('U'.($i+3), $formulaNICR2)
                ->setCellValue('V'.($i+3), $formulaTotal);
        }


        if($linhaAtual == 0){
            $intervalo = "A".($i+2).":W".($i+2);
            $this->linhaNaoHaCandidatos($planilhaCartas,$linhaAtual, $intervalo);
        }


        $i = $i+4;


        $planilhaCartas
                ->setCellValue("A".($i-1), "Doutorado" )
                ->setCellValue("B".($i-1), "Avaliador 1" )
                ->setCellValue("M".($i-1), "Avaliador 2" )
                ->setCellValue("A".($i), "Candidato" )
                ->setCellValue("B".($i), "Dom" )
                ->setCellValue("C".($i), "Facil" )
                ->setCellValue("D".($i), "Assid" )
                ->setCellValue("E".($i), "Relac" )
                ->setCellValue("F".($i), "Iniciativa" )
                ->setCellValue("G".($i), "Escrita" )

                ->setCellValue("H".($i), "AC" )
                ->setCellValue("I".($i), "RA" )
                ->setCellValue("J".($i), "PI" )
                ->setCellValue("K".($i), "NICR" )
                ->setCellValue("L".($i), "Dom" )
                ->setCellValue("M".($i), "Facil" )
                ->setCellValue("N".($i), "Assid" )
                ->setCellValue("O".($i), "Relac" )
                ->setCellValue("P".($i), "Iniciativa" )
                ->setCellValue("Q".($i), "Escrita" )

                ->setCellValue("R".($i), "AC" )
                ->setCellValue("S".($i), "RA" )
                ->setCellValue("T".($i), "PI" )
                ->setCellValue("U".($i), "NICR" )
                ->setCellValue("V".($i), "Total" )
                ->setCellValue("W".($i), "Nota Ponderada" );


        $planilhaCartas->getStyle("A".($i-1).":X".$i)->getFont()->setBold(true);

        $planilhaCartas->mergeCells("B".($i-1).":L".($i-1));
        $planilhaCartas->mergeCells("M".($i-1).":W".($i-1));

        //definindo linha do segundo header
        $linhaSegundoHeader = $i;


        //definindo a cor de fundo e cor da fonte do título do header: mestrado

        $planilhaCartas
            ->getStyle("A1")
            ->getFill()
            ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('FF808080');

        $planilhaCartas->getStyle("A1")->getFont()->getColor()->setRGB('FFFFFF');

        $planilhaCartas
            ->getStyle("B1:W1")
            ->getFill()
            ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('DEB887');

        $planilhaCartas->getStyle("A1")->getFont()->getColor()->setRGB('FFFFFF');

        //definindo a cor de fundo e cor da fonte do título do header: doutorado


        $planilhaCartas
            ->getStyle("A".($i-1))
            ->getFill()
            ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('FF808080');

        $planilhaCartas->getStyle("A".($i-1))->getFont()->getColor()->setRGB('FFFFFF');

        $planilhaCartas
            ->getStyle("B".($i-1).":W".($i-1))
            ->getFill()
            ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('DEB887');

        $planilhaCartas->getStyle("B".($i-1).":W".($i-1))->getFont()->getColor()->setRGB('000000');


        $qtd_linhas = $planilhaCartas->getHighestRow() - 2;


        if( ($i+1) == $ultimaLinha+3){
            $intervalo = "A".($i).":W".($i);
            $this->linhaNaoHaCandidatos($planilhaCartas,$ultimaLinha, $intervalo);
        }

        //Write cells
        for ($i=0; $i< $qtd_linhas-2; $i++){

        $formulaNotaPonderada = "=(V".($i+3)."/MAX(".'$V$'.(3).':$V$'.($qtd_linhas)."))*10";

            $planilhaCartas->setCellValue('W'.($i+3), $formulaNotaPonderada);
        }


       //Write cells
        for ($i=$qtd_linhas+3; $i< $ultimaLinha+3; $i++){

            $formulaAC = "=SUM(".'B'.$i.':G'.$i.")";
            $formulaAC2 = "=SUM(".'L'.$i.':Q'.$i.")";
            $formulaNICR = "=((H".($i)." * I".($i).")/10)+J".($i);
            $formulaNICR2 = "=((R".($i)." * S".($i).")/10)+T".($i);
            $formulaTotal = "=SUM(".'K'.$i.',U'.$i.")";

            $planilhaCartas
                ->setCellValue('A'.($i), "='Candidato'!A".($i))
                ->setCellValue('H'.($i), $formulaAC)
                ->setCellValue('K'.($i), $formulaNICR)
                ->setCellValue('R'.($i), $formulaAC2)
                ->setCellValue('U'.($i), $formulaNICR2)
                ->setCellValue('V'.($i), $formulaTotal);
        }


        for ($i=$qtd_linhas; $i< $ultimaLinha; $i++){

        $formulaNotaPonderada = "=(V".($i+3)."/MAX(".'$V$'.($qtd_linhas+3).':$V$'.($ultimaLinha+2)."))*10";

            $planilhaCartas->setCellValue('W'.($i+3), $formulaNotaPonderada);
        }


        $this->obterAtributosCartas($planilhaCartas,$model_recomendacoes,$model_candidato_mestrado,0,1); //1 é mestrado
        $this->obterAtributosCartas($planilhaCartas,$model_recomendacoes,$model_candidato_doutorado,$qtd_linhas,2); //2 é doutorado


        $qtd_linhas = $planilhaCartas->getHighestRow();

        for ($k=1; $k<=$qtd_linhas; $k++){
            $planilhaCartas->getRowDimension(''.$k.'')->setRowHeight(20);
        }


        $planilhaCartas->getRowDimension($linhaSegundoHeader)->setRowHeight(40);
        $planilhaCartas->getRowDimension(2)->setRowHeight(40);

        $planilhaCartas->setTitle("Cartas");


    }

    public function planilhaMediaFinalFormatacao($planilhaMediaFinal,$intervalo_tamanho){


        //define a página como formato em RETRATO

        $planilhaMediaFinal->getPageSetup()
            ->setOrientation(\PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

        //MESCLAGEM DE CELULAS
        $planilhaMediaFinal->mergeCells("A1:E1");

        //INSERE NEGRITO
        $planilhaMediaFinal->getStyle("A1:E2")->getFont()->setBold(true);

        //definindo altura da linha do header
        $planilhaMediaFinal->getRowDimension(1)->setRowHeight(20);
        $planilhaMediaFinal->getRowDimension(2)->setRowHeight(40);

        //DEFINE ALINHAMENTO CENTRAL
        $planilhaMediaFinal->getStyle( "A1:K999" )->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $planilhaMediaFinal->getStyle( "A1:K999" )->getAlignment()->setVertical(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        //DEFINE O TAMANHO DAS LARGURAS DAS COLUNAS
        $planilhaMediaFinal->getColumnDimension('A')->setWidth(40);
        $planilhaMediaFinal->getColumnDimension('B')->setWidth(10);
        $planilhaMediaFinal->getColumnDimension('C')->setWidth(10);
        $planilhaMediaFinal->getColumnDimension('D')->setWidth(15);
        $planilhaMediaFinal->getColumnDimension('E')->setWidth(10);


          $BStyle = array(
              'borders' => array(
                  'allborders' => array(
                      'style' => \PHPExcel_Style_Border::BORDER_THIN
                  )
              )
          );
        $planilhaMediaFinal->getStyle($intervalo_tamanho)->applyFromArray($BStyle);

    }

    public function planilhaMediaFinal($planilhaMediaFinal,$linhaAtual,$ultimaLinha,$possuiCarta){

        if ($possuiCarta == true){
            $labelTitulosEcartas = "Títulos + Carta";
        }
        else{
            $labelTitulosEcartas = "Títulos";
        }


        //INSERE VALORES NAS COLUNAS
        $planilhaMediaFinal
                ->setCellValue("A1", "Mestrado" )
                ->setCellValue("A2", "Candidato" )
                ->setCellValue("B2", "Prova" )
                ->setCellValue("C2", "Proposta" )
                ->setCellValue("D2", $labelTitulosEcartas )
                ->setCellValue("E2", "Média" );


        //ESCREVE VALORES NAS CÉLULAS
        for ($i=0; $i< $linhaAtual; $i++){

            if($possuiCarta == true){
                $formulaTitulosEcartas = "=AVERAGE('Títulos'!J".($i+4).",Cartas!W".($i+3).")";

            }
            else{
                $formulaTitulosEcartas = "=('Títulos'!J".($i+4).")";
            }

            $planilhaMediaFinal
                ->setCellValue('A'.($i+3), "='Candidato'!A".($i+3))
                ->setCellValue('B'.($i+3), "='Provas'!C".($i+3))
                ->setCellValue('C'.($i+3), "='Propostas'!E".($i+3))
                ->setCellValue('D'.($i+3), $formulaTitulosEcartas)
                ->setCellValue('E'.($i+3), "=AVERAGE(B".($i+3).",D".($i+3).")");
          }


        if($linhaAtual == 0){
            $intervalo = "A".($i+2).":E".($i+2);
            $this->linhaNaoHaCandidatos($planilhaMediaFinal,$linhaAtual, $intervalo);
        }


        $i = $i+4;

        //DEFININDO VALORES PARA AS COLUNAS
        $planilhaMediaFinal
                ->setCellValue("A".($i-1), "Doutorado" )
                ->setCellValue("A".($i), "Candidato" )
                ->setCellValue("B".($i), "Prova" )
                ->setCellValue("C".($i), "Proposta" )
                ->setCellValue("D".($i), $labelTitulosEcartas )
                ->setCellValue("E".($i), "Média" );

        //COLOCANDO NEGRITO
        $planilhaMediaFinal->getStyle("A".($i-1).":E".$i)->getFont()->setBold(true);

        //REALIZANDO MESCLAGEM
        $planilhaMediaFinal->mergeCells("A".($i-1).":E".($i-1));

        //definindo altura da linha do header

        $planilhaMediaFinal->getRowDimension($i-1)->setRowHeight(20);
        $planilhaMediaFinal->getRowDimension($i)->setRowHeight(40);

        //definindo a cor de fundo e cor da fonte do título do header: mestrado

        $planilhaMediaFinal
            ->getStyle("A1:C1")
            ->getFill()
            ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('FF808080');

        $planilhaMediaFinal->getStyle("A1:C1")->getFont()->getColor()->setRGB('FFFFFF');

        //definindo a cor de fundo e cor da fonte do título do header: doutorado


        $planilhaMediaFinal
            ->getStyle("A".($i-1).":C".($i-1))
            ->getFill()
            ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('FF808080');

        $planilhaMediaFinal->getStyle("A".($i-1).":C".($i-1))->getFont()->getColor()->setRGB('FFFFFF');

        $linhaSegundoHeader = $i;

        if($i+1 == $ultimaLinha+3){
            $intervalo = "A".($i).":E".($i);
            $this->linhaNaoHaCandidatos($planilhaMediaFinal,$ultimaLinha, $intervalo);
        }


        //INSERINDO VALORES NAS CÉLULAS
        for ($i=$i+1; $i< $ultimaLinha+3; $i++){


            if($possuiCarta == true){
                $formulaTitulosEcartas = "=AVERAGE('Títulos'!J".($i+2).",Cartas!W".($i).")";

            }
            else{
                $formulaTitulosEcartas = "=('Títulos'!J".($i+2).")";
            }

            $planilhaMediaFinal
                ->setCellValue('A'.($i), "='Candidato'!A".($i))
                ->setCellValue('B'.($i), "='Provas'!C".($i))
                ->setCellValue('C'.($i), "='Propostas'!E".($i))
                ->setCellValue('D'.($i), $formulaTitulosEcartas)
                ->setCellValue('E'.($i), "=AVERAGE(B".($i).",D".($i).")");



        }

        //DEFINE A ALTURAS DAS CÉLULAS

        $qtd_linhas = $planilhaMediaFinal->getHighestRow();

       for ($k=1; $k<=$qtd_linhas; $k++){
            $planilhaMediaFinal->getRowDimension(''.$k.'')->setRowHeight(20);
        }

        $planilhaMediaFinal->getRowDimension($linhaSegundoHeader)->setRowHeight(40);
        $planilhaMediaFinal->getRowDimension(2)->setRowHeight(40);

        //repetir cabeçalho
        $planilhaMediaFinal->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(2,2);


        //DEFININDO O NOME DA PLANILHA
        $planilhaMediaFinal->setTitle("Média Final");


    }


    public function actionGerarplanilha($idEdital){

        $arrayCurso = array(1 => "Mestrado", 2 => "Doutorado");
        $arrayColunas = array(
            0 => "A1", 1 => "A2", 2 => "B2", 3 => "C2", 4 => "D2",
            5 => "E2", 6 => "F2", 7 => "G2", 8 => "H2", 9 => "I2",
            10 => "J2", 11 => "K2");

        $linhasPesquisas = ArrayHelper::map(LinhaPesquisa::find()->orderBy('sigla')->all(), 'id', 'sigla');

        $model_candidato_mestrado = Candidato::find()->where("cursodesejado = 1 AND passoatual = 4 AND idEdital ='".$idEdital."'")->orderBy("nome")->all();
        $model_candidato_doutorado = Candidato::find()->where("cursodesejado = 2 AND passoatual = 4  AND idEdital ='".$idEdital."'")->orderBy("nome")->all();

        $model_recomendacoes = Recomendacoes::find()->where("edital_idEdital='".$idEdital."'")->all();

        $model_edital = $this->findModel($idEdital);

        //instanciando objeto Excel

        $objPHPExcel = new \PHPExcel();


        //função responsável pelo Header da planilha

        $intervaloHeader = 'A1:K1';
        $this->planilhaHeaderCandidato($objPHPExcel,$arrayColunas,$arrayCurso[1],$intervaloHeader);

        //parte referente ao mestrado (preenchimento da tabela a partir do banco)

        $i = $this->planilhaCandidatoPreencherDados($objPHPExcel,$model_candidato_mestrado,$linhasPesquisas,$arrayCurso,0,0);

        //fim da parte referente ao mestrado

        //parte referente ao doutorado (preenchimento da tabela a partir do banco)

            $j = $i;

            $objPHPExcel->getActiveSheet()->getRowDimension($j+4)->setRowHeight(40);


            $intervaloHeader = 'A'.($j+3).':K'.($j+3).'';

            $arrayColunas = array(
                0 => "A".($j+3), 1 => "A".($j+4), 2 => "B".($j+4), 3 => "C".($j+4), 4 => "D".($j+4),
                5 => "E".($j+4), 6 => "F".($j+4), 7 => "G".($j+4), 8 => "H".($j+4), 9 => "I".($j+4),
                10 => "J".($j+4), 11 => "K".($j+4));

            $this->planilhaHeaderCandidato($objPHPExcel,$arrayColunas,$arrayCurso[2],$intervaloHeader);

            $j= $this->planilhaCandidatoPreencherDados($objPHPExcel,$model_candidato_doutorado,$linhasPesquisas,$arrayCurso,$i+2,$j);

        //fim da parte referente ao doutorado


// Podemos renomear o nome das planilha atual, lembrando que um único arquivo pode ter várias planilhas
        $objPHPExcel->getActiveSheet()->setTitle('Candidato');

        //obtem intervalo referente ao tamanho da tabela (ex.: A1:k10)
        $intervalo_tamanho = $objPHPExcel->setActiveSheetIndex(0)->calculateWorksheetDimension();
        //função responsável pela formatação da planilha
        $this->planilhaCandidatoFormatacao($objPHPExcel,$intervalo_tamanho);

        //define o tamanho das linhas do header da planilha de candidatos
        $objPHPExcel->getActiveSheet()->getRowDimension("2")->setRowHeight(40);
        $objPHPExcel->getActiveSheet()->getRowDimension($i+4)->setRowHeight(40);


        //cria planilha de PROVAS
        $planilhaProvas = $objPHPExcel->createSheet(1);
        $this->planilhaProvas($planilhaProvas,$i,$j);
        $intervalo_tamanho = $objPHPExcel->setActiveSheetIndex(1)->calculateWorksheetDimension();
        $this->planilhaProvasFormatacao($planilhaProvas,$intervalo_tamanho);

        //cria planilhas Propostas
        $planilhaPropostas = $objPHPExcel->createSheet(2);
        $this->planilhaPropostas($planilhaPropostas,$i,$j);
        $intervalo_tamanho = $objPHPExcel->setActiveSheetIndex(2)->calculateWorksheetDimension();
        $this->planilhaPropostasFormatacao($planilhaPropostas,$intervalo_tamanho);

        //Cria planilhas de Títulos
        $planilhaTitulos = $objPHPExcel->createSheet(3);
        $this->planilhaTitulos($planilhaTitulos,$i,$j);
        $intervalo_tamanho = $objPHPExcel->setActiveSheetIndex(3)->calculateWorksheetDimension();
        $this->planilhaTitulosFormatacao($planilhaTitulos,$intervalo_tamanho);

        $index_planilha_Mediafinal = 5;

        if ($model_edital->cartarecomendacao == 1){
            //Cria planilhas de Cartas
            $planilhaCartas = $objPHPExcel->createSheet(4);
            $this->planilhaCartas($planilhaCartas,$model_recomendacoes,$model_candidato_mestrado,$model_candidato_doutorado,$i,$j);
            $intervalo_tamanho = $objPHPExcel->setActiveSheetIndex(4)->calculateWorksheetDimension();
            $this->planilhaCartasFormatacao($planilhaCartas,$intervalo_tamanho);
            $possuiCarta = true;
        }
        else{
            $index_planilha_Mediafinal = 4;
            $possuiCarta = false;
        }

        //Cria planilhas de Cartas
        $planilhaMediaFinal = $objPHPExcel->createSheet($index_planilha_Mediafinal);
        $this->planilhaMediaFinal($planilhaMediaFinal,$i,$j,$possuiCarta);
        $intervalo_tamanho = $objPHPExcel->setActiveSheetIndex($index_planilha_Mediafinal)->calculateWorksheetDimension();
        $this->planilhaMediaFinalFormatacao($planilhaMediaFinal,$intervalo_tamanho);



        // Acessamos o 'Writer' para poder salvar o arquivo
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

        // Salva diretamente no output, poderíamos mudar arqui para um nome de arquivo em um diretório ,caso não quisessemos jogar na tela

            header('Content-type: application/vnd.ms-excel');

            header('Content-Disposition: attachment; filename="Planilha_avaliacao_Edital_'.$idEdital.'.xls"');

            $objWriter->save('php://output');
            $objWriter->save('ARQUIVO.xls');


        echo "ok";


    }



//funções responsáveis pelas notificações de novas INSCRIÇÕES
    public function actionListacandidatos()
    {

            $ultima_visualizacao = Yii::$app->user->identity->visualizacao_candidatos;
            $candidato = Candidato::find()->where("inicio > '".$ultima_visualizacao."'")->orderBy("inicio DESC")->all();

            for ($i=0; $i<count($candidato); $i++){
                echo "<li><a href='#'>";
                echo "<div class='pull-left'>
                <img src='/img/candidato.png' class='img-circle'
                alt='user image'/>
                </div>";
                echo("<p>"."Email: ".$candidato[$i]->email)."<br>";
                echo("Data: ".$candidato[$i]->inicio)."</p></a></li>";
            }

    }

    public function actionQuantidadecandidatos()
    {

            $ultima_visualizacao = Yii::$app->user->identity->visualizacao_candidatos;
            $candidato = Candidato::find()->where("inicio > '".$ultima_visualizacao."'")->all();

            echo count($candidato);

    }

    public function actionZerarnotificacaoinscricoes()
    {
            $usuario = new User();
            $usuario = $usuario->findIdentity(Yii::$app->user->identity->id);
            $usuario->visualizacao_candidatos = date("Y-m-d H:i:s");
            $usuario->save();
    }

//fim das funções responsáveis pelas notificações de novas INSCRIÇÕES

//inicio das funcoes responsáveis pelas notificações de ENCERRAMENTO de novas inscrições:


    public function actionListaencerrados()
    {

            $ultima_visualizacao = Yii::$app->user->identity->visualizacao_candidatos_finalizados;
            $candidato = Candidato::find()->where("fim > '".$ultima_visualizacao."'")->orderBy("fim DESC")->all();

            for ($i=0; $i<count($candidato); $i++){
                echo "<li><a href='#'>";
                echo "<div class='pull-left'>
                <img src='/img/candidato.png' class='img-circle'
                alt='user image'/>
                </div>";
                echo("<p>"."Email: ".$candidato[$i]->email)."<br>";
                echo("Data: ".$candidato[$i]->fim)."</p></a></li>";
            }

    }

    public function actionQuantidadeencerrados()
    {

            $ultima_visualizacao = Yii::$app->user->identity->visualizacao_candidatos_finalizados;
            $candidato = Candidato::find()->where("fim > '".$ultima_visualizacao."'")->all();

            echo count($candidato);

    }

    public function actionZerarnotificacaoencerrados()
    {
            $usuario = new User();
            $usuario = $usuario->findIdentity(Yii::$app->user->identity->id);
            $usuario->visualizacao_candidatos_finalizados = date("Y-m-d H:i:s");
            $usuario->save();
    }
//fim das funções responsáveis pelas notificações de encerramento de novas inscrições

//funções responsáveis pelas notificações das cartas respondidas


    public function actionCartasrespondidas()
    {

            $ultima_visualizacao = Yii::$app->user->identity->visualizacao_cartas_respondidas;
            $recomendacao = Recomendacoes::find()->innerJoin('j17_candidatos','j17_candidatos.id = j17_recomendacoes.idCandidato')->
                where("dataResposta > '".$ultima_visualizacao."'")->orderBy("dataResposta DESC")->all();

            for ($i=0; $i<count($recomendacao); $i++){
                echo "<li><a href='#'>";
                echo "<div class='pull-left'>
                <img src='/img/candidato.png' class='img-circle'
                alt='user image'/>
                </div>";
                echo("<p>"."Candidato: ".$recomendacao[$i]->candidato->nome)."<br>";
                echo("<p>"."Recomendado por: ".$recomendacao[$i]->nome)."<br>";
                echo("Data Resposta: ".$recomendacao[$i]->dataResposta)."</p></a></li>";
            }

    }

    public function actionQuantidadecartasrecebidas()
    {

            $ultima_visualizacao = Yii::$app->user->identity->visualizacao_cartas_respondidas;
            $recomendacao = Recomendacoes::find()->innerJoin('j17_candidatos','j17_candidatos.id = j17_recomendacoes.idCandidato')->
                where("dataResposta > '".$ultima_visualizacao."'")->all();

            echo count($recomendacao);

    }

    public function actionZerarnotificacaocartas()
    {
            $usuario = new User();
            $usuario = $usuario->findIdentity(Yii::$app->user->identity->id);
            $usuario->visualizacao_cartas_respondidas = date("Y-m-d H:i:s");
            $usuario->save();
    }


//fim das funções responsáveis pelas notificações das cartas respondidas


    public function actionCreate()
    {
        $model = new Edital();

        $model->cartarecomendacao = 1;
        $model->mestrado = 1;
        $model->doutorado = 1;
		$model->cartaorientador = 0;

        if ($model->load(Yii::$app->request->post())) {


            if($model->doutorado == 1){
                $model->cartaorientador = 1;
            }

            if($model->mestrado == 1 && $model->doutorado == 0){
                    $model->vagas_doutorado = 0;
                    $model->cotas_doutorado = 0;
            }
            else if($model->mestrado == 0 && $model->doutorado == 1){

                    $model->vagas_mestrado = 0;
                    $model->cotas_mestrado = 0;
            }


            if($model->mestrado == 1 && $model->doutorado == 1)
                $model->curso = '3';
            else if($model->mestrado == 1)
                $model->curso = '1';
            else if($model->doutorado == 1)
                $model->curso = '2';
            else
                $model->curso = '0';

            if($model->uploadDocumento(UploadedFile::getInstance($model, 'documentoFile'))){
                if($model->save()){
					$this->mensagens('success', 'Sucesso', 'Edital salvo com sucesso.');
                    return $this->redirect(['view', 'id' => $model->numero]);
				}
                else{
                    $this->mensagens('danger', 'Erro', 'Erro ao salvar Edital.');
					return $this->render('create', [
                        'model' => $model,
                    ]);
				}
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Edital model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $datainicioBanco = $model->datainicio = date('d/m/Y', strtotime($model->datainicio));
        $datafimBanco = $model->datafim =  date('d/m/Y', strtotime($model->datafim));

        if ($model->load(Yii::$app->request->post())) {
            if($model->mestrado == 1 && $model->doutorado == 1)
                $model->curso = '3';
            else if($model->mestrado == 1)
                $model->curso = '1';
            else if($model->doutorado == 1)
                $model->curso = '2';
            else
                $model->curso = '0';


            if($model->uploadDocumento(UploadedFile::getInstance($model, 'documentoFile'))){
                $model->update = 1;
                if($model->save()){
					$this->mensagens('success', 'Sucesso', 'Edital atualizado com sucesso.');
                    return $this->redirect(['view', 'id' => $model->numero]);
                }
                else{
					$this->mensagens('danger', 'Erro', 'Erro ao salvar o Edital.');
                    return $this->render('update', [
                        'model' => $model,
                    ]);
                }
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Edital model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if($this->findModel($id)->delete())
			$this->mensagens('success', 'Sucesso', 'Edital excluído com sucesso.');
		else
			$this->mensagens('danger', 'Erro ao excluir', 'O sistema não permitiu a exclusão do edital.');

        return $this->redirect(['index']);
    }


    /*Apenas para para evitar a listagem dos editais*/
    public function actionDelete2($id)
    {
        $model = $this->findModel($id);
        $model->status = 0;

        $model->salve();
		return $this->redirect(['index']);
    }

    /**
     * Finds the Edital model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Edital the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Edital::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('A página solicitada não existe.');
        }
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
