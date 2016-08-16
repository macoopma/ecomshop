<?php
session_start();

if(!isset($_SESSION['login'])) header("Location:index.php");
include('../configuratie/configuratie.php');
function incLang($u) {
  $fichier = explode("/",$u);
  $what = end($fichier);
  return $what;
}
include("lang/lang".$_SESSION['lang']."/".incLang($_SERVER['PHP_SELF']));
$c = "";
if(!isset($_GET['sort'])) $_GET['sort']="livraison_id";
if(isset($_GET['sort']) AND $_GET['sort']=="livraison_id") $sort="livraison_id";
if(isset($_GET['sort']) AND $_GET['sort']=="livraison_nom_".$_SESSION['lang']) $sort="livraison_nom_".$_SESSION['lang'];
if(isset($_GET['sort']) AND $_GET['sort']=="livraison_country") $sort="livraison_country";
if(isset($_GET['sort']) AND $_GET['sort']=="livraison_image") $sort="livraison_image";
if(isset($_GET['sort']) AND $_GET['sort']=="livraison_max") $sort="livraison_max";
if(isset($_GET['sort']) AND $_GET['sort']=="livraison_active") $sort="livraison_active";
if(isset($_GET['sort']) AND $_GET['sort']=="livraison_delay_1") $sort="livraison_delay_1";

if(!isset($_GET['asc'])) $_GET['asc']="DESC";
if(isset($_GET['asc']) AND $_GET['asc']=="ASC") $_GET['asc']="DESC"; else $_GET['asc']="ASC";
?>

<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p align="center" class="largeBold"><?php print EDITER_MODIFIER_MODE_LIVRAISON;?></p>

<?php
if(isset($_GET['action']) AND $_GET['action']=='delete') {
	print "<p align='center'>";
 
	$requestCheck = mysql_query("SELECT users_shipping, users_nic, users_id FROM users_orders WHERE users_shipping = '".$_GET['id']."'");
	$requestCheckNum = mysql_num_rows($requestCheck);
	if($requestCheckNum > 0) {
	    print "<table border='0' cellpadding='5' cellspacing='0' align='center' width='700' class='TABLE'><tr><td align='center'>";
	    $displayText = ($requestCheckNum>1)? LES_COMMANDES_CI_DESSOUS_ONT_ETE_PASSEES : LA_COMMANDE_A_ETE_PASSE;
	    print $displayText."<br>";
	    while($requestCheckResult = mysql_fetch_array($requestCheck)) {
	        print "<a href='detail.php?id=".$requestCheckResult['users_id']."'>".$requestCheckResult['users_nic']."</a> ";
	    }
	    print "<div><img src='im/zzz.gif' width='1' height='5'></div>";
	    print "<div class='fontrouge'>".CONSEIL_NO_SUPPRIMER."</div>";
	    print "</td></tr></table>";
	}
	## Afficher avertissement
	$requestZ = mysql_query("SELECT livraison_nom_".$_SESSION['lang']." FROM ship_mode WHERE livraison_id = '".$_GET['id']."'");
	$resultZ = mysql_fetch_array($requestZ);
	print "<br><div class='fontrouge' align='center'><b>".strtoupper($resultZ['livraison_nom_'.$_SESSION['lang']])." ".VA_ETRE_SUPPRIME."</b></div><br>";
	print "<div align='center'>".ETES_VOUS_SUR."</div>";
	print "<br>";
	print "<div align='center'><a href='verzendfirma_wijzigen.php?id=".$_GET['id']."&action=deleteConfirmed'><b>".OUI."</b></a> | <a href='verzendfirma_wijzigen.php'><b>".NON."</b></a></div>";
	print "</p>";
	exit;
}
if(isset($_GET['action']) AND $_GET['action']=='deleteConfirmed') {
	print "<p align='center'>";
	$requestZ = mysql_query("SELECT livraison_nom_".$_SESSION['lang']." FROM ship_mode WHERE livraison_id = '".$_GET['id']."'");
	$resultZ = mysql_fetch_array($requestZ);
	mysql_query("DELETE from ship_mode WHERE livraison_id='".$_GET['id']."'");
	mysql_query("DELETE from ship_price WHERE livraison_id='".$_GET['id']."'");
	print "<div class='fontrouge' align='center'><b>".strtoupper($resultZ['livraison_nom_'.$_SESSION['lang']])." ".A_ETE_SUPPRIME."</b></div>";
	print "</p>";
}



