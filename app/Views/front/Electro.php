<canvas id='canv'></canvas>
<style>
    body {
        margin: 0;
        padding: 0;
        overflow: hidden;
        background: hsla(0, 5%, 5%, 1);
    }

    canvas {
        display: block;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
    }
</style>

<script>
    window.requestAnimFrame = (function() {
        return window.requestAnimationFrame ||
            window.webkitRequestAnimationFrame ||
            window.mozRequestAnimationFrame ||
            window.oRequestAnimationFrame ||
            window.msRequestAnimationFrame ||
            function(callback) {
                window.setTimeout(callback, 1000 / 60);
            };
    })();
    window.addEventListener('load', resize);
    window.addEventListener('resize', resize, false);
    window.addEventListener('mousemove', msmv, false);
    window.addEventListener('touchstart', ts, false);
    window.addEventListener('touchmove', tm, false);

    function msmv(e) {
        ŭ -= .5;
        var rect = e.target.getBoundingClientRect();
        msX = e.clientX - rect.left;
        msY = e.clientY - rect.top;
    }

    function ts(e) {
        if (e.touches.length == 1) {
            ŭ -= .5;
            e.preventDefault();
            msX = e.touches[0].pageX - (w.innerWidth - sw) * .5;
            msY = e.touches[0].pageY - (w.innerHeight - sh) * .5;
        }
    }

    function tm(e) {
        if (e.touches.length == 1) {
            ŭ -= .5;
            e.preventDefault();
            msX = e.touches[0].pageX - (window.innerWidth - w) * .5;
            msY = e.touches[0].pageY - (window.innerHeight - h) * .5;
        }
    }

    function resize() {
        c.width = w = window.innerWidth;
        c.height = h = window.innerHeight;
        c.style.position = 'absolute';
        c.style.left = (window.innerWidth - w) *
            .01 + 'px';
        c.style.top = (window.innerHeight - h) *
            .01 + 'px';
    }

    var num = 600;
    var FPS = 1000 / 60 >> 0;
    var ŭ = 0;

    function Part() {
        this.init.apply(this, arguments);
    }
    Part.prototype = {
        init: function(x, y) {
            this.x = x;
            this.y = y;
        },
        x: 0,
        y: 0,
        vel_x: 0,
        vel_y: 0,
        next: null
    };

    var msX = 0;
    var msY = 0;
    var first;
    var prev;

    var c = document.getElementById("canv");
    var $ = c.getContext("2d");
    var w = c.width = window.innerWidth;
    var h = c.height = window.innerHeight;
    for (var i = 0; i < num; i++) {
        var p = new Part(
            Math.random() * w,
            Math.random() * h
        );

        if (first == null) {
            first = prev = p;
        } else {
            prev.next = p;
            prev = p;
        }
    }
    run();

    function run() {
        window.requestAnimFrame(frame);
        window.requestAnimFrame(run, FPS);
    }

    function frame() {
        $.fillStyle = "hsla(0,5%,5%,1)";
        $.fillRect(0, 0, w, h);
        $.fillStyle = "hsla(" + (ŭ % 360) + ",100%,50%,1)";
        $.globalAlpha = .8;

        var gravX = msX;
        var gravY = msY;

        var f = first;

        do {
            var dX = gravX - f.x;
            var dY = gravY - f.y;
            var a = 20 / (dX * dX + dY * dY);
            var aX = a * dX;
            var aY = a * dY;

            f.vel_x += aX;
            f.vel_y += aY;
            f.x += f.vel_x;
            f.y += f.vel_y;

            f.vel_x *= 0.7;
            f.vel_y *= 0.8;

            if (f.x > w)
                f.x = 0;
            else if (f.x < 0)
                f.x = w;
            if (f.y > h)
                f.y = 0;
            else if (f.y < 0)
                f.y = h;

            $.fillRect(f.x, f.y, 15, 15);
        }
        while (f = f.next);
    }
</script>