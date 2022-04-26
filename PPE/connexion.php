<?php

// Acces a la base de données.
if(!isset($_POST["Email"]) || !isset($_POST["Password"])){
    echo "Tous les champs n'ont pas été rempli !";
}
else {
    try { 
        // Connexion a la BDD.
        $pdo = new PDO("mysql:host=localhost;dbname=achatenligne","root","");

        // Requete INSERT en mode prepare.
        $sel = $pdo->prepare("select Email, Password from connexions where Email=? and Password=?");

        // Vérifie que "email" et "password" correspondent aux données dans la bdd.
        $sel->execute(array(htmlspecialchars($_POST["Email"]), htmlspecialchars($_POST["Password"])));

        if($ligne = $sel->fetch()){
            header('Location: shop.html');
        }
        else{
            header('Location: erreur_connexion.html');
        }
    }
    catch(PDOException $e){ 
        echo $e->getMessage("Erreur !"); 
    }
}
?>
