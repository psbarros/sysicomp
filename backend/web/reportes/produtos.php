<?php

use yii\helpers\Html;
use app\models\Cautela;
use app\models\Equipamento;


	require('../lib/pdf/mpdf.php');

	$conn = new mysqli('localhost', 'root','', 'novoppgi');
	$query = "SELECT * FROM j17_cautela";
	$prepare = $conn->prepare($query);
	$prepare->execute();
	$resultSet = $prepare->get_result();
	while($produtos[] = $resultSet->fetch_array());
	$resultSet->close();
	$prepare->close();
	$conn->close();

	

	$html = '
		
					<h4 style="text-align: center;"><strong>PODER </strong><strong>EXECUTIVO</strong></h4>
					<h4 style="text-align: center;"><strong>MINIST&Eacute;RIO DA</strong><strong> EDUCA&Ccedil;&Atilde;O</strong></h4>
					<h4 style="text-align: center;"><strong>&nbsp; &nbsp;</strong><strong>UNIVERSIDADE<strong> FEDERAL</strong><strong> DO</strong><strong> AMAZONAS</strong></strong></h4>
					<h4 style="text-align: center;"><strong>INSTITUTO</strong><strong> DE</strong><strong> COMPUTA&Ccedil;&Atilde;O</strong></h4>
					<h4 style="text-align: center;">&nbsp;</h4>
					<h4 style="text-align: center;"><strong>CAUTELA DE EQUIPAMENTO</strong></h4>
	';


					foreach ($produtos as $j17_cautela) {
						$html .= '
							
					
					<table style="margin-left: auto; margin-right: auto;" border="2">
						<tbody>
							<tr>
								<td width="95">
									<p>Respons&aacute;vel:</p>
								</td>
								<td colspan="3" width="612">
								<p>'.$j17_cautela['NomeResponsavel'].'</p>
								</td>
							</tr>

							<tr>
								<td width="95">
									<p>Contato:</p>
								</td>
								<td width="214">
									<p>'.$j17_cautela['TelefoneResponsavel'].'.</p>
								</td>
								<td width="66">
									<p>Email:</p>
								</td>
								<td width="331">
									<p>'.$j17_cautela['Email'].'</p>
								</td>
							</tr>
						</tbody>
					</table>

					<p>&nbsp;</p>

					<table style="margin-left: auto; margin-right: auto; width: 600px;" border="2">
					<tbody>
					<tr>
					<td style="width: 147.5px;">
					<p>Origem:</p>
					</td>
					<td style="width: 179.5px;">
					<p>Nota Fiscal:</p>
					</td>
					<td style="width: 225px;">
					<p>Equipamento:</p>
					</td>
					<td style="width: 152px;">
					<p>N&ordm;. de S&eacute;rie:</p>
					</td>
					</tr>
					<tr>
					<td style="width: 147.5px;">
					<p>'.$j17_cautela['OrigemCautela'].'</p>
					</td>
					<td style="width: 179.5px;">
					<p>'.$j17_equipamento['NotaFiscal'].'</p>
					</td>
					<td style="width: 225px;">
					<p>&nbsp;</p>
					</td>
					<td style="width: 152px;">
					<p>&nbsp;</p>
					</td>
					</tr>
					<tr>
					<td style="width: 147.5px;">
					<p>Origem:</p>
					</td>
					<td style="width: 179.5px;">
					<p>Nota Fiscal:</p>
					</td>
					<td style="width: 225px;">
					<p>Equipamento:</p>
					</td>
					<td style="width: 152px;">
					<p>N&ordm;. de S&eacute;rie:</p>
					</td>
					</tr>
					<tr>
					<td style="width: 147.5px;">
					<p>&nbsp;</p>
					</td>
					<td style="width: 179.5px;">
					<p>&nbsp;</p>
					</td>
					<td style="width: 225px;">
					<p>&nbsp;</p>
					</td>
					<td style="width: 152px;">
					<p>&nbsp;</p>
					</td>
					</tr>
					</tbody>
					</table>

					';
				}

					$html .= '
					<p style="text-align: center;">&nbsp;</p>
					<p style="text-align: justify;">Declaro assumir total responsabilidade por extravio ou danos verificados ap&oacute;s a retirada do equipamento; neste caso, providenciarei o reparo ou a reposi&ccedil;&atilde;o do item emprestado em prazo de 30 dias a contar da data de devolu&ccedil;&atilde;o. Afirmo ter verificado, antes da retirada, que o equipamento encontrava-se:&nbsp;</p>
					<p style="text-align: left;">(&nbsp;&nbsp; ) em perfeitas condi&ccedil;&otilde;es de uso e bom estado de conserva&ccedil;&atilde;o&nbsp;</p>
					<p>(&nbsp;&nbsp; ) com os seguintes problemas e/ou danos (descrev&ecirc;-los):</p>
					<p>_______________________________________________________________________________</p>
					<p>_______________________________________________________________________________</p>
					<p>Validade da cautela: _______________.</p>
					
					<p style="text-align: center;">Local, Data: ________________________________.</p>
					
					<p>&nbsp;</p>
					<p style="text-align: center;">_________________________&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_________________________</p>
					<p style="text-align: center;">Respons&aacute;vel&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;Coordenador</p>
					<p style="text-align: center;">&nbsp;&nbsp;</p>
					<p style="text-align: center;">&nbsp; &nbsp; &nbsp;___/___/________ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; _________________________</p>
					<p style="text-align: center;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Recebedor</p>
					';
				


	$mpdf = new mPDF('c', 'A4');
	$css = file_get_contents('css/style.css');
	$mpdf ->WriteHTML($css, 1);
	$mpdf->WriteHTML($html);
	$mpdf->Output('reporte.pdf', 'I');
	$mpdf->charset_in='windows-1252';


?>