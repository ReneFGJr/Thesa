#
=Instalação=
Para preparar o ambiente, copie o arquivo "env" que está na raiz do projeto para ".env", arquivo do ambiente. É neste arquivo que será inserido as senhas de acesso a banco de dados e configurações.

* Altere o tipo de Ambiente que o software está, production (produção) ou development (desenvolvimento ou testes), deixe descomentado seu ambiente.

Ex:

CI_ENVIRONMENT = production
#CI_ENVIRONMENT = development

Altere o local de instalação de seu site
app.baseURL = 'http://__URL__/thesa'

* Configure o acesso ao banco de dados
database.default.hostname = localhost
database.default.database = thesa
database.default.username = root
database.default.password = root
database.default.DBDriver = MySQLi
#database.default.DBPrefix =
database.default.port = 3306

=Criação do banco de dados=
Crie um banco de dados no padrão UTF8 com o nome Thesa ou execute o comando do SPARK

php spark db:create thesa

=Criação da estrutura do banco de dados=
php spark migrate


# Usuário padrão: admin
# Senha padrão: admin