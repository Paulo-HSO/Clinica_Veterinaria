<?php
session_start();
if (!isset($_SESSION)) session_start();

  // Verifica se não há a variável da sessão que identifica o usuário
if (!isset($_SESSION['UsuarioID']) OR ($_SESSION['UsuarioNivel'] < 2)) {
      // Destrói a sessão por segurança
      session_destroy();
      // Redireciona o visitante de volta pro login
      header("Location: login.php"); exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="css/style2.css">
    <title>Pets Cadastrados</title>
</head>
<body>
    <header>
        <nav class='navbar navbar-expand-lg navbar-light'>
            <div class='container-fluid'>
                <a href='index.php'><img class='logo' src='img/Logo figma.png' alt='logo' width=180 height=70></a> 
                <button class='navbar-toggler' type='button' data-bs-toggle='collapse' data-bs-target='#navbarNavAltMarkup' aria-controls='navbarNavAltMarkup' aria-expanded='false' aria-label='Toggle navigation'>
                <span class='navbar-toggler-icon'></span>
                </button>
                <div class='collapse navbar-collapse' id='navbarNavAltMarkup'>
                <div class='navbar-nav'>
                    <a class='nav-link' href='petsadm.php'>Pets</a>
                    <a class='nav-link' href='agenda.php'>Agendamento</a>
                    <a class='nav-link' href='usuarios.php'>Usuários</a>
                    <a class='nav-link' href='edit_user.php'>Meu perfil</a>
                </div>
            </div>
            </div>
        </nav>
    </header>
    <main>
    <div class='lista'>
    <h1>Pets Cadastrados</h1>
    <?php
    include_once("conexao.php");

     if(isset($_SESSION['msg'])){
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }

    //receber o número da página
    $pag_atual = filter_input(INPUT_GET,'pagina', FILTER_SANITIZE_NUMBER_INT);
    $pagina = (!empty($pag_atual)) ? $pag_atual : 1;

    //setar a quantidade de items por página
    $qnt_result_pag = 3;

    //calcular o inicio da visualização
    $inicio = ($qnt_result_pag * $pagina) - $qnt_result_pag;

    $result_pet = "SELECT * FROM pet LIMIT $inicio, $qnt_result_pag";
    $result_pets = mysqli_query($conn, $result_pet);
    while($row_pet = mysqli_fetch_assoc($result_pets)){
        echo '<section class="container-fluid pets">';
            echo '<img src="img/paw.png" class="pata">';
            echo '<div class="info">';
            echo "<p><strong>Nome:</strong> " . $row_pet['nome'] . "</p>";
            echo "<nav class='nav-pag'>";
                echo "<a href='edit_pet.php?id=" . $row_pet['id'] . "'>Editar</a>";
                echo "<a href='fichapet.php?id=" . $row_pet['id'] . "'>Detalhes</a>";
            echo "</nav>";
            echo "</div>";
        echo '</section>';
    }
    ?>

    <nav class="nav-pag">

    <?php
    //paginação - Somar a quantidade de usuários
    $result_pag = "SELECT COUNT(id) as num_result FROM pet";
    $resultado_pag = mysqli_query($conn, $result_pag);
    $row_pg = mysqli_fetch_assoc($resultado_pag);

    //quantidade de paginas
    $quant_pag = ceil($row_pg['num_result'] / $qnt_result_pag);

    //limitar os links antes e depois
    $max_links = 2;
    echo "<a href='petsadm.php?pagina=1'>Primeira</a>";
    
    for($pag_ant = $pagina - $max_links; $pag_ant <= $pagina - 1; $pag_ant++){
        if($pag_ant >= 1){
            echo "<a href='petsadm.php?pagina=$pag_ant'>$pag_ant</a>";
        }
    }
    
    echo "$pagina";
    
    for($pag_dep = $pagina + 1; $pag_dep <= $pagina + $max_links; $pag_dep++){
        if($pag_dep <= $quant_pag){
            echo "<a class='pag' href='petsadm.php?pagina=$pag_dep'>$pag_dep</a>";
        }
    }

    echo "<a href='petsadm.php?pagina=$quant_pag'>Ultima</a>"
    
    ?>
    </nav>
    </div>
    </main>
</body>
</html>