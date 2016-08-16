<?php
session_start();

if(!isset($_SESSION['login'])) header("Location:index.php");


function sortProducts() {
  GLOBAL $order;
  foreach($order AS $item) {
	  $checkZZ = explode("+",$item);
	  $queryZZ = mysql_query("SELECT categories_id FROM products WHERE products_id='".$checkZZ[0]."'");
	  $rowZZ = mysql_fetch_array($queryZZ);
	  ##if(isset($splitZ[$rowZZ['categories_id']][$checkZZ[0]])) $grrrrr = $checkZZ[0]+10000; else $grrrrr = $checkZZ[0];
	  $splitZ[$rowZZ['categories_id']][] = $item."+".$rowZZ['categories_id'];
  }
  	sort($splitZ);
  	$splitZNum = count($splitZ)-1;
  	for($i=0; $i<=$splitZNum; $i++) {
    	foreach($splitZ[$i] AS $tyy) {
       	$splitR[] = $tyy;
    	}
  	}
  return $splitR;
}

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

include("lang/lang".$_SESSION['lang']."/".incLang($_SERVER['PHP_SELF']));
define("FPDF_FONTPATH","fpdf/");
$client_nic = $_GET['id'];

class PDF extends FPDF {
function Header() {
      include('../configuratie/configuratie.php');
      GLOBAL $numFact,$dateFact,$address_autre,$client_nic,$addresss,$y1,$y2,$y3;
      
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

      $addresss = explode("|",remApos($client['users_facture_adresse']));
         $split = explode("<br>",remApos($address_autre));
        $this->SetFont('times','B',16);
        $this->SetTextColor(0,0,0);
        $this->Cell(190,6,remApos($store_name),0,0,'L');                
        $this->Ln(5);
        $this->SetFont('Arial','',10);
        
        $this->Cell(95,10,remApos($address_street),0,0,'L');                        
        $this->Cell(95,4,BON_LIVRAISON.' n° BL-'.$numFact,0,0,'R');
        ##$this->SetFont('Arial','B',10);
        ##$this->SetTextColor(204,0,0);
        ##$this->Cell(17,4,'BL-'.$numFact,0,0,'R');     
        ##$this->SetFont('Arial','',10);
        ##$this->SetTextColor(0,0,0);
        $this->Ln(4);
        $this->Cell(95,10,$address_cp.' '.remApos($address_city),0,0,'L');  
        $this->Cell(95,4,A8.': '.$dateFact,0,0,'R');                      
        $this->Ln(4);
        $this->Cell(95,10,remApos($address_country),0,0,'L');   
        $this->Ln(5);
        if(!empty($tel)) {$this->Cell(95,10,'TEL : '.$tel,0,0,'L');} 
        $this->Ln(4);
        if(!empty($fax)) {
        $this->Cell(95,10,'FAX : '.$fax,0,0,'L');       
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
        $this->Cell(190,8,BON_LIVRAISON,1,0,'L',1);   
        
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
            if(!empty($addresss[1])) $this->Cell(90,10,remApos($addresss[1]),0,0,'L'); else $this->Cell(90,10,'',0,0,'L');
            $this->Cell(10,10,' ',0,0,'L');
            if(!empty($client['users_company'])) $this->Cell(90,10,remApos($client['users_company']),0,0,'L'); else $this->Cell(90,10,'',0,0,'L'); 
        $this->Ln(4);
        $this->Cell(90,10,remApos($addresss[2]),0,0,'L');
        $this->Cell(10,10,' ',0,0,'L');
        $this->Cell(90,10,remApos($client['users_address']),0,0,'L');               
            $this->Ln(4);
            if(!empty($addresss[3])) $this->Cell(90,10,remApos($addresss[3]),0,0,'L'); else  $this->Cell(90,10,'',0,0,'L');                           
            $this->Cell(10,10,' ',0,0,'L');
            if(!empty($client['users_surburb']))  $this->Cell(90,10,remApos($client['users_surburb']),0,0,'L'); else $this->Cell(90,10,'',0,0,'L');
        $this->Ln(4);
        $this->Cell(90,10,remApos($addresss[4]).' '.remApos($addresss[5]),0,0,'L');
        $this->Cell(10,10,' ',0,0,'L');
        $this->Cell(95,10,$client['users_zip'].' '.remApos($client['users_city']),0,0,'L'); 
        $this->Ln(4);
        $this->Cell(90,10,remApos($addresss[6]),0,0,'L');
        $this->Cell(10,10,' ',0,0,'L');
        $this->Cell(95,10,remApos($client['users_country']),0,0,'L'); 
        
        $this->Ln(15);
        $this->SetTextColor(204,204,204);
        $this->SetDrawColor(204,204,204);
        $this->SetFont('Arial','B',9);
        $this->Cell(190,7,REFERENCE,1,0,'L',0);  
        $this->Ln(7);
        $y2 = $this->GetY();
        $this->SetFont('Arial','U',9); 
        $this->Cell(63,5,'NIC',0,0,'C');
        $this->Cell(63,5,A10,0,0,'C');
        $this->Cell(63,5,A11,0,0,'C');
        $this->Ln(4);
        $this->SetFont('Arial','B',9);
        $this->Cell(63,5,str_replace("||","",$client['users_nic']),0,0,'C');
        $this->Cell(63,5,$client['users_password'],0,0,'C');
        $this->SetFont('Arial','',9);
        $this->Cell(63,5,$dateFact,0,0,'C');
        $this->Ln(10);
        
        $this->SetDrawColor(0,0,0);
        $this->SetLineWidth(0.5);
        $this->SetTextColor(0,0,0);
        $this->SetFont('Arial','B',13);
        $this->SetFillColor(241,241,241);
        $this->Cell(190,8,A13,1,0,'L',1);  
        $this->SetLineWidth(0.2);
        $this->Ln(8);
        $y3 = $this->GetY();
        $this->SetFont('Arial','B',9);
        $this->Cell(10,6,'X',1,0,'C');
 
        $this->Cell(105,6,A14."/".A15,1,0,'C');
  
        $this->Cell(60,6,CATEGORIE,1,0,'C');
        $this->Cell(15,6,'Aantal',1,0,'C');
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
        $this->Line(70,$y2,70,$y2+9);
        $this->Line(136,$y2,136,$y2+9);
        $this->Line(200,$y2,200,$y2+9);
}

function Footer()
{
    $this->SetY(-10);
    $this->SetFont('Arial','I',9);
    $this->SetTextColor(204,204,204);
    $this->Cell(0,5,'Pagina '.$this->PageNo().'/{nb}',0,0,'R');
    $CadreY = $this->GetY();
    $this->SetTextColor(0,0,0);
}
}


$pdf=new PDF();
$pdf->AliasNbPages();
$pdf->Open();
$pdf->AddPage();


$r3 = mysql_query("SELECT * FROM users_orders WHERE users_nic = '".$client_nic."'");
$client = mysql_fetch_array($r3);

 
function tax_price($_pays) {
         $_a = mysql_query("SELECT * FROM countries WHERE countries_name = '".$_pays."'");
         $_b = mysql_fetch_array($_a);
         $_tax = $_b['countries_shipping_tax'];
         return $_tax;
}
$yoyo = tax_price($client['users_country']);




$pdf->SetFont('Arial','',9);
$order = explode(",",$client['users_products']);
$order = sortProducts();

foreach ($order AS $item) {
    $check = explode("+",$item);
	## Categorie naam
    $queryCat = mysql_query("SELECT categories_name_".$_SESSION['lang']." FROM categories WHERE categories_id='".$check[9]."'");
    $rowCat = mysql_fetch_array($queryCat);
    
    if($check[1]!=="0" AND $check[3]!=="GC100") {
        $priceTTC = sprintf("%0.2f",($check[2] * $check[1]));
        if(!empty($check[6])) {
            $pdf->Cell(10,6,$pdf->Rect($pdf->GetX()+3,$pdf->GetY()+2,3,3),0,0,'C');
 
			$pdf->Cell(105,6,A14.": ".strtoupper($check[3]),0,0,'L');

            $pdf->Cell(60,6,$rowCat['categories_name_'.$_SESSION['lang']],0,0,'C');
  
            $pdf->Cell(15,6,$check[1],0,0,'C');
            $pdf->ln(4);
            $pdf->Cell(10,6,'',0,0,'C');
   
            $pdf->Cell(165,6,remApos($check[4]),0,0,'L');
            $pdf->ln(4); 
    
            $pdf->Cell(10,6,'',0,0,'C');
	   		$_opt = explode("|",$check[6]);
			## session update option price
			$lastArray = $_opt[count($_opt)-1];
			if(preg_match("#epz$#", $lastArray) AND is_numeric(substr($lastArray,0,-3))) unset($_opt[count($_opt)-1]);
			$ww = implode("|",$_opt);
			$newtext = str_replace("|","\n",$ww);
            $pdf->SetFont('Arial','I',6); 
            $pdf->SetTextColor(204,0,0);
            $pdf->MultiCell(165,3,$newtext,0,'L');
            $pdf->Ln(1);
            
            $pdf->SetFont('Arial','',9);
            $pdf->SetTextColor(0,0,0);
        }
        else {
            $pdf->Cell(10,6,$pdf->Rect($pdf->GetX()+3,$pdf->GetY()+2,3,3),0,0,'C');
            $pdf->Cell(105,6,A14.": ".strtoupper($check[3]),0,0,'L');
            $pdf->Cell(60,6,$rowCat['categories_name_'.$_SESSION['lang']],0,0,'C');
            $pdf->Cell(15,6,$check[1],0,0,'C');
            $pdf->ln(4);
            $pdf->Cell(10,6,'',0,0,'C');
            $pdf->Cell(165,6,remApos($check[4]),0,0,'L');
            $pdf->Ln(4);
        }
    }
}

$pdf->Output();
?>