// lijst
$result = mysql_query("SELECT * FROM ship_mode ORDER BY ".$sort." ".$_GET['asc']."");
if(isset($_GET['pays']) AND $_GET['pays']!=="") print "<p align='center' style='background:#888888; padding:10px; color:#FFFFFF; border:#FFFF00 2px solid; font-size:13px';><b>".SELECT_DELIVERY." '".$_GET['pays']."'.</b><p>";
if(mysql_num_rows($result) > 0) {
print "<table border='0' cellpadding='3' cellspacing='0' align='center' class='TABLE' width='700'>";
print "<tr height='35'>";
print "<td height='19' align='left' valign='top'><a href='verzendfirma_wijzigen.php?sort=livraison_id&asc=".$_GET['asc']."' style='text-decoration:none;'><span style='color:#999999'><b>ID</b></span></a><br>&nbsp;</td>";
print "<td height='19' align='left' valign='top'><a href='verzendfirma_wijzigen.php?sort=livraison_nom_".$_SESSION['lang']."&asc=".$_GET['asc']."'><b>".NOM."</b></a><br>&nbsp;</td>";
print "<td height='19' align='left' valign='top'><a href='verzendfirma_wijzigen.php?sort=livraison_country&asc=".$_GET['asc']."'><b>".PAYS."</b></a><br>&nbsp;</td>";
print "<td height='19' align='left' valign='top'><a href='verzendfirma_wijzigen.php?sort=livraison_image&asc=".$_GET['asc']."'><b>".IMAGE."</b></a><br>&nbsp;</td>";
print "<td height='19' align='left' valign='top'><a href='verzendfirma_wijzigen.php?sort=livraison_max&asc=".$_GET['asc']."'><b>".POIDS_MAX."</b></a>&nbsp;(gr.)</td>";
print "<td height='19' align='left' valign='top' width='110'><a href='verzendfirma_wijzigen.php?sort=livraison_delay_1&asc=".$_GET['asc']."'><b>".DELAI_DE_LIVRAISON."</b></a><br>(".JOURS.")</td>";
print "<td height='19' align='center'><a href='verzendfirma_wijzigen.php?sort=livraison_active&asc=".$_GET['asc']."'><b>".ACTIVE."</b></a><br>&nbsp;</td>";
print "<td align='center'>&nbsp;</td>";
print "<td width='25' height='19' align='center'>&nbsp;</td>";
print "<td width='25' height='19' align='center'>&nbsp;</td>";


while($liv = mysql_fetch_array($result)) {
  if($c=="#F1F1F1") $c = "#E8E8E8"; else $c = "#F1F1F1";
       print "</tr><tr bgcolor='".$c."' onmouseover=\"this.style.backgroundColor='#FFFFAA';\" onmouseout=\"this.style.backgroundColor='';\">";
       print "<td>";

       print "<span style='color:#999999'>".$liv['livraison_id']."</span>";
       print "</td>";
       print "<td>";
 
       print "<a href='verzendfirma_details.php?id=".$liv['livraison_id']."' style='background:none; decoration:none'>".$liv['livraison_nom_'.$_SESSION['lang']]."</a>";
       print "</td>";
       print "<td align='left' valign='top'>";
  
          $openLeg3 = "<a href='javascript:void(0);' style='background:none' onClick=\"window.open('uitleg.php?open=liv&id=".$liv['livraison_id']."','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=250,width=510,toolbar=no,scrollbars=yes,resizable=yes');\">";
          print $openLeg3."<img src='im/map.png' border='0' title='".VOIR_LISTE."'></a>";
       print "</td>";
       print "<td align='center' valign='top'>";
   
       print ($liv['livraison_image']=="")? "--" : "<img src='im/val.gif'>";
       print "</td>";
       print "<td  align='left' valign='top' width='100'>";
    
       $maxWeight = ($liv['livraison_max']==999999999)? "--" : $liv['livraison_max'];
       print $maxWeight;
       print "</td>";
       print "<td  align='left' valign='top' width='100'>";
     
       if($liv['livraison_delay_1']==100) $liv['livraison_delay_1']="--";
       if($liv['livraison_delay_2']==100) $liv['livraison_delay_2']="--";
       if($liv['livraison_delay_1']=="--" AND $liv['livraison_delay_2']=="--") {
	   		print "--";
	   }
	   else {
	   		print $liv['livraison_delay_1']."|".$liv['livraison_delay_2'];
	   }
       print "</td>";
       print "<td align='center'>";
 
       print ($liv['livraison_active']=='yes')? "<img src='im/noPassed.gif' title='".ACTIVATED."'>" : "<img src='im/passed.gif' title='".DESACTIVE."'>";
       print "</td>";
  
       $tarifRequest = mysql_query("SELECT id FROM ship_price WHERE livraison_id = '".$liv['livraison_id']."'");
       print "<td>";
       if(mysql_num_rows($tarifRequest)==0) {
            $openLeg300 = "<a href=\"javascript:void(0);\" onClick=\"window.open('uitleg.php?open=price&id=".$liv['livraison_id']."','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=250,width=510,toolbar=no,scrollbars=yes,resizable=yes');\">";
            print "<div align='center'>".$openLeg300."<img src='im/i3.gif' width='10' height='10' border='0' title='".NO_TARIF_POUR_MODE_LIVRAISON."'></a></div>";
            print "<div align='center'><a href='verzendprijzen.php?shipId=".$liv['livraison_id']."'>".TARIF."</a></div>";
       }
       else {
            print "<div align='center'><a href='verzendprijzen.php?shipId=".$liv['livraison_id']."'>".TARIF."</a></div>";
       }
       print "</td>";
       print "<td>";
   
       print "<div align='center'><a href='verzendfirma_details.php?id=".$liv['livraison_id']."' style='background:none; decoration:none'><img src='im/details.gif' border='0' title='".MODIFIER."'></a></div>";
       print "</td>";
       print "<td>";
    
       print "<div align='center'><a href='verzendfirma_wijzigen.php?id=".$liv['livraison_id']."&action=delete' style='background:none; decoration:none'><img src='im/supprimer.gif' border='0' title='".SUPPRIMER."'></a></div>";
       print "</td>";
}

print "</tr>";
print "</table>";
print "<br><br>";

$checkLivTable=1;
if($checkLivTable==1) {
	$resultss = mysql_query("SELECT * FROM ship_mode WHERE livraison_active='yes' ORDER BY livraison_id");
	if(mysql_num_rows($resultss) > 0) {
		print "<table border='0' width='700' cellpadding='0' cellspacing='0' align='center' style='b#ackground:#FFFFFF; padding:0px; border:#CCCCCC 1px solid;'><tr>";
		print "<td align='center' style='background:#FFFFFF; padding:10px;'>";
		print "<span style='font-weight:bold; color:#CC0000'>".strtoupper(VERIF_TARIFS)."</span>";
		print "</td>";
		print "</tr><tr><td>";
		while($liv = mysql_fetch_array($resultss)) {
		    $requestLiv2 = mysql_query("SELECT livraison_nom_".$_SESSION['lang'].", livraison_country FROM ship_mode WHERE livraison_id = '".$liv['livraison_id']."'") or die (mysql_error());
		    $resultLiv2 = mysql_fetch_array($requestLiv2);
		    print "<br><div align='center'><b>&bull;&nbsp;<a href='verzendprijzen.php?shipId=".$liv['livraison_id']."'>".strtoupper($resultLiv2['livraison_nom_'.$_SESSION['lang']])."</a></b></div>";
		    if($resultLiv2['livraison_country']!=="") {
		      $payLiv2 = explode("|",$resultLiv2['livraison_country']);
		      $_zonne = array();
		      foreach($payLiv2 AS $itemsLiv2) {
		         if($itemsLiv2!=='') {
		           $requestPays2 = mysql_query("SELECT countries_shipping FROM countries WHERE countries_id = '".$itemsLiv2."'") or die (mysql_error());
		           $resultPays2 = mysql_fetch_array($requestPays2);
		           $_zonne[] = $resultPays2['countries_shipping'];
		         }
		      }
		      ## Afficher zones 
		      if(isset($_zonne) AND count($_zonne)>0) {
		          $_zonne = array_unique($_zonne);
		          sort($_zonne);
		          foreach($_zonne AS $items) {
		              print "<div align='center'>- ".$items;
						$resultssZ = mysql_query("SELECT ".$items." FROM ship_price WHERE livraison_id = '".$liv['livraison_id']."'");
						$zonePrice = array();
							while($resultssZZ = mysql_fetch_array($resultssZ)) {
								$zonePrice[] = $resultssZZ[$items];
							}
							$totalZonePrice=array_sum($zonePrice);
							if($totalZonePrice==0) {
								print " -> <a href='verzendprijzen.php?shipId=".$liv['livraison_id']."&actionZone=".$items."'><span class='fontrouge'>".A_VERIFIER."</span></a></div>";
							}
							else {
								print " -> OK</div>";
							}
		          }
		      }
		    }
		}
		print "<br></td></tr></table>";
	}
}



$contriesQuery = mysql_query("SELECT countries_id FROM countries WHERE countries_shipping != 'exclude'") or die (mysql_error());
$contriesQueryNum = mysql_num_rows($contriesQuery);
$livQuery = mysql_query("SELECT livraison_country FROM ship_mode WHERE livraison_country != '' AND livraison_active='yes'") or die (mysql_error());
$livQueryNum = mysql_num_rows($livQuery);

if($contriesQueryNum > 0 AND $livQueryNum > 0 AND !isset($_GET['pays'])) {
    while ($contriesResult = mysql_fetch_array($contriesQuery)) {
        $countries[] = $contriesResult['countries_id'];
    }
    while ($livResult = mysql_fetch_array($livQuery)) {
        $livZ[] = $livResult['livraison_country'];
    }
    if(count($livZ) >0) {
        $livZ = implode('|', $livZ);
        $livZ = str_replace('|||','|',$livZ);
        $livZ = str_replace('||','|',$livZ);
        $livArray = explode('|', $livZ);
         
        foreach($livArray as $key => $value) {
            if($value == "") unset($livArray[$key]);
        }
    }
    if(count($countries) > 0 AND count($livArray) > 0) {
        foreach($countries AS $pays) {
            if(!in_array($pays, $livArray)) $countriesNo[] = $pays;
        }
    }

    if(isset($countriesNo) AND count($countriesNo) >0) {
        foreach($countriesNo AS $paysId) {
            $contriesIdQuery = mysql_query("SELECT countries_name, countries_id FROM countries WHERE countries_id='".$paysId."'") or die (mysql_error());
            $contriesIdResult = mysql_fetch_array($contriesIdQuery);
            $contriesName[] = $contriesIdResult['countries_name']."|".$contriesIdResult['countries_id'];
        }
    }
    // landen zonder verzending 1
    if(isset($contriesName) AND count($contriesName) > 0) {
    	print "<br>";
        print "<table border='0' align='center' cellpadding='3' cellspacing='0' class='TABLE' width='700'>";
		print "<tr bgcolor='#FFFFFF'>";
        print "<td height='35' colspan='10' align='left' style=padding:10px;'>";
        print "<div align='left' style='color:#CC0000'><b>".PAYS_ACTIFS."</b></span><div>";
        print "<div align='left'>(".EXCLURE_PAYS.")</span></div>";
        print "<div align='center'><img src='im/zzz.gif' width='1' height='5'></div>";          
        print "</td>";
        print "</tr>";
	    foreach($contriesName AS $contriesNameDisplay) {
      	      $explodeContryResult = explode("|", $contriesNameDisplay);
			  print "<td align='left'>&bull;&nbsp;<a href='verzendzone.php?pays=".$explodeContryResult[0]."' style='background:none'>".$explodeContryResult[0]."</a></td></tr><tr>";
      	}
      	print "</tr></table><br><br><br>";
    }
}







 

##if(!isset($_GET['w'])) print "<p align='center'><a href='verzendfirma_wijzigen.php?w=diplayCountries'>Afficher la liste des pays par mode le livraison</a></p>";
(isset($_GET['w']) AND $_GET['w']=="diplayCountries")? $checkMode=1 : $checkMode=0;
	if($checkMode==1) {
		$requestZZZ = mysql_query("SELECT countries_id, countries_name, countries_shipping FROM countries WHERE countries_shipping!='exclude'") or die (mysql_error());
		if(mysql_num_rows($requestZZZ) > 0) {
			$couleur = "#FFFF00";
				while($paysZZZ = mysql_fetch_array($requestZZZ)) {
					$requestMode = mysql_query("SELECT livraison_id, livraison_country, livraison_nom_".$_SESSION['lang']." FROM ship_mode WHERE livraison_country LIKE '%|".$paysZZZ['countries_id']."|%' AND livraison_active='yes'") or die (mysql_error());
					$pays1 = $paysZZZ['countries_id'];
					if(mysql_num_rows($requestMode) > 0) {
						while($requestModeResult = mysql_fetch_array($requestMode)) {
							$livraison[] = $paysZZZ['countries_name']."||".$paysZZZ['countries_shipping']."||".$requestModeResult['livraison_nom_'.$_SESSION['lang']];
						}
					}
				}
		}
		// landen zonder verzending
			print "<br><br>";
			print "<table border='0' cellpadding='3' cellspacing='0' align='center' class='TABLE2'><tr>";
			print "<td><b>Pays</b></td>";
			print "<td><b>Zone</b></td>";
			print "<td><b>Mode</b></td>";
			print "</tr>";
			foreach($livraison AS $key => $value) {
				if($c=="#E8E8E8") {$c="#F1F1F1";} else {$c="#E8E8E8";}
				$d="#000000";
				$p = str_replace("||", "</td><td>", $value);
				if(isset($livraison[$key+1])) {
					$cutPays1 = explode("||", $value);
					$cutPays1 = $cutPays1[0];
					$cutPays2 = explode("||", $livraison[$key+1]);
					$cutPays2 = $cutPays2[0];
					if($cutPays1==$cutPays2) {$c="#999999"; $d="#FFFFFF";} else {$c=$c;}
				}
				if(isset($livraison[$key-1])) {
					$cutPays1 = explode("||", $value);
					$cutPays1 = $cutPays1[0];
					$cutPays2 = explode("||", $livraison[$key-1]);
					$cutPays2 = $cutPays2[0];
					if($cutPays1==$cutPays2) {$c="#999999"; $d="#FFFFFF";} else {$c=$c;}
				}
				print "<tr style='background:".$c."; color:".$d.";'><td>".$p."</td></tr><tr>";
			}
			print "</tr></table><br>";
	}
}
else {
   print "<p align='center' class='fontrouge'><b>".AUCUN_MODE_DE_LIVRAISON."</b></p>";
}
?>
</body>
</html>
