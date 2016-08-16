<?php
session_start();
 
if(!isset($_SESSION['login'])) header("Location:index.php");


if(isset($_SESSION['user']) AND $_SESSION['user']=='user') {
	print "<html>";
	print "<head>";
	print "<title>Niet toegelaten</title>";
	print "<link rel='stylesheet' href='style.css'>";
	print "</head>";
	print "<body>";
	print "<p align='center' style='FONT-SIZE: 15px; color:#FF0000;'>Beperkte toegang</p>";
	print "</body>";
	print "</html>";
	exit;
}

require("fpdf/fpdf.php");
include('../configuratie/configuratie.php');
function incLang($u) {
$fichier = explode("/",$u);
$what = end($fichier);
return $what;
}
function remApos($rem) {
    $_rem = str_replace("&#146;","'",$rem);
    return $_rem;
}

 
function dateFr($fromDate,$langId) {
     $_qq = explode(" ",$fromDate);
   	 $_qq1 = explode("-",$_qq[0]);
   	 if($langId==1 OR $langId==3) $_qq3 = $_qq1[2]."/".$_qq1[1]."/".$_qq1[0];
   	 if($langId==2) $_qq3 = $_qq[0];
   	 return $_qq3;
}

 
function tax_price($_pays) {
         $_a = mysql_query("SELECT * FROM countries WHERE countries_name = '".$_pays."'");
         $_b = mysql_fetch_array($_a);
         $_tax = $_b['countries_shipping_tax'];
         return $_tax;
}

include("lang/lang".$_SESSION['lang']."/".incLang($_SERVER['PHP_SELF']));
define("FPDF_FONTPATH","fpdf/");
$client_nic = $_GET['id'];

