<?php
 
require("../admin/fpdf/fpdf.php");
include('../configuration/configuratie.php');

$la_link = "../includes/lang/geschenkbon/lang".$_GET['l']."/index.php";
include($la_link);

function remApos($rem) {
    $_rem = str_replace("&#146;","'",$rem);
    return $_rem;
}

$query = mysql_query("SELECT gc_amount, gc_end, gc_number FROM gc WHERE gc_number = '".$_GET['cert']."'");
$row = mysql_fetch_array($query);
$amount = $row['gc_amount'];
$valideExp = explode("-",$row['gc_end']);
$valide = mktime(0,0,0,$valideExp[1], $valideExp[2],$valideExp[0]);
$valide = date("d M Y",$valide);

define("FPDF_FONTPATH","../admin/fpdf/");
$pdf=new FPDF();
$pdf->Open();
$pdf->AddPage();

$pdf->Image('im/gc.jpg',10,10,0,0,'JPG');

 
$pdf->SetFont('Arial','B',25);
$pdf->Ln(7);
$pdf->SetTextColor(220,50,50);
$pdf->Cell(141,10,CHEQUE_CADEAU_MIN,0,0,'C');
$pdf->SetTextColor(0,0,0);

 
$pdf->SetFont('Arial','B',20);
$pdf->Ln(10);
$pdf->Cell(141,10,$amount." ".$symbolDevise,0,0,'C');

 
$pdf->SetFont('Arial','',10);
$pdf->Ln(7);
$pdf->Cell(141,10,LE_MONTANT_DE_CE_CHEQUE." :",0,0,'C');

 
$pdf->Ln(6);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(141,10,"http://".$www2.remApos($domaineFull),0,0,'C');

 
$pdf->SetFont('Arial','I',8);
$pdf->Ln(7);
$pdf->Cell(141,10,INT2." : ".$row['gc_number'],0,0,'C');

 
$pdf->SetFont('Arial','I',6);
$pdf->Ln(6);
$pdf->Cell(141,10,VALIDE_JUSQUAU." ".$valide,0,0,'C');

 
$pdf->SetFont('Arial','I',6);
$pdf->Ln(2);
$pdf->Cell(141,10,NON_CUMULABLE." ".$store_name.".",0,0,'C');

$pdf->Output();
?>
