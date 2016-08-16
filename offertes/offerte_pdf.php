<?php
session_start();

require("../admin/fpdf/fpdf.php");
include('../configuratie/configuratie.php');



if(isset($_GET['lang'])) $_SESSION['lang'] = $_GET['lang'];
if(isset($_POST['lang1'])) $_SESSION['lang'] = $_POST['lang1'];
if(!isset($_SESSION['lang']) or empty($_SESSION['lang'])) {
  $_SESSION['lang'] = $langue;
}
if(isset($_GET['lang'])) {
  $_SESSION['lang'] = $_GET['lang'];
}
include("../includes/lang/lang_".$_SESSION['lang'].".php");

define("FPDF_FONTPATH","../admin/fpdf/");

 
function tax_details($taxeItem,$totalTaxe1) {
    Global $taxeName,$taxePosition;
    $exist =array();
    $a = "";
    
        for($uu=0; $uu<=count($taxeItem)-1; $uu++) {
            if(in_array($taxeItem[$uu],$exist)) {
                $montant[$taxeItem[$uu]] = $montant[$taxeItem[$uu]] + $totalTaxe1[$uu];
            }
            else {
                $exist[] = $taxeItem[$uu];
                $montant[$taxeItem[$uu]] = $totalTaxe1[$uu];
            }
        }

        while (list($key, $val) = each($montant)) {
            if($taxePosition == "No tax") {
                $multipleTax[] = "0.00>0.00";
            }
            else {
                $multipleTax[] = $key.">".sprintf("%0.2f",$val);
            }
        }
            $_SESSION['multipleTax'] = implode("|",$multipleTax);
}

 
function tax_price($_pays,$productTax) {
        GLOBAL $iso,$taxePosition;
         $_originQueryTax = mysql_query("SELECT * FROM countries WHERE iso = '".$iso."'");
         $_originTotoTax = mysql_fetch_array($_originQueryTax);
         $_originTax = $_originTotoTax['countries_product_tax'];
         if($productTax !== "0.00" and $_originTax !== $productTax) {$_originTaxFinal = $productTax;} else {$_originTaxFinal = $_originTax;}
         
         $_a = mysql_query("SELECT * FROM countries WHERE countries_name = '".$_pays."'");
         $_b = mysql_fetch_array($_a);
         $_tax = $_b['countries_product_tax'];
		 if($productTax !== "0.00" and $_tax !== $productTax) {$_taxFinal = $productTax;} else {$_taxFinal = $_tax;}
         
        if($_b['countries_product_tax_active'] == "yes") {
            $montant_taxe = sprintf("%0.2f",$_taxFinal);
        }
        else {
            if($taxePosition == "Tax included" ) {
                $montant_taxe = sprintf("%0.2f",$_originTaxFinal);
            }
            else {
                $montant_taxe = sprintf("%0.2f",0);
            }
        }
        return array($montant_taxe,$_originTax);
}

 
function shipping_price($originIso,$_pays,$_poids,$activerPromoLivraison,$totalHtFinal,$livraisonComprise,$livraisonId) {
    GLOBAL $_SESSION, $_GET, $taxePosition;
	$hid = mysql_query("SELECT * FROM devis WHERE devis_number = '".$_GET['id']."'");
	$myhid = mysql_fetch_array($hid);
	$devisTva = $myhid['devis_tva'];

    if($_poids>0) {
         $_a = mysql_query("SELECT * FROM countries WHERE countries_name = '".$_pays."'");
         $_b = mysql_fetch_array($_a);
         $_zone = $_b['countries_shipping'];
         $_tax = $_b['countries_shipping_tax'];
         $_iso = $_b['iso'];

		 if($livraisonId!=="") {
         	$_c = mysql_query("SELECT * FROM ship_price WHERE weight >= ".$_poids." AND livraison_id='".$livraisonId."' ORDER BY weight");
         	while ($_d = mysql_fetch_array($_c)) {
                	$_sp[] = $_d['weight'];
         	}
         }
         else {
		 	$_sp[0] = 0;
		 }
  
   
         $_f = mysql_query("SELECT ".$_zone." FROM ship_price WHERE weight = '".$_sp[0]."' AND livraison_id='".$livraisonId."'");
         $_p = mysql_fetch_array($_f);

   

         $query = mysql_query("SELECT free_shipping_zone FROM admin");
         $zoneZ = mysql_fetch_array($query);
         if(preg_match ("/\b$_zone\b/i", $zoneZ['free_shipping_zone'])) {$gratos="yes"; $gratosPack="yes";} else {$gratos="no"; $gratosPack="no";}
         $shipPrice = $_p[$_zone]/$_poids;
         if($_b['iso']=="RM") $shipPrice = sprintf("%0.2f",0);
         if($activerPromoLivraison == "oui" and $totalHtFinal>=$livraisonComprise and $gratos=="yes") $shipPrice = sprintf("%0.2f",0);
         $livraisonhors = sprintf("%0.2f",$shipPrice*$_poids);
         if($_b['countries_shipping_tax_active'] == "yes") {$shipTax = sprintf("%0.2f",$livraisonhors*($_tax/100));} else {$shipTax = sprintf("%0.2f",0);}
		 if($_b['countries_packing_tax_active'] == "yes") {$packTax = $_b['countries_packing_tax'];} else {$packTax = sprintf("%0.2f",0);}

		if(isset($devisTva) AND !empty($devisTva) AND $_iso !== $originIso) {
			$shipTax = sprintf("%0.2f",0);
			$packTax = sprintf("%0.2f",0);
		}
		if($taxePosition=="No tax") {
			$shipTax = sprintf("%0.2f",0);
			$packTax = sprintf("%0.2f",0);
		}
         return array($shipPrice, $livraisonhors, $shipTax, $gratos, $packTax, $gratosPack);
    }
    else {
         $shipPrice = sprintf("%0.2f",0);
         $livraisonhors = sprintf("%0.2f",0);
         $shipTax = sprintf("%0.2f",0);
         $gratos="no";
         $packTax = sprintf("%0.2f",0);
         $gratosPack = "no";
         return array($shipPrice, $livraisonhors, $shipTax, $gratos, $packTax, $gratosPack);
    }
}

 
class PDF extends FPDF {
function Header() {
    global $www2,$_GET,$store_name,$store_company,$address_street,$numFact,$dateFact,$address_cp,$address_city,$address_country,$tel,$fax,$domaineFull,$mailOrder,$address_autre,$item,$split;
    $numFact = $_GET['id'];
    $r3 = mysql_query("SELECT *
                     FROM devis
                     WHERE devis_number = '".$_GET['id']."'
                     ");
    $devis = mysql_fetch_array($r3);
    $dateFact = explode(" ", $devis['devis_date_added']);
	$dateFact[0] = ereg_replace("([0-9]+)-([0-9]+)-([0-9]+)","\\3-\\2-\\1",$dateFact[0]);
    if($devis['devis_shipping']==0) {
		print "<font face=arial size=2><center><br><br><br>Selecteer een verzend firma</center></font>";
		exit;
	}


    $this->SetFont('times','B',16); 
    $this->SetTextColor(0,0,0);
 
 
 
 
    $this->Cell(190,6,$store_company,0,1,'L',0,'http://'.$www2.$domaineFull);
    $this->SetFont('Arial','',9);
    $this->Cell(127,4,$address_street,0,0,'L');
    $this->Cell(63,4,DEVIS.' n°: '.$numFact,0,1,'R');
    $this->SetFont('Arial','',9);
    $this->SetTextColor(0,0,0);
    $this->Cell(95,4,$address_cp.' '.$address_city,0,0,'L');
    $this->Cell(95,4,DATE.': '.$dateFact['0'],0,1,'R');
    $this->Cell(95,4,$address_country,0,1,'L');
    if(!empty($tel)) {$this->Cell(95,4,TELEPHONE.' : '.$tel,0,1,'L');}
    if(!empty($fax)) { $this->Cell(95,4,FAX.' : '.$fax,0,1,'L');}
    $this->Cell(95,4,'Website: http://'.$www2.$domaineFull,0,1,'L');
    $this->Cell(95,4,'E-mail: '.$mailOrder,0,1,'L');
    
    $split = explode("<br>",$address_autre);
    if(!empty($address_autre)) {
        foreach ($split as $item) {
            $this->Cell(95,4,$item,0,1,'L');}
    }
    $this->Ln(5);

    $this->SetTextColor(204,204,204);
    $this->SetDrawColor(204,204,204);
    $this->SetFont('Arial','B',9);
  
    $this->Cell(190,7,REFERENCES,1,0,'L',0);
    
        
              $this->SetFont('Arial','B',10);
              $this->Cell(0,8,'Pagina '.$this->PageNo().'/{nb}',0,0,'R');
              
    
    
    $this->Ln(7);
    $this->SetFont('Arial','U',9); 
    $y2 = $this->GetY();
    $this->Cell(40,5,'N° '.DEVIS,0,0,'C');
    $this->Cell(40,5,NUMERO_DE_CLIENT,0,0,'C');
    $this->Cell(40,5,DATEEXP,0,0,'C');
    $this->Cell(70,5,NO_TVA,0,0,'C');
    $this->Ln(4);
    $this->SetFont('Arial','',9);
    $this->Cell(40,5,$devis['devis_number'],0,0,'C');
    if(!empty($devis['devis_client'])) {$this->Cell(40,5,$devis['devis_client'],0,0,'C'); } else {$this->Cell(40,5,'--',0,0,'C'); }
    $this->SetFont('Arial','',9);
    $dateExpirArray = explode(" ",$devis['devis_date_end']);
	$dateExpirFinal = ereg_replace("([0-9]+)-([0-9]+)-([0-9]+)","\\3-\\2-\\1",$dateExpirArray[0]);
    //$dateExpirFinalArray = explode("-", $dateExpirArray[0]);
    //$dateExpirFinal = date("M j, Y", mktime(0, 0, 0, $dateExpirFinalArray[1], $dateExpirFinalArray[2], $dateExpirFinalArray[0]));
    $this->Cell(40,5,$dateExpirFinal,0,0,'C');
    if(!empty($devis['devis_tva'])) {$this->Cell(70,5,$devis['devis_tva'],0,0,'C'); } else {$this->Cell(70,5,'--',0,0,'C'); }

    $this->SetDrawColor(204,204,204);
    $this->Line(10,$y2+9,200,$y2+9);
    $this->Line(10,$y2,10,$y2+9);
    $this->Line(50,$y2,50,$y2+9);
    $this->Line(90,$y2,90,$y2+9);
    $this->Line(132,$y2,132,$y2+9);
    $this->Line(200,$y2,200,$y2+9);
    $this->Ln(10);
    
}
 
function Footer()
{
    $this->SetY(-10);
    $this->SetFont('Arial','I',8);
    $this->SetTextColor(204,204,204);
    $this->Cell(0,5,'Page '.$this->PageNo().'/{nb}',0,0,'R');
    $this->SetTextColor(0,0,0);
}
}

$pdf=new PDF();
$pdf->AliasNbPages();
$pdf->Open();
$pdf->AddPage();

 
$r3 =mysql_query("SELECT *
                     FROM devis
                     WHERE devis_number = '".$_GET['id']."'
                     ");
$devis = mysql_fetch_array($r3);
 
$numFact = $_GET['id'];
 
$dateFact = explode(" ", $devis['devis_date_added']);
 
$split = explode("<br>",$address_autre);
 
$addresss1 = $devis['devis_firstname']." ".$devis['devis_lastname']."|".$devis['devis_company']."|".$devis['devis_address']."|"."|".$devis['devis_cp']."|".$devis['devis_city']."|".$devis['devis_country']."|";
$addresss = explode("|",$addresss1);

 
$payment = DEVIS;

 
$pdf->SetFont('Arial','',9);
$pdf->Cell(190,4,INTRO1,0,1,'L');    
$pdf->MultiCell(190,4,INTRO2,0,'L');
if($devis['devis_note_client']!=="") {  
  $pdf->SetFont('Arial','BU',10);
  $pdf->MultiCell(190,4,MESSAGE.":",0,'L');
  $pdf->SetFont('Arial','',9);
  $pdf->MultiCell(190,4,$devis['devis_note_client'],0,'L');
}

$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(0.5);
$pdf->SetFont('Arial','B',13);
$pdf->Ln(5);
$pdf->SetFillColor(241,241,241);
$pdf->Cell(190,8,DEVIS,1,1,'L',1);    

$pdf->SetLineWidth(0.2);
$pdf->SetFont('Arial','B',9);
$pdf->Cell(190,6,A3." : ".$devis['devis_lastname'].' '.$devis['devis_firstname'],1,1,'L');    


$pdf->SetFont('Arial','',9);

if(!empty($devis['devis_company'])) $pdf->Cell(90,4,$devis['devis_company'],0,1,'L'); else $pdf->Cell(90,4,'',0,1,'L');
$pdf->Cell(90,4,$devis['devis_address'],0,1,'L');
$pdf->Cell(90,4,$devis['devis_city'],0,1,'L');
$pdf->Cell(15,4,$devis['devis_cp'],0,0,'L');
$pdf->Cell(75,4,strtoupper($devis['devis_country']),0,1,'L');
$y1 = $pdf->GetY();
$pdf->Cell(90,4,'Tel : '.$devis['devis_tel'],0,1,'L');




$pdf->Ln(5);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(0.5);
$pdf->SetTextColor(0,0,0);
$pdf->SetFont('Arial','B',13);
$pdf->SetFillColor(241,241,241);
$pdf->Cell(190,8,COMMANDE,1,0,'L',1);   
$pdf->SetLineWidth(0.2);
$pdf->Ln(8);

$pdf->SetFont('Arial','B',9);
$pdf->Cell(135,6,ARTICLES,1,0,'C');
$pdf->Cell(15,6,'E.P',1,0,'C');
$pdf->Cell(20,6,PRIX_UNITAIRE,1,0,'C');
$pdf->Cell(20,6,SOUS_TOTAL,1,0,'C');
$y4 = $pdf->GetY()+5;
$pdf->Ln(8);




$pays = mysql_query("SELECT * FROM countries WHERE countries_name = '".$_GET['country']."'");
$p = mysql_fetch_array($pays);

$hid = mysql_query("SELECT *
                     FROM devis
                     WHERE devis_number = '".$_GET['id']."'
                     ");
$myhid = mysql_fetch_array($hid);

if(!empty($myhid['devis_products_new'])) {
    $_SESSION['list2'] = $myhid['devis_products_new'];
}
else {
    $_SESSION['list2'] = $myhid['devis_products'];
}

$split = explode(",",$_SESSION['list2']);
foreach ($split as $item) {
                        
                        $check = explode("+",$item);
                        $query = mysql_query("SELECT p.products_sup_shipping, p.products_tax, p.products_name_".$_SESSION['lang'].", p.products_id, p.categories_id,p.products_download,p.fournisseurs_id,p.products_desc_".$_SESSION['lang'].",p.products_price,p.products_weight,p.products_note_".$_SESSION['lang'].",p.products_ref,p.products_im,p.products_image,p.products_image2,p.products_image3,p.products_image4,p.products_option_note_".$_SESSION['lang'].",p.products_visible,p.products_taxable,p.products_tax,p.products_date_added,p.products_qt, s.specials_new_price, s.specials_last_day, s.specials_first_day, s.specials_visible
                                              FROM products as p
                                              LEFT JOIN specials as s
                                              ON (p.products_id = s.products_id)
                                              WHERE p.products_id = '".$check[0]."'");
                        $row = mysql_fetch_array($query);
    if($check[1]!=="0") {
                        
						$new_price = $row['specials_new_price'];
                        $old_price = $row['products_price'];
						if(empty($new_price)) {
							$price = $check[2];
						}
						else {
							if($row['specials_visible']=="yes") {
								$today = mktime(0,0,0,date("m"),date("d"),date("Y"));
								$dateMaxCheck = explode("-",$row['specials_last_day']);
								$dateMax = mktime(0,0,0,$dateMaxCheck[1],$dateMaxCheck[2],$dateMaxCheck[0]);
								$dateDebutCheck = explode("-",$row['specials_first_day']);
								$dateDebut = mktime(0,0,0,$dateDebutCheck[1],$dateDebutCheck[2],$dateDebutCheck[0]);
								
								if($dateDebut <= $today  and $dateMax >= $today) {
									$price = $check[2];
								}
								else {
									$price = $check[2];
								}
							}
							else {
								$price = $check[2];
							}
						}
                        
                        if($row['products_taxable']=="yes") {

                        $tutu = tax_price($_GET['country'],$row['products_tax']);

                           if($taxePosition=="Tax included") {
                              $priceTTC = $price * $check[1];
                              $totalht = $priceTTC*100/($tutu[0]+100);
                              $total_taxe = $totalht * ($tutu[0]/100);
                              $price_display = $priceTTC;
                              $priceTTCDeee = $check[7] * $check[1];
                              $totalhtDeee = $priceTTCDeee*100/($tutu[0]+100);
                           }
                           if($taxePosition=="Plus tax") {
                              $totalht = $price* $check[1];
                              $priceTTC = $totalht + ($totalht* ($tutu[0]/100));
                              $total_taxe = $totalht * ($tutu[0]/100);
                              $price_display = $totalht;
                              $totalhtDeee = $check[7] * $check[1];
                              $priceTTCDeee = $totalhtDeee + ($totalhtDeee* ($tutu[0]/100));
                           }
                           if($taxePosition=="No tax") {
                              $priceTTC = $price * $check[1];
                              $totalht = $priceTTC;
                              $total_taxe = 0;
                              $price_display = $priceTTC;
                              $priceTTCDeee = $check[7] * $check[1];
                              $totalhtDeee = $priceTTCDeee;
                           }
                        }
                        else {
                           $priceTTC = ($price * $check[1]);
                           $totalht = $priceTTC;
                           $total_taxe = 0;
                           $price_display = $priceTTC;
                           $tutu[0] = 0;
                           $priceTTCDeee = $check[7] * $check[1];
                           $totalhtDeee = $check[7] * $check[1];
                        }
                                               
                        if($p['countries_product_tax_active']=="no") {
                        $total_taxe = 0;
                        }
                        
                        $_price[] = $price_display;

                        if($p['countries_product_tax_active'] == "yes") $products_tax = $tutu[0]; else $products_tax = "0";

                        
                        $p_ = $row['products_weight'];
                        if($row['products_download'] == "yes") {$p_=0;}
                        $poidsOptionsArray = explode('|',$check[8]);
                        $poidsOptions = sprintf("%0.2f",array_sum($poidsOptionsArray));
                        $poid[] = ($check[1]*$p_)+($check[1]*$poidsOptions);
                        $_SESSION['poids'] = sprintf("%0.2f",array_sum($poid));

                        
                        $emballage[] = $check[1] * $row['products_sup_shipping'];

                        
                        if($row['products_tax'] !=="0.00" and $p['countries_product_tax'] !== $row['products_tax']) {
            				  if($p['countries_product_tax'] == "0.00") { $pTax="0.00";} else { $pTax = $row['products_tax'];}
                        }
						      else {
							     $pTax = $p['countries_product_tax']; 
						      }
						if($row['products_taxable'] == "no") {$pTax="0.00";}
						if($taxePosition=="No tax") {$pTax="0.00";}
                        if(!empty($myhid['devis_tva']) AND $p['iso'] !== $iso) {$pTax="0.00";}
                        
                        $taxeItem[]=$pTax;
                        $itemId[] = $row['products_id'];

        				
                        if($row['products_ref'] == "GC100") {
                            if($taxePosition == "Tax included") {
                                $priceHtCadeau[] = DisplayProductPrice($iso,$row['products_tax'],$priceTTC);
                                $priceTTCCadeau[] = $priceTTC;
                                $priceHtDeee1[] = DisplayProductPrice($iso,$row['products_tax'],$priceTTCDeee);
                                $priceTTCDeee1[] = $priceTTCDeee;
                            }
                            else {
                                $priceHtCadeau[] = DisplayProductPrice($iso,$row['products_tax'],$totalht);
                                $priceTTCCadeau[] = $priceTTC;
                                $priceHtDeee1[] = DisplayProductPrice($iso,$row['products_tax'],$totalhtDeee);
                                $priceTTCDeee1[] = $priceTTCDeee;
                            }
                        }
                        else {
                            $priceHtCadeau[] = 0;
                            $priceTTCCadeau[] = 0;
                            $priceHtDeee1[] = $totalhtDeee;
                            $priceTTCDeee1[] = $priceTTCDeee;
                        }
                        
                        $totalTaxeArray[] = $total_taxe;
                        $totalTaxeFinal = array_sum($totalTaxeArray);
                        $_SESSION['itemTax'] = sprintf("%0.2f",$totalTaxeFinal);

                        $totalHtArray[] = $totalht;
                        $_SESSION['totalHtFinal'] = sprintf("%0.2f",array_sum($totalHtArray));

                        $totalTTCArray[] = $priceTTC;
                        $totalTTCFinal = array_sum($totalTTCArray);
                        
                        $liste[] = $row['products_id']."+".
                                   $check[1]."+".
                                   $price."+".
                                   $row['products_ref']."+".
                                   $row['products_name_'.$_SESSION['lang']]."+".
                                   $pTax."+".
                                   $check[6]."+".
                                   $check[7]."+".
                                   $check[8];

        $pdf->SetFont('Arial','',9);
        
        if(!empty($check[6])) {
            $pdf->Cell(190,4,"REF : ".strtoupper($check[3]),0,1,'L');
            $pdf->Cell(133,4,$check[4],0,0,'L');
            $pdf->Cell(20,4,$check[1],0,0,'C');
            $pdf->Cell(15,4,$check[2],0,0,'C');
            $pdf->Cell(17,4,sprintf("%0.2f",$price_display),0,0,'R');
            $pdf->Ln(4);
            $pdf->SetFont('Arial','I',6); 
            $pdf->SetTextColor(204,0,0);
            $pdf->Cell(0.1,3,'',0,0,'C');
            
	   		$_opt = explode("|",$check[6]);
			## session update option price
			$lastArray = $_opt[count($_opt)-1];
			if(preg_match("#epz$#", $lastArray) AND is_numeric(substr($lastArray,0,-3))) unset($_opt[count($_opt)-1]);
			$ww = implode("|",$_opt);
			$newtext = str_replace("|","\n",$ww);
            
            if($row['products_download'] == "yes") {$newtext =  TELECHARGER." | ".$newtext;}
            
            $pdf->MultiCell(133,3,$newtext,0,'L');
            $pdf->Cell(15,3,'',0,0,'C');
            $pdf->Cell(20,3,'',0,0,'C');
            $pdf->Cell(20,3,'',0,0,'C');
            $pdf->SetFont('Arial','',9); 
            $pdf->SetTextColor(0,0,0);
            $pdf->Ln(2);
        }
        else {
            
            $pdf->Cell(190,4,"REF : ".strtoupper($check[3]),0,1,'L');
            $pdf->Cell(133,4,$check[4],0,0,'L');
            $pdf->Cell(20,4,$check[1],0,0,'C');
            $pdf->Cell(15,4,$check[2],0,0,'C');
            $pdf->Cell(17,4,sprintf("%0.2f",$price_display),0,0,'R');
            $pdf->Ln(6);
        }
        if(isset($check[7])) {$ecoTaxFact[] = $check[7];} else {$ecoTaxFact[] = 0;}
    }
}










                        $ola = shipping_price($iso,$_GET['country'],$_SESSION['poids'],$activerPromoLivraison,$_SESSION['totalHtFinal'],$livraisonComprise,$devis['devis_shipping']);
                        $_SESSION['shipPrice'] = $ola[0];
                        $_SESSION['livraisonhors'] = $ola[1];
                        $_SESSION['shipTax'] = $ola[2];
                        $gratos = $ola[3];

                        if($activerPromoLivraison == "oui" AND $_SESSION['totalHtFinal']>=$livraisonComprise AND $gratos=="yes") {
                            $gratos = "yes";
                            $gratoPack = "yes";
                        }
                        else {
                            $gratos = "no";
                            $gratoPack = "no";
                        }

                        $_SESSION['users_shipping_price'] = sprintf("%0.2f",$_SESSION['livraisonhors']+$_SESSION['shipTax']);
                        $totht = $_SESSION['totalHtFinal']+$_SESSION['livraisonhors'];
                        $_SESSION['totalTax'] = $totalTaxeFinal;
                        $_SESSION['totalTax'] = sprintf("%0.2f",$_SESSION['totalTax']);
                        $totttc = $totht+$_SESSION['totalTax']+$_SESSION['shipTax'];
                        $_SESSION['totalToPayTTC'] = sprintf("%0.2f",$totttc);
                        
                        
                        /*
                        if(isset($deee)) {
                            $ecoRaxAmount = sprintf("%0.2f",array_sum($deee));
                            if($ecoRaxAmount>0) {$_SESSION['deee'] = $ecoRaxAmount;} else {$_SESSION['deee'] = sprintf("%0.2f",0);}
                        }
                        else {
                            $_SESSION['deee']=0;
                        }
                        */
                        
if($remiseOnTax == "TTC") {
    $totalPriceTTCCadeau = array_sum($priceTTCCadeau);
    $totalPriceTTCDeee = array_sum($priceTTCDeee1);
    $_SESSION['ff'] = $totalTTCFinal-$totalPriceTTCCadeau-$totalPriceTTCDeee;
}
else {
    $totalPriceHtCadeau = array_sum($priceHtCadeau);
    $totalPriceHtDeee = array_sum($priceHtDeee1);
    $_SESSION['ff'] = $_SESSION['totalHtFinal']-$totalPriceHtCadeau-$totalPriceHtDeee;
}

if($activerPromoLivraison == "oui" and $_SESSION['ff']>=$livraisonComprise and $gratos=="yes") {
	$shipPriceDisplay = LIVRAISON_GRATUITE;
}
else {
	$shipPriceDisplay = sprintf("%0.5f",$_SESSION['shipPrice'])." ".$symbolDevise."/gr HT";
}

$totalFraisEmballage = sprintf("%0.2f",array_sum($emballage));

if($totalFraisEmballage > 0 AND $gratoPack == "no" AND $gratos == "no") {
   if($taxePosition=="Tax included") {
        $_SESSION['priceEmballageTTC'] = sprintf("%0.2f",$totalFraisEmballage);
        $_SESSION['totalEmballageHt'] = sprintf("%0.2f",$_SESSION['priceEmballageTTC']*100/($ola[4]+100));
        $_SESSION['totalEmballageTva'] = sprintf("%0.2f",$_SESSION['totalEmballageHt'] * ($ola[4]/100));
   }
   if($taxePosition=="Plus tax") {
        $_SESSION['priceEmballageTTC'] = sprintf("%0.2f",$totalFraisEmballage + ($totalFraisEmballage* ($ola[4]/100)));
        $_SESSION['totalEmballageHt'] = sprintf("%0.2f",$totalFraisEmballage);
        $_SESSION['totalEmballageTva'] = sprintf("%0.2f",$totalFraisEmballage* ($ola[4]/100));
   }
   if($taxePosition=="No tax") {
        $_SESSION['priceEmballageTTC'] = sprintf("%0.2f",$totalFraisEmballage);
        $_SESSION['totalEmballageHt'] = sprintf("%0.2f",$totalFraisEmballage);
        $_SESSION['totalEmballageTva'] = sprintf("%0.2f",0);
   }
}
else {
        $_SESSION['priceEmballageTTC'] = sprintf("%0.2f",0);
        $_SESSION['totalEmballageHt'] = sprintf("%0.2f",0);
        $_SESSION['totalEmballageTva'] = sprintf("%0.2f",0);
}

if($activerRemise == "oui" and $_SESSION['ff']>=$remiseOrderMax) {
       if($remiseType == "%") {$_SESSION['montantRemise'] = $_SESSION['ff']*($remise/100);}
       if($remiseType == $symbolDevise) {$_SESSION['montantRemise'] = $remise;}
        $_SESSION['montantRemise'] = sprintf("%0.2f",$_SESSION['montantRemise']);
        $discount[] = $_SESSION['montantRemise'];
        $textRemise = REMISE." (-$remise$remiseType)";
}
else {
        $_SESSION['totalToPayTTC'] = sprintf("%0.2f",$totttc);
        $_SESSION['montantRemise'] = sprintf("%0.2f",0);
        $discount[] = 0;
}
 
if($myhid['devis_remise_commande']>0) {
      if($_SESSION['ff'] >= $myhid['devis_remise_commande']) {
        $discount[] = $myhid['devis_remise_commande'];
        $_SESSION['accountRemiseEffec'] = sprintf("%0.2f",$myhid['devis_remise_commande']);						            
      }
      else {
        $discount[] = 0;
        $_SESSION['accountRemiseEffec'] = sprintf("%0.2f",0);
      }
}

if(!empty($myhid['devis_remise_coupon'])) {
    $_SESSION['coupon_name'] = $myhid['devis_remise_coupon'];
    
  	$query = mysql_query("SELECT *
                 FROM code_promo
                 WHERE code_promo = '".$_SESSION['coupon_name']."'");
 		$result = mysql_fetch_array($query);
 		$seuilPromoCode = $result['code_promo_seuil'];
 		
if($_SESSION['ff'] > $result['code_promo_seuil']) {
      $remiseCoupon = $result['code_promo_reduction'];
      $remiseCouponType = $result['code_promo_type'];
         if($remiseCouponType == "%") $_SESSION['montantRemise2'] = $_SESSION['ff']*($remiseCoupon/100);
         if($remiseCouponType == $symbolDevise) $_SESSION['montantRemise2'] = $remiseCoupon;
            $_SESSION['montantRemise2'] = sprintf("%0.2f",$_SESSION['montantRemise2']);
            $discount[] = $_SESSION['montantRemise2'];                        
            $textCoupon1 = REMISE." (-".$remiseCoupon."".$remiseCouponType.")";
            $textCoupon2 = $textCoupon1." | Coupon: ".$result['code_promo'];
            $display_coupon = 1;
}
else {
          $_SESSION['totalToPayTTC'] = sprintf("%0.2f",$totttc);
          $_SESSION['montantRemise2'] = sprintf("%0.2f",0);
          $discount[] = 0;
}
}

            $totalDiscount = array_sum($discount);

if($p['countries_product_tax_active'] == "yes") {
	$_priceNb = count($_price)-1;
    for($i=0; $i<=$_priceNb; $i++) {

	 if($totalDiscount==0) {
                if($taxePosition=="Tax included") {
                    $totalTaxe1[] =  $totalHtArray[$i]*$taxeItem[$i]/100;
                }
                else {
                    $totalTaxe1[] = $_price[$i]*$taxeItem[$i]/100;
                }
          }
          else {
            if($taxePosition=="Plus tax" or $taxePosition=="No tax") {
                if($remiseOnTax == "TTC") {
                    /*
                    $prixHtFinalTTC = $_price[$i]+($_price[$i]*($taxeItem[$i]/100));
                    print $totalTTCFinal;
                    $ratio = sprintf("%0.1f",$prixHtFinalTTC/$totalTTCFinal);
                    
                    $totalTaxe1[] = (($totalTTCFinal-($totalDiscount))*$ratio)*($taxeItem[$i]/100);
                    print_r($totalTaxe1);
                    */
                    $prixHtFinalTTC = $_price[$i]+($_price[$i]*($taxeItem[$i]/100));
                    $pCent = sprintf("%0.2f",$_price[$i]/$_SESSION['totalHtFinal']*100);
                    $montantReductionProduct = $totalDiscount*$pCent/100;
                    $htTotalToPay = ($prixHtFinalTTC-$montantReductionProduct)/(1+$taxeItem[$i]/100);
                    $totalTaxe1[] = (($htTotalToPay))*($taxeItem[$i]/100);
                    $htTotalToPay1[] = $htTotalToPay;
                }
                else {
                    $ratio = sprintf("%0.4f",$_price[$i]/$_SESSION['totalHtFinal']);
                    $totalTaxe1[] = (($_SESSION['totalHtFinal']-$totalDiscount)*$ratio)*($taxeItem[$i]/100);
            }
            }
            if($taxePosition=="Tax included") {
                if($remiseOnTax == "TTC") {
                    /*
                    $prixHtFinal = $_price[$i]/(1+$taxeItem[$i]/100);
                    $ratio = sprintf("%0.1f",$prixHtFinal/$_SESSION['totalHtFinal']);
                    $totalTaxe1[] = (($totalTTCFinal-$totalDiscount)*$ratio)*($taxeItem[$i]/100);
                    $htTotalToPay[] = ($totalTTCFinal-$totalDiscount)/(1+$taxeItem[$i]/100);
                    */
                    $pCent = sprintf("%0.2f",$_price[$i]/$totalTTCFinal*100);
                    $montantReductionProduct = $totalDiscount*$pCent/100;
                    $prixHtFinal = ($_price[$i]-$montantReductionProduct)/(1+$taxeItem[$i]/100);
                    $htTotalToPay = ($totalTTCFinal-$totalDiscount)/(1+$taxeItem[$i]/100);
                    $totalTaxe1[] = (($prixHtFinal)*1)*($taxeItem[$i]/100);
                    $htTotalToPay1[] = $prixHtFinal;
                }
                else {
                   $prixHtFinal = $_price[$i]/(1+$taxeItem[$i]/100);
                   $ratio = sprintf("%0.1f",$prixHtFinal/$_SESSION['totalHtFinal']);
                   $totalTaxe1[] = (($_SESSION['totalHtFinal']-$totalDiscount)*$ratio)*($taxeItem[$i]/100);
            }
          }
	}
	}
		$totalTaxe = array_sum($totalTaxe1);
}
else {
	  $totalTaxe = 0;
}

if(isset($htTotalToPay1)) {
    $_SESSION['totalHtFinal'] = sprintf("%0.2f",array_sum($htTotalToPay1));
    $_SESSION['totalToPayTTC'] = sprintf("%0.2f",($_SESSION['totalHtFinal']+$totalTaxe));
}
else {
    $_SESSION['totalToPayTTC'] = sprintf("%0.2f",($_SESSION['totalHtFinal']-$totalDiscount+$totalTaxe));
}

$_SESSION['itemTax'] = sprintf("%0.2f",$totalTaxe);

            ##$pdf->Cell(15,4,TOTAL_POIDS." : ",0,0,'L');
            ##$pdf->Cell(14,4,$_SESSION['poids']." gr",0,0,'L');
            ##$pdf->Cell(40,4,$shipPriceDisplay,0,0,'L');
            
			## Afficher mode de livraison
			/*
            $requestModeA = mysql_query("SELECT livraison_nom_".$_SESSION['lang']." FROM ship_mode WHERE livraison_id = '".$devis['devis_shipping']."'") or die (mysql_error());
			if(mysql_num_rows($requestModeA) > 0) {
				$pdf->Ln(0);
            	$pdf->SetX(10);
            	$pdf->SetFont('Arial','I',7);
				$requestModeResultA = mysql_fetch_array($requestModeA);
				$livName = $requestModeResultA['livraison_nom_'.$_SESSION['lang']];
				$pdf->Ln(4);
	            $pdf->Cell(40,4,$livName,0,0,'L');
            	$pdf->Ln(0);
            	$pdf->SetFont('Arial','',9);
            }
            */

$ecoTaxFactFinal = sprintf("%0.2f",array_sum($ecoTaxFact));
if($ecoTaxFactFinal>0) {
            $pdf->Ln(4);
            $pdf->SetX(10);
            $pdf->SetTextColor(0,204,0);
            $pdf->SetFont('Arial','I',7);
            $pdf->Cell(12,4,"Eco-part : ",0,0,'L');
            $pdf->Cell(40,4,$ecoTaxFactFinal." ".$symbolDevise,0,0,'L');
            $pdf->SetTextColor(0,0,0);
            $pdf->Ln(0);
            $pdf->SetFont('Arial','',9);
}

$pdf->Ln(6);
              $pdf->SetX(126);
              $pdf->Cell(50,4,TOTAL." ".HT ,0,0,'L'); $pdf->Cell(20,5,$_SESSION['totalHtFinal'],0,1,'R');              

if($activerRemise == "oui" and $_SESSION['ff']>=$remiseOrderMax) {
              $pdf->SetX(126);
              $pdf->Cell(50,4,$textRemise,0,0,'L'); 
              $pdf->SetTextColor(204,0,0); 
              $pdf->Cell(20,4,'-'.$_SESSION['montantRemise'],0,1,'R'); 
              $pdf->SetTextColor(0,0,0);
}               

if(isset($_SESSION['accountRemiseEffec']) AND $_SESSION['accountRemiseEffec']>0) {
              $pdf->SetX(126);
              $pdf->Cell(50,4,REMISE_SUR_COMMANDES,0,0,'L'); 
              $pdf->SetTextColor(204,0,0); 
              $pdf->Cell(20,4,'-'.$_SESSION['accountRemiseEffec'],0,1,'R'); 
              $pdf->SetTextColor(0,0,0);

}

if(isset($display_coupon) AND $display_coupon == 1) {
              $pdf->SetX(126);
              $pdf->Cell(50,4,$textCoupon2,0,0,'L');
              $pdf->SetTextColor(204,0,0); 
              $pdf->Cell(20,4,'-'.$_SESSION['montantRemise2'],0,1,'R'); 
              $pdf->SetTextColor(0,0,0);
}

                if(!isset($totalTaxe1)) $totalTaxe1=0;
                
                tax_details($taxeItem,$totalTaxe1);
                $explodMultiple = explode("|",$_SESSION['multipleTax']);
                $explodMultipleNum = count($explodMultiple);
                if($explodMultipleNum>0) {
                    foreach ($explodMultiple as $item) {
                        $itemDisplay= explode(">",$item);
                                $pdf->SetX(126);
                        $pdf->Cell(50,4,TVA." ".$itemDisplay[0]."%",0,0,'L');
                                $pdf->SetTextColor(0,0,0); 
                        $pdf->Cell(20,4,$itemDisplay[1],0,1,'R'); 
                                $pdf->SetTextColor(0,0,0);
                    }
                }

                $_SESSION['totalToPayTTC'] = $_SESSION['totalToPayTTC'] + $_SESSION['livraisonhors'];
				## Afficher mode de livraison
            	$requestModeA = mysql_query("SELECT livraison_nom_".$_SESSION['lang']." FROM ship_mode WHERE livraison_id = '".$devis['devis_shipping']."'") or die (mysql_error());
				if(mysql_num_rows($requestModeA) > 0) {
					$requestModeResultA = mysql_fetch_array($requestModeA);
					$livName = " (".$requestModeResultA['livraison_nom_'.$_SESSION['lang']].")";
            	}
            	else {
					$livName = "";
				}
                                $pdf->SetX(126);
                                $pdf->Cell(50,3,"",0,0,'L');
                                $pdf->Cell(20,3,"",0,1,'R'); 

                                $pdf->SetX(126);
                                $pdf->Cell(50,4,LIVRAISON.$livName,0,0,'L');
                                $pdf->SetTextColor(0,0,0); 
                                $pdf->Cell(20,4,$_SESSION['livraisonhors'],0,1,'R'); 

                $_SESSION['totalToPayTTC'] = $_SESSION['totalToPayTTC'] + $_SESSION['shipTax'];
                                $pdf->SetX(126);
                                $pdf->Cell(50,4,strtoupper($taxeName)." ".$p['countries_shipping_tax']."%",0,0,'L');
                                $pdf->SetTextColor(0,0,0); 
                                $pdf->Cell(20,4,$_SESSION['shipTax'],0,1,'R'); 




if($_SESSION['priceEmballageTTC'] > 0) {
								$_SESSION['totalToPayTTC'] = sprintf("%0.2f",($_SESSION['totalToPayTTC']+$_SESSION['totalEmballageHt']));
                                $pdf->SetX(126);
                                $pdf->Cell(50,3,"",0,0,'L');
                                $pdf->Cell(20,3,"",0,1,'R'); 

                                $pdf->SetX(126);
                                $pdf->Cell(50,4,PAKING_COST,0,0,'L');
                                $pdf->SetTextColor(0,0,0); 
                                $pdf->Cell(20,4,$_SESSION['totalEmballageHt'],0,1,'R'); 
								$_SESSION['totalToPayTTC'] = sprintf("%0.2f",($_SESSION['totalToPayTTC']+$_SESSION['totalEmballageTva']));
                                $pdf->SetX(126);
                                $pdf->Cell(50,4,strtoupper($taxeName)." ".$p['countries_shipping_tax']."%",0,0,'L');
                                $pdf->SetTextColor(0,0,0); 
                                $pdf->Cell(20,4,$_SESSION['totalEmballageTva'],0,1,'R'); 
}

                if(isset($_SESSION['contre']) and $_SESSION['contre'] == 1) {
                    $_SESSION['totalToPayTTC'] = sprintf("%0.2f",($_SESSION['totalToPayTTC']+$seuilContre));
                                $pdf->SetX(126);
                                $pdf->Cell(50,4,CONTRE_REMBOURSEMENT,0,0,'L');
                                $pdf->SetTextColor(0,0,0); 
                                $pdf->Cell(20,4,sprintf("%0.2f",$seuilContre),0,1,'R'); 
			    }

				$totalTax1 = sprintf("%0.2f",$_SESSION['itemTax'] + $_SESSION['shipTax'] + $_SESSION['totalEmballageTva']);
                                $pdf->SetX(126);
                                $pdf->Cell(50,3,"",0,0,'L');
                                $pdf->Cell(20,3,"",0,1,'R'); 
                                
                                $pdf->SetX(126);
                                $x = $pdf->GetX();
                                $y = $pdf->GetY();
                                $pdf->Line($x,$y-1,$x+70,$y-1);
                                $pdf->Cell(50,4,TOTAL." ".strtoupper($taxeName),0,0,'L');
                                $pdf->SetTextColor(0,0,0); 
                                $pdf->Cell(20,4,$totalTax1,0,1,'R');
                                $pdf->SetX(126);
                                $x = $pdf->GetX();
                                $y = $pdf->GetY();
                                $pdf->Line($x,$y+1,$x+70,$y+1);


                                $pdf->SetX(126);
                                $pdf->Cell(50,3,"",0,0,'L');
                                $pdf->Cell(20,3,"",0,1,'R'); 
                                
                                $montantToPay = str_replace("&agrave;", "à", MONTANT_A_PAYER);
                                $pdf->SetX(126);
                                $pdf->Cell(50,4,$montantToPay,0,0,'L');
                                $pdf->SetTextColor(204,0,0); 
                                $pdf->SetFont('Arial','B',11);
                                $pdf->Cell(20,4,$symbolDevise." ".sprintf("%0.2f",$_SESSION['totalToPayTTC']),0,1,'R');
                                $pdf->SetTextColor(0,0,0);
                                $pdf->SetFont('Arial','',9);

$tototo = implode(",",$liste);
$_SESSION['list2'] = $tototo;



$espace = 5;
$y = $pdf->GetY()+$espace;



$pdf->Output();
?>