// HEADER
class PDF extends FPDF {
function Header() {
    global $factPrefixe,$www2,$client_nic,$_GET,$store_name,$store_company,$address_street,$numFact,$dateFact,$address_cp,$address_city,$address_country,$tel,$fax,$domaineFull,$mailOrder,$address_autre,$item,$split;
  
         $r3 = mysql_query("SELECT * FROM users_orders WHERE users_nic = '".$client_nic."'");
         $client = mysql_fetch_array($r3);
   
         $numFact = str_replace("||","",$client['users_fact_num']);
		 if($numFact=="") $numFact="XXXX";
    
          if($client['users_date_payed'] == "0000-00-00 00:00:00") {
              $dateFact = dateFr($client['users_date_added'],$_SESSION['lang']);
          }
          else {
               $dateFact = dateFr($client['users_date_payed'],$_SESSION['lang']);
          }

          $split = explode("<br>",$address_autre);
 
          $addresss = explode("|",$client['users_facture_adresse']);
  
          if($client['users_payment'] == "BO") $payment = A1;
          if($client['users_payment'] == "ch") $payment = A2;
          if($client['users_payment'] == "cc") $payment = A3;
          if($client['users_payment'] == "pp") $payment = "Paypal";
          if($client['users_payment'] == "mb") $payment = "MoneyBookers";
          if($client['users_payment'] == "ma") $payment = A4;
          if($client['users_payment'] == "BL") $payment = A4A;
          if($client['users_payment'] == "tb") $payment = TRAITE_BANCAIRE;
          if($client['users_payment'] == "wu") $payment = "Western Union";
          if($client['users_payment'] == "ss") $payment = "Liaison-SSL";
          if($client['users_payment'] == "eu") $payment = "1euro.com";
          if($client['users_payment'] == "pn") $payment = "Pay.nl";


        $this->SetFont('times','B',16);
        $this->SetTextColor(0,0,0);
     
        //$this->Image('../im/logo.jpg','9','10','40','20','JPG','');
        //$this->Ln(13);
        $this->Cell(190,6,remApos($store_name),0,0,'L');               
        $this->Ln(5);
        $this->SetFont('Arial','',10);
        
        $this->Cell(95,10,remApos($address_street),0,0,'L');                      
        
        if(substr($client['users_nic'], 0, 5) == "TERUG") $displayFactName = A5." D'".str_replace("||","",$client['users_nic']); else $displayFactName = A5;
        $this->Cell(95,4,$displayFactName.' '.$numFact,0,0,'R');
        
        ##$this->SetFont('Arial','B',10);
        ##$this->SetTextColor(204,0,0);
        ##$this->Cell(30,4,$numFact,1,0,'R');    
        $this->SetFont('Arial','',10);
        $this->SetTextColor(0,0,0);
        $this->Ln(4);
        $this->Cell(95,10,$address_cp.' '.remApos($address_city),0,0,'L');   
        $this->Cell(95,4,A8.': '.$dateFact,0,0,'R');                      
        
        $this->Ln(4);
        $this->Cell(95,10,remApos($address_country),0,0,'L');   

         if(substr($client['users_nic'], 0, 5) == "TERUG") {
            ##$_1 = explode("-",$client['users_nic']);
            $_1 = str_replace("TERUG-","",str_replace("||","",$client['users_nic']));
            $corrFact = CORRECTION_FACTURE." #".$_1;
            $this->SetFont('Arial','B',10);
            $this->SetTextColor(204,0,0);
            $this->Cell(95,4,$corrFact,0,0,'R');                      
            $this->SetTextColor(0,0,0);
            $this->SetFont('Arial','',10);
         }
        
        $this->Ln(5);
        if(!empty($tel)) {
            $this->Cell(95,10,TEL.' : '.$tel,0,0,'L');
        }           
        $this->Ln(4);
        if(!empty($fax)) {
          $this->Cell(95,10,FAX.' : '.$fax,0,0,'L');         
          $this->Ln(5);
        }
        $this->Cell(95,10,'Web: http://'.$www2.$domaineFull,0,0,'L');
        $this->Ln(4);
        $this->Cell(95,10,'Email: '.$mailOrder,0,0,'L');
        
        if(!empty($address_autre)) {
        $this->Ln(5);
        foreach ($split as $item) {
            $this->Cell(95,10,remApos($item),0,0,'L');         
            $this->Ln(4);
        }
        }
        else {
            $this->Ln(5);
        }

        $this->SetDrawColor(0,0,0);
        $this->SetLineWidth(0.5);
        $this->SetFont('Arial','B',13);
        $this->Ln(6);
        $this->SetFillColor(241,241,241);

        if($client['users_refund']=='yes') {
            $titreR = $displayFactName." (".REMBOURSEE.")";
        }
        else {
            $titreR = $displayFactName;
        }
        $this->Cell(190,8,$titreR,1,0,'L',1);   
        
              $this->SetFont('Arial','B',10);
              $this->Cell(0,8,'Pagina '.$this->PageNo().'/{nb}',0,0,'R');     
              
        $this->Ln(13);
        
        $this->SetLineWidth(0.2);
        $this->SetFont('Arial','B',9);
        $this->Cell(90,7,A9.':',1,0,'L'); 
        $this->Cell(10,7,' ',0,0,'L');    
        $this->Cell(90,7,A9A.':',1,0,'L'); 
        
        $this->Ln(5);
        
        $this->SetFont('Arial','',10);
        $this->Cell(90,10,remApos($addresss[0]),0,0,'L');
        $this->Cell(10,10,' ',0,0,'L');
        $y1 = $this->GetY();
        
        $this->Cell(90,10,remApos($client['users_gender']).'. '.remApos($client['users_lastname']).' '.remApos($client['users_firstname']),0,0,'L'); 
        
        $this->Ln(4);
            if(!empty($addresss[1])) $this->Cell(90,10,remApos($addresss[1]),0,0,'L'); else $this->Cell(90,10,'--',0,0,'L');
            $this->Cell(10,10,' ',0,0,'L');
            if(!empty($client['users_company'])) $this->Cell(90,10,remApos($client['users_company']),0,0,'L'); else $this->Cell(90,10,'--',0,0,'L'); 
        
        $this->Ln(4);
        $this->Cell(90,10,remApos($addresss[2]),0,0,'L');
        $this->Cell(10,10,' ',0,0,'L');
        $this->Cell(90,10,remApos($client['users_address']),0,0,'L');              
        
            $this->Ln(4);
            if(!empty($addresss[3])) $this->Cell(90,10,remApos($addresss[3],0,0,'L')); else  $this->Cell(90,10,'--',0,0,'L');                            
            $this->Cell(10,10,' ',0,0,'L');
            if(!empty($client['users_surburb']))  $this->Cell(90,10,remApos($client['users_surburb']),0,0,'L'); else $this->Cell(90,10,'--',0,0,'L');
        
        $this->Ln(4);
        $this->Cell(90,10,remApos($addresss[4]).' '.remApos($addresss[5]),0,0,'L');
        $this->Cell(10,10,' ',0,0,'L');
        $this->Cell(95,10,remApos($client['users_zip']).' '.remApos($client['users_city']),0,0,'L');  
        $this->Ln(4);
        $this->Cell(90,10,remApos($addresss[6]),0,0,'L');
        $this->Cell(10,10,' ',0,0,'L');
        $this->Cell(95,10,remApos($client['users_country']),0,0,'L');  

        $this->Ln(15);
        $this->SetTextColor(204,204,204);
        $this->SetDrawColor(204,204,204);
        $this->SetFont('Arial','B',9);
        $this->Cell(190,7,A100,1,0,'L',0);    
        $this->Ln(7);
        $y2 = $this->GetY();
        $this->SetFont('Arial','U',9); 
        $this->Cell(40,5,'NIC',0,0,'C');
        $this->Cell(40,5,A10,0,0,'C');
        $this->Cell(40,5,A12,0,0,'C');
        $this->Cell(70,5,NO_TVA,0,0,'C');
        $this->Ln(4);
        $this->SetFont('Arial','B',9);
        $this->Cell(40,5,str_replace("||","",$client['users_nic']),0,0,'C');
        $this->Cell(40,5,$client['users_password'],0,0,'C');
        $this->SetFont('Arial','',9);
        $this->Cell(40,5,$payment,0,0,'C');
        if(isset($addresss[7]) AND !empty($addresss[7])) {$this->Cell(70,5,remApos($addresss[7]),0,0,'C'); } else {$this->Cell(70,5,'--',0,0,'C'); }
        $this->Ln(10);
        
        $this->SetDrawColor(0,0,0);
        $this->SetLineWidth(0.5);
        $this->SetTextColor(0,0,0);
        $this->SetFont('Arial','B',13);
        $this->SetFillColor(241,241,241);
        $this->Cell(190,8,A13,1,0,'L',1);    
        $this->SetLineWidth(0.2);
        $this->Ln(8);
        $y4 = $this->GetY();
        $this->SetFont('Arial','B',9);
        //$this->Cell(20,6,A14,1,0,'C');
        $this->Cell(115,6,A15,1,0,'C');
        $this->Cell(15,6,'Qt',1,0,'C');
        $this->Cell(20,6,A45,1,0,'C');
        $this->Cell(20,6,TVA,1,0,'C');
        $this->Cell(20,6,A24,1,0,'C');
        $this->Ln(6);

        
        $this->SetDrawColor(0,0,0);
        $this->Line(10,$y1,10,$y1+29);
        $this->Line(100,$y1,100,$y1+29);
        $this->Line(110,$y1,110,$y1+29);
        $this->Line(200,$y1,200,$y1+29);
        $this->Line(10,$y1+29,100,$y1+29);
        $this->Line(110,$y1+29,200,$y1+29);
        
        $this->SetDrawColor(204,204,204);
        $this->Line(10,$y2+9,200,$y2+9);
        $this->Line(10,$y2,10,$y2+9);
        $this->Line(50,$y2,50,$y2+9);
        $this->Line(90,$y2,90,$y2+9);
        $this->Line(132,$y2,132,$y2+9);
        $this->Line(200,$y2,200,$y2+9);
}
// FOOTER
function Footer()
{
global $y4;
    $this->SetY(-10);
    $this->SetFont('Arial','I',9);
    $this->SetTextColor(204,204,204);
    $this->Cell(0,5,'Page '.$this->PageNo().'/{nb}',0,0,'R');
    $CadreY = $this->GetY();
    $this->SetTextColor(0,0,0);
    
    
    $y = $CadreY-5;
    $this->Line(10,$y4,10,$y);
    $this->Line(200,$y4,200,$y);
    $this->Line(10,$y,200,$y);
}
}







