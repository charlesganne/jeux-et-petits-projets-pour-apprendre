<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre titre</title>
    <link rel="stylesheet" href="style_main_php.css">
</head>
<body>

    <?php
    session_start();
    $conn = new PDO("mysql:host=localhost;dbname=jeu_echec;charset=utf8", "root", "");
    $id_client = $_SESSION['id_client'];
    $email = $_SESSION['email'];

    // affichage du nom de la personne connectée
    echo "vous êtes " . $email . "<br><hr>";

    // la page d'acueil sera ici


    //  _______              ____    _        ______              _    _ 
    // |__   __|     /\     |  _ \  | |      |  ____|     /\     | |  | |
    //    | |       /  \    | |_) | | |      | |__       /  \    | |  | |
    //    | |      / /\ \   |  _ <  | |      |  __|     / /\ \   | |  | |
    //    | |     / ____ \  | |_) | | |____  | |____   / ____ \  | |__| |
    //    |_|    /_/    \_\ |____/  |______| |______| /_/    \_\  \____/ 


    // connexion à la base de donnée
    $conn = new PDO("mysql:host=localhost;dbname=jeu_echec;charset=utf8", "root", "");

    // récupération de toutes les parties en cours du joueur
    $sql = "SELECT joueur_1, joueur_2, point_1, point_2, pendule_j1, pendule_j2, id_partie FROM Partie WHERE (joueur_1 = $id_client OR joueur_2 = $id_client) AND statut = 'en cours'";
    $result = $conn->query($sql);

    // tableau des parties en cours
    echo '<h2 id="parties_en_cours">Parties en cours</h2><br><br>';
    echo '<table border="1">';

    echo '<tr><th>Nom adversaire</th><th>Scors adversaire</th><th>Votre score</th><th>Temps restant adversaire</th><th>Votre temps restant</th></tr>';

    // traitement du résultats de la requette qui retourne les parties en cours (si le joueur n'a aucune partie en cours, le boucle while n'est pas executée) 

    while ($row = $result->fetch()) 
    {
        // crée une nouvelle ligne du tableau html sur la page
        echo '<tr>';

        // déterminer qui est l'adversaire :

        // initialisation des variables avec des valeurs invalides
        $adversaire = -1;
        $score_adversaire = -1;
        $score_joueur = -1;
        $penduls_adversaire = -1;
        $pendule_joueur = -1;
        $id_partie = $row['id_partie'];
        
        if($id_client == $row['joueur_1'])
        {
            $adversaire = $row['joueur_2'];
            $score_adversaire = $row['point_2'];
            $score_joueur = $row['point_1'];
            $penduls_adversaire = $row['pendule_j2'];
            $pendule_joueur = $row['pendule_j1'];
        }
        else
        {
            $adversaire = $row['joueur_1'];
            $score_adversaire = $row['point_1'];
            $score_joueur = $row['point_2'];
            $penduls_adversaire = $row['pendule_j1'];
            $pendule_joueur = $row['pendule_j2'];
        }

        // récupération du nom de l'adversaire plutot que son identifiant
        $sql = "SELECT nom FROM Clients WHERE id_client = '$adversaire'";
        $nom_adversaire = $conn->query($sql)->fetch()['nom'];;

        // affichage de chaque cellule de chaque colonne
        echo '<td>' . $nom_adversaire . '</td>';
        echo '<td>' . $score_adversaire . "  points" . '</td>';
        echo '<td>' . $score_joueur . "  points" . '</td>';
        echo '<td>' . $penduls_adversaire . "  secondes" . '</td>';
        echo '<td>' . $pendule_joueur . "  secondes" . '</td>';
        echo '<td><a href="jouer_partie.php?id_partie=' . $id_partie . '&id_client=' . $id_client . '"><button>Rejoindre partie</button></a></td>';

        // fin de la ligne 
        echo '</tr>';
    }

    // Fin du tableau
    echo '</table>';

    echo "<hr>";

    // Ajout des boutons pour l'accès aux différentes fonctionnalités (ils contiennent des liens internes à la page pour pas faire trop de fichiers différents)


    //    _____   _    _   ______   _____     _____   _    _   ______   _____  
    //   / ____| | |  | | |  ____| |  __ \   / ____| | |  | | |  ____| |  __ \ 
    //  | |      | |__| | | |__    | |__) | | |      | |__| | | |__    | |__) |
    //  | |      |  __  | |  __|   |  _  /  | |      |  __  | |  __|   |  _  / 
    //  | |____  | |  | | | |____  | | \ \  | |____  | |  | | | |____  | | \ \ 
    //   \_____| |_|  |_| |______| |_|  \_\  \_____| |_|  |_| |______| |_|  \_\

    echo "<h2> Voir les parties disponibles</h2>"; 

    // bouton pour afficher toutes les parties disponibles. redirige simplement vers le tableau présent plus bas sur la page sans le modifier

    //commande SQL pour avoir les parties disponible sans filtre 
    $sql = "
    SELECT partie.id_partie, clients.nom, partie.niveau, partie.pendule_j1, partie.temps_coup 
    FROM partie INNER JOIN clients 
    ON (clients.id_client = partie.joueur_1 OR clients.id_client = partie.joueur_2)
    WHERE statut = 'En attente' AND clients.id_client <> '$id_client'";
    $parties_disponibles = $conn->query($sql);

    echo '
    <form method="post">
        <input type="submit" name = "afficher_parties_seulement" value="Sans aucun filtre"><br><br>
    </form>';

    // Vérifier si le bouton "Afficher toutes les parties disponibles" a été cliqué
    if (isset($_POST['afficher_parties_seulement'])) {
        // Redirection vers le bas de la page où se trouve le tableau des parties disponibles sans plus de modifications
        echo '<script>window.location = "#parties_disponibles";</script>';
    }


    // bouton pour filtrer le nom de l'adversaire
    echo 
    '<form method="post">
        <label for="nom_joueur">Filtrer sur le nom du joueur :</label>
        <input type="text" id="nom_joueur" name="nom_joueur" required>
        <input type="submit" name="afficher_parties_nom_adversaire" value="Chercher les parties disponibles"><br><br>
    </form>';

    // Vérifier si le bouton "Afficher les parties disponibles" a été cliqué
    if (isset($_POST['afficher_parties_nom_adversaire'])) 
    {
        
        // Récupérer le nom sélectionné dans le formulaire
        $nom = $_POST['nom_joueur'];
        
        // préparation de la requette
        $sql = "
        SELECT partie.id_partie, clients.nom, partie.niveau, partie.pendule_j1, partie.temps_coup 
        FROM partie INNER JOIN clients 
        ON (clients.id_client = partie.joueur_1 OR clients.id_client = partie.joueur_2) 
        WHERE statut = 'En attente' AND clients.nom = '$nom'";
         
        // exécution de la requette
        $parties_disponibles = $conn->query($sql);
         
        // Redirection vers le bas de la page où se trouve le tableau des parties disponibles
        echo '<script>window.location = "#parties_disponibles";</script>';
    }

    // bouton pour filtrer le niveau de l'adversaire
    echo 
    '<form method="post">
    <label for="niveau">Niveau :</label>
    <select id="niveau" name="niveau">
        <option value="facile">facile</option>
        <option value="moyen">moyen</option>
        <option value="difficile">difficile</option>
    </select>
        <input type="submit" name="afficher_parties_niveau_adversaire" value="Chercher les parties disponibles"><br><br>
    </form>';


    // Vérifier si le bouton "Afficher les parties disponibles" a été cliqué
    if (isset($_POST['afficher_parties_niveau_adversaire'])) 
    {
        // Récupérer le niveau sélectionné dans le formulaire
        $niveau = $_POST['niveau'];
        
        // préparation de la requette
        $sql = "
        SELECT partie.id_partie, clients.nom, partie.niveau, partie.pendule_j1, partie.temps_coup 
        FROM partie INNER JOIN clients 
        ON (clients.id_client = partie.joueur_1 OR clients.id_client = partie.joueur_2) 
        WHERE statut = 'En attente' AND partie.niveau = '$niveau'";
        
        // exécution de la requette
        $parties_disponibles = $conn->query($sql);
        
        // Redirection vers le bas de la page où se trouve le tableau des parties disponibles 
        echo '<script>window.location = "#parties_disponibles";</script>';
    }



    //  _   _   ______  __          __           _____              __  __   ______ 
    // | \ | | |  ____| \ \        / /          / ____|     /\     |  \/  | |  ____|
    // |  \| | | |__     \ \  /\  / /          | |  __     /  \    | \  / | | |__   
    // | . ` | |  __|     \ \/  \/ /           | | |_ |   / /\ \   | |\/| | |  __|  
    // | |\  | | |____     \  /\  /            | |__| |  / ____ \  | |  | | | |____ 
    // |_| \_| |______|     \/  \/              \_____| /_/    \_\ |_|  |_| |______|


    echo '<hr>'; // separateur

    // bouton créer partie
    echo 
    '<form method="post">
        <input type="submit" name="creer_partie" value="créer une partie">
    </form>';


    // Vérifier si le bouton "Créer une partie" a été cliqué
    if (isset($_POST['creer_partie'])) 
    {
        // Redirection vers le bas de la page où se trouve le formulaire avec les informations nécessaires à la création d'une partie
        echo '<script>window.location = "#creer_partie";</script>';
    }


    echo '<hr>';

    //         _    ____    _____   _   _                _____              __  __   ______ 
    //        | |  / __ \  |_   _| | \ | |              / ____|     /\     |  \/  | |  ____|
    //        | | | |  | |   | |   |  \| |             | |  __     /  \    | \  / | | |__   
    //    _   | | | |  | |   | |   | . ` |             | | |_ |   / /\ \   | |\/| | |  __|  
    //   | |__| | | |__| |  _| |_  | |\  |             | |__| |  / ____ \  | |  | | | |____ 
    //    \____/   \____/  |_____| |_| \_|              \_____| /_/    \_\ |_|  |_| |______|

    if (isset($_POST['rejoindre'])) {
        // Récupérer l'ID de la partie à rejoindre depuis le formulaire
        $partieId = $_POST['partie_id']; // Supposons que vous ayez un input hidden avec le nom "partie_id"

        // Exécuter la requête SQL pour mettre à jour la partie
        $sql = "UPDATE partie 
        SET joueur_2 = COALESCE(joueur_2, :id_client),
            joueur_1 = COALESCE(joueur_1, :id_client),
            statut = 'En cours'
        WHERE id_partie = :partieId AND (joueur_1 IS NULL OR joueur_2 IS NULL)"
    ;
        ;
        

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_client', $id_client);
        $stmt->bindParam(':partieId', $partieId);
        $stmt->execute();
    }


    //  _______              ____    _        ______              _    _ 
    // |__   __|     /\     |  _ \  | |      |  ____|     /\     | |  | |
    //    | |       /  \    | |_) | | |      | |__       /  \    | |  | |
    //    | |      / /\ \   |  _ <  | |      |  __|     / /\ \   | |  | |
    //    | |     / ____ \  | |_) | | |____  | |____   / ____ \  | |__| |
    //    |_|    /_/    \_\ |____/  |______| |______| /_/    \_\  \____/ 


    echo '<h2 id = "parties_disponibles">Parties disponibles</h2>';
    
    // Tableau des parties disponibles
    echo '<table>';
    echo '
    <tr>
        <th>Nom adversaire</th>
        <th>Niveau adversaire</th>
        <th>Pendule</th>
        <th>Temps par coup</th>
        <th>Rejoindre</th>
        <th>Identifiant</th>
    </tr>';

    // Boucle pour afficher les parties disponibles
    while ($row = $parties_disponibles->fetch()) 
    {
        // crée une nouvelle ligne
        echo '<tr>';

        // affichage de chaque cellule
        echo '<td>' . $row['nom'] . '</td>';
        echo '<td>' . $row['niveau'] . '</td>';
        echo '<td>' . $row['pendule_j1'] . '</td>';
        echo '<td>' . $row['temps_coup'] . '</td>';
        echo 
            '<td>' .
                '<form method="post">' .
                    // champ caché pour stocker l'ID de la partie. cela permet que l'information fasse partie du formulaire sans que l'utilisateur ne le sache et n'ait à la remplire
                    '<input type="hidden" name="partie_id" value="' . $row['id_partie'] . '">' . 
                    // Bouton de soumission du formulaire
                    '<input type="submit" name="rejoindre_partie" value="rejoindre partie">' . 
                '</form>' .
            '</td>'; 
        echo '<td>' . $row['id_partie'] . '</td>';

        // fin de la ligne
        echo '</tr>';
    }

    echo '</table> <br>';

    // si le joueur appuie sur le bouton pour rejoindre la partie, la base de donnée est actualisée et la page rechargée :
    if (isset($_POST['rejoindre_partie'])) 
    {
        
        // récupation l'identifiant de la partie qui est rejoint par le client
        $partie_id = $_POST['partie_id'];

        // Vérifier si joueur_1 ou joueur_2 est nul
        $sql = "SELECT joueur_1, joueur_2 FROM Partie WHERE id_partie = '$partie_id'";
        $result = $conn->query($sql)->fetch();

        if ($result['joueur_1'] == NULL) 
        {
            $sql_update = "
                UPDATE Partie
                SET joueur_1 = '$id_client', statut = 'en cours' 
                WHERE id_partie = $partie_id";
        } 
        else if ($row['joueur_2'] == NULL) 
        {
            $sql_update = "
                UPDATE Partie
                SET joueur_2 = '$id_client', statut = 'en cours' 
                WHERE id_partie = $partie_id";
        } 
        else 
        {
            echo "erreur !!!!!! cette partie est deja rejoint par deux joueurs";
        }

        // Exécution de la requête de mise à jour
        $conn->query($sql_update);
         
        // Redirection vers le haut de la page où se trouve le tableau des parties en cours
        echo '<script>window.location = "#parties_en_cours";</script>';
        // Actualisation de la page 
        header("Refresh:0");
    }
    echo "<hr>";

    //  _   _   ______  __          __           _____              __  __   ______ 
    // | \ | | |  ____| \ \        / /          / ____|     /\     |  \/  | |  ____|
    // |  \| | | |__     \ \  /\  / /          | |  __     /  \    | \  / | | |__   
    // | . ` | |  __|     \ \/  \/ /           | | |_ |   / /\ \   | |\/| | |  __|  
    // | |\  | | |____     \  /\  /            | |__| |  / ____ \  | |  | | | |____ 
    // |_| \_| |______|     \/  \/              \_____| /_/    \_\ |_|  |_| |______|


    echo '<h2 id="creer_partie">Création d\'une partie</h2>';

    echo '
    <form action="creation_partie.php" method="post">
        <label for="pendule">Pendule (en secondes) :</label>
        <input type="number" id="pendule" name="pendule" required><br><br>

        <label for="temps_par_coup">Temps par coup (en secondes) :</label>
        <input type="number" id="temps_par_coup" name="temps_par_coup" required><br><br>

        <label for="couleur">Couleur :</label>
        <select id="couleur" name="couleur">
            <option value="noir">Noir</option>
            <option value="blanc">Blanc</option>
        </select><br><br>

        <label for="niveau">Niveau du joueur :</label>
        <select id="niveau" name="niveau" required>
            <option value="facile">Facile</option>
            <option value="moyen">Moyen</option>
            <option value="difficile">Difficile</option>
        </select><br><br>

        <input type="submit" value="Envoyer">
    </form>';

    //   _____   _______              _______ 
    //  / ____| |__   __|     /\     |__   __|
    // | (___      | |       /  \       | |   
    //  \___ \     | |      / /\ \      | |   
    //  ____) |    | |     / ____ \     | |   
    // |_____/     |_|    /_/    \_\    |_|   


    $req = "SELECT client1.nom AS j1, client2.nom AS j2, vainqueur.nom AS v FROM partie 
    INNER JOIN clients AS client1 ON  client1.id_client = partie.joueur_1
    INNER JOIN clients AS client2 ON  client2.id_client = partie.joueur_2
    INNER JOIN clients AS vainqueur ON  vainqueur.id_client = partie.id_vainqueur
    WHERE (partie.joueur_1 = '$id_client' OR partie.joueur_2 = '$id_client') AND partie.statut = 'finie'";
    
    $parties = $conn->query($req);

    echo '<table>';
    echo '
    <tr>
        <th>Blanc</th>
        <th>Noir</th>
        <th>Vainqueur</th>
    </tr>';

    // Boucle pour afficher les parties disponibles
    while ($row = $parties->fetch()) 
    {
        // crée une nouvelle ligne
        echo '<tr>';

        // affichage de chaque cellule
        echo '<td>' . $row['j1'] . '</td>';
        echo '<td>' . $row['j2'] . '</td>';
        echo '<td>' . $row['v'] . '</td>';

        // fin de la ligne
        echo '</tr>';
    }

    echo '</table> <br>';

    // moyenne des points : 
    $req = "SELECT AVG(CASE WHEN joueur_1 = '$id_client' THEN point_1 ELSE point_2 END)
     AS moyenne FROM partie WHERE (joueur_1 = '$id_client' OR joueur_2 = '$id_client') 
     AND statut = 'finie'";

    // Exécuter la requête et récupérer le résultat
    $resultat = $conn->query($req);
    $moyenne = $resultat->fetch()['moyenne'];

    // Afficher la moyenne des points
    echo '<br> Voici la moyenne de vos points par partie : ' . $moyenne;
    
    // Combien de partie gagné : 
    $req = "SELECT count(*) AS gagnee 
    FROM partie
    WHERE (joueur_1 = '$id_client' OR joueur_2 = '$id_client')
    AND (id_vainqueur = '$id_client')
    AND statut = 'finie'";

    $resultat = $conn->query($req);
    $nb_gagnee = $resultat->fetch()['gagnee'];
    
    $req = "SELECT count(*) AS perdue  
    FROM partie
    WHERE (id_vainqueur != '$id_client') 
    AND (joueur_1 = '$id_client' OR joueur_2 = '$id_client') 
    AND statut = 'finie'";
    $resultat = $conn->query($req);
    $nb_perdue = $resultat->fetch()['perdue'];

    $pourcentage = $nb_gagnee / ($nb_perdue + $nb_gagnee+1);
    echo '<br> Voici le nombre de partie gagnée : ' . $nb_gagnee;
    echo '<br> Voici le nombre de partie perdue : ' . $nb_perdue;
    echo '<br> Voici le pourcentatge de victoire  : ' . $pourcentage;
    ?>

</body>
</html>