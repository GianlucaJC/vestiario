<?php
$filename="../firma/firma.png";
$nofirma=0;

if (!file_exists($filename)) {
	$nofirma=1;
	return;
}
					  //require('../../pdf/fpdf.php');
					  require('../../fpdi/fpdf.php'); 
					  require('../../fpdi/fpdi.php'); 					  
					  class PDF extends FPDI{
  								var $widths;
								var $aligns;
								
								////estensione JS
								var $javascript;
								var $n_js;

								function subWrite($h, $txt, $link='', $subFontSize=12, $subOffset=0)
								{
									// resize font
									$subFontSizeold = $this->FontSizePt;
									$this->SetFontSize($subFontSize);
									
									// reposition y
									$subOffset = ((($subFontSize - $subFontSizeold) / $this->k) * 0.3) + ($subOffset / $this->k);
									$subX        = $this->x;
									$subY        = $this->y;
									$this->SetXY($subX, $subY - $subOffset);

									//Output text
									$this->Write($h, $txt, $link);

									// restore y position
									$subX        = $this->x;
									$subY        = $this->y;
									$this->SetXY($subX,  $subY + $subOffset);

									// restore font size
									$this->SetFontSize($subFontSizeold);
								}
								function IncludeJS($script) {
									$this->javascript=$script;
								}

								function _putjavascript() {
									$this->_newobj();
									$this->n_js=$this->n;
									$this->_out('<<');
									$this->_out('/Names [(EmbeddedJS) '.($this->n+1).' 0 R]');
									$this->_out('>>');
									$this->_out('endobj');
									$this->_newobj();
									$this->_out('<<');
									$this->_out('/S /JavaScript');
									$this->_out('/JS '.$this->_textstring($this->javascript));
									$this->_out('>>');
									$this->_out('endobj');
								}

								function _putresources() {
									parent::_putresources();
									if (!empty($this->javascript)) {
										$this->_putjavascript();
									}
								}

								function _putcatalog() {
									parent::_putcatalog();
									if (!empty($this->javascript)) {
										$this->_out('/Names <</JavaScript '.($this->n_js).' 0 R>>');
									}
								}

								function AutoPrint($dialog=false)
								{
									//Open the print dialog or start printing immediately on the standard printer
									$param=($dialog ? 'true' : 'false');
									$script="print($param);";
									$this->IncludeJS($script);
								}

								function AutoPrintToPrinter($server, $printer, $dialog=false)
								{
									//Print on a shared printer (requires at least Acrobat 6)
									$script = "var pp = getPrintParams();";
									if($dialog)
										$script .= "pp.interactive = pp.constants.interactionLevel.full;";
									else
										$script .= "pp.interactive = pp.constants.interactionLevel.automatic;";
									$script .= "pp.printerName = '\\\\\\\\".$server."\\\\".$printer."';";
									$script .= "print(pp);";
									$this->IncludeJS($script);
								}
								///// fine estensione classa JS
								
								//Page header
									function Header(){
									
										$this->SetTextColor(0,0,0);
										$x=$this->GetX();$y=$this->GetY();
										$this->Cell(63,15,'',1,0,'C');
										$this->SetXY($x,$y);
										$this->SetFont('Times','B',28);				
										$this->Cell(40,15,'   Liofilchem',0,0,'C');																				
										$this->subWrite(5,'   ®','',20,-5);										
										$this->Cell(12,15,'',0,0);

										$this->SetFont('Times','B',10);				
										$x=$this->GetX();$y=$this->GetY();
										$this->Cell(63,7,'Richiesta Abbigliamento/',0,0,'C');										
										$this->SetXY($x,$y+4);
										$this->Cell(63,7,'Dichiarazione di consegna',0,0,'C');										
										$this->SetXY($x,$y+8);
										$this->Cell(63,7,'Abbigliamento',0,0,'C');										

										$this->Rect($x,$y,63,15);
										$this->SetXY($x+63,$y);
										
										$x=$this->GetX();$y=$this->GetY();
										$this->Cell(64,15,'',1,0,'C');
										
										$this->SetFont('Times','B',10);					
										$this->SetXY($x,$y-5);
										$this->Cell(64,15,'',0,0,'C');
										$this->SetXY($x+25,$y);
										$this->SetFont('Times','',10);		
										$this->Cell(15,15,'Rev. 1 del 23.08.2021',0,0,'C');
										
										$this->SetTextColor(0,0,0);
										$this->SetXY($x,$y+5);
										$this->Cell(64,15,'Pag 1 di 1',0,1,'C');
										
									}

									//footer
									function Footer(){
										$this->SetFont('Times','',8);
										
										$this->SetY(-20);										
										$this->Cell(0,10,'____________________________________________________________________________',0,0,'C');
										
										$this->SetY(-15);										
										$this->Cell(0,10,'©Questo documento è di proprietà della Liofilchem    s.r.l. che se ne riserva tutti i diritti',0,0,'C');
										$x=$this->GetX();$y=$this->GetY();										
										$this->SetXY(113,282);
										$this->subWrite(5,'®','',7,-5);
										
										$this->SetXY($x,$y);
										//1.5 cm dal basso												
										$this->SetY(-10);
										//Times italic 8
										$this->SetFont('Times','I',8);
										//numero pagina
										//$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	
									}									
									
									function SetWidths($w)
									{
										//Set the array of column widths
										$this->widths=$w;
									}

									function SetAligns($a)
									{
										//Set the array of column alignments
										$this->aligns=$a;
									}


									function CheckPageBreak($h)
									{
										//If the height h would cause an overflow, add a new page immediately
										    if($this->GetY()+$h>$this->PageBreakTrigger)
											{$this->AddPage($this->CurOrientation);
											$this->SetX(20);}
									}

									function NbLines($w,$txt)
									{
										//Computes the number of lines a MultiCell of width w will take
										$cw=&$this->CurrentFont['cw'];
										if($w==0)
											$w=$this->w-$this->rMargin-$this->x;
										$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
										$s=str_replace("\r",'',$txt);
										$nb=strlen($s);
										if($nb>0 and $s[$nb-1]=="\n")
											$nb--;
										$sep=-1;
										$i=0;
										$j=0;
										$l=0;
										$nl=1;
										while($i<$nb)
										{
											$c=$s[$i];
											if($c=="\n")
											{
												$i++;
												$sep=-1;
												$j=$i;
												$l=0;
												$nl++;
												continue;
											}
											if($c==' ')
												$sep=$i;
											$l+=$cw[$c];
											if($l>$wmax)
											{
												if($sep==-1)
												{
													if($i==$j)
														$i++;
												}
												else
													$i=$sep+1;
												$sep=-1;
												$j=$i;
												$l=0;
												$nl++;
											}
											else
												$i++;
										}
										return $nl;
									}	

						}	


						//$nominativo=stripslashes($_POST['id_dip']);
						
						$tipo_richiesta=$load_richiesta[0]['tipo_richiesta'];
						$id_dipendente=$load_richiesta[0]['id_dipendente'];
						$nominativo=$load_richiesta[0]['dipendente'];
						
						
						
						$pdf = new PDF('P', 'mm');
						$pdf->SetTextColor(0,0,0);  ////imposta testo nero
						$pdf->SetFont('Times','B',10);							
						$pdf->AddPage();
						$pdf->AliasNbPages();
						$pdf->SetAuthor('Liofilchem');
						$pdf->SetTitle('Richiesta vestizione');


						$pdf->SetFillColor(255,255,255);
						
						$pdf->SetFont('Times','',8);							
						$dx="Data: ".date('d/m/Y');
						$pdf->Cell(100,4,$dx,0,0,'L',1);						
						$pdf->Ln(10);

						$tx="Il/La sottoscritto/a $nominativo dipendente della Liofilchem® S.r.L. con sede in Roseto degli Abruzzi,";
						$pdf->MultiCell(190,4,$tx,0,1,'J',1);
						
						$pdf->Ln(4);
						$pdf->Cell(190,4,'HA RICHIESTO IL SEGUENTE MATERIALE E DICHIARA',0,1,'C',1);
						$pdf->Ln(4);

						$pdf->SetFont('Times','B',8);
						$pdf->Cell(55,6,'ABBIGLIAMENTO ',1,0,'C',1);
						$pdf->Cell(30,6,'CODICE',1,0,'C',1);
						$pdf->Cell(40,6,"QUANTITA'/TAGLIA",1,0,'C',1);
						$pdf->Cell(65,6,'CONSEGNATO DATA',1,1,'C',1);
						
						$codice_articolo=$_POST['codice_articolo'];
						$quantita=$_POST['qta_impegno'];
						$taglie=$_POST['taglia'];
						$prodotto=$_POST['prodotto'];
						$qta_impegno_cur=$_POST['qta_impegno_cur'];
												
						
						$data=date("d-m-Y");
						
						
						$image = imagecreatefrompng($filename);
						$bg = imagecreatetruecolor(imagesx($image), imagesy($image));
						imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
						imagealphablending($bg, TRUE);
						imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
						imagedestroy($image);
						$quality = 100; // 0 = low / smaller file, 100 = better / bigger file 10
						$f_jpeg=str_replace(".png",".jpg",$filename);
						imagejpeg($bg, $f_jpeg, $quality);
						imagedestroy($bg);			
						//$genera=99;
						$genera=1;

						$da_firmare=$main_impegno->product_to_sign($impegno);						
						for ($sca=0;$sca<=count($da_firmare)-1;$sca++) {
							$qt=$da_firmare[$sca]['quantita'];
							if ($qt=="0" || strlen($qt)==0) continue;
							
							$id_sign=$da_firmare[$sca]['id'];
							$articolo=$da_firmare[$sca]['codice_articolo'];
							$tg=$da_firmare[$sca]['taglia'];
							$x=$pdf->getX();$y=$pdf->getY();
							$pdf->MultiCell(55,4,$articolo,1,'J');
							$x1=$pdf->getX();$y1=$pdf->getY();
							$pdf->setXY($x+55,$y);
							$hx=$y1-$y;					
							
							

							$pdf->Cell(30,$hx,$codice,1,0,'C',1);	
							$qt_tg="$qt/$tg";
							$pdf->Cell(40,$hx,$qt_tg,1,0,'C',1);						
							$pdf->Cell(65,$hx,$data,1,1,'C',1);	
							
							$delete_after_sign=$main_impegno->delete_after_sign($id_sign);
							
						}
						
						

						$pdf->Ln(4);

						$pdf->SetFont('Times','',8);
						$tx="di ricevere in dotazione il materiale in elenco, da utilizzare esclusivamente all’interno dell'azienda e durante le attività lavorative, in accordo alle Procedure Operative Standard di Vestizione e Comportamento in vigore";
						$pdf->MultiCell(190,4,$tx,0,1,'J',1);
						$pdf->Ln(4);
						
						$pdf->SetFont('Times','B',8);							
						$pdf->Cell(190,4,'Il sottoscritto si impegna:',0,1,'C',1);
						$pdf->Ln(4);
						
						$pdf->SetFont('Times','',8);		



						$tx="1. indossare e trattare con cura il materiale consegnato (il materiale smarrito e/o non riconsegnato sarà totalmente addebitato);";
						$pdf->Cell(190,4,$tx,0,1,'L',1);
						$tx="2. conservare il materiale consegnato nel proprio armadietto;";
						$pdf->Cell(190,4,$tx,0,1,'L',1);
						$tx="3. informare il proprio responsabile dei difetti riscontrati;";
						$pdf->Cell(190,4,$tx,0,1,'L',1);
						$tx="4. non effettuare, di propria iniziativa modifiche o lavori di sartoria se non espressamente autorizzati;";
						$pdf->Cell(190,4,$tx,0,1,'L',1);
						$tx="5. non manomettere il micro-chip (se presente);";
						$pdf->Cell(190,4,$tx,0,1,'L',1);
						$tx="6. riconsegnare settimanalmente i capi con il micro-chip, depositandoli a fine turno del venerdì, nella “BUCA DI RACCOLTA ABBIGLIAMENTO SPORCO” (avendo cura di non lasciare nulla nelle tasche);";
						$pdf->MultiCell(190,4,$tx,0,1,'L',1);
						$tx="7. lavare le T-Shirt, le Felpe, le polo e le Scarpe e ogni altro capo senza micro-chip secondo le proprie necessità.";
						$pdf->Cell(190,4,$tx,0,1,'L',1);
							
						$pdf->Ln(6);
						$pdf->SetFont('Times','B',8);
						$tx="Roseto degli Abruzzi, $data";
						$pdf->Cell(140,4,$tx,0,0,'L',1);
						$tx="Il Dipendente";
						$pdf->Cell(50,4,$tx,0,1,'L',1);
						$tx="";
						$pdf->Cell(140,4,$tx,0,0,'L',1);
						//$tx="___________________________";
						//$pdf->Cell(50,4,$tx,0,1,'L',1);

						$pdf->SetAlpha(.7);
						$y=$pdf->getY();
						$pdf->Image('../firma/firma.jpg',147,$y,36);
						$pdf->SetAlpha(1);
						
						$pdf->Ln(18);
						$tx="";
						$pdf->Cell(140,4,$tx,0,0,'L',1);

						

						if ($genera==1) {
							@mkdir("../dipendenti/info/".$tipo_richiesta."/".$id_dipendente);
							$file_pdf= uniqid().".pdf";
							$pdf->Output("../dipendenti/info/".$tipo_richiesta."/$id_dipendente/$file_pdf","F");
						}
						@unlink("../firma/firma.jpg");
						@unlink("../firma/firma.png");
						
						

?>