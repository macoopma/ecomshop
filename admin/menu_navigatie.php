<?php
//session_start();

if(!isset($_SESSION['login'])) header("Location:index.php");
$tt = "Admin ".$domaine;
?>
<html>
<head>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<title><?php print $tt;?></title>
</head>
<FRAMESET ROWS='7,89%' BORDER=0 FRAMEBORDER=0 cols="*"> 
  <FRAME SRC='top.php' NAME='top' NORESIZE SCROLLING=no MARGINWIDTH=0 MARGINHEIGHT=0 FRAMEBORDER=0>
  <FRAMESET ROWS='40,93%' BORDER=0 FRAMEBORDER=0 cols="*"> 
    <FRAMESET COLS='175,87%' BORDER=0 FRAMEBORDER=0 rows="*"> 
      <FRAME SRC='menu2.php' NAME='cart' NORESIZE SCROLLING=no MARGINWIDTH=0 MARGINHEIGHT=0 BORDER=0 FRAMEBORDER=0>
      <FRAME SRC='top_pagina_midden.php' NAME='centre_haut' NORESIZE SCROLLING=no MARGINWIDTH=0 MARGINHEIGHT=0 FRAMEBORDER=0>
    </FRAMESET>
    <FRAMESET COLS='195,50%' BORDER=0 FRAMEBORDER=0> 
      <FRAME SRC='menu_links.php' NAME='menu' NORESIZE MARGINWIDTH=0 MARGINHEIGHT=0 FRAMEBORDER=0>
      <FRAME SRC='menu.php' NAME='main' MARGINWIDTH=0 MARGINHEIGHT=0 FRAMEBORDER=0>
    </FRAMESET>
  </FRAMESET>
</FRAMESET>
<NOFRAMES> 
</NOFRAMES>
</body>
</html>