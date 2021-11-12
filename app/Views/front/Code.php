<body>
<canvas id ='oldSkool'></canvas>
<script>
  var db = document.body;
  var c = document.getElementById('oldSkool');
  var $ = c.getContext('2d');
  c.width = window.innerWidth;
  c.height = window.innerHeight;
  var resume;
  function relay(){
  window.requestAnimationFrame(relay);
  resume();
}
</script>
  <!--Script to Relay!-->
<script>
  //BEGIN SCRIPT RELAY TO CODEPEN.IO... 
  
  _s = db.querySelectorAll("script")[1].innerHTML.split("\n");
  x = 0; 
  y = 1; 
  c.width = w = window.innerWidth;
  c.height = h = _s.length*20;
  db.style.margin = 0;
  db.style.background = "hsla(0,0%,0%,1)";
  db.style.overflow = "hidden";
  $.textBaseline = "top";
  $.font = "1.1em monospace";
  
//PAUSE RELAY: DRAW INSPECTOR GADGET...
/*
                ___
         _..--"\  `|`""--.._
      .-'       \  |        `'-.
     /           \_|___...----'`\
    |__,,..--""``(_)--..__      |
    '\     _.--'`.I._     ''--..'
      `''"`,#TMR/_|_\###,---'`
        ,#'  _.:`___`:-._ '#,
       #'  ,~'-;(oIo);-'~, '#
       #   `~-(  |    )=~`  #
       #       | |_  |      #
       #       ; ._. ;      #
       #  _..-;|\ - /|;-._  #
       #-'   /_ \\_// _\  '-#
     /`#    ; /__\-'__\;    #`\
    ;  #\.--|  |O  O   |'-./#  ;
    |__#/   \ _;O__O___/   \#__|
     | #\    [I_[_]__I]    /# |
     \_(#   /  |O  O   \   #)_/
           /   |        \
          /    |         \
         /    /\          \
        /     | `\         ;
       ;      \   '.       |
        \-._.__\     \_..-'/
         '.\  \-.._.-/  /'`
            \_.\    /._/
             \_.;  ;._/
           .-'-./  \.-'-.
          (___.'    '.___)
*/
//RESUME SCRIPT RELAY...
  
resume = function(){
    $.globalCompositeOperation = "source-over";
    $.shadowBlur = 0; $.fillStyle = "hsla(0,0%,0%,0.4)";
    $.fillRect(0,0,w,h);
    $.shadowColor = "hsla(120,100%,50%,0.5)";
    $.shadowBlur = 9; $.fillStyle = "hsla(120,100%,20%,1)";
    $.globalCompositeOperation = "lighter";
  _s.forEach(function(t, i) {
      if (i <= y) {
        if (i == y) { t = t.substr(0, x); }
        $.fillText(t, 100, 100+i*16);		}
	});
                 
	$.fillStyle = "hsla(120,100%,50%,1)";
	$.fillRect(100+$.measureText(_s[y].substr(0, x)).width, 102+y*16, 10, 14);
	x++;
		if (x >= _s[y].length) { y++; x = 0;}
			if (y*16 > innerHeight-200) { $.translate(0, -0.5);}
				if (y >= _s.length-1) { window.clearInterval();}}
  
relay();
  
//END SCRIPT RELAY _
</script>

</body>