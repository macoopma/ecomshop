<HTML>
<HEAD>
<!-- http://purecss.io/ 


<link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/pure-min.css">
--> 
<STYLE>

BODY {font-family: Tahoma, Geneva, sans-serif; 
      margin:5}

BODY2 {font-family: Century Gothic, sans-serif;
      margin:5}
h2 {font-family: Georgia, Serif;
	color:white;
	background: #244A88;
	border: 1px solid #ddd;
	margin: 15px 0;
	padding: 15px;
}

table tr:hover td {
	background: #f2f2f2;
	background: -webkit-gradient(linear, left top, left bottom, from(#f2f2f2), to(#f0f0f0));
	background: -moz-linear-gradient(top,  #f2f2f2,  #f0f0f0);	
}

pre {
    font-size: 90%;
    line-height: 1.2em;
    font-family: "Courier 10 Pitch", Courier, monospace; 
    white-space: pre; 
    white-space: pre-wrap; 
    white-space: -moz-pre-wrap; 
    white-space: -o-pre-wrap; 

    height:1%;
    width: auto;
    display: block;
    clear: both;
    color: #555555;
    padding: 1em 1em;
    margin: auto 40px auto 40px;
    background: #f4f4f4;
    border: solid 1px #e1e1e1
} 
code { 
    font-size: 90%;
    font-family: Monaco, Consolas, "Andale Mono", "DejaVu Sans Mono", monospace; 
    color: #555555;
    border: 1px solid #ffffff;
	padding: 0 8px;
	white-space: nowrap;
    border-radius: 20px;
    display: inline-block;
    background: #d4d4d4;
}

.todo {
	font-size: 90%;
    color: #ffffff;
    border: 1px solid #ffffff;
	padding: 0 8px;
	border-radius: 20px;
    background: #B4CD3E;
	white-space: nowrap;
	display: inline-block;
}

.new {
    color: #ffffff;
    border: 1px solid #ffffff;
	padding: 0 8px;
	border-radius: 20px;
    background: #D34B4D;
	white-space: nowrap;
	display: inline-block;
}

table{border-collapse:collapse;border-spacing:0}
td,th{
		padding:0;
		vertical-align:top
		}

		.pure-table {border-collapse:collapse;border-spacing:0;empty-cells:show;border:1px solid #cbcbcb}
.pure-table caption{color:#000;font:italic 85%/1 arial,sans-serif;padding:1em 0;text-align:center}
.pure-table td,.pure-table th{border-left:1px solid #cbcbcb;border-width:0 0 0 1px;font-size:inherit;margin:0;overflow:visible;padding:.3em .3em}
.pure-table td:first-child,.pure-table th:first-child{border-left-width:0}
.pure-table thead{background-color:#e0e0e0;color:#000;text-align:left;vertical-align:bottom}
.pure-table td{background-color:transparent}
.pure-table-odd td{background-color:#f2f2f2}
.pure-table-striped tr:nth-child(2n-1) td{background-color:#f2f2f2}
.pure-table-bordered td{border-bottom:1px solid #cbcbcb}
.pure-table-bordered tbody>tr:last-child>td{border-bottom-width:0}
.pure-table-horizontal td,.pure-table-horizontal th{border-width:0 0 1px;border-bottom:1px solid #cbcbcb}
.pure-table-horizontal tbody>tr:last-child>td{border-bottom-width:0}


</STYLE>
<TITLE>Updates</TITLE>
</HEAD>
<BODY>
<CENTER>
<H2>Updates </H2>
</CENTER>
<DIV class=new>Dit zijn nieuwe files die ik zelf gemaakt heb</DIV>
<BR><DIV class=todo>Dit zijn dingen die ik nog moet doen </DIV>
<P>
<CENTER>
<TABLE class="pure-table pure-table-bordered pure-table-striped " width=80%>
<THEAD>
<TR><TH> Datum </TH><TH>Folder </TH><TH>File  </TH> <TH>Opmerking </TH>
</TR>
</THEAD>
<TBODY>
<TR>
<TD>22MAR2015 </TD><TD> / </TD><TD> /mini_maker.php </TD><TD> kleine aanpassing rond lijn 40 voor compatibiliteit met PHP 5.3</TD>
</TR>
<TR>
<TD> 30APR2015 </TD><TD>/admin</TD> <TD>/admin/detail.php </TD><TD> van het factuurnummer een editeerbaar veld gemaakt, en nakijken bij de aanroep of het niet net aangepast is, en indien wel de update wegschrijven. Dit is zo'n script dat zichzelf aanroept met <code>"action=write"</code> om dan de configuratie file helemaal opnieuw te schrijven. </TD>
</TR> 
<TR>
<TD> 30APR2015 </TD><TD> /includes</TD><TD> /includes/factuur_nummer_maken.php</TD><TD> automatisch aanmaken van factuurnummer afgezet </TD> 
</TR>

<TR>
<TD> 30APR2015 </TD><TD>/klantlogin </TD><TD> /klantlogin/login.php  </TD> <TD> op lijn 817 de filter iets strenger gemaakt zodat de klant zelf geen factuur meer kan maken als er nog geen factuurnummer gemaakt is, en die moeten we nu met de hand ingeven, dat doen we in <code>/admin/detail.php</code> </TD>
</TR>


<TR>
<TD> &nbsp </TD><TD>/mollie </TD><TD> <DIV class="new">/mollie/mollie_payment.php </DIV> </TD> <TD>  Hiernaar wordt gelinked vanuit selecteer_betaling.php en vanuit direct_betalen.php . Er wordt een update in de database gedaan, en op het einde wordt automatisch  mollie_payment_exec.php aangeroepen</TD>
</TR>
<TR >
<TD> &nbsp </TD><TD>/mollie </TD><TD><DIV class="new"> /mollie/mollie_payment_exec.php  </DIV></TD> <TD> Deze wordt vanuit mollie_payment.php aangeroepen, er wordt een Mollie payment object in aangemaakt en op het einde wordt er automatisch naar de Mollie website doorgegaan.</TD>
</TR>
<TR>
<TD> &nbsp </TD><TD>/mollie </TD><TD><DIV class="new"> /mollie/mollie_return.php </DIV></TD> <TD>Na de betaling via Mollie wordt deze pagina aangeroepen. Er staat niet meer op dan een bedankje. <DIV class=todo>Ik zou deze iets meer kunnen doen lijken op <code> /bevestigd.php</code> </DIV> </TD>
</TR>

<TR>
<TD> &nbsp </TD><TD>/mollie </TD><TD><DIV class="new"> /mollie/mollie_verify.php </DIV> </TD> <TD>Eens Mollie de bevestiging/cancellatie gekregen heeft van de betaling zal die deze pagina aanroepen, er worden wat database updates gedaan, en een van de twee email PHP scripts aangeroepen. </TD>
</TR>

<TR>
<TD> &nbsp </TD><TD>/mollie </TD><TD> <DIV class="new">/mollie/email-complete-inc.php</DIV>  </TD> <TD>Spreekt voor zich, deze wordt aangeroepen vanuit mollie_verify.php . <DIV class="todo">De email staat nog maar in 1 taal</DIV> </TD>
</TR>

<TR>
<TD> &nbsp </TD><TD>/mollie </TD><TD> <DIV class="new">/mollie/email-failed-inc.php</DIV>  </TD> <TD>Spreekt voor zich, deze wordt aangeroepen vanuit mollie_verify.php . <DIV class="todo">De email staat nog maar in 1 taal</DIV>  </TD>
</TR>
<TR>
<TD> &nbsp </TD><TD>/mollie-api </TD><TD> <DIV class="new">*.*</DIV>  </TD> <TD>Gewoon een kopie van de de API folder van mollie zonder er iets in te wijzigen. Die heb ik <A href="https://www.mollie.com/be/modules">hier</A> gevonden.  </TD>
</TR>

<TR>
<TD> &nbsp </TD><TD>/configuratie </TD><TD> /configuratie/configuratie.php</TD> <TD> Een aantal variabelen aan toegevoegd: <UL><LI>MolliePayment<LI>MollieKey<LI>MollieReturn</UL> </TD>
</TR>
<TR>
<TD> &nbsp </TD><TD>/admin </TD><TD> /admin/site_config.php  </TD> <TD>Deze dient om de configuratie van de site te doen, ik heb er in de menu bovenaan iets bijgestopt om naar de Mollie sectie onderaan te linken, en dan onderaan een deel gemaakt om de betaling met Mollie aan/uit te zetten en de parameters in te geven. Dit is zo'n script dat zichzelf aanroept met <code>"action=write"</code> om dan de configuratie file helemaal opnieuw te schrijven.</TD>
</TR>
<TR>
<TD> &nbsp </TD><TD>/admin </TD><TD> /admin/detail.php  </TD> <TD>Ergens op lijn 1007 paymode13 uitgevonden en rond lijn 1023 een optie toegevoegd voor betlaing met Mollie in een popup lijst. Dit is de tweede update in deze file.</TD>
</TR>
<TR>
<TD> &nbsp </TD><TD>/ </TD><TD> /selecteer_betaling.php  </TD> <TD>Een heel stuk bijgemaakt om de mogelijkheid tot betalen met mollie te kunnen aanbieden, enkel wanneer die in de configuratie aangezet is. Er wordt een Mollie object aangemaakt, en dat wordt gebruikt om de kanalen die mogeglijk zijn bij Mollie op te vragen en de juiste logos te laten zien.</TD>
</TR>

<TR>
<TD> &nbsp </TD><TD>/ </TD><TD> /direct_betalen.php  </TD> <TD>Bijna hetzelfde dan de selecteer_betaling.php enkel gaat het hier om een soort shortcut.</TD>
</TR>
<TR>
<TD> &nbsp </TD><TD>/includes/lang </TD><TD> /includes/lang/lang3.php  </TD> <TD>Een constante toegevoegd voor <code>CLIQUEZ_SUR_LE_LOGO_MOLLIE </code> <DIV class=todo>Die zou ik in de andere talen ook moeten toevoegen</DIV> </TD>
</TR>

<TR>
<TD> &nbsp </TD><TD>/includes </TD><TD> /includes/tabel_top1.php</TD> <TD>Een probleempje moeten oplossen indien deze niet vanuit zijn de "/" folder wordt opgeroepen, opgelost met <CODE>if isset($newfol) ...</CODE>  </TD>
</TR>
<TR>
<TD> &nbsp </TD><TD>/includes </TD><TD> /includes/menu_tab.php  </TD> <TD> Iets moeten fixen omdat een constante "HOME" niet bestaat als deze door &eacute;&eacute;n van m'n eigen scripts wordt aangeroepen, dus iets gemaakt met <CODE> if (!defined("HOME") )   {DEFINE("HOME","Home"); } </CODE>  </TD>
</TR>

<TR>
<TD> &nbsp </TD><TD>/includes </TD><TD> /includes/plug.inc.php  </TD> <TD> Ik denk dat ik hier enkel wat PHP_EOL aan de print heb gehangen om het geheel wat leesbaarder te maken, maar niks functioneel aangedaan. </TD>
</TR>


<!--
<TR>
<TD> &nbsp </TD><TD>/ </TD><TD> /.php  </TD> <TD>  </TD>
</TR>
-->


</TBODY>
</TABLE>
</CENTER>
<P>
<HR>
Van elke aangepaste file staat er in dezelfde folder nog het origineel , en dat is gewoon hernoemd in ORIG_....php 
<BR>Als er een tweede update gedaan wordt komt er een ORIG_1_....php zodat de allereerste altijd ORIG_....php zal blijven, en dus eigenlijk een soort ORIG_0_....php is.
</BODY>
</HTML>
