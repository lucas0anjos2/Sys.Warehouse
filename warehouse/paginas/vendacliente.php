<?php
session_start();
if(!isset($_SESSION['login'])){
	header('location: ../index.php');
}
if ($_SESSION['administrador'] != 1){
	header('location: venda.php');
}
?>

<!doctype html>
<html lang="pt-br">
<head>
	<title>Januário - Disk Cerveja</title>        
	<meta charset="utf-8">
	<meta name="viewport"       content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description"    content="WareHouse">
	<meta name="author"         content="ENGTEC - Engenharia e Computação">
	
	<link rel="icon" href="../imagens/favicon.ico">
	<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="../css/estilo.css" >
	<link rel="stylesheet" type="text/css" href="../css/vendas.css">

	<script src="../js/jquery.min.js"></script>
	<script src="../js/script.js"></script>
	<script src="../js/bootstrap.min.js"></script>
	<script type="../js/vendas.js"></script>

</head>

<body>
	<div class = "jumbotron text-center removerMargem">
		<h1 class="text-titulo"><strong>JANUÁRIO</strong></h1>
		<h4><img class="rounded-circle" src="../svg/star.svg" alt="Generic placeholder image" width="20" height="20"> <img class="rounded-circle" src="../svg/star.svg" alt="Generic placeholder image" width="20" height="20"> <img class="rounded-circle" src="../svg/star.svg" alt="Generic placeholder image" width="20" height="20"><strong> DISK CERVEJA </strong><img class="rounded-circle" src="../svg/star.svg" alt="Generic placeholder image" width="20" height="20"> <img class="rounded-circle" src="../svg/star.svg" alt="Generic placeholder image" width="20" height="20"> <img class="rounded-circle" src="../svg/star.svg" alt="Generic placeholder image" width="20" height="20"></h4>
	</div>
	<div class="modal fade" id="modalpesquisar" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalpesquisar">Muitos clientes com esse mesmo nome/cpf! Tente ser mais espefico.</h5>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger btn-sm ok" data-dismiss="modal">Fechar</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="modalpesquisarf" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalpesquisarf">Nenhum cliente encontrado. Verifique os dados digitados</h5>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger btn-sm ok" data-dismiss="modal">Fechar</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="modalTipo" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalTipo">Escolha um tipo de pagamento!</h5>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger btn-sm ok" data-dismiss="modal">Fechar</button>
				</div>
			</div>
		</div>
	</div>
	<?php
		require_once("../crud/bd.php");
		$pesquisa = 
			'
			<div class="row rowForm">
				<div class="col-md-2 lblal">
					<label style="margin-top:10px;">Nome/CPF: </label>
				</div>
				<div class="col-md-3">
					<input type="text" style="width:200px;" id="txtPesquisar" name="txtPesquisar"  class="form-control" value="">
				</div>
				<div class="col-md-2"style="margin-left:20px;margin-top:7px;">Pagamento:</div>
				<div class="col-md-3" ">
				
					<select id="selPag" name="selPag" style="width:150px;" class="form-control" >
						<option selected value="vazio">...</option>
						<option value="avista">À vista</option>
						<option value="prazo">À prazo</option>
						<option value="cartao">Cartão</option>
					</select>
				</div>
				<div class="col-md-1" style="margin-left:12px;">
					<input type="submit" style="float:left; width:90px; margin-left:-25px;" id="btnPesquisar" name="btnPesquisar" class="btn btn-warning" value="Buscar">
				</div>
			</div>
			'
		;
		$pesquisado = $limite = "";
		if(isset($_POST["btnPesquisar"]))
		{
			$key = $_POST["txtPesquisar"];
			if($key != "")
			{
				if($_POST["selPag"] != "vazio"){
					$select  = "SELECT nome,cpf,rg,limite_de_credito,divida FROM cliente as c,(SELECT id_pessoa,nome,cpf,rg FROM pessoa WHERE nome LIKE '%$key%' OR cpf LIKE '%$key%') as p WHERE c.id_pessoa = p.id_pessoa";
					if($resultado = mysqli_query($conexao,$select)){
						$cont = mysqli_num_rows($resultado);
						if($cont == 1)
						{
							$row = mysqli_fetch_array($resultado);	
							$nome = $row["nome"];
							$cpf = $row["cpf"];
							$rg = $row["rg"];
							$limite = $row["limite_de_credito"];
							$divida = $row["divida"];
							$pesquisado = 
								'
								<div class="row rowForm">
									<div class="col-md-2 lblAl">
										<label>CPF: </label>
									</div>
									<div class="col-md-3">
										<input class="form-control" type="text" id="txtCPF" value="'.$cpf.'" readonly>
									</div>
									<div class="col-md-2"></div>
									<div class="col-md-2 lblAl">
										<label style="float: left; margin-left: 30px; margin-top:5px;">RG: </label>
									</div>
									<div class="col-md-3">
										<input class="form-control" type="text" id="txtRG"value="'.$rg.'" readonly>
									</div>
								</div>
								<div class="row rowForm">
									<div class="col-md-2 lblAl">
										<label>Nome: </label>
									</div>
									<div class="col-md-10">
										<input width="540px" class="form-control" type="text" class="confTxtBox" id="txtNome" value="'.$nome.'" readonly>
									</div>
								</div>
								<div class="row rowForm">
									<div class="col-md-2 lblAl">
										<label style="float: left; margin-top:7px;" >Divida: </label>
									</div>
									<div class="col-md-3">
										<input class="form-control" type="number" step="any" id="numLimite" value="'.$divida.'" readonly>
									</div>
									<div class="col-md-1"></div>
									<div class="col-md-3 lblAl" style="margin-top:7px;">
										<label style="float: left;">Limite Restante: </label>
									</div>
									<div class="col-md-3">
										<input class="form-control" type="number" step="any" id="numLimite" value="'.$limite.'" readonly>
									</div>
								</div>
								'
							;
							$divida = $divida + $_SESSION["valortotal"];
							$limite = $limite - $_SESSION["valortotal"];
							$tipo = $_POST["selPag"];
							$link = "../crud/vendaClienteInserir.php?key=$key&limite=$limite&tipo=$tipo&divida=$divida";
						}else{
							echo("<script language='javascript'>$('#modalpesquisar').modal('show'); </script>");
						}
					}else{
						echo("<script language='javascript'>$('#modalpesquisarf').modal('show'); </script>");
					}
				}else{
					echo("<script language='javascript'>$('#modalTipo').modal('show'); </script>");
				}
			}
		}

	?>
	<div class="container">
		<h2 class="subTitulo">Vendas</h2>
		<div class="row">
			<div class="col-md-2"></div>
			<div class="col-md-8 mt-3">
				<div class="row">
					<div class="col-md-12">
						<form action="" method="POST" id="formCli"> 
							<div class="row rowForm">
								<div class="col-md-6" align="right"></div>
								<div class="col-md-3">
									<a id="btnAdicionar" class="btn btn-outline-primary" href="principal.php" role="button">Cadastrar Cliente</a>
								</div>
								<div class="col-md-3" align="center">
									<a id="btnCancelar" class="btn btn-danger" href="principal.php" role="button">Cancelar</a>
								</div>
							</div> 
							<?php echo($pesquisa);?>
							<?php echo($pesquisado)?>
							<div class="row rowForm rowTable">
								<div class="col-md-3 lblAl">
									<label style="font-weight: bold;">Produtos da Compra:</label>
								</div>
								<div class="col-md-5"></div>
								<div class="col-md-2">
									
								</div>
								<div class="col-md-2">
									
								</div>
							</div>
							<div style="height: 10px;"></div>
							<div class="row divTable">
								<table class="table table-sm table-bordered">
									<thead class="thead-light">
										<tr style="text-align: center;">
											<th style="width: 5%;">#</th>
											<th style="width: 55%;">Nome Produto</th>
											<th style="width: 15%;">Preço</th>
											<th style="width: 5%;">Quant.</th>
											<th style="width: 15%;">Preço Total</th>
											<th style="width: 5%;">Excluir</th>	
										</tr>
										<?php
											foreach($_SESSION["dbgrid"] as $key => $valor)
											{
												echo($valor);
											}
										?>
									</thead>
								</table>
							</div>
							<div class="row rowForm" style="vertical-align: center;">
								<div class="col-md-1"></div>
								<div class="col-md-5">
									<h2>Valor da Compra:</h2>
								</div>
								<div class="col-md-6">
									<input type="number" step="any" id="numTotal" value=<?php echo($_SESSION["valortotal"]); ?>>
								</div>
							</div>
							<div class="row rowForm">
								<div class="col-md-6">
									
								</div>
								<div class="col-md-6">
									<a id="btnFinalizar" href="<?php echo($link); ?>" class="btn btn-success">Finalizar Compra</a>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>   
		</div>
	</div>
	<br>
	<br>
	<br>
</body>
</html>