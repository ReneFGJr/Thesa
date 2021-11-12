<canvas id="thesa_canvas"></canvas>
<h2 id="title"><?php echo $title;?></h2>
<h5 id="subtitle"><?php echo $subtitle;?></h5>

<style>
@import url(https://fonts.googleapis.com/css?family=Poiret+One);

h2{
  font-family: 'Poiret One', cursive;
  left: 75%;
  position: absolute;
  top: 50px;
  transform: translate( -50%, -50%);
  font-size:4.0em;
  letter-spacing:10px;
  color:white;
}   
h5{
  font-family: 'Poiret One', cursive;
  left: 75%;
  position: absolute;
  top: 100px;
  transform: translate( -50%, -50%);
  font-size:1.6em;
  letter-spacing:1px;
  color: white;
} 
</style>

<script>
function anim() {
  var c = document.getElementById("thesa_canvas");
  var $ = c.getContext("2d");
  var w = c.width = window.innerWidth/2;;
  var h = c.height = window.innerHeight;

  var cosang, sinang;
  var c2;
  var $$;
  var t;
  var scd, scr;
  var dia;
  var cx;
  var cy;
  var pcnt;
  var maxsc;
  var scy;
  var u = 0;

  var delay = 1;
  var cnt = delay - 1;
  var num = 5;
  var pov = 300;
  var midX = w / 2;
  var midY = h / 2;
  var parr = {};
  var darr = {};
  var rndSp = 0.13;
  var rad = 8;
  var dia = 2 * rad;
  var sz = 140;
  var scy = 0.95;
  var maxZ = Math.sqrt(2) * sz;
  var maxsc = 0.5 * Math.ceil(2 * rad * pov / (pov - maxZ));
  var sp = 2 * Math.PI / 1200;
  var ang = 0;
  var st = Math.random() * (Math.PI * 2)
  var inct = 2 * Math.PI / (4500);
  var p1 = Math.random() * (Math.PI * 2);
  var p2 = Math.random() * (Math.PI * 2);

  draw();
  run();

  function run() {
    window.requestAnimationFrame(run);
    go();
  }

  function draw() {
    var mind = 8;
    var maxd = 2 * maxsc;
    var _x = 0;
    var diam;
    var z;
    var alpha;
    var _midX;
    var _midY;
    var rad;
    var g;

    c2 = document.createElement('canvas');
    c2.width = maxd * (maxd + 1) / 2;
    c2.height = maxd;
    $$ = c2.getContext("2d");

    for (diam = mind; diam <= maxd; diam++) {
      rad = diam / 2;
      var g1 = 'hsla(' + diam * maxd + ',85%, 60%,0)';
      var g2 = 'hsla(' + diam * maxd + ',95%, 60%,.2)';

      _midX = _x + rad;
      _midY = rad;
      g = $$.createRadialGradient(_midX, _midY, 0, _midX, _midY, rad);
      g.addColorStop(0.0, g1);
      g.addColorStop(0.5, g1);
      g.addColorStop(1, g2);
      $$.fillStyle = g;
      $$.beginPath();
      $$.arc(_midX, _midY, rad, 0, 2 * Math.PI, false);
      $$.closePath();
      $$.fill();

      _x += diam;
    }
  }

  function go() {
    grav = 0;
    t = new Date();
    cnt++;

    if (cnt >= delay) {
      cnt = 0;
      for (i = 0; i < num; i++) {
        st = (st + inct) % (Math.PI * 2);
        _x = sz * Math.sin(8 * st + p1);
        _y = scy * sz * Math.cos(5 * st + p2);
        _z = sz * Math.cos(7 * st + 0.8 * Math.cos(3 * st));
        var p = add(_x, _y, _z, 0, 0, 0);
        p.solid = 340;
        p.fade = 401;
      }
    }
    ang = (ang + sp) % (Math.PI * 2);
    sinang = Math.sin(ang);
    cosang = Math.cos(ang);
    $.globalCompositeOperation = 'source-over';
    $.globalAlpha = 1;
    $.fillStyle = "hsla(100%,100%,100%,1)";
    $.fillRect(0, 0, w, h);
    $.globalCompositeOperation = 'lighter';
    pcnt = 0;
    p = parr.first;
    while (p != null) {
      nxt = p.next;
      p.age++;

      if (p.age > p.solid) {
        p.velX += rndSp * (Math.random() * 2 - 1);
        p.velY += rndSp * (Math.random() * 2 - 1);
        p.velZ += rndSp * (Math.random() * 2 - 1);

        p.x += p.velX;
        p.y += p.velY;
        p.z += p.velZ;
        $.globalAlpha = .8 - (p.age - p.solid) / (p.fade - p.solid);
      }
      rotX = cosang * p.x + sinang * p.z;
      rotZ = -sinang * p.x + cosang * p.z;
      m = pov / (pov - rotZ);
      p.px = rotX * m + midX;
      p.py = p.y * m + midY;
      if (p.age >= p.fade) {
        p.dead = true;
      }
      if (rotZ > maxZ) {
        inV = true;
      } else {
        inV = false;
      }
      if (p.dead) {
        clear(p);
      } else if (!inV) {
        scd = Math.round(m * dia);
        scr = 0.5 * scd;
        cx = 0.5 * (scd - 1) * scd;
        if (scr <= maxsc) {
          $.drawImage(c2, cx, 0, scd, scd,
            p.px - scr, p.py - scr, scd, scd);

          pcnt++;
        }
      }
      p = nxt;
    }
  }

  function add(_x, _y, _z, _vx, _vy, _vz) {
    var newp;
    if (darr.first != null) {
      newp = darr.first;
      if (newp.next != null) {
        darr.first = newp.next;
        newp.next.prev = null;
      } else {
        darr.first = null;
      }
    } else {
      newp = {};
    }
    if (parr.first == null) {
      parr.first = newp;
      newp.prev = null;
      newp.next = null;
    } else {
      newp.next = parr.first;
      parr.first.prev = newp;
      parr.first = newp;
      newp.prev = null;
    }
    newp.x = _x;
    newp.y = _y;
    newp.z = _z;
    newp.velX = _vx;
    newp.velY = _vy;
    newp.velZ = _vz;
    newp.age = 0;
    newp.dead = false;
    return newp;
  }

  function clear(p) {
    if (parr.first == p) {
      if (p.next != null) {
        p.next.prev = null;
        parr.first = p.next;
      } else {
        parr.first = null;
      }
    } else {
      if (p.next == null) {
        p.prev.next = null;
      } else {
        p.prev.next = p.next;
        p.next.prev = p.prev;
      }
    }
    if (darr.first == null) {
      darr.first = p;
      p.prev = null;
      p.next = null;
    } else {
      p.next = darr.first;
      darr.first.prev = p;
      darr.first = p;
      p.prev = null;
    }
  }
}
anim();

window.addEventListener('resize', function() {
  c.width = w = window.innerWidth;
  c.height = h = window.innerHeight;
}, false);
</script>