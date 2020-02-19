<div class="container">
	<div class="row">
		<div class="col-12">
			<ul>
				<li>API</li>
				<ul><li><a href="api_check">API Check</a></li></ul>
			</lu>

			<a name="api_check"></a>
			<h1>Check (API-POST)</h1>
			<p>Verifica se existe o termo no vocabulário especificado</p>
			<pre>
				token = código de acesso
				q = termo de consulta
				th = tesauro de busca
				new = força a criação de um novo termo
			</pre>

			<p>Exemplo</p>
			<p>curl https://www.ufrgs.br/tesauros/index.php/thesa/api/check/
				<br>
				POST {q=Universidade Federal&th=8;&new=0;&token=08083940r9j340r9j3049r8j90
			</p>

		</div>
	</div>
</div>
