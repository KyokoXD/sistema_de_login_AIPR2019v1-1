<?php

//É necessário fazer a conexão com o Banco de Dados
require_once "configDB.php";

function verificar_entrada ($entrada) {
    $saida = trim($entrada); //Remove espaços antes e depois
    $saida = stripslashes($saida); //remove as barras
    $saida = htmlspecialchars($saida);
    return $saida;
}

if(isset($_POST['action']) && 
    $_POST['action'] == 'cadastro'){
        //Pegar os campos do formulário
        $nomeCompleto = verificar_entrada($_POST['nomeCompleto']);
        $nomeUsuario = verificar_entrada($_POST['nomeUsuário']);
        $emailUsuario = verificar_entrada($_POST['emailUsuário']);
        $senhaUsuario = verificar_entrada($_POST['senhaUsuário']);
        $senhaConfirma = verificar_entrada($_POST['senhaConfirma']);
        $concordar = $_POST['concordar'];
        $dataCriacao = date("Y-m-d H:i:s");
        

        echo "<h5>Nome completo: $nomeCompleto</h5>";
        echo "<h5>Nome Usuário: $nomeUsuario</h5>";
        echo "<h5>E-mail Usuário: $emailUsuario</h5>";
        echo "<h5>Senha Usuário: $senhaUsuario</h5>";
        echo "<h5>Senha Confirma: $senhaConfirma</h5>";
        echo "<h5>Concordar: $concordar</h5>";
        echo "<h5>Data de Criação: $dataCriacao</h5>";

        //Hash de senha / Codificação de senha em 40 caracteres
   $senha = sha1($senhaUsuario);
$senhaC = sha1($senhaConfirma);

if($senha != $senhaC){
echo "<h1>As senhas não conferem</h1>";
exit();
}else{
//echo "<h5> senha codificada: $senha</h5>";
//Verifiacr se o usuario já existe no banco de dados
$sql = $conecta->prepare("SELECT nomeUsuario, email FROM usuario WHERE nomeUsuario = ? OR email = ?");
//substituir cada ? por string abaixo
$sql->bind_param("ss",$nomeUsuario, $emailUsuario);
$sql->execute();
$resultado = $sql->get_result();
$linha = $resultado->fetch_array(MYSQLI_ASSOC);
if($linha['nomeUsuario']== $nomeUsuario){
echo "<p>Nomen de usuário indisponivel, tente outro</p>";
}elseif ($linha['email'] == $emailUsuario){
echo "<p>E-mail já em uso, tente outro</p>";
}else{
$sql = $conecta->prepare("INSERT INTO usuario (nome, nomeUsuario, email, senha, dataCriacao)values(?, ?, ?, ?, ?)");
$sql->bind_param("sssss",$nomeCompleto, $nomeUsuario, $emailUsuario, $senha, $dataCriacao);
if($sql->execute()){
echo "<p>Registrado com sucesso</p>";
}else {
echo "<p>Algo deu errado. Tente outra vez</p>";
}
}
}
} else {
echo "<h1 style='color:red'>Esta Página não pode ser acessada diretamente</h1>";  
}