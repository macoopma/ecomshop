var ua = navigator.userAgent.toLowerCase();
var divw=0;
var divh=0;

if(document.getElementById || document.all)
	document.write('<div id="imgtrailer" style="position:absolute;visibility:hidden;"></div>')

function gettrailobject()
	{
	if(document.getElementById)
		return document.getElementById("imgtrailer")
	else if(document.all)
		return document.all.trailimagid
	}

function gettrailobj()
	{
	if(document.getElementById)
		return document.getElementById("imgtrailer").style
	else if(document.all)
		return document.all.trailimagid.style
	}

function truebody()
	{
	return (!window.opera && document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
	}

function hidetrail()
	{
	document.onmousemove='';
	gettrailobj().visibility="hidden";
	}

function trailOn(thumbimg,imgtitle,imgscription,imgsize,filesize,credit,level,thw,thh,bb)
	{
	if(ua.indexOf('opera') == -1 && ua.indexOf('safari') == -1)
		{
		maxwidth = credit;     // maximum image width.
		maxheight = credit;    // maximum image height.
		// height
		if(thh > maxheight) {
            thh1 = maxheight;
            reduch = thh1/thh;
            thw1 = thw*reduch;
        }
        else {
            thh1 = thh;
            thw1 = thw;
        }
        // width
        if(maxwidth > 0) {
            if(thw1 > maxwidth) {
                thw1 = maxwidth;
                reducw = thw1/thw;
                thh1 = thh*reducw;
            }
            if(thh1 > maxheight) {
                thh1 = maxheight;
                reduch = thh1/thh;
                thw1 = thw*reduch;
            }
        }

        thw = thw1;
        thh = thh1;
		
		if(bb!='bold') {
            bb1="";
            bb2="";
            pad=0;
            load="zzz";
            padd=0;
        }
        else {
            bb1="<b>";
            bb2="</b>";
            pad=3;
            load="loader";
            padd=5;
        }
		gettrailobj().left="-500px";
		divthw = parseInt(thw) + 2;
		gettrailobject().innerHTML = '<table><tr><td align="center"><div style="background-color: #FFFFFF; layer-background-color: #CC0000; border: 1px solid #999999; padding:'+padd+'px; width:'+divthw+'px;"><div style="background-color: #FFFFFF; layer-background-color: #FFFFFF; border: 1px solid #FFFFFF; background-image: url(im/'+load+'.gif);"><img src="'+thumbimg+'" border="0" width="'+thw+'" height="'+thh+'"><div align="center" style="padding:'+pad+'px;">'+bb1+' '+imgtitle+' '+bb2+'</div></div></div></td></tr></table>';
		gettrailobj().visibility="visible";
		divw = parseInt(thw)+25;
		divh = parseInt(thh)+130;
		document.onmousemove=followmouse;
		}
	}

function followmouse(e)
	{
	var docwidth=document.all? truebody().scrollLeft+truebody().clientWidth : pageXOffset+window.innerWidth-15
	var docheight=document.all? Math.min(truebody().scrollHeight, truebody().clientHeight) : Math.min(document.body.offsetHeight, window.innerHeight)
if(typeof e != "undefined")
	{
	if(docwidth < 15+e.pageX+divw)
		xcoord = e.pageX-divw-5;
	else
		xcoord = 15+e.pageX;
	if(docheight < 15+e.pageY+divh)
		ycoord = 15+e.pageY-Math.max(0,(divh + e.pageY - docheight - truebody().scrollTop - 30));
	else
		ycoord = 15+e.pageY;
	}
else if(typeof window.event != "undefined")
	{
	if(docwidth < 15+truebody().scrollLeft+event.clientX+divw)
		xcoord = truebody().scrollLeft-5+event.clientX-divw;
	else
		xcoord = truebody().scrollLeft+15+event.clientX;

	if(docheight < 15+truebody().scrollTop+event.clientY+divh)
		ycoord = 15+truebody().scrollTop+event.clientY-Math.max(0,(divh + event.clientY - docheight - 30));
	else
		ycoord = truebody().scrollTop+15+event.clientY;
	}
	gettrailobj().left=xcoord+"px"
	gettrailobj().top=ycoord+"px"
	}
