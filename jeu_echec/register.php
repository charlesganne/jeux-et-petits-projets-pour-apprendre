<?php
session_start();

// Vérifie si les données d'inscription sont envoyées via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupère les données d'inscription depuis le formulaire
    $newUsername = $_POST["new_username"];
    $newPassword = $_POST["new_password"];
    $newMail = $_POST["mail"];

    // Insérer les données d'inscription dans la base de données

    // connexion à la base de donnée
    $conn = new PDO("mysql:host=localhost;dbname=jeu_echec;charset=utf8", "root", "");

    // vérifier que l'adresse mail n'est pas déjè utilisée
    $sql = "SELECT email FROM Clients WHERE email = '$newMail'";
    $result = $conn->query($sql);

    $email = "";

    while ($row = $result->fetch()) {
        $email = $row['email'];
    }

    if($email == "")
    {
        // insérer le nouvel utilisateur
        $sql = "INSERT INTO clients (nom, email, mdp, elo) VALUES ('$newUsername', '$newMail', '$newPassword', 0)";
        $conn->query($sql);
        echo "vous avez bien été enregistré <br/>";
        echo '<a href="main.html"><button>retourner à la page de connexion</button></a>';
    }
    else
    {
        echo "cet adresse mail est deja utilisée, auriez vous oublié votre mot de passe ?<br/>";
    }

}
?>
