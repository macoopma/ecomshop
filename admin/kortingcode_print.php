<?php
session_start();

if(!isset($_SESSION['login'])) header("Location:index.php");
require("fpdf/fpdf.php");
include('../configuratie/configuratie.php');
function incLang($u) {
  $fichier = explode("/",$u);
  $what = end($fichier);
  return $what;
}

function dateFr($fromDate,$langId) {
     $_qq = explode(" ",$fromDate);
   	 $_qq1 = explode("-",$_qq[0]);
   	 if($langId==1 OR $langId==3) $_qq3 = $_qq1[2]."/".$_qq1[1]."/".$_qq1[0];
   	 if($langId==2) $_qq3 = $_qq[0];
   	 return $_qq3;
}
include("lang/lang".$_SESSION['lang']."/".incLang($_SERVER['PHP_SELF']));

$itemsName = "";
 
function remApos($rem) {
    $_rem = str_replace("&#146;","'",$rem);
    return $_rem;
}
 
$query = mysql_query("SELECT code_promo_reduction, code_promo_end, code_promo, code_promo_type, code_promo_items, code_promo_stat, code_promo_seuil
                        FROM code_promo
                        WHERE code_promo = '".$_GET['cert']."'
                        ");
$row = mysql_fetch_array($query);
$amount = $row['code_promo_reduction'];
$seuil = $row['code_promo_seuil'];
$valide = dateFr($row['code_promo_end'],$_SESSION['lang']);
 
if($row['code_promo_items']!=="") {
   $items = explode("|",$row['code_promo_items']);
   foreach($items AS $item) {
      $queryItems = mysql_query("SELECT products_name_".$_SESSION['lang']." FROM products WHERE products_id = '".$item."'");
      $itemNameSelect = mysql_fetch_array($queryItems);
      $itemName[] = $itemNameSelect['products_name_'.$_SESSION['lang']]; 
   }
   $itemsName = implode(", ",$itemName);
}

if($itemsName=="") {
   $offreValable = " - ".OFFRE_VALABLE;
   $ss1 = 0;
}
else {
   $offreValable = " - ".OFFRE_VALABLE_ARTICLES;
   $ss1 = 1;
}
 
if($row['code_promo_stat']=="prive") {
   $public = NON_CUMULABLE.". ".UTILISABLE_PRIVEE." ".$store_name;
}
else {
   $public = NON_CUMULABLE.". ".UTILISABLE_PUBLIC." ".$store_name;
}

define("FPDF_FONTPATH","../admin/fpdf/");
$pdf=new FPDF();
$pdf->Open();
$pdf->AddPage();

$pdf->Image('../geschenkbon/im/achtergrond.jpg',10,10,0,0,'JPG');

 
$pdf->SetFont('Arial','B',25);
$pdf->Ln(2);
$pdf->SetTextColor(220,50,50);
$pdf->Cell(141,10,CODE_MIN." : ".$row['code_promo'],0,0,'C');
$pdf->SetTextColor(0,0,0);

if(isset($seuil) AND $seuil > 0) {
   $pdf->SetFont('Arial','i',7);
   $pdf->Ln(10);
   $pdf->Cell(141,5,"- ".POUR_UN_ACHAT." ".$seuil." ".$devise." -",0,0,'C');
}
else {
   $pdf->SetFont('Arial','i',7);
   $pdf->Ln(10);
   $pdf->Cell(141,5,"",0,0,'C');
}

 
$pdf->SetFont('Arial','B',20);
$pdf->Ln(5);
$pdf->Cell(141,10,"Een korting van ".$amount." ".$row['code_promo_type'],0,0,'C');

 
$pdf->SetFont('Arial','i',10);
$pdf->Ln(7);
$pdf->Cell(141,10,LE_MONTANT_DE_CE_CODE." :",0,0,'C');

 
$pdf->Ln(6.5);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(141,10,"http://".$www2.remApos($domaineFull),0,0,'C');

 
$pdf->SetFont('Arial','I',6);
$pdf->Ln(12.5);
$pdf->Cell(141,10,VALIDE_JUSQUAU." ".$valide.$offreValable,0,0,'C');

 
$pdf->SetFont('Arial','I',6);
$pdf->Ln(2);
$pdf->Cell(141,10,$public,0,0,'C');

 
if($ss1==1) {
    $pdf->SetFillColor(241,241,241);
    $pdf->Ln(8);
    
    $pdf->SetFont('Arial','UB',8);
    $y1 = $pdf->GetY();
  
    if(count($itemName)>1) $pdf->Cell(141,4.5,ARTICLES_SOUMI,0,1,'L',1); else $pdf->Cell(141,4.5,ARTICLE_SOUMI,0,1,'L',1);
    
   
    $pdf->SetFont('Arial','',8);
    foreach($itemName AS $it) {
       $pdf->Cell(141,4.5,"- ".$it,0,1,'L',1);
    }
    
    $pdf->SetFont('Arial','I',6);
    $pdf->Cell(141,4.5,$store_company,0,1,'R',1);
    $y2 = $pdf->GetY();
    
     
    $diff = $y2-$y1;
    $pdf->SetDrawColor(204,204,204);
    $pdf->SetLineWidth(0.3);
    $pdf->Line(10,$y1,10,$y1+$diff);  
    $pdf->Line(151,$y1,151,$y1+$diff); 
    $pdf->Line(10,$y1+$diff,151,$y1+$diff); 
    $pdf->Line(10,$y1,151,$y1);
}

$pdf->Output();
?>
