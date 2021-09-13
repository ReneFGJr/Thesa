<?php
class creativecommons extends CI_Model  {

    /* Mostra licenca */
    function licence($id=0)
    {
        $sx = '<a rel="license" href="http://creativecommons.org/licenses/by/4.0/">
                <img alt="Licença Creative Commons" style="border-width:0" src="https://i.creativecommons.org/l/by/4.0/88x31.png" /></a>
                <br/>Este trabalho está licenciado com uma Licença:
                <br/>
                <a rel="license" href="http://creativecommons.org/licenses/by/4.0/">
                Creative Commons - Atribuição  4.0 Internacional</a>.';
        return($sx);
    }    
}