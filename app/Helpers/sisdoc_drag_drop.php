<?php

function upload_form()
{
    $sx = form_open_multipart();
    $sx .= form_upload('file');
    $sx .= form_submit(array('name' => 'submit', 'class' => 'btn btn-primary', 'value' => 'Upload'));
    $sx .= form_close();
    return($sx);
}

function ajax($dir, $arr_file_types = ['image/png', 'image/gif', 'image/jpg', 'image/jpeg'])
    {
        if (!(in_array($_FILES['file']['type'], $arr_file_types))) {
            return false;
        }
        $file = $dir. $_FILES['file']['name'];
        $xfile = $file;
        $id = 1;
        while (file_exists($file))
            {
                $file = $xfile.'.'.$id;
                $id++;
            }
        move_uploaded_file($_FILES['file']['tmp_name'], $file);

        return true;         
    }

function upload($url='')
{
    //$URL = 'http://localhost/sisdoc/';
    //https://stackoverflow.com/questions/53950415/how-to-upload-multiple-files-with-drag-drop-and-browse-with-ajax
    $sx = '';
    $sx .= '
    <script>
        var fileobj;
        function upload_file(e) {
            e.preventDefault();
            fileobj = e.dataTransfer.files[0];
            ajax_file_upload(fileobj);
        }
        
        function file_explorer() {
            document.getElementById(\'selectfile\').click();
            document.getElementById(\'selectfile\').onchange = function() {
                fileobj = document.getElementById(\'selectfile\').files[0];
                ajax_file_upload(fileobj);
            };
        }
        
        function ajax_file_upload(file_obj) {
            if(file_obj != undefined) {
                var form_data = new FormData();                  
                form_data.append(\'file\', file_obj);
                var xhttp = new XMLHttpRequest();
                xhttp.open("POST", "'.$url.'", true);
                xhttp.onload = function(event) {
                    oOutput = document.querySelector(\'.img-content\');
                    old = oOutput.innerHTML;
                    if (xhttp.status == 200) {
                        oOutput.innerHTML = "\'"+ this.responseText + old + "\'"
                    } else {
                        oOutput.innerHTML = "Error " + xhttp.status + " '.lang('brapci.drag_drop_erro_1').'";
                    }
                }        
                xhttp.send(form_data);
            }
        }               
    </script>'
    ;

    $sx .= '
        <div id="drop_file_zone" ondrop="upload_file(event)" ondragover="return false">
            <div id="drag_upload_file">
                <p>Drop file here</p>
                <p>or</p>
                <p><input type="button" value="Select File" onclick="file_explorer();" /></p>
                <input type="file" id="selectfile" />
            </div>
        </div>
        <div class="img-content"></div>
        ';

    $sx .= '
        <style>
            #drop_file_zone {
                background-color: #EEE;
                border: #999 5px dashed;
                height: 200px;
                padding-left: 100px 10px;
                margin: 0px 100px 0px 100px;
                font-size: 18px;
            }
            #drag_upload_file {
            width:50%;
            margin:0 auto;
            }
            #drag_upload_file p {
            text-align: center;
            }
            #drag_upload_file #selectfile {
            display: none;
            }
        }
        </style>
        ';

    
    return $sx;
}
