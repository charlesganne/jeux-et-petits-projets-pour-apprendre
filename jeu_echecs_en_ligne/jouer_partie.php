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
		// récupération de l'id du client et l'id de la partie actuellement jouée :
		$id_joueur = $_GET['id_client'];
		$id_partie = $_GET['id_partie'];

		// connexion à la base de donnée :
		$bdd = new PDO("mysql:host=localhost;dbname=jeu_echec;charset=utf8", "root", "");

		// récupération de si le joueur est noir ou blanc :
		$req = "SELECT joueur_1, joueur_2 FROM Partie WHERE id_partie = '$id_partie'";
		$id_joueurs = $bdd->query($req)->fetch();

		// déclaration de qui est blanc et qui est noir (le premier joueur est blanc,
		// et le deuxième est noir)
		if($id_joueurs['joueur_1'] == $id_joueur) {
			$couleur_joueur = "blanc";
			$couleur_adversaire = "noir";
		} else {
			$couleur_joueur = "noir";
			$couleur_adversaire = "blanc";
		}
		echo 'Vous êtes la couleur ' . $couleur_joueur . ', et votre identifiant est : ' . $id_joueur;

		// récuperation de si l'adversaire a proposé partie nulle
		$req = "SELECT propose_null FROM Partie WHERE id_partie = '$id_partie'";
		$a_propose_nulle = $bdd->query($req)->fetch()['propose_null'];

		if(($a_propose_nulle == 1 && $couleur_joueur == "noir") || ($a_propose_nulle == 2 && $couleur_joueur == "blanc")) /* l'adversaire a proposé nulle*/ {
			echo '
			<form method="post">
				<label for="reponse_null">l adversaire a proposé de faire partie nulle, voulez vous accepter ou refuser ?</label>
				<select name="reponse_null" id="reponse_null" required>
					<option value="">Sélectionnez une réponse</option>
					<option value="accepter">Accepter</option>
					<option value="refuser">Refuser</option>
				</select>
				<br>
				<input type="submit" value="envoyer" action = "reponse_null">
				<br><br>
			</form>';
			if(isset($_POST['reponse_null']) && $_POST['reponse_null'] == "accepter") {
				unset($_POST['reponse_null']);
				$req = "UPDATE Partie SET statut = 'finie' WHERE id_partie = '$id_partie'";
				$bdd->query($req);
				echo "vous avez accépté nulle (maintenant il faut gerer la fin de partie et les stat)";
			} else if(isset($_POST['reponse_null']) && $_POST['reponse_null'] == "refuser")/* refuse la partie nulle */ {
				unset($_POST['reponse_null']);
				$req = "UPDATE Partie SET propose_null = 'NULL' WHERE id_partie = '$id_partie'";
				$bdd->query($req);
				echo 'vous avez refusé !';
			}
		} else /* l'adversaire n'a pas proposé nulle (execution normale) */{
			// quelques fonctions :

				function initialise_echiquier()
				{
					// une pièce sera modélisée par un tableau [nom, couleur, code html]
					// pour expliciter le nom de la variable : p->pion, t->tour, c->cavalier, f->fou,
					// re->reine, ro->roi, b->blanc, n->noir
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
						array($pb1, $pb2, $pb3, $pb4, $pb5, $pb6, $pb7, $pb8),
						array($tb1, $cb1, $fb1, $reb, $rob, $fb2, $cb2, $tb2)
					);
					return $echiquier;
				}

				function affiche_echiquier($echiquier) 
				{
					// Début du conteneur pour l'échiquier
					echo '<div id="echiquier">';
					
					echo '<table border="1">';
					for ($i = 0; $i < 8; $i++) 
					{
						echo '<tr><td>' . 8-$i . '</td>';
						for ($j = 0; $j < 8; $j++) 
						{
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
			
				function efface_page() 
				{
					echo '<script>effacerPage();</script>';
				}
			
				function echec_vers_matriciel($lettre, $nombre) 
				{
					// retourne : [abscisse, ordonnee]
					$abscisse = -1;
					$ordonnee = -1;
			
					// transformer en coordonnées du tableau de grille d'échec en coordonnées matriciels :
					// ordonnée (combientième tableau)
					switch ($nombre)
					{
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
					switch ($lettre)
					{
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

			// récupération de à qui c'est le tour de jouer. si c'est le tour du client, on lui 
			// propose de jouer, si non on lui demande d'actualiser la page régulièrement :
				$req = "SELECT joueur_dont_c_est_le_tour FROM Partie WHERE id_partie = '$id_partie'";
				$joueur_dont_c_est_le_tour = $bdd->query($req)->fetch()['joueur_dont_c_est_le_tour'];

			// charger l'échiquier depuis la base de donnée ou en créer un si c'est le début de la partie :
				// requete vers la base de donnée, si l'entrée échiquier est à NULL, on initialise un 
				// échiquier, sinon on unserialize celui de la BDD
				$req = "SELECT etat_echiquier FROM Partie WHERE id_partie = '$id_partie'";
				$echiquier = $bdd->query($req)->fetch()['etat_echiquier'];
				if($echiquier == NULL)
				{
					// si la partie n'a pas encore commencé, l'échiquier est initialisé ici
					$echiquier = initialise_echiquier();
				}
				else
				{
					// sinon on charge le bon echiquier de la base de donnee
					$echiquier = unserialize($echiquier);
				}

			if(($joueur_dont_c_est_le_tour == 1 && $couleur_joueur == "blanc") || ($joueur_dont_c_est_le_tour == 2 && $couleur_joueur == "noir")) /* si c'est a moi de jouer */{
				// si c'est au joueur blanc de jouer et que je suis le joueur blanc
				// ou si au joueur noir et que je suis le joueur noir

				// corps principale du code de la page
				affiche_echiquier($echiquier);

				// demander la pièce à déplacer
				echo '
				
				<form method="post" id = "formulaire_de_demande_de_piece_a_deplacer">

					<h2>séléction de la pièce à déplacer</h2>

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
				</form>
				<form method="post" id="formulaire_proposition_nulle">
					<input type = "submit" name = "proposer_partie_nulle" value = "Proposer partie nulle">
				</form>';

				if (isset($_POST["lettre"]) && isset($_POST["nombre"]) && isset($_POST["lettre2"]) && isset($_POST["nombre2"])) /* si l'utilisateur a soumit le coup a jouer */{
					// récupérer les valeurs de coordonnée de depart soumises
					$lettre = $_POST["lettre"];
					$nombre = $_POST["nombre"];
			
					// conversion en coordonnées lisible par le tableau php (retourne : [abscisse, ordonnee])
					$coordonnees = echec_vers_matriciel($lettre, $nombre);
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
								if($ordonnee-1 >= 0 && $abscisse-2 >= 0)
								{
									if($echiquier[$ordonnee-1][$abscisse-2][1] == $couleur_adversaire || $echiquier[$ordonnee-1][$abscisse-2][0] == 'pv')
									{
										array_push($tableau_mvts_disponibles, array($ordonnee-1,$abscisse-2));
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
			
						// les désactiver afin qu'elles ne puissent plus être detectees
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
							// redéclaration locale de la variable $pv, car elle a seulement été d
							// declaree dans la fonction qui declare le nouvel échiquier
							$pv = array('pv', 'pv', '');
			
							// modification de l'échiquier en consequent du coup
							$echiquier[$ordonnee2][$abscisse2] = $echiquier[$ordonnee][$abscisse];
							$echiquier[$ordonnee][$abscisse] = $pv;
			
							// ici il faut créer une nouvelle ligne dans la table echiquier 
							// pour en registrer le coup joué
			
							// id_coup = trouver le max de id_coup dans la table echiquier et ajouter 1
							// coup = lettre+nombre (départ) - lettre+nombre(arrivée)
			
							// encodage du nouvel echiquier
							$nouvel_echiquier_pret_a_etre_enregistre_en_bdd = serialize($echiquier);
			
							// enregistrement du nouvel echiquier
							$req = "UPDATE Partie 
							SET etat_echiquier = '$nouvel_echiquier_pret_a_etre_enregistre_en_bdd'
							WHERE id_partie = '$id_partie'";
							$bdd->query($req);
			
							// mettre à jour dans la table partie le champs joueur_dont_c_est_le_tour
							if($joueur_dont_c_est_le_tour == 1)
							{
								$req = "UPDATE Partie
								SET joueur_dont_c_est_le_tour = 2
								WHERE id_partie = '$id_partie'";
								$bdd->query($req);
							}
							else 
							{
								$req = "UPDATE Partie
								SET joueur_dont_c_est_le_tour = 1
								WHERE id_partie = '$id_partie'";
								$bdd->query($req);
							}
			
							// affichage de la nouvelle page une fois le coup joué
							header("Refresh:0");
							efface_page();
							echo 'vous êtes la couleur ' . $couleur_joueur;
							affiche_echiquier($echiquier);
							echo '<p>felicitation, votre pièce a été déplacée, on attend maintenant que 
							l adversaire joue. Vous pouvez frequement actualiser la page</p>';
						}
						else
						{
							efface_page();
							echo 'vous êtes la couleur ' . $couleur_joueur;
							affiche_echiquier($echiquier);
							echo '<p>veuillez choisir une case d arrivée valide</p>';
			
							// l'échiquier affiché est coloré afin d'indiquer au joueur
							// les endroits où il a le droit de se deplacer
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

				else if(isset($_POST['proposer_partie_nulle']) && $_POST['proposer_partie_nulle'] == "Proposer partie nulle") /* si le joueur n'a pas soumi de coup ET qu'il veut proposer partie nulle*/ {
					if($couleur_joueur == "blanc") {
						$req = "UPDATE Partie SET propose_null = 1 WHERE id_partie = '$id_partie'";
						$bdd->query($req);
						$req = "UPDATE Partie SET joueur_dont_c_est_le_tour = 2 WHERE id_partie = '$id_partie'";
						$bdd->query($req);
					} else if($couleur_joueur == "noir") {
						$req = "UPDATE Partie SET propose_null = 2 WHERE id_partie = '$id_partie'";
						$bdd->query($req);
						$req = "UPDATE Partie SET joueur_dont_c_est_le_tour = 1 WHERE id_partie = '$id_partie'";
						$bdd->query($req);
					}

					// pour qu'il ne puisse plus être détécté
					unset($_POST['proposer_partie_nulle']);

					// desactiver l'id avec javascript formulaire_proposition_nulle
					echo '<script>document.getElementById("formulaire_proposition_nulle").remove(); </script>';
					echo '<script>document.getElementById("formulaire_de_demande_de_piece_a_deplacer").remove(); </script>';
					
					echo "vous avec bien proposé partie nulle, vous pouvez desormais actualiser la page regulièrement avin de connaitre la reponse de votre adversaire";
				}
			}
			else
			{
				// si ce n'est pas à nous de jouer, afficher l'échiquier suite au dernier coup
				// sans afficher le formulaire pour jouer

				echo 'vous êtes la couleur ' . $couleur_joueur;
				affiche_echiquier($echiquier);
				// afficher l'échiquier
				// affichage d'un message qui demande d'actualiser la page régulièrement
				echo '<p>Ce n est pas à vous de jouer, veuillez actualiser fréquement la page afin 
					de savoir l orsque ça sera votre tour</p>';
			}
		}

		
	?>

</body>
</html>
