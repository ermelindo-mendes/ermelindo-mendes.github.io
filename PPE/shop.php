<?php

// Acces a la base de donnees
$ans = $_POST["achoix"];

//isset permet de vérifier que tous les champs sont remplis
if(!isset($_POST["Genre"]) || !isset($_POST["Type"]) || !isset($_POST["Taille"]) || !isset($_POST["Couleur"]) || !isset($_POST["qte"])){
    echo "Tous les champs n'ont pas été rempli !";
}
// PARTIE CONSULTATION
// Le code pour afficher la BDD quand bouton "consulter" est choisi.
elseif($ans == "consulter"){
    // Connexion a la BDD
    $pdo = new PDO("mysql:host=localhost;dbname=achatenligne","root","");

    // Requete INSERT en mode prepare
    $sel = $pdo->prepare("select * from stock");
    $sel->execute();
    echo '<table border="3" style="font-size:30px" td align="center">';
    echo "<tr>";
    echo '<td> <strong>' .'Genre' .'</strong></td>';
    echo '<td> <strong>' .'Type' .'</strong></td>';
    echo '<td> <strong>' .'Taille' .'</strong></td>';
    echo '<td> <strong>' .'Couleur' .'</strong></td>';
    echo '<td> <strong>' .'Prix' .'</strong></td>';
    echo '<td> <strong>' .'Quantité' .'</strong></td>';
    echo "</tr>";
    while($ligne = $sel->fetch()){
        echo "<tr>";
        //htmlspecialchars permet de sécuriser le code en modifiant certain caractères tel que "<".
        echo '<td>' .htmlspecialchars($ligne['Genre']) .'</td>';
        echo '<td>' .htmlspecialchars($ligne['Type']).'</td>';
        echo '<td>' .htmlspecialchars($ligne['Taille']).'</td>';
        echo '<td>' .htmlspecialchars($ligne['Couleur']).'</td>';
        echo '<td>' .htmlspecialchars($ligne['Prix']).'</td>';
        echo '<td>' .htmlspecialchars($ligne['Quantite']).'</td>';
        echo "</tr>";
    }
}


// PARTIE ACHAT
// Le code pour modifier la quantité d'une ligne dans la BDD quand bouton "acheter" est choisi.
elseif($ans == "acheter"){
    $Genre = htmlspecialchars($_POST["Genre"]);
    $Vete = htmlspecialchars($_POST["Type"]);
    $Taille = htmlspecialchars($_POST["Taille"]);
    $Couleur = htmlspecialchars($_POST["Couleur"]);
    $qt = htmlspecialchars($_POST['qte']); 
    $pdo = new PDO("mysql:host=localhost;dbname=achatenligne","root","");
    $sel = $pdo->prepare("select * from stock where Genre= '$Genre' and Type= '$Vete' and Taille= '$Taille' and Couleur= '$Couleur'");
    $sel->execute();

    while($ligne = $sel->fetch()){
        $q = htmlspecialchars($ligne['Quantite']);
    }
    // Si la quantité choisie dans la boutique est inférieur à celle présente dans la bdd:
    if($qt > $q){
        echo "Nous sommes navrés, il est impossible de passer commande. Il n'y a que " . $q . " articles disponible correspondant à votre demande.";
    }
    else{
        // On multiplie la quantité choisie par -1 afin d'obtenir la version négative.
        $qt = $_POST['qte'] * -1;
        $pdo = new PDO("mysql:host=localhost;dbname=achatenligne","root","");
        $sel = $pdo->prepare("select * from stock where Genre= '$Genre' and Type= '$Vete' and Taille= '$Taille' and Couleur= '$Couleur'");
        while($ligne = $sel->fetch()){
            $q = $ligne['Quantite'];
        }
        // On additione la quantité actuelle avec la version négative de la quantité séléctionné afin de soustraire ce nombre dans la BDD
        $qtt = $q + $qt;
        $maj = $pdo->prepare("update stock set Quantite=? where Genre= '$Genre' and Type= '$Vete' and Taille= '$Taille' and Couleur= '$Couleur'");
        $maj->execute(array($qtt));
        header('Location: mess_achat.html');
    }
}
else{
    try{ 
        echo "Oupss";
    }
    catch(PDOException $e){ 
        echo $e->getMessage("Erreur !"); 
    }
}
?>