$pdf=new PDF();
$pdf->AliasNbPages();
$pdf->Open();
$pdf->AddPage();

$r3 = mysql_query("SELECT * FROM users_orders WHERE users_nic = '".$client_nic."'");
$client = mysql_fetch_array($r3);
 
$yoyo = tax_price($client['users_country']);

$y1 = $pdf->GetY();
$y2 = $pdf->GetY();
$y4 = $pdf->GetY();

$pdf->SetFont('Arial','',9);
$order = explode(",",$client['users_products']);

foreach ($order as $item) {
    $check = explode("+",$item);
    if($check[1]!=="0") {
         
        if(isset($check[7])) {$ecoTaxFact[] = $check[7]*$check[1];} else {$ecoTaxFact[] = 0;}
        if(!empty($check[6])) {
            $pdf->Cell(115,6,A14.": ".strtoupper($check[3]),0,0,'L');
            $pdf->ln(4);
            $pdf->Cell(115,6,remApos($check[4]),0,0,'L');
            $pdf->Cell(15,6,$check[1],0,0,'C');
                $priceU = ($client['users_products_tax_statut']=="TTC")? sprintf("%0.2f",$check[2]/(1+($check[5]/100))) : sprintf("%0.2f",$check[2]);
            $pdf->Cell(20,6,$priceU,0,0,'C');
            ($check[5]>0)? $pdf->Cell(20,6,$check[5]."%",0,0,'C') : $pdf->Cell(20,6,"--",0,0,'C');
                $priceTTC = ($client['users_products_tax_statut']=="TTC")? sprintf("%0.2f",($check[2]*$check[1])/(1+($check[5]/100))) : sprintf("%0.2f",($check[2] * $check[1]));
            $pdf->Cell(18,6,$priceTTC,0,0,'R');
            $pdf->ln(4); 
            $pdf->SetFont('Arial','I',6); 
            $pdf->SetTextColor(204,0,0); 
            $pdf->Cell(2,3,'',0,0,'C');
			            
	   		$_opt = explode("|",$check[6]);
			## session update option price
			$lastArray = $_opt[count($_opt)-1];
			if(preg_match("#epz$#", $lastArray) AND is_numeric(substr($lastArray,0,-3))) unset($_opt[count($_opt)-1]);
			$ww = implode("|",$_opt);
			$newtext = str_replace("|","\n",$ww);
            
            $pdf->MultiCell(113,3,$newtext,0,'L');
            $pdf->Cell(15,1,'',0,0,'C');
            $pdf->Cell(20,1,'',0,0,'C');
            $pdf->Cell(20,1,'',0,0,'C');
            $pdf->SetFont('Arial','',9);
            $pdf->SetTextColor(0,0,0);
            $pdf->Ln(3);
        }
        else {
            $pdf->Cell(115,6,A14.": ".strtoupper($check[3]),0,0,'L');
            $pdf->ln(4);
            $pdf->Cell(115,6,$check[4],0,0,'L');
            $pdf->Cell(15,6,$check[1],0,0,'C');
                $priceU = ($client['users_products_tax_statut']=="TTC")? sprintf("%0.2f",$check[2]/(1+($check[5]/100))) : sprintf("%0.2f",$check[2]);
            $pdf->Cell(20,6,$priceU,0,0,'C');
            ($check[5]>0)? $pdf->Cell(18,6,$check[5]."%",0,0,'C') : $pdf->Cell(18,6,"--",0,0,'C');
                $priceTTC = ($client['users_products_tax_statut']=="TTC")? sprintf("%0.2f",($check[2]*$check[1])/(1+($check[5]/100))) : sprintf("%0.2f",($check[2] * $check[1]));
            $pdf->Cell(20,6,$priceTTC,0,0,'R');
            $pdf->Ln(6);
        }
    }
}

