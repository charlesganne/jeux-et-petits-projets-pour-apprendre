<?php
session_start();

// Vérifie si les données de connexion sont envoyées via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données de connexion depuis le formulaire
    $email = $_POST["email"];
    $password = $_POST["password"];
    $new_password = $_POST["new_password"];

    // valider les informations de connexion (vérifier dans la base de données) :

    // connexion à la base de donnée
    $conn = new PDO("mysql:host=localhost;dbname=jeu_echec;charset=utf8", "root", "");

    // récupération des infos de connexion
    $sql = "SELECT mdp FROM clients WHERE email = '$email'";
    $result = $conn->query($sql);

    // traitement du résultats (si le nom d'utilisateur n'existe pas, le boucle while n'est pas executée) 
    $vrai_mot_de_passe = ""; // le mot de passe est forcément différent de la chaien vide car c'est une contrainte du langage html
    while ($row = $result->fetch()) {
        $vrai_mot_de_passe = $row['mdp'];
    }

    $connexion_valide = ($password == $vrai_mot_de_passe && $vrai_mot_de_passe <> "");

    // Si les informations sont valides, rediriger l'utilisateur vers une page spécifique
    if($connexion_valide)
    {
        // insérer le nouvel utilisateur
        $sql = "UPDATE clients SET mdp = '$new_password' WHERE email = '$email'";
        $conn->query($sql);
        echo "le changement à bien été enregistré <br/>";
        echo '<a href="main.html"><button>retourner à la page de connexion</button></a>';
    }
    // Sinon, afficher un message d'erreur
    else // connexion invalide
    {
        echo "vous avez peut être fait une erreure de saisie <br/>";
        echo "vous vous êtes peut être aussi trompé de compte, êtes vous bien le proprietaire de $email ? <br/>";
        echo '<a href="main.html"><button>retourner à la page de connexion</button></a>';

    }
    
}
?>
