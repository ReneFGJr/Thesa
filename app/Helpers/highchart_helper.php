<?php

function cloudetags($subject)
    {
        $sx = '';
        $sx .= '<script type="text/javascript" src="'.URL.'js/jquery-3.6.0.min.js'.'"></script>';
        $sx .= '<script type="text/javascript" src="'.URL.'js/jQWCloudv3.1.js'.'"></script>';     

        $sx .= '
            <style>
                #wordCloud {
                    height: 350px;
                    width: 100%;
                    background-color: #ffffff;
                    border: 1px solid #0000FF;
                }
            </style>';
        $sx .= bs(bsc('<div id="wordCloud"></div>'));

        $sx .= '
            <script>
                $(document).ready(function() {
                    $("#wordCloud").jQWCloud({
                    words : [';

        $r = 0;
        foreach ($subject as $key => $value) 
        {
            if ($key != '(nc)') 
            {
                if ($r > 0) { $sx .= ',' . cr(); }
            }
            $r++;
            $sx .= cr()."{ word : '$key', weight : '$value' }";
        }
                        
            $sx .= '],
                minFont : 12,
                maxFont : 50,
                //cloud_font_family: "Tahoma",
                //verticalEnabled: false,
                padding_left : 1,
                //showSpaceDIV: true,
                //spaceDIVColor: \'white\',
                word_common_classes : \'WordClass\',
                word_mouseEnter : function() {
                    $(this).css("text-decoration", "underline");
                }, word_mouseOut : function() {
                    $(this).css("text-decoration", "none");
                }, word_click : function() {
                    $vlr = [1,2,3,4,5];
                    window.location.replace("http://localhost/"+ $(this).text());
                    alert("You have selected:" + $(this).text());
                }, beforeCloudRender : function() {
                    date1 = new Date();
                }, afterCloudRender : function() {
                    var date2 = new Date();
                    console.log("Cloud Completed in " + (date2.getTime() - date1.getTime()) + " milliseconds");
                }
                });

                });
            </script>';        

        return $sx;
    }

function load_grapho_script()
{
    global $load_grapho_script;

    if (!isset($load_grapho_script)) {
        $sx = '
                    <script src="https://code.highcharts.com/highcharts.js"></script>
                    <script src="https://code.highcharts.com/highcharts-3d.js"></script>
                    <script src="https://code.highcharts.com/modules/exporting.js"></script>
                    <script src="https://code.highcharts.com/modules/export-data.js"></script>
                    <script src="https://code.highcharts.com/modules/accessibility.js"></script>';
        $load_grapho_script = 1;
    }
    return ($sx);
}

function highchart_column($data)
    {
        if (!isset($data['height'])) { $data['height'] = '500'; }
			/************************************************************************* JAVA SCRIPTS */
			$js = '
					Highcharts.chart(\''.$data['id'].'\', {
                    height: '.$data['height'].',
  					chart: {
    					type: \'column\'
  					},
			title: {
				text: \''.$data['title'].'\'
			},
			 xAxis: {
				categories: ['.$data['categorias'].'] 
                    },
			yAxis: {
				min: 0,
				title: {
				text: \'Total\'
				},
				stackLabels: {
				enabled: true,
				style: {
					fontWeight: \'bold\',
					color: ( // theme
					Highcharts.defaultOptions.title.style &&
					Highcharts.defaultOptions.title.style.color
					) || \'gray\'
				}
				}
			},
			legend: {
				align: \'left\',
				x: 130,
				verticalAlign: \'top\',
				y: 25,
				floating: true,
				backgroundColor:
				Highcharts.defaultOptions.legend.backgroundColor || \'white\',
				borderColor: \'#CCC\',
				borderWidth: 1,
				shadow: false
			},
			tooltip: {
				headerFormat: \'<b>{point.x}</b><br/>\',
				pointFormat: \'{series.name}: {point.y}<br/>Total: {point.stackTotal}\'
			},  
			plotOptions: {
				column: {
				stacking: \'normal\',
				dataLabels: {
					enabled: true
				}
				}
			},
			'.$data['dados'].'
			});';

            $sx = load_grapho_script();			
			$sx .= '
				<figure class="highcharts-figure">
				<div id="'.$data['id'].'"></div>
				</figure>';
			$sx .= cr().'<script>'.$js.'</script>';
            return $sx;        
    }

function highchart_column_comparision($data=array())
    {
        $sx = load_grapho_script();

    }

function highchart_grapho($data = array())
{
    global $idg;
    if (isset($idg)) {
        $idg = 0;
    } else {
        $idg++;
    }
    $sx = load_grapho_script();
    $tps = array('column', 'bar');

    $sx .= '
                <figure class="highcharts-figure">
                <div id="container' . $idg . '" style="height: 600px;"></div>
                </figure>';

    if (!isset($data['type'])) {
        $type_bar = 'bar';
    } else {
        $type_bar = $data['type'];
    }

    $subtitle = '';
    $title = 'Title';
    $LABEL_ROTATION = 0;
    $LEG_HOR = '$LEG_HOR';
    $CATS = "'Apples', 'Oranges', 'Pears', 'Grapes', 'Bananas'";
    $DATA = '15654, 4064, 1987, 976, 846';
    if (isset($data['LEG_HOR'])) {
        $LEG_HOR = $data['LEG_HOR'];
    }
    if (isset($data['TITLE'])) {
        $title = $data['TITLE'];
    }
    if (isset($data['TYPE'])) {
        $type_bar = $data['TYPE'];
    }
    if (isset($data['DATA'])) {
        $DATA = '';
        for ($r = 0; $r < count($data['DATA']); $r++) {
            if (strlen($DATA) > 0) {
                $DATA .= ', ';
            }
            $DATA .= $data['DATA'][$r];
        }
    }
    if (isset($data['CATS'])) {
        $CATS = '';
        for ($r = 0; $r < count($data['CATS']); $r++) {
            if (strlen($CATS) > 0) {
                $CATS .= ', ';
            }
            $CATS .= "'" . $data['CATS'][$r] . "'";
        }
    }

    $sx .= '
            <script>
            // Set up the chart
            const chart' . $idg . ' = new Highcharts.Chart({
            chart: {
                renderTo: \'container' . $idg . '\',
                type: \'' . $type_bar . '\',
                options3d: {
                enabled: true,
                alpha: 0,
                beta: 5,
                depth: 50,
                viewDistance: 45
                }
            },
                
            title: { text: \'' . $title . '\' },
            subtitle: { text: \'' . $subtitle . '\' },
            plotOptions: {
                column: {
                depth: 125
                }
            },           
            
            xAxis: {
                categories: [' . $CATS . '],
                labels: {
                rotation: ' . $LABEL_ROTATION . ',
                style: {
                    fontSize: \'14px\',
                    fontFamily: \'Tahoma, Verdana, sans-serif\'
                    }
                },
            },            
            series: [ { name: \'' . $LEG_HOR . '\', data: [ ' . $DATA . '] }]
            });
            </script>';
    return ($sx);
}
