/*
Author: Robert Hashemian
http://www.hashemian.com/
*/

function calcage(secs, num1, num2) {
  s = ((Math.floor(secs/num1))%num2).toString();
  if(s.length < 2)
    s = "0" + s;
  return "<b>" + s + "</b>";
}

function CountBack(secs) {
  if(secs < 0) {
    document.getElementById("cntdwn").innerHTML = FinishMessage;
    return;
  }
  DisplayStr = DisplayFormat.replace(/%%D%%/g, calcage(secs,86400,100000));
  DisplayStr = DisplayStr.replace(/%%H%%/g, calcage(secs,3600,24));
  DisplayStr = DisplayStr.replace(/%%M%%/g, calcage(secs,60,60));
  DisplayStr = DisplayStr.replace(/%%S%%/g, calcage(secs,1,60));

  document.getElementById("cntdwn").innerHTML = DisplayStr;
  if(CountActive)
    setTimeout("CountBack(" + (secs-1) + ")", 990);
}

function putspan(backcolor, forecolor) {
 document.write("<span id='cntdwn' style='background-color:" + backcolor + 
                "; color:" + forecolor + "'></span>");
}

if(typeof(BackColor)=="undefined")
  BackColor = "white";
if(typeof(ForeColor)=="undefined")
  ForeColor= "black";
if(typeof(TargetDate)=="undefined")
  TargetDate = "12/31/2020 5:00 AM";
if(typeof(DisplayFormat)=="undefined")
  DisplayFormat = "%%D%% Days, %%H%% Hours, %%M%% Minutes, %%S%% Seconds.";
if(typeof(CountActive)=="undefined")
  CountActive = true;
if(typeof(FinishMessage)=="undefined")
  FinishMessage = "";
  
putspan(BackColor, ForeColor);
var dthen = new Date(TargetDate);
var dnow = new Date();
ddiff = new Date(dthen-dnow);
gsecs = Math.floor(ddiff.valueOf()/1000);
CountBack(gsecs);
