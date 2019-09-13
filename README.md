# Thesa - Tesauro Semântico Aplicado

Sistema desenvolvido em PHP/MySQL para construção de Tesauros com maior nível semântico entre os termos.

# Versão atual v0.19.04.02

# Instalação

Requesitos para instalação:

. Servidor Appache com PHP 5.0 ou superior
. Banco de Dados MySQL

Por ser ainda uma versão preliminar, o Thesa não disponibiliza um instalador. É necessário o ajuste dos arquivos abaixo para seu funcionamento.

Deve-se criar uma base de dados no MySQL

Arquivo /application/config/database.php ajustar:

<pre>
$db['default'] = array(
	'dsn'	=> '',
	'hostname' => 'localhost',
	'username' => 'root',
	'password' => 'root',
	'database' => 'thesa',
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);
</pre>

Restaure a base de dados com base no arquivo:
/_documment/sql/thesa.sql

O login de acesso inicial é:
usuário: thesa
senha: admin
 
