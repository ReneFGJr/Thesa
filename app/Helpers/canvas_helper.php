<?php

        function graph_demo()
            {
                $file = 'D:\GoogleDrive\Artigos\2021\EmQuestão - Identidade\cocitacao.txt';
                $txt = file_get_contents($file);
                
                $ln = explode(chr(13),$txt);
                $rv = array();
                for ($r=0;$r < count($ln);$r++)
                    {
                        $l = explode('#',$ln[$r]);
                        if (isset($l[1]))
                        {
                        if (isset($rv[$l[0]]))
                            {
                                $rv[$l[0]] .= ';'.$l[1];
                            } else {
                                $rv[$l[0]] = $l[1];
                            }
                        }
                    }
                foreach($rv as $id => $ln)
                    {
                        echo $ln.'<br>';
                    }
            }
        /*********************************************************************************** Open */
        function canvas_open($cv,$x,$y)
            {
                $sx = '';
                $sx .= '<canvas id="'.$cv.'" width="'.$x.'" height="'.$y.'"';
                $sx .= ' style="width: 100%; border:1px solid #000000;"';
                $sx .= '></canvas>'.cr();
                $sx .= '<script>'.cr();
                $sx .= 'var canvas = document.getElementById("'.$cv.'");'.cr();
                $sx .= 'var context = canvas.getContext("2d");'.cr();
                return $sx;
            }
        /*********************************************************************************** Close */
        function canvas_close()
            {                
                    $sx = '</script>'.cr();
                    return $sx;
            }

        /*********************************************************************************** Rect */
        function canvas_rect($x,$y,$w,$z,$cor = 'green')
            {
                    
                    $sx = 'context.fillStyle = "'.$cor.'";'.cr();
                    //$sx = 'content.fillStyle = "rgba(0, 0, 200, 0.5)";'.cr();
                    //$sx .= 'context.fillRect(10, 10, 150, 100);';
                    $sx .= "context.fillRect($x,$y,$w,$z);".cr();
                    return $sx;
            }
        /************************************************************************************ TESTE */
        function canvas_test()
             {

                $sx = canvas_open("canvas",768,1024);
                $sx .= '                
                const canvas = document.getElementById("canvas");
                const ctx = canvas.getContext("2d");

                ctx.fillStyle = "green";
                ctx.fillRect(10, 10, 150, 100);
                </script>';

                $sx = bs(bsc($sx,12));
                return $sx;
            }
        /*************************************************************************************** TEXT */
        function canvas_text($x,$y,$txt='',$xcor='black',$sz = 8,$alg='')
            {
                global $cv;
                $sx = 'context.font = "bold '.$sz.'pt Calibri";'.cr();
                $sx .= 'context.globalAlpha = 1;'.cr();
                if ($alg=='right') { $sx .= 'context.textAlign = "right";'.cr(); }
                //$sx .= 'context.textBaseline = "middle";'.cr();
                $sx .= 'context.fillStyle = "'.$xcor.'";'.cr();
                $sx .= 'context.fillText("'.$txt.'", '.$x.', '.$y.');'.cr();
                return $sx;
            } 
        function canvas_line($x,$y,$z,$w,$xcor='black',$size=1)
            {
                $sx = '';
                $sx .= 'context.beginPath();'.cr();
                $sx .= 'context.lineWidth = '.$size.';'.cr();
                $sx .= 'context.moveTo('.$x.', '.$y.');'.cr();
                $sx .= 'context.lineTo('.$z.', '.$w.');'.cr();
                $sx .= 'context.strokeStyle = "'.$xcor.'";'.cr();
                $sx .= 'context.stroke();'.cr();
                return $sx;
            }
        /*************************************************************************************** CIRCLE */   
        function canvas_circle($x,$y,$r,$xcor='')
            {
                global $opacy;
                if (!isset($opacy)) { $opacy = 0.5; }
                $b = 1;
                $sx = ' context.beginPath();
                        context.arc('.$x.', '.$y.', '.$r.', 0, 2 * Math.PI, false);
                        context.fillStyle = "'.$xcor.'";
                        context.fill();
                        context.globalAlpha = '.$opacy.';
                        context.lineWidth = '.$b.';
                        context.strokeStyle = "#330000";
                        context.stroke();'.cr();
                return $sx;
            }    

        function graph_bubble($dt)
            {
                global $multi,$cv;
                $idc = 'MyCv';
                $cv = $idc;
                $cor =array('#000000','#008000','#ff0000','#ff00ff','#404000','#0000ff','#008000','#ff0000','#ff00ff','#404000','#0000ff','#008000','#ff0000','#ff00ff','#404000','#0000ff','#008000','#ff0000','#ff00ff','#404000','#0000ff','#008000','#ff0000','#ff00ff','#404000','#0000ff','#008000','#ff0000','#ff00ff','#404000','#0000ff','#008000','#ff0000','#ff00ff','#404000','#0000ff');
                $offsetx = 400;
                $offsety = 60;
                $linespace = 20;
                
                $sx = '';                
                $sx .= $this->canvas($idc,1024,768);

                $sx .= legends('xxxxxxxxxxxxxx');

                for ($y=0;$y < count($dt);$y++)
                {
                    $txt = $dt[$y][0];
                    $sx .= canva_text(
                                $offsetx,
                                $offsety+$y*$linespace,
                                $txt,
                                $idc,
                                $cor[0],
                                10);
                for ($r=1;$r < count($dt[$y]);$r++)
                    {
                        $vlr = round($dt[$y][$r] * $multi);
                        $sx .= canva_circle(
                                    $r*30+$offsetx,
                                    $offsety+$y*$linespace,
                                    $vlr,
                                    $idc,
                                    $cor[$y]
                                    );
                    }
                }

              
                
                return $sx;
            }

        function legends($txt,$sz = 10,$x=-10,$y=432)
            {
                global $cv;
                $xo = $x;
                $yo = $y;
                $sp = 30;
                $xcor = '#000000';
                $rt = (2 * 3.1416) * 270 / 360;
                $sx = '
                    context.font = "'.$sz.'pt Calibri";
                    context.globalAlpha = 1;
                    context.rotate('.$rt.');
                    // textAlign aligns text horizontally relative to placement
                    context.textAlign = "right";
                    // textBaseline aligns text vertically relative to font style
                    context.textBaseline = "middle";
                    context.fillStyle = "'.$xcor.'";
                    ';
                for ($r=2003;$r <= 2020;$r++)
                    {
                        $sx .= 'context.fillText("'.$r.'", '.$x.', '.$y.');'.cr();
                        $y = $y + $sp;
                    }

                $sx .= 'context.rotate(-'.$rt.');';
                $x = $yo-2; 
                for ($r=2003;$r <= 2020;$r++)
                    {
                        $sx .= '
                        context.beginPath();
                        context.moveTo('.$x.', 50);
                        context.lineTo('.$x.', 600);
                        context.lineWidth = 0.5;

                        // set line color
                        context.strokeStyle = "#888";
                        context.stroke();
                        ';       
                        $x = $x + $sp;
                    }

                $x = $xo+70;
                for ($r=0;$r <= 26;$r++)
                    {
                        $sx .= '
                        context.beginPath();
                        context.moveTo(410,'.$x.');
                        context.lineTo(950,'.$x.');
                        context.lineWidth = 0.4;

                        // set line color
                        context.strokeStyle = "#888";
                        context.stroke();
                        ';       
                        $x = $x + 20;
                    } 
                return $sx;
            }