$ecoTaxFactFinal = sprintf("%0.2f",array_sum($ecoTaxFact));
if($ecoTaxFactFinal>0) {
    $pdf->Ln(4);
    $pdf->Cell(2,6,"",0,0,'L');
    $pdf->SetTextColor(0,204,0);
    $pdf->SetFont('Arial','I',9);
    $pdf->Cell(186,6,"Eco-part : ".$ecoTaxFactFinal." ".$symbolDevise,0,0,'L');
    $pdf->SetFont('Arial','',9);
    $pdf->SetTextColor(0,0,0);
    $pdf->Ln(3);
}
 
if($mlFact!=="") {
$mlFactD = str_replace("<br>"," - ",$mlFact);
    $pdf->Ln(4);
    $pdf->Cell(2,6,"",0,0,'L');
    $pdf->SetTextColor(0,204,0);
    $pdf->SetFont('Arial','I',9);
    $pdf->Cell(186,6,$mlFactD,0,0,'L');
    $pdf->SetFont('Arial','',9);
    $pdf->SetTextColor(0,0,0);
    $pdf->Ln(2);
}
 
$pdf->Ln(5);
$y3 = $pdf->GetY();
$pdf->SetDrawColor(204,204,204);
$pdf->Line(10,$y3-1,200,$y3-1);
$pdf->SetDrawColor(0,0,0);
$pdf->SetFillColor(241,241,241);
$pdf->Cell(2,2,'',0,0,'C');
$pdf->Cell(186,2,'',0,0,'C',1);
$pdf->Ln();
$pdf->SetFillColor(241,241,241);
$pdf->Cell(2,2,'',0,0,'C');
$pdf->Cell(131,4,'',0,0,'C',1);


