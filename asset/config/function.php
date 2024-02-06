<?php
require ('asset/config/init.inc.php');


// -------------------------------------------------------On créer un tableau qui va contenir les info de notre BDD ---------------------------------------------------------
$pdoCount = $pdo->prepare('SELECT id_tache, tache,fait FROM `taches`');
$pdoCount->execute();
$resultat = $pdoCount->fetchAll(PDO::FETCH_ASSOC);//on récupère sous forme d'un tableau associatif tout les éléémneets de notre base de données
// ----------------------------------------------------Fin de la création du tableau qui va contenir les info de notre BDD ----------------------------------------------------



// fonction qui va gérer l'ajout des éléments de notre todoList
/**
 * fonctiion qui va nous permettre d'ajouter un élément dans notre todoliste et dans notre bdd
 *
 * @param [type] $pdo
 * @return void
 */
function addTodoListe($pdo){
    extract($_POST);//on fait un extract pour pouvoir récupérer en tant que variable les index de la super global POST 
    $pdoStatement = $pdo->prepare('INSERT INTO `taches`(`tache`,`fait`) VALUES (
        :tache,
        :fait
    )');//Pour l'affichage seul les colonnes tache et fait nous interesse 
    $pdoStatement->bindValue('tache',$tache,PDO::PARAM_STR);
    $pdoStatement->bindValue('fait',0,PDO::PARAM_INT);// la colonne de notre base de données va prendre comme valeur par défaut 0 pour "non fait" et 1 pour "fait"
    $pdoStatement->execute();
    header('location: index.php');
}

// fin de la partie qui gère l'ajout des éléments de la todolist dans une base de données 




    
// fonction qui va afficher les éléments de notre bes de donnnées 
/**
 * fonction qui va parmettre d'afficher les éléments de notre BDD
 *
 * @param [type] $pdo
 * @return void
 */
function printTodoliste($pdo){
    //on récupèrele nombre de ligne de la base de données 
    global $resultat;//on récupere le tableau $resultat qui a une porté global et on transforme cette porté, en une porté locale dans notre fonction pour pouvoir travailler avec.

    // echo "<pre>";
    // var_dump($resultat);
    // echo "</pre>";
    
    
    for ($i=0; $i<= count($resultat)-1; $i++){
        
        if($i+1==$resultat[$i]['id_tache']){//avant d'afficher nos élééments l'on s'assure que notre base est correctement incrémenté 
            //on va créer ici les elemens td que l'on va placer dans chaques lignes tr de notre tableau
            echo "<tr>";
             
            echo "<td>";
            echo "<p>" . $resultat[$i]['id_tache'] . "</p>";//on utilise pas la variable $i car cela nous permet de voir directement si notre base est correctement incrémenté 
            echo "</td>";    
    
            echo "<td>";

            //si le tableau associatif résultat au ième tableau contient la valeur fait qui est égale à 1, alors on ajoute la class fait a notre paragraphe sinon nonFait

            // La condition ternaire suivante est l'équivalant de la condition juste en bas, elle est l'application directe de plusieurs notions vu pendant les cours word Press
            ?> 
            <p <?= $resultat[$i]['fait']==1 ? "class='fait'>".$resultat[$i]['tache']  : ">". $resultat[$i]['tache'] ; ?> </p>
            <?php

            // if($resultat[$i]['fait']==1){//si le tableau associatif résultat au ième tableau contient la valeur fait qui est égale à 1, alors on ajoute la class fait a notre paragraphe 
            //     echo "<p class='fait'>" . $resultat[$i]['tache'] . "</p>";//on récupère les taches qui sont dans notre tableau associatif
            // }else{
            //     echo "<p>" . $resultat[$i]['tache'] . "</p>";//on récupère les taches qui sont dans notre tableau associatif
            // }
            echo "</td>";
    
            echo "<td>";
                ?>
                <p><a href='index.php?delete_tache=<?= $resultat[$i]['id_tache'] ;?>'><i class="fa-solid fa-trash"></i></a></p><!-- pour supprimer un élément on va envoyer l'id de lélément en question dans notre lien, puis on va supprimer l'élément cible grace a $_GET dans le fonction delet -->
                <?php
            echo "</td>";
    
            echo "<td>";
            ?>
            <p><a href='index.php?do_it=<?= $resultat[$i]['id_tache'] ;?>'><i class="fa-regular fa-circle-check"></i></a></p><!-- pour dire que une tache est fait on va envoyer l'id de lélément en question dans notre lien, puis on va mettre jour l'élément cible grace à du css combiné à $_GET dans le fonction doIt -->
            <?php
            echo "</td>";
    
            echo "</tr>";
        
        }else{
            echo"<p class='error'>error : Problème d'incrémentation de la base de données, la tache n°" . $i+1 ." n'existe pas." . "</p>";//l'orsque l'on affiche nos éléments, ils sont sélectioner grace à une boucle for. Si l'indexe de la boucle for et l'auto incrémentation n'est pas la même, on aura une erreur. 
            return "Error" ;//on stop directement la fonction en cas d'erreur liee à la BDD
        }
        
    }  
}
  
