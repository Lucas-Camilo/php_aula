<?php
include("cls_contatos.php");
include("cl_banco.php");
include("cls_tipo.php");
if (isset($_GET['op'])) $op = $_GET['op'];
else $op = "";
if ($op == "") {
    header("Location: index.php");
    exit;
}
include("vsessao.php");
if ($op == "ic") {
    $conec = conec::conecta_mysql("localhost", "root", "", "contatos");
    try {
        $conec->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sth = $conec->prepare("SELECT * FROM tipo");
        $sth->execute();

        print "<p align='center'>Incluir Contato</p>    
    <form method='post' action='mcontatos.php?op=iic'>
        <p align='center'>
		<br>Nome<input type='text' name='nome'
		             size='50' maxglength='50'>
                <br>Email<input type='email' name='email'
		             size='50' maxglength='50'>
		<br><select name='tipoc'>
                       <option value=''>Selecione um tipo";
        $linha = $sth->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_FIRST);
        do{
            $ous = new tipo($linha[0], $linha[1]);
            print "<option value='".$ous->getIdt()."'>".$ous->getNomet();
        }while ($linha = $sth->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT));
                print "</select>
		<br><input type='submit' value='Incluir'>
        </p></form>";
    } catch (Exception $e) {
        print "<br>Falha:" . $e->getMessage();
        print "<br><a href='sistema.php'>Voltar</a>";
        exit;
    }
}
if($op=="iic")
{
    $mensagem = "";
    $contato = new contatos(null, $_POST['nome'], $_POST['email'], $_POST['tipoc']);
    if ($contato->getNomec() == "" || $contato->getEmailc() == ""|| $contato->getTipoc() == "") {
        $mensagem .= "<br>Dados não preenchidos Corretamente";
        exit;
    }
    print $mensagem;
    print "<br><a href='mtipo.php?op=it'>Voltar</a>";
    $conec = conec::conecta_mysql("localhost", "root", "", "contatos");
    try {
        $conec->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION
        );
        $sth = $conec->prepare("INSERT INTO tipo values(?, ?, ?, ?)");
        $sth->execute(array(
            $contato->getIdc(),
            $contato->getNomec(),
            $contato->getEmailc(),
            $contato->getTipoc()
        ));
        print "<br> Tipo Incluido com sucesso
            <br><a href='sistema.php'>Voltar</a>";
    } catch (Exception $e) {
        print "Erro" . $e->getMessage() .
            "<br><a href='sistema.php'>Voltar</a>";
        exit;
    }
    exit;
}