$totalDiscount = abs($client['users_account_remise_app'] + $client['users_remise'] + $client['users_remise_coupon']);

if(isset($totalDiscount) AND $totalDiscount>0) {
    ## Total HT avant remise
    $totalHtAvantRemise = sprintf("%0.2f",$client['users_products_ht']+$totalDiscount);
    if($client['users_products_tax_statut']=="") $pdf->Cell(35,4,A23,0,0,'R',1); else $pdf->Cell(35,4,TOTAL_HT_AVANT_REMISE,0,0,'R',1);
    $pdf->Cell(20,4,$totalHtAvantRemise,0,0,'R',1);
    ## Afficher remises
    if(abs($client['users_account_remise_app'])>0) {
        $sig = (substr($client['users_nic'], 0, 5) == "TERUG")? "" : "-";
        $pdf->Ln();
        $pdf->SetFillColor(241,241,241);
        $pdf->Cell(2,2,'',0,0,'C');
        $pdf->Cell(131,4,'',0,0,'C',1);
        $pdf->Cell(35,4,A51,0,0,'R',1);
        $pdf->SetTextColor(204,0,0);
        $pdf->Cell(20,4,$sig.sprintf("%0.2f",abs($client['users_account_remise_app'])),0,0,'R',1);
        $pdf->SetTextColor(0,0,0);
    }
    if(abs($client['users_remise'])>0) {
        $sig1 = ($client['users_remise']<0)? "" : "-";
        $pdf->Ln();
        $pdf->SetFillColor(241,241,241);
        $pdf->Cell(2,2,'',0,0,'C');
        $pdf->Cell(131,4,'',0,0,'C',1);
        $pdf->Cell(35,4,A21,0,0,'R',1);
        $pdf->SetTextColor(204,0,0);
        $pdf->Cell(20,4,$sig1.sprintf("%0.2f",abs($client['users_remise'])),0,0,'R',1);
        $pdf->SetTextColor(0,0,0);
    }
    if(abs($client['users_remise_coupon'])>0) {
        $sig2 = ($client['users_remise_coupon']<0)? "" : "-";
        $pdf->Ln();
        $pdf->SetFillColor(241,241,241);
        $pdf->Cell(2,2,'',0,0,'C');
        $pdf->Cell(131,4,'',0,0,'C',1);
        $pdf->Cell(35,4,A22,0,0,'R',1);
        $pdf->SetTextColor(204,0,0);
        $pdf->Cell(20,4,$sig2.sprintf("%0.2f",abs($client['users_remise_coupon'])),0,0,'R',1);
        $pdf->SetTextColor(0,0,0);
    }
    ## Saut de ligne gris
    $pdf->Ln();
    $pdf->SetFillColor(241,241,241);
    $pdf->Cell(2,2,'',0,0,'C');
    $pdf->Cell(186,4,'',0,0,'C',1);

    ## Total HT
    $pdf->Ln();
    $pdf->SetFillColor(241,241,241);
    $pdf->Cell(2,2,'',0,0,'C');
    $pdf->Cell(131,4,'',0,0,'C',1);
    if($client['users_products_tax_statut']=="") $pdf->Cell(35,4,A23,0,0,'R',1); else $pdf->Cell(35,4,TOTAL_HT,0,0,'R',1);
    $pdf->Cell(20,4,$client['users_products_ht'],0,0,'R',1);
}
else {
    if($client['users_products_tax_statut']=="") $pdf->Cell(35,4,A23,0,0,'R',1); else $pdf->Cell(35,4,TOTAL_HT,0,0,'R',1);
    $pdf->Cell(20,4,$client['users_products_ht'],0,0,'R',1);
}

 
if($client['users_products_tax_statut']!=="") {
                 
                $explodMultiple = explode("|",$client['users_multiple_tax']);
                $explodMultipleNum = count($explodMultiple);
                    foreach ($explodMultiple as $item) {
                        if($item == "0.00>0.00") {
                                $itemDisplay = "0.00%                0.00";
                                $pdf->Ln();
                                $pdf->Cell(2,0,'',0,0,'C');
                                $pdf->Cell(131,4,'',0,0,'C',1);
                                $pdf->Cell(55,4,TVA." ".$itemDisplay,0,0,'R',1);
                        }
                        else {
                                $itemDisplay = str_replace(">", "%              ", $item);
                                $pdf->Ln();
                                $pdf->Cell(2,0,'',0,0,'C');
                                $pdf->Cell(131,4,'',0,0,'C',1);
                                $pdf->Cell(55,4,TVA." ".$itemDisplay,0,0,'R',1);
                        }
                    }
}
//$pdf->Cell(20,4,$itemDisplay,0,0,'R',1);
$pdf->Ln();
$pdf->SetFillColor(241,241,241);
$pdf->Cell(2,2,'',0,0,'C');
$pdf->Cell(131,4,'',0,0,'C',1);
$pdf->Cell(35,4,A19,0,0,'R',1);
$pdf->Cell(20,4,$client['users_ship_ht'],0,0,'R',1);
 
