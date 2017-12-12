<?php
	session_start();

	require_once "classes/Conexao.class.php";
	require_once "classes/Usuario.class.php";
	require_once "classes/enviar_post.class.php";

	if(isset($_GET['logout'])):
		if($_GET['logout'] == 'confirmar'):
			Login::deslogar();
		endif;
	endif; 

	if(isset($_SESSION['logado'])):

?>
<html lang="pt-br">
	<head>
		<meta charset="utf-8"/>
		<title>Dashboard</title> 
		<meta name="author" content="Deyvison Cruz">
		<meta name="description" content="">
		<link rel="stylesheet" type="text/css" href="css/main.css">
	</head>
	<body>

	<nav>Api - Getrack 
	</nav>
	<nav>
	Bem vindo <?php echo $_SESSION['administrador']; ?> |
	<a href="dashboard.php?logout=confirmar">Sair</a>
	
	</nav>
<?php
	

$c = new curl();
$c->username ='candidato@getrak';
$c->password ='12345678';
//$c->setOAUTH_URL();

//$jToken = $c->requestPostOAuth();
/*echo $jToken;
$json = json_decode($jToken );

        echo "<div>access_token: " . $json->{'access_token'} . "</div>";
        echo "<br />";
        echo "<div>token_type: " . $json->{'token_type'} . "</div>";
        echo "<br />";
        echo "<div>expires_in: " . $json->{'expires_in'} . "</div>";
        echo "<br />";
        echo "<div>scope: " . $json->{'scope'} . "</div>";
        echo "<br />";
        echo "<div>jti: " . $json->{'jti'} . "</div>";
        echo "<br />";
    */
$c->updateTokenBD();
$c->setURL("https://api.getrak.com/v0.1/localizacoes");

echo "</br>";

echo "</br>";

$date=date_create("2013-03-15");
date_add($date,date_interval_create_from_date_string("40 seconds"));
echo date_format($date,"Y-m-d H:min:s");
$jGet = $c->requestGet();

echo  "------------------" . $c->verifyToken();
if( $v== true)
	echo "tesate"; 
else 
	echo "ffffffffffffff";

$json = json_decode($jGet,FALSE );
foreach ( $json->veiculos as $item)
{
	echo "<div>placa: " . $item->{'placa'} . "</div>";
        echo "<br />"; 
}

       
?>
	




<?php
	else:
		echo "Voce nao tem permissao de acesso. <a href=\"index.php\">Clique aqui para voltar</a>";
	endif;
?>

	</body>
</html>
