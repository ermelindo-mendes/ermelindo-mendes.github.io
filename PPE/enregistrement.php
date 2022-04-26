<?php

$isOk = true;

if(isset($_POST['valider'])){

	// Vérifie si les mots de passes saisis sont identiques
	if(htmlspecialchars($_POST['password']) != htmlspecialchars($_POST['confirmPassword'])){
		$isOk = false;
		header('Location: mdp_inscription.html');
	}
		
	if($isOk == true){
		// Insertion des donnees formulaire dans la table connexion
		try {
			// Connexion a la BDD
			$pdo = new PDO("mysql:host=localhost;dbname=achatenligne","root",""); 
			// echo "Connexion BDD OK";
			
			// Requete INSERT en mode preparer
			$ins = $pdo->prepare("insert into connexions (Nom,Prenom,Email,Password) values (?,?,?,?)");
			$ins->execute (array (htmlspecialchars($_POST['nom']), htmlspecialchars($_POST['prenom']), htmlspecialchars($_POST['email']), htmlspecialchars($_POST['password'])));

			header('location: formulaireLiens.html');
			// À analyser et comprendre le refresh. Ex = header('refresh:3;url=shop.html');
		}
		catch(PDOException $e){ 
			echo $e->getMessage("Erreur !"); 
		}
	}
}
?>
