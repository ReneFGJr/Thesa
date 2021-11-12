<style>
body {
  overflow: hidden;
  background: black;
}
</style>
  
<script>
(function(){
  var ctx = Sketch.create({autoclear: false});
  var dots = [];
  var max = 100;
  var h = 0; 
  Dot = function(){
    this.init = function(){
      this.x = random(-30, ctx.width);
      this.y = ctx.height;
      this.size = 2;
      this.vx = random(-3, 3);
      this.vy = random(-8);
      
      this.color = "hsl("+ h +", 100%, 50%)";
      h += .1;
      if(h > 360){
        h=0;
      }
      this.life = 0;
      this.gravity = .05;
      this.maxLife = 1000;
    };
    this.update = function(){
      this.x += this.vx;
      this.y += this.vy;
      this.vy += this.gravity;
      this.size += .01;
      this.life++;
    };
    this.draw = function(){
      ctx.fillStyle = this.color;
      ctx.fillRect(this.x, this.y, this.size, this.size);
    };
  }
  ctx.update = function(){
    for(var i=0; i<dots.length;i++){
      dots[i].update();
      if(dots[i].y - dots[i].size > ctx.height || dots[i].y + dots[i].size < 0 || dots[i].x - dots[i].size > ctx.width || dots[i].x + dots[i].size < 0 || dots[i].life > dots[i].maxLife){
        this.makeDot(dots.splice(i, 1)[0]);
      }
    }
  };
  ctx.setup = function(){
    for(var i=0; i<max; i++){
      var dot = new Dot();
      dot.init();
      dots.push(dot);
    }
  };
  ctx.draw = function(){
    ctx.fillStyle = "rgba(0,0,0,.1)";
    ctx.fillRect(0,0,ctx.width, ctx.height);
    for(var i=0; i<dots.length; i++){
      dots[i].draw();
    }
  };
  ctx.makeDot = function(reuseDot) {
    var dot = reuseDot ? reuseDot : new Dot();
    dot.init();
    dots.push(dot);
  };
  
})();
</script>