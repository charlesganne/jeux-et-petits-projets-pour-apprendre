<?php
session_start();

// vérifie si les données de connexion sont envoyées via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données de connexion depuis le formulaire
    $email = $_POST["email"];
    $password = $_POST["password"];

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

    // récupération de l'identifiant
    $sql = "SELECT id_client FROM clients WHERE email = '$email'";
    $result = $conn->query($sql);

    // traitement du résultats (si le nom d'utilisateur n'existe pas, le boucle while n'est pas executée) 
    $id_client = 0; 
    while ($row = $result->fetch()) {
        $id_client = $row['id_client'];
    }

    $connexion_valide = ($password == $vrai_mot_de_passe && $vrai_mot_de_passe <> "");
    // Si les informations sont valides, rediriger l'utilisateur vers une page spécifique
    if($connexion_valide)
    {
        // Redirection vers le site avec toutes les fonctionnalités
        header("refresh:0; url=./main.php"); // refresh:0 permet de la faire instantanément, on peut aussi ajouter un délai en secondes

        // envoie de la variable id dans le fichier traitement où on en a besoin
        //session_start();
        $_SESSION['id_client'] = $id_client;
        $_SESSION['email'] = $email;
   
   
        exit(); // pour que le script PHP s'arrête après la redirection
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
