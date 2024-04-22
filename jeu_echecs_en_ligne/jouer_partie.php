<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Échiquier</title>
    <link rel="stylesheet" href="style_jouer_partie.css">
    <script>
	function effacerEchiquier() {
	    var echiquier = document.getElementById('echiquier');
	    if (echiquier) {
	        echiquier.innerHTML = ''; // Efface le contenu de l'échiquier
	    }
	}
	</script>
</head>
<body>

	
	<?php
	session_start();


	// État initial du jeu d'échecs représenté par un tableau 8x8
	$echiquier = array(
    array('<span style="color: black;">♜</span>', '<span style="color: black;">♞</span>', '<span style="color: black;">♝</span>', '<span style="color: black;">♛</span>', '<span style="color: black;">♚</span>', '<span style="color: black;">♝</span>', '<span style="color: black;">♞</span>', '<span style="color: black;">♜</span>'),
    array('<span style="color: black;">♟</span>', '<span style="color: black;">♟</span>', '<span style="color: black;">♟</span>', '<span style="color: black;">♟</span>', '<span style="color: black;">♟</span>', '<span style="color: black;">♟</span>', '<span style="color: black;">♟</span>', '<span style="color: black;">♟</span>'),
    array('', '', '', '', '', '', '', ''),
    array('', '', '', '', '', '', '', ''),
    array('', '', '', '', '', '', '', ''),
    array('', '', ' ', '', '', '', '', ''),
    array('<span style="color: white;">♙</span>', '<span style="color: white;">♙</span>', '<span style="color: white;">♙</span>', '<span style="color: white;">♙</span>', '<span style="color: white;">♙</span>', '<span style="color: white;">♙</span>', '<span style="color: white;">♙</span>', '<span style="color: white;">♙</span>'),
    array('<span style="color: white;">♖</span>', '<span style="color: white;">♘</span>', '<span style="color: white;">♗</span>', '<span style="color: white;">♕</span>', '<span style="color: white;">♔</span>', '<span style="color: white;">♗</span>', '<span style="color: white;">♘</span>', '<span style="color: white;">♖</span>')
);

	


	function afficherEchiquier($echiquier)
	{
    	// Début du conteneur pour l'échiquier
    	echo '<div id="echiquier">';
    	
    	echo '<table border="1">';
    	for ($i = 0; $i < 8; $i++) {
    	    echo '<tr>';
    	    for ($j = 0; $j < 8; $j++) {
    	        // Détermine la couleur de fond de la case en fonction de ses coordonnées
    	        $color = ($i + $j) % 2 == 0 ? 'background-color: #f0d9b5;' : 'background-color: #b58863; color: #fff;';
    	        echo '<td style="' . $color . '">' . $echiquier[$i][$j] . '</td>';
    	    }
    	    echo '</tr>';
    	}
    	echo '</table>';
		
    	// Fin du conteneur pour l'échiquier
    	echo '</div>';
	}
	afficherEchiquier($echiquier);

	echo '
	<form method="post">
	    <button type="submit" name="submit">effacer l\'échiquier</button>
	</form>
	';

	// Vérifie si le bouton a été cliqué
	if (isset($_POST['submit'])) {
	    // Code à exécuter lorsque le bouton est cliqué
	    echo '<script>effacerEchiquier();</script>';
	}




	?>


</body>
</html>