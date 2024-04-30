<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Échiquier</title>
    <link rel="stylesheet" href="style_jouer_partie.css">
	<script>
        function effacerPage() {
            document.body.innerHTML = ''; // Efface le contenu de la page
        }
    </script>
</head>
<body>



	<?php
	//session_start();

	// ces informations sont à déclarer plus tard grace à la base de donnée et aux données des autres fichiers
	$couleur_joueur = "blanc";
	$couleur_adversaire = "noir";

	// une pièce sera modélisée par un tebleau [nom, couleur, code html]
	// pour expliciter le nom de la variable : p->pion, t->tour, c->cavalier, f->fou, re->reine, ro->roi, b->blanc, n->noir

	$pb1 = array('pion', 'blanc', '<span style="color: white;">♙</span>');
	$pb2 = array('pion', 'blanc', '<span style="color: white;">♙</span>');
	$pb3 = array('pion', 'blanc', '<span style="color: white;">♙</span>');
	$pb4 = array('pion', 'blanc', '<span style="color: white;">♙</span>');
	$pb5 = array('pion', 'blanc', '<span style="color: white;">♙</span>');
	$pb6 = array('pion', 'blanc', '<span style="color: white;">♙</span>');
	$pb7 = array('pion', 'blanc', '<span style="color: white;">♙</span>');
	$pb8 = array('pion', 'blanc', '<span style="color: white;">♙</span>');

	$pn1 = array('pion', 'noir', '<span style="color: black;">♟</span>');
	$pn2 = array('pion', 'noir', '<span style="color: black;">♟</span>');
	$pn3 = array('pion', 'noir', '<span style="color: black;">♟</span>');
	$pn4 = array('pion', 'noir', '<span style="color: black;">♟</span>');
	$pn5 = array('pion', 'noir', '<span style="color: black;">♟</span>');
	$pn6 = array('pion', 'noir', '<span style="color: black;">♟</span>');
	$pn7 = array('pion', 'noir', '<span style="color: black;">♟</span>');
	$pn8 = array('pion', 'noir', '<span style="color: black;">♟</span>');

	$tb1 = array('tour', 'blanc', '<span style="color: white;">♖</span>');
	$tb2 = array('tour', 'blanc', '<span style="color: white;">♖</span>');

	$tn1 = array('tour', 'noir', '<span style="color: black;">♜</span>');
	$tn2 = array('tour', 'noir', '<span style="color: black;">♜</span>');

	$cb1 = array('cavalier', 'blanc', '<span style="color: white;">♘</span>');
	$cb2 = array('cavalier', 'blanc', '<span style="color: white;">♘</span>');

	$cn1 = array('cavalier', 'noir', '<span style="color: black;">♞</span>');
	$cn2 = array('cavalier', 'noir', '<span style="color: black;">♞</span>');

	$fb1 = array('fou', 'blanc', '<span style="color: white;">♗</span>');
	$fb2 = array('fou', 'blanc', '<span style="color: white;">♗</span>');

	$fn1 = array('fou', 'noir', '<span style="color: black;">♝</span>');
	$fn2 = array('fou', 'noir', '<span style="color: black;">♝</span>');

	$reb = array('reine', 'blanc', '<span style="color: white;">♕</span>');
	$rob = array('roi', 'blanc', '<span style="color: white;">♔</span>');

	$ren = array('reine', 'noir', '<span style="color: black;">♛</span>');
	$ron = array('roi', 'noir', '<span style="color: black;">♚</span>');

	// pièce vide (pv) qui modélisera un espace vacant
	$pv = array('pv', 'pv', '');

	// état initial du jeu d'échecs représenté par un tableau 8x8
	$echiquier = array(
		array($tn1, $cn1, $fn1, $ren, $ron, $fn2, $cn2, $tn2),
		array($pn1, $pn2, $pn3, $pn4, $pn5, $pn6, $pn7, $pn8),
		array($pv, $pv, $pv, $pv, $pv, $pv, $pv, $pv),
		array($pv, $pv, $pv, $pv, $pv, $pv, $pv, $pv),
		array($pv, $pv, $pv, $pv, $pv, $pv, $pv, $pv),
		array($pv, $pv, $pv, $pv, $pv, $pv, $pv, $pv),
		array($pv, $pb2, $pb3, $pv, $pv, $pb6, $pb7, $pb8),//array($pb1, $pb2, $pb3, $pb4, $pb5, $pb6, $pb7, $pb8),
		array($tb1, $cb1, $fb1, $reb, $rob, $fb2, $cb2, $tb2)
	);

	function afficherEchiquier($echiquier) 
	{
		// Début du conteneur pour l'échiquier
    	echo '<div id="echiquier">';
    	
    	echo '<table border="1">';
    	for ($i = 0; $i < 8; $i++) {
    	    echo '<tr><td>' . 8-$i . '</td>';
    	    for ($j = 0; $j < 8; $j++) {
    	        // Détermine la couleur de fond de la case en fonction de ses coordonnées
    	        $color = ($i + $j) % 2 == 0 ? 'background-color: #f0d9b5;' : 'background-color: #b58863; color: #fff;';
    	        echo '<td id = ' . $i . $j . ' style="' . $color . '">' . $echiquier[$i][$j][2] . '</td>';
    	    }
    	    echo '</tr>';
    	}
    	echo '<tr><td></td><td>A</td><td>B</td><td>C</td><td>D</td><td>E</td><td>F</td><td>G</td><td>H</td></tr>';
    	echo '</table>';
		
    	// Fin du conteneur pour l'échiquier
    	echo '</div>';
	}

	function echec_vers_matriciel($lettre, $nombre) 
	// retourne : [abscisse, ordonnee]
	{
		$abscisse = -1;
		$ordonnee = -1;

		// transformer en coordonnées du tableau de grille d'échec en coordonnées matriciels :
		// ordonnée (combientième tableau)
		switch ($nombre) {
		    case 8:
		        $ordonnee = 0;
		        break;
		    case 7:
		        $ordonnee = 1;
		        break;
		    case 6:
		        $ordonnee = 2;
		        break;
			case 5:
		        $ordonnee = 3;
		        break;
			case 4:
		        $ordonnee = 4;
		        break;
			case 3:
		        $ordonnee = 5;
		        break;
			case 2:
		        $ordonnee = 6;
		        break;
			case 1:
		        $ordonnee = 7;
		        break;
		}

		// transformer en coordonnées du tableau en coordonnées matriciels :
		// abscisse (combientième place dans le tableau précédement séléctionné)
		switch ($lettre) {
		    case 'A':
		        $abscisse = 0;
		        break;
		    case 'B':
		        $abscisse = 1;
		        break;
		    case 'C':
		        $abscisse = 2;
		        break;
			case 'D':
		        $abscisse = 3;
		        break;
			case 'E':
		        $abscisse = 4;
		        break;
			case 'F':
		        $abscisse = 5;
		        break;
			case 'G':
		        $abscisse = 6;
		        break;
			case 'H':
		        $abscisse = 7;
		        break;
		}

		return array($abscisse, $ordonnee);	
	}

	function appelerEffacerPage() 
	{
		echo '<script>effacerPage();</script>';
	}

	function appartient($big_tab, $small_tab)
	{
		$taille = count($big_tab);
		for($i = 0; $i < $taille; $i++)
		{
			if($big_tab[$i][0] == $small_tab[1] && $big_tab[$i][1] == $small_tab[0])
			{
				return true;
			}
		}
		return false;
	}

	function mouvements_possibles($echiquier, $abscisse, $ordonnee, $couleur_adversaire, $couleur_joueur) 
	{
		// prend l'échiquier, les coordonnées de la pièce à déplacer, les couleurs des joueurs
		// retourne le tableau des mouvements disponibles [[x1, y1], [x2, y2], ...]
		$tableau_mvts_disponibles = array();
		if($echiquier[$ordonnee][$abscisse][0] == 'pv')
		// si la case contient la pièce vide
		{
			echo "veuillez selectionner une case avec une pièce";
		}
		else if($echiquier[$ordonnee][$abscisse][1] == $couleur_adversaire)
		{
			echo "veuillez selectionner une case avec une pièce qui vous appartient";
		}
		else
		{
			// le joueur a bien séléctionné une de ses pièces, on determine maintenant les endroits possibles où il a le droit de se depacer
			// disjonction de cas selon les mouvements possibles des pièces, et ajout de ses mouvements possibles au tableau des mouvements disponibles :
			
			// pion
			if($echiquier[$ordonnee][$abscisse][0] == 'pion')
			{
				// blanc
				if($echiquier[$ordonnee][$abscisse][1] == 'blanc')
				{
					// la pièce n'a pas encore bougée, elle peut bouger de deux cases
					if($ordonnee == 6)
					{
						if($echiquier[$ordonnee-2][$abscisse][0] == 'pv' && $echiquier[$ordonnee-1][$abscisse][0] == 'pv')
						{
							array_push($tableau_mvts_disponibles, array($ordonnee-2, $abscisse));
						}
					}
					// si elle peut bouger d'une case
					if($echiquier[$ordonnee-1][$abscisse][0] == 'pv')
					{
						array_push($tableau_mvts_disponibles, array($ordonnee-1, $abscisse));
					}
					// si la case en haut à gauche est ennemie
					if($abscisse-1 >= 0 && $echiquier[$ordonnee-1][$abscisse-1][1] == 'noir')
					{
						array_push($tableau_mvts_disponibles, array($ordonnee-1, $abscisse-1));
					}
					// si la case en haut à droite est ennemie
					if($abscisse+1 <=7 && $echiquier[$ordonnee-1][$abscisse+1][1] == 'noir')
					{
						array_push($tableau_mvts_disponibles, array($ordonnee-1, $abscisse+1));
					}
				}
				// noir
				else
				{
					// la pièce n'a pas encore bougée, elle peut bouger de deux cases
					if($ordonnee == 1)
					{
						if($echiquier[$ordonnee+2][$abscisse][0] == 'pv' && $echiquier[$ordonnee+1][$abscisse][0] == 'pv')
						{
							array_push($tableau_mvts_disponibles, array($ordonnee+2, $abscisse));
						}
					}
					// si elle peut bouger d'une case
					if($echiquier[$ordonnee+1][$abscisse][0] == 'pv')
					{
						array_push($tableau_mvts_disponibles, array($ordonnee+1, $abscisse));
					}
					// si la case en bas à gauche est ennemie
					if($abscisse-1 >= 0 && $echiquier[$ordonnee+1][$abscisse-1][1] == 'blanc')
					{
						array_push($tableau_mvts_disponibles, array($ordonnee+1, $abscisse-1));
					}
					// si la case en bas à droite est ennemie
					if($abscisse+1 <=7 && $echiquier[$ordonnee+1][$abscisse+1][1] == 'blanc')
					{
						array_push($tableau_mvts_disponibles, array($ordonnee+1, $abscisse+1));
					}
				}
			}

			// tour
			else if($echiquier[$ordonnee][$abscisse][0] == 'tour')
			{
				// traitement de chaque direction (haut, bas, gauche, droite) :
				// haut
				for($i=1; $i < 8; $i++)
				{
					if($ordonnee - $i < 0 || $echiquier[$ordonnee-$i][$abscisse][1] == $couleur_joueur)
					{
						break;
					}
					if($echiquier[$ordonnee-$i][$abscisse][1] == $couleur_adversaire)
					{
						array_push($tableau_mvts_disponibles, array($ordonnee-$i, $abscisse));
						break;
					}
					array_push($tableau_mvts_disponibles, array($ordonnee-$i, $abscisse));
				}
				// bas
				for($i=1; $i < 8; $i++)
				{
					if($ordonnee + $i > 7 || $echiquier[$ordonnee+$i][$abscisse][1] == $couleur_joueur)
					{
						break;
					}
					if($echiquier[$ordonnee+$i][$abscisse][1] == $couleur_adversaire)
					{
						array_push($tableau_mvts_disponibles, array($ordonnee+$i, $abscisse));
						break;
					}
					array_push($tableau_mvts_disponibles, array($ordonnee+$i, $abscisse));
				}
				// gauche
				for($i=1; $i < 8; $i++)
				{
						if($abscisse - $i < 0 || $echiquier[$ordonnee][$abscisse - $i][1] == $couleur_joueur)
						{
							break;
						}
						if($echiquier[$ordonnee][$abscisse - $i][1] == $couleur_adversaire)
						{
							array_push($tableau_mvts_disponibles, array($ordonnee, $abscisse - $i));
							break;
						}
						array_push($tableau_mvts_disponibles, array($ordonnee, $abscisse - $i));
				}
				// droite
				for($i=1; $i < 8; $i++)
				{
						if($abscisse + $i > 7 || $echiquier[$ordonnee][$abscisse + $i][1] == $couleur_joueur)
						{
							break;
						}
						if($echiquier[$ordonnee][$abscisse + $i][1] == $couleur_adversaire)
						{
							array_push($tableau_mvts_disponibles, array($ordonnee, $abscisse + $i));
							break;
						}
						array_push($tableau_mvts_disponibles, array($ordonnee, $abscisse + $i));
				}
			}

			//cavalier
			else if($echiquier[$ordonnee][$abscisse][0] == 'cavalier')
			{
					if($ordonnee-2 >= 0 && $abscisse+1 <= 7)
					{
						if($echiquier[$ordonnee-2][$abscisse+1][1] == $couleur_adversaire || $echiquier[$ordonnee-2][$abscisse+1][0] == 'pv')
						{
							array_push($tableau_mvts_disponibles, array($ordonnee-2,$abscisse+1));
						}
					}
					if($ordonnee-2 >= 0 && $abscisse-1 >= 0)
					{
						if($echiquier[$ordonnee-2][$abscisse-1][1] == $couleur_adversaire || $echiquier[$ordonnee-2][$abscisse-1][0] == 'pv')
						{
							array_push($tableau_mvts_disponibles, array($ordonnee-2,$abscisse-1));
						}
					}
					if($ordonnee+2 <= 7 && $abscisse+1 <= 7)
					{
						if($echiquier[$ordonnee+2][$abscisse+1][1] == $couleur_adversaire || $echiquier[$ordonnee+2][$abscisse+1][0] == 'pv')
						{
							array_push($tableau_mvts_disponibles, array($ordonnee+2,$abscisse+1));
						}
					}
					if($ordonnee+2 <= 7 && $abscisse-1 >= 0)
					{
						if($echiquier[$ordonnee+2][$abscisse-1][1] == $couleur_adversaire || $echiquier[$ordonnee+2][$abscisse-1][0] == 'pv')
						{
							array_push($tableau_mvts_disponibles, array($ordonnee+2,$abscisse-1));
						}
					}
					if($ordonnee-1 >= 0 && $abscisse+2 <= 7)
					{
						if($echiquier[$ordonnee-1][$abscisse+2][1] == $couleur_adversaire || $echiquier[$ordonnee-1][$abscisse+2][0] == 'pv')
						{
							array_push($tableau_mvts_disponibles, array($ordonnee-1,$abscisse+2));
						}
					}
					if($ordonnee-1 >= 0 && $abscisse-1 >= 0)
					{
						if($echiquier[$ordonnee-1][$abscisse-1][1] == $couleur_adversaire || $echiquier[$ordonnee-1][$abscisse-1][0] == 'pv')
						{
							array_push($tableau_mvts_disponibles, array($ordonnee-1,$abscisse-1));
						}
					}
					if($ordonnee+1 <= 7 && $abscisse+2 <= 7)
					{
						if($echiquier[$ordonnee+1][$abscisse+2][1] == $couleur_adversaire || $echiquier[$ordonnee+1][$abscisse+2][0] == 'pv')
						{
							array_push($tableau_mvts_disponibles, array($ordonnee+1,$abscisse+2));
						}
					}
					if($ordonnee+1 <= 7 && $abscisse-2 >= 0)
					{
						if($echiquier[$ordonnee+1][$abscisse-2][1] == $couleur_adversaire || $echiquier[$ordonnee+1][$abscisse-2][0] == 'pv')
						{
							array_push($tableau_mvts_disponibles, array($ordonnee+1,$abscisse-2));
						}
					}
			}

			//fou
			else if($echiquier[$ordonnee][$abscisse][0] == 'fou')
			{
					// traitement des directions en diagonale
					//haut droite
					for($i=1; $i<8; $i++)
					{
						if($ordonnee-$i < 0 || $abscisse+$i > 7 || $echiquier[$ordonnee-$i][$abscisse+$i][1] == $couleur_joueur)
						{
							break;
						}
						else if($echiquier[$ordonnee-$i][$abscisse+$i][1] == $couleur_adversaire)
						{
							array_push($tableau_mvts_disponibles, array($ordonnee-$i, $abscisse+$i));
							break;
						}
						array_push($tableau_mvts_disponibles, array($ordonnee-$i, $abscisse+$i));
					}
					//haut gauche
					for($i=1; $i<8; $i++)
					{
						if($ordonnee-$i < 0 || $abscisse-$i < 0 || $echiquier[$ordonnee-$i][$abscisse-$i][1] == $couleur_joueur)
						{
							break;
						}
						else if($echiquier[$ordonnee-$i][$abscisse-$i][1] == $couleur_adversaire)
						{
							array_push($tableau_mvts_disponibles, array($ordonnee-$i, $abscisse-$i));
							break;
						}
						array_push($tableau_mvts_disponibles, array($ordonnee-$i, $abscisse-$i));
					}
					//bas droite
					for($i=1; $i<8; $i++)
					{
						if($ordonnee+$i > 7 || $abscisse+$i > 7 || $echiquier[$ordonnee+$i][$abscisse+$i][1] == $couleur_joueur)
						{
							break;
						}
						else if($echiquier[$ordonnee+$i][$abscisse+$i][1] == $couleur_adversaire)
						{
							array_push($tableau_mvts_disponibles, array($ordonnee+$i, $abscisse+$i));
							break;
						}
						array_push($tableau_mvts_disponibles, array($ordonnee+$i, $abscisse+$i));
					}
					//bas gauche
					for($i=1; $i<8; $i++)
					{
						if($ordonnee+$i > 7 || $abscisse-$i < 0 || $echiquier[$ordonnee+$i][$abscisse-$i][1] == $couleur_joueur)
						{
							break;
						}
						else if($echiquier[$ordonnee+$i][$abscisse-$i][1] == $couleur_adversaire)
						{
							array_push($tableau_mvts_disponibles, array($ordonnee+$i, $abscisse-$i));
							break;
						}
						array_push($tableau_mvts_disponibles, array($ordonnee+$i, $abscisse-$i));
					}
			}

			//reine (copier/coller du fou et de la tour)
			else if($echiquier[$ordonnee][$abscisse][0] == 'reine')
			{
					// traitement de chaque direction (haut, bas, gauche, droite) :
					// haut
					for($i=1; $i < 8; $i++)
					{
						if($ordonnee - $i < 0 || $echiquier[$ordonnee-$i][$abscisse][1] == $couleur_joueur)
						{
							break;
						}
						if($echiquier[$ordonnee-$i][$abscisse][1] == $couleur_adversaire)
						{
							array_push($tableau_mvts_disponibles, array($ordonnee-$i, $abscisse));
							break;
						}
						array_push($tableau_mvts_disponibles, array($ordonnee-$i, $abscisse));
					}
					// bas
					for($i=1; $i < 8; $i++)
					{
						if($ordonnee + $i > 7 || $echiquier[$ordonnee+$i][$abscisse][1] == $couleur_joueur)
						{
							break;
						}
						if($echiquier[$ordonnee+$i][$abscisse][1] == $couleur_adversaire)
						{
							array_push($tableau_mvts_disponibles, array($ordonnee+$i, $abscisse));
							break;
						}
						array_push($tableau_mvts_disponibles, array($ordonnee+$i, $abscisse));
					}
					// gauche
					for($i=1; $i < 8; $i++)
					{
						if($abscisse - $i < 0 || $echiquier[$ordonnee][$abscisse - $i][1] == $couleur_joueur)
						{
							break;
						}
						if($echiquier[$ordonnee][$abscisse - $i][1] == $couleur_adversaire)
						{
							array_push($tableau_mvts_disponibles, array($ordonnee, $abscisse - $i));
							break;
						}
						array_push($tableau_mvts_disponibles, array($ordonnee, $abscisse - $i));
					}
					// droite
					for($i=1; $i < 8; $i++)
					{
						if($abscisse + $i > 7 || $echiquier[$ordonnee][$abscisse + $i][1] == $couleur_joueur)
						{
							break;
						}
						if($echiquier[$ordonnee][$abscisse + $i][1] == $couleur_adversaire)
						{
							array_push($tableau_mvts_disponibles, array($ordonnee, $abscisse + $i));
							break;
						}
						array_push($tableau_mvts_disponibles, array($ordonnee, $abscisse + $i));
					}
					// traitement des directions en diagonale
					//haut droite
					for($i=1; $i<8; $i++)
					{
						if($ordonnee-$i < 0 || $abscisse+$i > 7 || $echiquier[$ordonnee-$i][$abscisse+$i][1] == $couleur_joueur)
						{
							break;
						}
						else if($echiquier[$ordonnee-$i][$abscisse+$i][1] == $couleur_adversaire)
						{
							array_push($tableau_mvts_disponibles, array($ordonnee-$i, $abscisse+$i));
							break;
						}
						array_push($tableau_mvts_disponibles, array($ordonnee-$i, $abscisse+$i));
					}
					//haut gauche
					for($i=1; $i<8; $i++)
					{
						if($ordonnee-$i < 0 || $abscisse-$i < 0 || $echiquier[$ordonnee-$i][$abscisse-$i][1] == $couleur_joueur)
						{
							break;
						}
						else if($echiquier[$ordonnee-$i][$abscisse-$i][1] == $couleur_adversaire)
						{
							array_push($tableau_mvts_disponibles, array($ordonnee-$i, $abscisse-$i));
							break;
						}
						array_push($tableau_mvts_disponibles, array($ordonnee-$i, $abscisse-$i));
					}
					//bas droite
					for($i=1; $i<8; $i++)
					{
						if($ordonnee+$i > 7 || $abscisse+$i > 7 || $echiquier[$ordonnee+$i][$abscisse+$i][1] == $couleur_joueur)
						{
							break;
						}
						else if($echiquier[$ordonnee+$i][$abscisse+$i][1] == $couleur_adversaire)
						{
							array_push($tableau_mvts_disponibles, array($ordonnee+$i, $abscisse+$i));
							break;
						}
						array_push($tableau_mvts_disponibles, array($ordonnee+$i, $abscisse+$i));
					}
					//bas gauche
					for($i=1; $i<8; $i++)
					{
						if($ordonnee+$i > 7 || $abscisse-$i < 0 || $echiquier[$ordonnee+$i][$abscisse-$i][1] == $couleur_joueur)
						{
							break;
						}
						else if($echiquier[$ordonnee+$i][$abscisse-$i][1] == $couleur_adversaire)
						{
							array_push($tableau_mvts_disponibles, array($ordonnee+$i, $abscisse-$i));
							break;
						}
						array_push($tableau_mvts_disponibles, array($ordonnee+$i, $abscisse-$i));
					}
			}

			//roi
			else if($echiquier[$ordonnee][$abscisse][0] == 'roi')
			{
					//bas
					if($ordonnee+1 <= 7 && $echiquier[$ordonnee+1][$abscisse][1] != $couleur_joueur)
					{
						array_push($tableau_mvts_disponibles, array($ordonnee+1, $abscisse));
					}
					//haut
					if($ordonnee-1 >= 0 && $echiquier[$ordonnee-1][$abscisse][1] != $couleur_joueur)
					{
						array_push($tableau_mvts_disponibles, array($ordonnee-1, $abscisse));
					}
					//droite
					if($abscisse+1 <= 7 && $echiquier[$ordonnee][$abscisse+1][1] != $couleur_joueur)
					{
						array_push($tableau_mvts_disponibles, array($ordonnee, $abscisse+1));
					}
					//gauche
					if($abscisse-1 >= 0 && $echiquier[$ordonnee][$abscisse-1][1] != $couleur_joueur)
					{
						array_push($tableau_mvts_disponibles, array($ordonnee, $abscisse-1));
					}
					//bas/gauche
					if($ordonnee+1 <= 7 && $abscisse-1 >= 0 && $echiquier[$ordonnee+1][$abscisse-1][1] != $couleur_joueur)
					{
						array_push($tableau_mvts_disponibles, array($ordonnee+1, $abscisse-1));
					}
					//bas/droite
					if($ordonnee+1 <= 7 && $abscisse+1 <= 7 && $echiquier[$ordonnee+1][$abscisse+1][1] != $couleur_joueur)
					{
						array_push($tableau_mvts_disponibles, array($ordonnee+1, $abscisse+1));
					}
					//haut/gauche
					if($ordonnee-1 >= 0 && $abscisse-1 >= 0 && $echiquier[$ordonnee-1][$abscisse-1][1] != $couleur_joueur)
					{
						array_push($tableau_mvts_disponibles, array($ordonnee-1, $abscisse-1));
					}
					//haut/droite
					if($ordonnee-1 >= 0 && $abscisse+1 <= 7 && $echiquier[$ordonnee-1][$abscisse+1][1] != $couleur_joueur)
					{
						array_push($tableau_mvts_disponibles, array($ordonnee-1, $abscisse+1));
					}
			}
		}
		return $tableau_mvts_disponibles;
	}

	function demander_quelle_piece_deplacer($echiquier) 
	{
		// retourne [lettre, nombre]
		echo '
		<h2>séléction de la pièce à déplacer</h2>
	    <form method="post">

	        <label for="lettre">Sélectionnez une lettre (A à H) :</label>
	        <select id="lettre" name="lettre" required>
	        	<option value="">-- Choisissez un nombre --</option>
	            <option value="A">A</option>
	            <option value="B">B</option>
	            <option value="C">C</option>
	            <option value="D">D</option>
	            <option value="E">E</option>
	            <option value="F">F</option>
	            <option value="G">G</option>
	            <option value="H">H</option>
	        </select>

	        <br><br>
	        <label for="nombre">Sélectionnez un nombre (1 à 8) :</label>
	        <select id="nombre" name="nombre" required>
	            <option value="">-- Choisissez un nombre --</option>
	            <option value="1">1</option>
	            <option value="2">2</option>
	            <option value="3">3</option>
	            <option value="4">4</option>
	            <option value="5">5</option>
	            <option value="6">6</option>
	            <option value="7">7</option>
	            <option value="8">8</option>
	        </select>
	        <br><br>
	        <input type="submit" value="Valider">
	    </form>';

		// récupérer les valeurs soumises
		if(isset($_POST["submit"]) && $_POST != array())
		{
			$lettre = $_POST["lettre"];
			$nombre = $_POST["nombre"];

			return array($lettre, $nombre);
		}
	}

	function demander_a_quel_endroit_la_mettre()
	// retourne [lettre, nombre]
	{
		// formulaire pour demander où on veut ammener la pièce
		echo '
		<h2>séléction de la case d arrivée</h2>
	    <form method="post">

	        <label for="lettre">Sélectionnez une lettre (A à H) :</label>
	        <select id="lettre" name="lettre" required>
	        	<option value="">-- Choisissez un nombre --</option>
	            <option value="A">A</option>
	            <option value="B">B</option>
	            <option value="C">C</option>
	            <option value="D">D</option>
	            <option value="E">E</option>
	            <option value="F">F</option>
	            <option value="G">G</option>
	            <option value="H">H</option>
	        </select>

	        <br><br>
	        <label for="nombre">Sélectionnez un nombre (1 à 8) :</label>
	        <select id="nombre" name="nombre" required>
	            <option value="">-- Choisissez un nombre --</option>
	            <option value="1">1</option>
	            <option value="2">2</option>
	            <option value="3">3</option>
	            <option value="4">4</option>
	            <option value="5">5</option>
	            <option value="6">6</option>
	            <option value="7">7</option>
	            <option value="8">8</option>
	        </select>
	        <br><br>
	        <input type="submit" value="Valider">
	    </form>';

		// récupérer les valeurs soumises
		$lettre = $_POST["lettre"];
		$nombre = $_POST["nombre"];
		return array($lettre, $nombre);
	}
















	afficherEchiquier($echiquier);

	// demander la pièce à déplacer
	echo '
	<h2>séléction de la pièce à déplacer</h2>
	<form method="post">

		<label for="lettre">Sélectionnez une lettre (A à H) :</label>
		<select id="lettre" name="lettre" required>
			<option value="">-- Choisissez un nombre --</option>
			<option value="A">A</option>
			<option value="B">B</option>
			<option value="C">C</option>
			<option value="D">D</option>
			<option value="E">E</option>
			<option value="F">F</option>
			<option value="G">G</option>
			<option value="H">H</option>
		</select>

		<br><br>
		<label for="nombre">Sélectionnez un nombre (1 à 8) :</label>
		<select id="nombre" name="nombre" required>
			<option value="">-- Choisissez un nombre --</option>
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5">5</option>
			<option value="6">6</option>
			<option value="7">7</option>
			<option value="8">8</option>
		</select>

		<br><br>
		<h2>séléction de la case d arrivée</h2>

		<br><br>
		<label for="lettre2">Sélectionnez une lettre (A à H) :</label>
		<select id="lettre2" name="lettre2" required>
			<option value="">-- Choisissez un nombre --</option>
			<option value="A">A</option>
			<option value="B">B</option>
			<option value="C">C</option>
			<option value="D">D</option>
			<option value="E">E</option>
			<option value="F">F</option>
			<option value="G">G</option>
			<option value="H">H</option>
		</select>

		<br><br>
		<label for="nombre2">Sélectionnez un nombre (1 à 8) :</label>
		<select id="nombre2" name="nombre2" required>
			<option value="">-- Choisissez un nombre --</option>
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5">5</option>
			<option value="6">6</option>
			<option value="7">7</option>
			<option value="8">8</option>
		</select>
				
		<br><br>
		<input type="submit" value="Valider">
	</form>';


	if (isset($_POST["lettre"]) && isset($_POST["nombre"]) && isset($_POST["lettre2"]) && isset($_POST["nombre2"])) 
	{
		// récupérer les valeurs soumises
		$lettre = $_POST["lettre"];
		$nombre = $_POST["nombre"];

		$coordonnees = echec_vers_matriciel($lettre, $nombre);
		// retourne : [abscisse, ordonnee]

		$abscisse = $coordonnees[0];
		$ordonnee = $coordonnees[1];

		// vérifier la pièce demandée :
		// prend l'échiquier, les coordonnées de la pièce à déplacer, les couleurs des joueurs
		// retourne le tableau des mouvements disponibles [[x1, y1], [x2, y2], ...]
		$tableau_mvts_disponibles = array();
		if($echiquier[$ordonnee][$abscisse][0] == 'pv')
		// si la case contient la pièce vide
		{
			echo "veuillez selectionner une case avec une pièce";
		}
		else if($echiquier[$ordonnee][$abscisse][1] == $couleur_adversaire)
		{
			echo "veuillez selectionner une case avec une pièce qui vous appartient";
		}
		else
		{
			// le joueur a bien séléctionné une de ses pièces, on determine maintenant les endroits possibles où il a le droit de se depacer
			// disjonction de cas selon les mouvements possibles des pièces, et ajout de ses mouvements possibles au tableau des mouvements disponibles :
			
			// pion
			if($echiquier[$ordonnee][$abscisse][0] == 'pion')
			{
				// blanc
				if($echiquier[$ordonnee][$abscisse][1] == 'blanc')
				{
					// la pièce n'a pas encore bougée, elle peut bouger de deux cases
					if($ordonnee == 6)
					{
						if($echiquier[$ordonnee-2][$abscisse][0] == 'pv' && $echiquier[$ordonnee-1][$abscisse][0] == 'pv')
						{
							array_push($tableau_mvts_disponibles, array($ordonnee-2, $abscisse));
						}
					}
					// si elle peut bouger d'une case
					if($echiquier[$ordonnee-1][$abscisse][0] == 'pv')
					{
						array_push($tableau_mvts_disponibles, array($ordonnee-1, $abscisse));
					}
					// si la case en haut à gauche est ennemie
					if($abscisse-1 >= 0 && $echiquier[$ordonnee-1][$abscisse-1][1] == 'noir')
					{
						array_push($tableau_mvts_disponibles, array($ordonnee-1, $abscisse-1));
					}
					// si la case en haut à droite est ennemie
					if($abscisse+1 <=7 && $echiquier[$ordonnee-1][$abscisse+1][1] == 'noir')
					{
						array_push($tableau_mvts_disponibles, array($ordonnee-1, $abscisse+1));
					}
				}
				// noir
				else
				{
					// la pièce n'a pas encore bougée, elle peut bouger de deux cases
					if($ordonnee == 1)
					{
						if($echiquier[$ordonnee+2][$abscisse][0] == 'pv' && $echiquier[$ordonnee+1][$abscisse][0] == 'pv')
						{
							array_push($tableau_mvts_disponibles, array($ordonnee+2, $abscisse));
						}
					}
					// si elle peut bouger d'une case
					if($echiquier[$ordonnee+1][$abscisse][0] == 'pv')
					{
						array_push($tableau_mvts_disponibles, array($ordonnee+1, $abscisse));
					}
					// si la case en bas à gauche est ennemie
					if($abscisse-1 >= 0 && $echiquier[$ordonnee+1][$abscisse-1][1] == 'blanc')
					{
						array_push($tableau_mvts_disponibles, array($ordonnee+1, $abscisse-1));
					}
					// si la case en bas à droite est ennemie
					if($abscisse+1 <=7 && $echiquier[$ordonnee+1][$abscisse+1][1] == 'blanc')
					{
						array_push($tableau_mvts_disponibles, array($ordonnee+1, $abscisse+1));
					}
				}
			}

			// tour
			else if($echiquier[$ordonnee][$abscisse][0] == 'tour')
			{
				// traitement de chaque direction (haut, bas, gauche, droite) :
				// haut
				for($i=1; $i < 8; $i++)
				{
					if($ordonnee - $i < 0 || $echiquier[$ordonnee-$i][$abscisse][1] == $couleur_joueur)
					{
						break;
					}
					if($echiquier[$ordonnee-$i][$abscisse][1] == $couleur_adversaire)
					{
						array_push($tableau_mvts_disponibles, array($ordonnee-$i, $abscisse));
						break;
					}
					array_push($tableau_mvts_disponibles, array($ordonnee-$i, $abscisse));
				}
				// bas
				for($i=1; $i < 8; $i++)
				{
					if($ordonnee + $i > 7 || $echiquier[$ordonnee+$i][$abscisse][1] == $couleur_joueur)
					{
						break;
					}
					if($echiquier[$ordonnee+$i][$abscisse][1] == $couleur_adversaire)
					{
						array_push($tableau_mvts_disponibles, array($ordonnee+$i, $abscisse));
						break;
					}
					array_push($tableau_mvts_disponibles, array($ordonnee+$i, $abscisse));
				}
				// gauche
				for($i=1; $i < 8; $i++)
				{
					if($abscisse - $i < 0 || $echiquier[$ordonnee][$abscisse - $i][1] == $couleur_joueur)
					{
						break;
					}
					if($echiquier[$ordonnee][$abscisse - $i][1] == $couleur_adversaire)
					{
						array_push($tableau_mvts_disponibles, array($ordonnee, $abscisse - $i));
						break;
					}
					array_push($tableau_mvts_disponibles, array($ordonnee, $abscisse - $i));
				}
				// droite
				for($i=1; $i < 8; $i++)
				{
					if($abscisse + $i > 7 || $echiquier[$ordonnee][$abscisse + $i][1] == $couleur_joueur)
					{
						break;
					}
					if($echiquier[$ordonnee][$abscisse + $i][1] == $couleur_adversaire)
					{
						array_push($tableau_mvts_disponibles, array($ordonnee, $abscisse + $i));
						break;
					}
					array_push($tableau_mvts_disponibles, array($ordonnee, $abscisse + $i));
				}
			}

			//cavalier
			else if($echiquier[$ordonnee][$abscisse][0] == 'cavalier')
			{
				if($ordonnee-2 >= 0 && $abscisse+1 <= 7)
				{
					if($echiquier[$ordonnee-2][$abscisse+1][1] == $couleur_adversaire || $echiquier[$ordonnee-2][$abscisse+1][0] == 'pv')
					{
						array_push($tableau_mvts_disponibles, array($ordonnee-2,$abscisse+1));
					}
				}
				if($ordonnee-2 >= 0 && $abscisse-1 >= 0)
				{
					if($echiquier[$ordonnee-2][$abscisse-1][1] == $couleur_adversaire || $echiquier[$ordonnee-2][$abscisse-1][0] == 'pv')
					{
						array_push($tableau_mvts_disponibles, array($ordonnee-2,$abscisse-1));
					}
				}
				if($ordonnee+2 <= 7 && $abscisse+1 <= 7)
				{
					if($echiquier[$ordonnee+2][$abscisse+1][1] == $couleur_adversaire || $echiquier[$ordonnee+2][$abscisse+1][0] == 'pv')
					{
						array_push($tableau_mvts_disponibles, array($ordonnee+2,$abscisse+1));
					}
				}
				if($ordonnee+2 <= 7 && $abscisse-1 >= 0)
				{
					if($echiquier[$ordonnee+2][$abscisse-1][1] == $couleur_adversaire || $echiquier[$ordonnee+2][$abscisse-1][0] == 'pv')
					{
						array_push($tableau_mvts_disponibles, array($ordonnee+2,$abscisse-1));
					}
				}
				if($ordonnee-1 >= 0 && $abscisse+2 <= 7)
				{
					if($echiquier[$ordonnee-1][$abscisse+2][1] == $couleur_adversaire || $echiquier[$ordonnee-1][$abscisse+2][0] == 'pv')
					{
						array_push($tableau_mvts_disponibles, array($ordonnee-1,$abscisse+2));
					}
				}
				if($ordonnee-1 >= 0 && $abscisse-1 >= 0)
				{
					if($echiquier[$ordonnee-1][$abscisse-1][1] == $couleur_adversaire || $echiquier[$ordonnee-1][$abscisse-1][0] == 'pv')
					{
						array_push($tableau_mvts_disponibles, array($ordonnee-1,$abscisse-1));
					}
				}
				if($ordonnee+1 <= 7 && $abscisse+2 <= 7)
				{
					if($echiquier[$ordonnee+1][$abscisse+2][1] == $couleur_adversaire || $echiquier[$ordonnee+1][$abscisse+2][0] == 'pv')
					{
						array_push($tableau_mvts_disponibles, array($ordonnee+1,$abscisse+2));
					}
				}
				if($ordonnee+1 <= 7 && $abscisse-2 >= 0)
				{
					if($echiquier[$ordonnee+1][$abscisse-2][1] == $couleur_adversaire || $echiquier[$ordonnee+1][$abscisse-2][0] == 'pv')
					{
						array_push($tableau_mvts_disponibles, array($ordonnee+1,$abscisse-2));
					}
				}
			}

			//fou
			else if($echiquier[$ordonnee][$abscisse][0] == 'fou')
			{
				// traitement des directions en diagonale
				//haut droite
				for($i=1; $i<8; $i++)
				{
					if($ordonnee-$i < 0 || $abscisse+$i > 7 || $echiquier[$ordonnee-$i][$abscisse+$i][1] == $couleur_joueur)
					{
						break;
					}
					else if($echiquier[$ordonnee-$i][$abscisse+$i][1] == $couleur_adversaire)
					{
						array_push($tableau_mvts_disponibles, array($ordonnee-$i, $abscisse+$i));
						break;
					}
					array_push($tableau_mvts_disponibles, array($ordonnee-$i, $abscisse+$i));
				}
				//haut gauche
				for($i=1; $i<8; $i++)
				{
					if($ordonnee-$i < 0 || $abscisse-$i < 0 || $echiquier[$ordonnee-$i][$abscisse-$i][1] == $couleur_joueur)
					{
						break;
					}
					else if($echiquier[$ordonnee-$i][$abscisse-$i][1] == $couleur_adversaire)
					{
						array_push($tableau_mvts_disponibles, array($ordonnee-$i, $abscisse-$i));
						break;
					}
					array_push($tableau_mvts_disponibles, array($ordonnee-$i, $abscisse-$i));
				}
				//bas droite
				for($i=1; $i<8; $i++)
				{
					if($ordonnee+$i > 7 || $abscisse+$i > 7 || $echiquier[$ordonnee+$i][$abscisse+$i][1] == $couleur_joueur)
					{
						break;
					}
					else if($echiquier[$ordonnee+$i][$abscisse+$i][1] == $couleur_adversaire)
					{
						array_push($tableau_mvts_disponibles, array($ordonnee+$i, $abscisse+$i));
						break;
					}
					array_push($tableau_mvts_disponibles, array($ordonnee+$i, $abscisse+$i));
				}
				//bas gauche
				for($i=1; $i<8; $i++)
				{
					if($ordonnee+$i > 7 || $abscisse-$i < 0 || $echiquier[$ordonnee+$i][$abscisse-$i][1] == $couleur_joueur)
					{
						break;
					}
					else if($echiquier[$ordonnee+$i][$abscisse-$i][1] == $couleur_adversaire)
					{
						array_push($tableau_mvts_disponibles, array($ordonnee+$i, $abscisse-$i));
						break;
					}
					array_push($tableau_mvts_disponibles, array($ordonnee+$i, $abscisse-$i));
				}
			}

			//reine (copier/coller du fou et de la tour)
			else if($echiquier[$ordonnee][$abscisse][0] == 'reine')
			{
				// traitement de chaque direction (haut, bas, gauche, droite) :
				// haut
				for($i=1; $i < 8; $i++)
				{
					if($ordonnee - $i < 0 || $echiquier[$ordonnee-$i][$abscisse][1] == $couleur_joueur)
					{
						break;
					}
					if($echiquier[$ordonnee-$i][$abscisse][1] == $couleur_adversaire)
					{
						array_push($tableau_mvts_disponibles, array($ordonnee-$i, $abscisse));
						break;
					}
					array_push($tableau_mvts_disponibles, array($ordonnee-$i, $abscisse));
				}
				// bas
				for($i=1; $i < 8; $i++)
				{
					if($ordonnee + $i > 7 || $echiquier[$ordonnee+$i][$abscisse][1] == $couleur_joueur)
					{
						break;
					}
					if($echiquier[$ordonnee+$i][$abscisse][1] == $couleur_adversaire)
					{
						array_push($tableau_mvts_disponibles, array($ordonnee+$i, $abscisse));
						break;
					}
					array_push($tableau_mvts_disponibles, array($ordonnee+$i, $abscisse));
				}
				// gauche
				for($i=1; $i < 8; $i++)
				{
					if($abscisse - $i < 0 || $echiquier[$ordonnee][$abscisse - $i][1] == $couleur_joueur)
					{
						break;
					}
					if($echiquier[$ordonnee][$abscisse - $i][1] == $couleur_adversaire)
					{
						array_push($tableau_mvts_disponibles, array($ordonnee, $abscisse - $i));
						break;
					}
					array_push($tableau_mvts_disponibles, array($ordonnee, $abscisse - $i));
				}
				// droite
				for($i=1; $i < 8; $i++)
				{
					if($abscisse + $i > 7 || $echiquier[$ordonnee][$abscisse + $i][1] == $couleur_joueur)
					{
						break;
					}
					if($echiquier[$ordonnee][$abscisse + $i][1] == $couleur_adversaire)
					{
						array_push($tableau_mvts_disponibles, array($ordonnee, $abscisse + $i));
						break;
					}
					array_push($tableau_mvts_disponibles, array($ordonnee, $abscisse + $i));
				}
				// traitement des directions en diagonale
				//haut droite
				for($i=1; $i<8; $i++)
				{
					if($ordonnee-$i < 0 || $abscisse+$i > 7 || $echiquier[$ordonnee-$i][$abscisse+$i][1] == $couleur_joueur)
					{
						break;
					}
					else if($echiquier[$ordonnee-$i][$abscisse+$i][1] == $couleur_adversaire)
					{
						array_push($tableau_mvts_disponibles, array($ordonnee-$i, $abscisse+$i));
						break;
					}
					array_push($tableau_mvts_disponibles, array($ordonnee-$i, $abscisse+$i));
				}
				//haut gauche
				for($i=1; $i<8; $i++)
				{
					if($ordonnee-$i < 0 || $abscisse-$i < 0 || $echiquier[$ordonnee-$i][$abscisse-$i][1] == $couleur_joueur)
					{
						break;
					}
					else if($echiquier[$ordonnee-$i][$abscisse-$i][1] == $couleur_adversaire)
					{
						array_push($tableau_mvts_disponibles, array($ordonnee-$i, $abscisse-$i));
						break;
					}
					array_push($tableau_mvts_disponibles, array($ordonnee-$i, $abscisse-$i));
				}
				//bas droite
				for($i=1; $i<8; $i++)
				{
					if($ordonnee+$i > 7 || $abscisse+$i > 7 || $echiquier[$ordonnee+$i][$abscisse+$i][1] == $couleur_joueur)
					{
						break;
					}
					else if($echiquier[$ordonnee+$i][$abscisse+$i][1] == $couleur_adversaire)
					{
						array_push($tableau_mvts_disponibles, array($ordonnee+$i, $abscisse+$i));
						break;
					}
					array_push($tableau_mvts_disponibles, array($ordonnee+$i, $abscisse+$i));
				}
				//bas gauche
				for($i=1; $i<8; $i++)
				{
					if($ordonnee+$i > 7 || $abscisse-$i < 0 || $echiquier[$ordonnee+$i][$abscisse-$i][1] == $couleur_joueur)
					{
						break;
					}
					else if($echiquier[$ordonnee+$i][$abscisse-$i][1] == $couleur_adversaire)
					{
						array_push($tableau_mvts_disponibles, array($ordonnee+$i, $abscisse-$i));
						break;
					}
					array_push($tableau_mvts_disponibles, array($ordonnee+$i, $abscisse-$i));
				}
			}

			//roi
			else if($echiquier[$ordonnee][$abscisse][0] == 'roi')
			{
				//bas
				if($ordonnee+1 <= 7 && $echiquier[$ordonnee+1][$abscisse][1] != $couleur_joueur)
				{
					array_push($tableau_mvts_disponibles, array($ordonnee+1, $abscisse));
				}
				//haut
				if($ordonnee-1 >= 0 && $echiquier[$ordonnee-1][$abscisse][1] != $couleur_joueur)
				{
					array_push($tableau_mvts_disponibles, array($ordonnee-1, $abscisse));
				}
				//droite
				if($abscisse+1 <= 7 && $echiquier[$ordonnee][$abscisse+1][1] != $couleur_joueur)
				{
					array_push($tableau_mvts_disponibles, array($ordonnee, $abscisse+1));
				}
				//gauche
				if($abscisse-1 >= 0 && $echiquier[$ordonnee][$abscisse-1][1] != $couleur_joueur)
				{
					array_push($tableau_mvts_disponibles, array($ordonnee, $abscisse-1));
				}
				//bas/gauche
				if($ordonnee+1 <= 7 && $abscisse-1 >= 0 && $echiquier[$ordonnee+1][$abscisse-1][1] != $couleur_joueur)
				{
					array_push($tableau_mvts_disponibles, array($ordonnee+1, $abscisse-1));
				}
				//bas/droite
				if($ordonnee+1 <= 7 && $abscisse+1 <= 7 && $echiquier[$ordonnee+1][$abscisse+1][1] != $couleur_joueur)
				{
					array_push($tableau_mvts_disponibles, array($ordonnee+1, $abscisse+1));
				}
				//haut/gauche
				if($ordonnee-1 >= 0 && $abscisse-1 >= 0 && $echiquier[$ordonnee-1][$abscisse-1][1] != $couleur_joueur)
				{
					array_push($tableau_mvts_disponibles, array($ordonnee-1, $abscisse-1));
				}
				//haut/droite
				if($ordonnee-1 >= 0 && $abscisse+1 <= 7 && $echiquier[$ordonnee-1][$abscisse+1][1] != $couleur_joueur)
				{
					array_push($tableau_mvts_disponibles, array($ordonnee-1, $abscisse+1));
				}
			}

			// récupérer les valeurs de la case d'arrivée
			$lettre2 = $_POST["lettre2"];
			$nombre2 = $_POST["nombre2"];
			unset($_POST["lettre2"]);
			unset($_POST["nombre2"]);
			unset($_POST["lettre"]);
			unset($_POST["nombre"]);

			$coordonnees2 = echec_vers_matriciel($lettre2, $nombre2);
			// retourne : [abscisse, ordonnee]
			
			$abscisse2 = $coordonnees2[0];
			$ordonnee2 = $coordonnees2[1];

			if(appartient($tableau_mvts_disponibles, $coordonnees2))
			{
				$echiquier[$ordonnee2][$abscisse2] = $echiquier[$ordonnee][$abscisse];
				$echiquier[$ordonnee][$abscisse] = $pv;

				appelerEffacerPage();
				afficherEchiquier($echiquier);
				echo '<p>felicitation, votre pièce a été déplacée, on attend maintenant que l adversaire joue</p>';
			}
			else
			{
				appelerEffacerPage();
				afficherEchiquier($echiquier);
				echo '<p>veuillez choisir une case d arrivée valide</p>';

				if($ordonnee >=0 && $ordonnee <= 7 && $abscisse >= 0 && $ordonnee <= 7 && $tableau_mvts_disponibles != array())
				{
					echo '<script>document.getElementById("' . $ordonnee . $abscisse . '").style.backgroundColor = "#54e74a";</script>';
					foreach ($tableau_mvts_disponibles as $mvt)
					{
						echo '<script>document.getElementById("' . $mvt[0] . $mvt[1] . '").style.backgroundColor = "#f8473e";</script>';
					}
				}
				echo'
					<form method="post">
						<input type="submit" name="reload_page" value="D accord, pardon">
					</form>';
				if (isset($_POST['reload_page']))
				{
					header("Refresh:0");
				}
			}
		}
	}
	
	?>

</body>
</html>