// fin de la fonction qui va afficher les éléments de notre bes de donnnées 




// fonction qui va nous permettre de supprimer des éléments

// echo "<pre>";
// print_r($_GET);
// echo "</pre>";


/**
 * fonction qui va parmettre de supprimer les éléments sélectionner
 *
 * @param [type] $pdo
 * @param [type] $delVar
 * @return void
 */
function delete($pdo,$delVar){
    $deletePdo = $pdo->prepare("DELETE FROM `taches` WHERE id_tache=$delVar;SET @num := 0; UPDATE taches SET id_tache = @num := (@num+1);ALTER TABLE taches AUTO_INCREMENT = 1");//on supprime lélément sélectionner dans notre BDD, et on met à jour la nouvelle auto incrémentation
    $deletePdo->execute();
    header('location: index.php');//une fois que la suppression a bien été effectuer on retourne sur l'index
}

//fin de la fonction qui va nous permettre de supprimer des éléments


// fonction qui va permettre de modifier des éléments 


/**
 * permet de modifier la valeur fait dans notre base de données
 *
 * @param [type] $pdo
 * @param [type] $positionFait
 * @return void
 */
function doIt($pdo,$positionFait){
    global $resultat;//on récupere le tableau $resultat qui a une porté global et on transforme cette porté, en une porté locale dans notre fonction pour pouvoir travailler avec. 
    // Cette condition permet à l'utilisateur de marquer une tâche comme 'fait' et de la modifier en cas d'erreur
    
    // La verssion en ternaire est beaucoup moins lisible et compréhensible que la version avec des structures conditionelles, c'est pourquoi j'ai choisi d'utiliser des structures conditionelles

    // $resultat[$positionFait-1]['fait']==0 ? $pdoCount = $pdo->prepare('UPDATE `taches` SET `fait`= 1 WHERE id_tache=' . $positionFait) : $pdoCount = $pdo->prepare('UPDATE `taches` SET `fait`= 0 WHERE id_tache=' . $positionFait) ;
    
    if($resultat[$positionFait-1]['fait']==0){
        $pdoCount = $pdo->prepare('UPDATE `taches` SET `fait`= 1 WHERE id_tache=' . $positionFait);//on met à jour la colonne fait dans la bdd à 1 si elle est de base à 0
    }
    if($resultat[$positionFait-1]['fait']==1){
        $pdoCount = $pdo->prepare('UPDATE `taches` SET `fait`= 0 WHERE id_tache=' . $positionFait);//on met à jour la colonne fait dans la bdd à 0 si elle est de base à 1
    }
    $pdoCount->execute();
    header('location: index.php');
}


// fin de la fonction qui va permettre de modifier des éléments 

?>