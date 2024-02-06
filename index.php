<?php

require ('asset/config/function.php');




if (isset($_POST['ajout'])) {
    if( !empty($_POST["tache"])){
        addTodoListe($pdo);
        
    }else {
        echo "<p>Veuillez entrer une tache à effectuer</p>";
    }
}

if(isset($_GET["delete_tache"])){//si le tableau associatif $_GET["delete_tache"] est remplie on execute la fonction delete 
    $delVar =$_GET["delete_tache"];
    delete($pdo,$delVar);
}
// echo "<pre>";
// print_r($_POST);
// echo "</pre>";


if(isset($_GET["do_it"])){//si le tableau associatif $_GET["do_it"] est remplie on execute la fonction doIt
    $fait = $_GET["do_it"];
    doIt($pdo,$fait);
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToDoList</title>
    <link rel="stylesheet" href="./asset/css/style.css">
    <!-- partie qui va gérer les icones ansomefont -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Partie google font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300&display=swap" rel="stylesheet">
</head>
<body>
    <nav>
        <fieldset>
            <legend>ToDoList</legend>
            <form action="" method="post">
                <input type="text" id="tache" placeholder="Tâche à effectuer" name="tache" >
                <input type="submit" id="ajouter" name="ajout" value="ajouter">
            </form>
        </fieldset>        
    </nav>
        

    <main>

        <div class="buttonRecap">
            <!-- <button>toutes les tâches</button>
            <button>restante</button> -->
        </div>

        <section>
        <?= empty($resultat) ? "<p>Aucune tâche à effectuer.</p>" : null ?>
            <table>
                <!-- on va afficher ici chaques les lignes qui vont contenir les taches -->
                <?php printTodoliste($pdo); ?>
            </table>
        </section>


    </main>
</body>
</html>