if($client['users_products_tax_statut']!=="") {
$pdf->Ln();
$pdf->SetFillColor(241,241,241);
$pdf->Cell(2,2,'',0,0,'C');
$pdf->Cell(131,4,'',0,0,'C',1);
$pdf->Cell(35,4,TVA.' ('.$yoyo.'%)',0,0,'R',1);
$pdf->Cell(20,4,$client['users_ship_tax'],0,0,'R',1);
}

 
if(abs($client['users_sup_ttc']) > 0) {
$pdf->Ln();
$pdf->SetFillColor(241,241,241);
$pdf->Cell(2,2,'',0,0,'C');
$pdf->Cell(131,4,'',0,0,'C',1);
$pdf->Cell(35,4,EMBALLAGE,0,0,'R',1);
$pdf->Cell(20,4,$client['users_sup_ht'],0,0,'R',1);
 
if($client['users_products_tax_statut']!=="") {
$pdf->Ln();
$pdf->SetFillColor(241,241,241);
$pdf->Cell(2,2,'',0,0,'C');
$pdf->Cell(131,4,'',0,0,'C',1);
$pdf->Cell(35,4,TVA.' ('.$taxe.'%)',0,0,'R',1);
$pdf->Cell(20,4,$client['users_sup_tax'],0,0,'R',1);
}
}

if(abs($client['users_remise_gc'])>0) {
    $sig3 = ($client['users_remise_gc']<0)? "" : "-";
    $pdf->Ln();
    $pdf->SetFillColor(241,241,241);
    $pdf->Cell(2,2,'',0,0,'C');
    $pdf->Cell(131,4,'',0,0,'C',1);
    $pdf->Cell(35,4,A22C,0,0,'R',1);
    $pdf->SetTextColor(204,0,0);
    $pdf->Cell(20,4,$sig3.sprintf("%0.2f",abs($client['users_remise_gc'])),0,0,'R',1);
    $pdf->SetTextColor(0,0,0);
}
if(abs($client['users_contre_remboursement'])>0) {
    $sig4 = ($client['users_contre_remboursement']<0)? "-" : "";
    $pdf->Ln();
    $pdf->SetFillColor(241,241,241);
    $pdf->Cell(2,2,'',0,0,'C');
    $pdf->Cell(131,4,'',0,0,'C',1);
    $pdf->Cell(35,4,A4A,0,0,'R',1);
    $pdf->Cell(20,4,$sig4.sprintf("%0.2f",sprintf("%0.2f",abs($client['users_contre_remboursement']))),0,0,'R',1);
}
$pdf->Ln();
 
$pdf->SetFillColor(241,241,241);
$pdf->Cell(2,2,'',0,0,'C');
$pdf->Cell(131,8,'',0,0,'C',1);
$pdf->SetFont('Arial','B',9);
$pdf->Cell(35,8,strtoupper(A46),0,0,'R',1);
$pdf->Cell(20,8,$client['users_total_to_pay'].' '.$symbolDevise,0,0,'R',1);
$pdf->SetFont('Arial','',9);

 
$espace = 9;
$y = $pdf->GetY()+$espace;
$pdf->SetDrawColor(204,204,204);
$pdf->Line(10,$y,200,$y);
$pdf->SetDrawColor(0,0,0);

$pdf->Output();
?>
