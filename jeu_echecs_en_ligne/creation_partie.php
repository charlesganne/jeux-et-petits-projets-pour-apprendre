<?php
session_start();
// vérifie si les données de connexion sont envoyées via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {	

	// connexion à la base de donnée
    $conn = new PDO("mysql:host=localhost;dbname=jeu_echec;charset=utf8", "root", "");

    // récupération de l'identifiant du client connecté
   	$id_client = $_SESSION['id_client'];

   	// récupération des informations du formulaire
   	$pendule = $_POST["pendule"];
   	$temps_par_coup = $_POST['temps_par_coup'];
    $niveau = $_POST["niveau"];
    $couleur = $_POST['couleur'];

    if($couleur == "blanc")
    {
    	$sql = "INSERT INTO partie (joueur_1, pendule_j1, pendule_j2, temps_coup, niveau, point_1, point_2) VALUES('$id_client', '$pendule', '$pendule', '$temps_par_coup','$niveau', 0, 0)";
		$conn->query($sql);
    }
    else
    {
    	$sql = "INSERT INTO partie (joueur_2, pendule_j1, pendule_j2, temps_coup, niveau, point_1, point_2) VALUES('$id_client', '$pendule', '$pendule', '$temps_par_coup','$niveau', 0, 0)";
		$conn->query($sql);
    }

    echo "partie créée :) <br/>";
  	

}
?>