<?php

function cookies_acept()
{
    $sx = '
        <div class="box-cookies hide">
        <p class="msg-cookies">Este site usa cookies para garantir que você obtenha a melhor experiência.</p>
        <button class="btn-cookies">Aceitar!</button>
        </div>';

    $sx .= '
        <style>
        .box-cookies.hide {
            display: none !important;
        }

        .box-cookies {
            position: fixed;
            background: rgba(0, 0, 0, .9);
            width: 100%;
            z-index: 998;
            bottom: 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .box-cookies .msg-cookies,
        .box-cookies .btn-cookies {
            text-align: center;
            padding: 25px;
            color: #fff;
            font-size: 18px;
        }

        .box-cookies .btn-cookies {
            background: #1e88e5;
            cursor: pointer;
            align-self: normal;
        }

        @media screen and (max-width: 600px) {
            .box-cookies {
                flex-direction: column;
            }
        }
        </style>';

        $sx .= '
        <script>
            (() => {
            if (!localStorage.pureJavaScriptCookies) {
                document.querySelector(".box-cookies").classList.remove(\'hide\');
            }
            
            const acceptCookies = () => {
                document.querySelector(".box-cookies").classList.add(\'hide\');
                localStorage.setItem("pureJavaScriptCookies", "accept");
            };
            
            const btnCookies = document.querySelector(".btn-cookies");
            
            btnCookies.addEventListener(\'click\', acceptCookies);
            })();        
        </script>';
        return $sx;
}
