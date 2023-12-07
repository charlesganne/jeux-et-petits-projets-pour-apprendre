#include <stdio.h>
#include "fonctions_generales.h"
#include <stdlib.h>
#include <stdbool.h>
#include <time.h>
#include <string.h>

#define NETTOIE_TERMINAL "clear" // remplacer clear par cls sur Windows 

int** init_grille(int dim)
{
	// Prend en entrée un entier, la dimension de la grille de jeu entrée par
	// l'utilisateur, alloue dynamiquement un tableau de pointeurs, puis alloue chaque sous tableau
	// et retourne le pointeur vers le premier élément de la grille
	int** grille = (int**)malloc(dim * sizeof(int*));
	for(int i = 0; i < dim; i++)
	{
		grille[i] = (int*)calloc(dim, sizeof(int));
	}
	return grille;
}

void libere_grille(int** grille, int dim)
{
	// Libère chaque ligne/sous tableau, puis libère le tableau complet
	for(int i = 0; i < dim; i++)
	{
		free(grille[i]);
	}
	free(grille);
}

void affiche_grille(int** grille, int dim)
{
	// Formattage de l'affichage en encadrant la grille, 
	// en affichant pas les 0 et en mettant des séparations
    printf("+----------------------------+\n");

    for (int i = 0; i < dim; i++)
    {
        printf("|");
        for (int j = 0; j < dim; j++)
        {
            if (grille[i][j] == 0) // Le 0 encode une case vide
            {
                printf("     ");
            }
            else if(grille[i][j] == -1) // Le -1 encode l'obstacle dans le mode puzzle
            {
            	printf("X    ");
            }
            else
            {
                printf("%-5d", grille[i][j]); // Remplit à gauche par des espaces si le nombre fait moins de 5 caractères
            }
        }
        printf("|\n");
    }

    printf("+----------------------------+\n");
}

void affiche_2_grilles(int** grille1, int** grille2, int dim)
{
	// Formattage de l'affichage en encadrant les grilles, 
	// en affichant pas les 0 et en mettant des séparations
    printf("+---------------------------------------------------------------------------+\n");

    for (int i = 0; i < dim; i++)
    {
        printf("|");
        for (int j = 0; j < dim; j++)
        {
            if (grille1[i][j] == 0)
            {
                printf("     ");
            }
            else
            {
                printf("%-5d", grille1[i][j]);
            }
        }
        printf("|");
        printf("     ");
        printf("|");
        for (int j = 0; j < dim; j++)
        {
            if (grille2[i][j] == 0)
            {
                printf("     ");
            }
            else
            {
                printf("%-5d", grille2[i][j]);
            }
        }
        printf("|\n");
    }

    printf("+----------------------------------------------------------------------+\n");
}

void haut(int** grille, int dim)
{
	// Cette fonction est spécialement conçue pour s'adapter aux deux modes de jeu ( puzzle et non puzzle )
	// L'idée de l'algorithme est de sommer toutes les cases sommables selon les règles sans se soucier de les "écraser" en haut, puis de les "projetter" seulement ensuite
	for(int ord = 0; ord < dim; ord++) // Parcourt de la grille de haut en bas pour traiter chaque ligne une à une
	{
		for(int abs = 0; abs < dim; abs++) // Parcourt du sous tableau afin de traiter toutes les colonnes en même temps
		{
			int case_courante = grille[ord][abs];
			if(case_courante != 0)
			{
				int indice_case_suivante = ord+1;
				while(indice_case_suivante < dim)
				{
					int case_suivante = grille[indice_case_suivante][abs];
					if(case_suivante == -1)
					{
						break;
					}
					if(case_suivante != 0) // Si on "trouve" une case non vide, avec le if précédent on sait déjà que ce n'est pas un obstacle
					{
						if(case_courante == case_suivante) // Si elle peut etre ajoutée à la précédente (case courante)
						{
							// on l'ajoute
							grille[ord][abs] += grille[indice_case_suivante][abs];
							grille[indice_case_suivante][abs] = 0;
						}
						// et on passe à la case suivante (on traite d'abord les autres de la ligne)
						break;
					}
					// sinon on regarde celle d'après ...
					indice_case_suivante++;
				}
			}
		}
	}

	// "projection orthogonale" des cases vers le haut
	for(int ord = 0; ord < dim-1; ord++) // parcourt de la grille de haut en bas 
	{
		for(int abs = 0; abs < dim; abs++) // pour traiter chaque colonne en même temps
		{
		int case_courante = grille[ord][abs];
			if(case_courante == 0)
			{
				int indice_case_suivante = ord+1;
				while(indice_case_suivante < dim && grille[indice_case_suivante][abs] != -1)
				{
					int case_suivante = grille[indice_case_suivante][abs];
					if(case_suivante != 0)
					{
						grille[ord][abs] = case_suivante;
						grille[indice_case_suivante][abs] = 0;
						break;
					}
					indice_case_suivante++;
				}
			}
		}
	}
}

void bas(int** grille, int dim)
{
	// L'algorithme étant exactement le même que pour la fonction haut, cette fonction ne sera pas expliquée
	for(int i = dim-1; i >= 0; i--)
	{
		for(int j = 0; j < dim; j++)
		{
			int case_courante = grille[i][j];
			if(case_courante != 0)
			{
				int k = i-1;
				while(k >= 0)
				{
					int case_suivante = grille[k][j];
					if(case_suivante == -1)
					{
						break;
					}
					if(case_suivante != 0)
					{
						if(grille[i][j] == grille[k][j])
						{
							grille[i][j]+=grille[k][j];
							grille[k][j]=0;
						}
						break;
					}
					k--;
				}
			}
		}
	}

	for(int i = dim-1; i > 0; i--)
	{
		for(int j = 0; j < dim; j++)
		{
			int case_courante = grille[i][j];
			if(case_courante == 0)
			{
				int k = i-1;
				while(k >= 0 && grille[k][j] != -1)
				{
					int case_suivante = grille[k][j];
					if(case_suivante != 0)
					{
						grille[i][j]=case_suivante;
						grille[k][j]=0;
						break;
					}
				k--;
				}
			}
		}
	}
}

void gauche(int** grille, int dim)
{
	// L'algorithme étant exactement le même que pour la fonction haut, cette fonction ne sera pas expliquée
	for(int i = 0; i < dim; i++)
	{
		for(int j = 0; j < dim; j++)
		{
			int case_courante = grille[i][j];
			if(case_courante != 0)
			{
				int k = j+1;
				while(k < dim && grille[i][k] != -1)
				{
					int case_suivante = grille[i][k];
					if(case_suivante != 0)
					{
						if(case_courante == case_suivante)
						{
							grille[i][j] += grille[i][k];
							grille[i][k] = 0;
						}
						break;
					}
					k++;
				}
			}
		}
	}

	for(int i = 0; i < dim; i++)
	{
		for(int j = 0; j < dim-1; j++)
		{
			int case_courante = grille[i][j];
			if(case_courante == 0)
			{
				int k = j+1;
				while(k < dim && grille[i][k] != -1)
				{
					int case_suivante = grille[i][k];
					if(case_suivante != 0)
					{
						grille[i][j] = case_suivante;
						grille[i][k] = 0;
						break;
					}
					k++;
				}
			}
		}
	}
}

void droite(int** grille, int dim)
{
	// L'algorithme étant exactement le même que pour la fonction haut, cette fonction ne sera pas expliquée
	for(int i = 0; i < dim; i++)
	{
		for(int j = dim-1; j >= 0; j--)
		{
			int case_courante = grille[i][j];
			if(case_courante != 0)
			{
				int k = j-1;
				while(k >= 0 && grille[i][k] != -1)
				{
					int case_suivante = grille[i][k];
					if(case_suivante != 0)
					{
						if(case_courante == case_suivante)
						{
							grille[i][j] += grille[i][k];
							grille[i][k] = 0;
						}
						break;
					}
					k--;
				}
			}
		}
	}

	for(int i = 0; i < dim; i++)
	{
		for(int j = dim-1; j > 0; j--)
		{
			int case_courante = grille[i][j];
			if(case_courante == 0)
			{
				int k = j-1;
				while(k >= 0 && grille[i][k] != -1)
				{
					int case_suivante = grille[i][k];
					if(case_suivante != 0)
					{
						grille[i][j] = case_suivante;
						grille[i][k] = 0;
						break;
					}
					k--;
				}
			}
		}
	}
}

bool est_valide(int** grille, int dim, char direction)
{
	bool valide = false;
	// Créaton d'une grille temporaire dans laquelle on copie la vraie grille et sur laquelle on va faire la transformation indiquée. 
	// On va ensuite comparer les 2, et si elles sont identiques, on retournera un booléen indiquant que le mouvement est invalide
	int** grille_tmp = (int**)malloc(dim * sizeof(int*));
	for(int i = 0; i < dim; i++)
	{
		grille_tmp[i] = (int*)calloc(dim, sizeof(int));
	}
	for(int i = 0; i < dim; i++)
	{
		for(int j = 0; j < dim; j++)
		{
			grille_tmp[i][j] = grille[i][j];
		}
	}

	// modification de la grille temporaire
	// pas de "default" car la vérification que la commande est valide aura
	// déjà été fait avant l'appel de cette fonction (elle n'est pas amenée à être utilisée par l'utilisateur)
	switch(direction)
	{
		case 'z':
		{
			haut(grille_tmp, dim);
			break;
		}
		case 'q':
		{
			gauche(grille_tmp, dim);
			break;
		}
		case 's':
		{
			bas(grille_tmp, dim);
			break;
		}
		case 'd':
		{
			droite(grille_tmp, dim);
			break;
		}
	}

	// comparaison des deux matrices
	for(int i = 0; i < dim; i++)
	{
		for(int j = 0; j < dim; j++)
		{
			if(grille_tmp[i][j] != grille[i][j])
			{
				valide = true; // si on trouve une case qui differe de l'autre grille, c'est que le mouvement a apporte une modification a la grille et que le mouvement etait valide
			}
		}
	}

	// libération de la matrice temporaire
	libere_grille(grille_tmp, dim);
	return valide;
}

bool situation_bloquee(int** grille, int dim)
{
	// Il suffit qu'au moins un mouvement parmis les 4 possibles soit valide
	// Note : l'expression conditionelle peut parfois être plus explicite à comprendre en distribuant le "!" avec les lois de De Morgan
	return !( est_valide(grille, dim, 'z')
		   || est_valide(grille, dim, 'q')
		   || est_valide(grille, dim, 's')
		   || est_valide(grille, dim, 'd') ); 
}

void inserer_nombre(int** grille, int dim)
{
	int cases_dispo = 0;

	// compte le nombre de cases libres
	for(int ord = 0; ord < dim; ord++)
	{
		for(int abs = 0; abs < dim; abs++)
		{
			if(grille[ord][abs] == 0)
			{
				cases_dispo++;
			}
		}
	}

	// choisit aléatoirement une case libre
	int index = rand() % cases_dispo;
	for(int ord = 0; ord < dim; ord++)
	{
		for(int abs = 0; abs < dim; abs++)
		{
			if(grille[ord][abs] == 0)
			{
				if(index == 0)
				{
					if(rand() % 2 == 0)
					{
						grille[ord][abs] = 4;
					}
					else
					{
						grille[ord][abs] = 2;
					}
					return;
				}
				index--;
			}
		}
	}
}

bool partie_gagnee(int** grille, int dim)
{
	// Si la valeur 2048 est présente dans la grille, la partie est gagnée et le jeu peut s'arréter
	bool gagne = false;
	for(int i = 0; i < dim; i++)
	{
		for(int j = 0; j < dim; j++)
		{
			if(grille[i][j] == 2048)
			{
				gagne = true;
			}
		}
	}
	return gagne;
}

int** read_file(char* nom_fichier, int* taille_grille)
{
	// Fonction pour le mode puzzle
	/*
	abreviations : 
	v->valeur
	X->obstacle
	le format de la grille qui devra être contenue dans le fichier est le suivant :

	<début du fichier><dimension de la grille>
	v v ... v X v
	X v X ... X v
	.  .        .
	.    .      .
	.      .    .
	v X v v ... v
	<fin du fichier avec retour a la ligne>

	exemple (également fourni dans un fichier)

	"6
	2 4 0 X 4 16
	0 0 0 0 X 2
	512 0 0 2 4 X
	0 0 X 2 4 0
	2 2 2 2 2 2
	2 4 8 16 32 64
	"

	NB : les nombres ou obstacles sont séparés par des espaces simples,
		 même si le rendu visuel dans le fichier n'est pas pratique à visualiser
	*/

	// Cette fonction prend en argument une chaine de caracteres qui est le nom d'un fichier 
	// (le chemin absolu ou relatif vers celui ci, mais le plus simple est de mettre tous les fichiers
	// du projet dans le même dossier) ainsi qu'un pointeur ("taille_grille") vers un entier. L'entier 
	// à cette addresse à la fin de la fonction sera la taille de la grille de jeu.

	// Si le nom du fichier est valide, la fonction retourne un tableau avec la grille,
	// sinon elle retourne le pointeur NULL

	// Ouverture du fichier en mode lecture
	FILE *fichier = fopen(nom_fichier, "r");

	// Vérification si l'ouverture a réussi
	if(fichier == NULL)
	{
		printf("impossible d'ouvrir le fichier %s\n", nom_fichier);
		return NULL; // sort de la fonction
	}

	// Obtention des dimension de la grille
	char zzz[2]; // nom choisit volontairement pas explicite pour ne pas risquer de le reutiliser par la suite, cette variable ne sera utilisée que sur les 2 lignes suivantes
	fgets(zzz, 2, fichier);
	int dim = zzz[0] - '0'; // la dimension de la matrice étant entre 4 et 9, on aura toujours 1 digit à convertir, donc cette methode convient
	*taille_grille = dim;

	// Déclaration de la grille
	int** grille = init_grille(dim);

	// Buffer pour stocker chaque ligne lue
	char ligne[1000];

	// se replacer au bon endroit dans le fichier 
	fseek(fichier, 0, SEEK_SET);
	fgets(ligne, 1000, fichier);

	// Lecture des lignes suivant la taille une par une
	for(int i = 0; i < dim; i++)
	{
		fgets(ligne, 1000, fichier);
		char d[] = " \n"; // séparateurs éventuels dont on ne tient pas compte dans le traitement de la ligne
		char *p = strtok(ligne, d);
		for(int j = 0; j < dim; j++)
		{
			if(p[0] != 'X')
			{
				grille[i][j] = atoi(p);
			}
			else
			{
				grille[i][j] = -1; // pour coder les X, (en majuscule), on met -1
			}
			p = strtok(NULL, d); // obtention de la case suivante de la ligne
		}
	}

	// Fermeture du fichier et nettoyage
	fclose(fichier);
	return grille;
}

bool normal()
{
	// La fonction retourne un booléen indiquant si le joueur 
	// ayant terminé sa partie veut rejouer

	// Elle contient les fonctions définies précédement pour que le jeu se déroule

	// initialisation du générateur de nombres aléatoires
	srand(time(0));

	// Introduction à la partie 
	system(NETTOIE_TERMINAL);
	printf("Vous êtes dans le mode normal\n");

	// Demande de la taille de la grille à l'utilisateur
	int taille;
	char ligne[10]; // Contiendra les ligne entrées par l'utilisateur tout au long du mode normal

	printf("Veuillez entrer la taille de la grille sur laquelle vous voulez jouer, un entier naturel entre 4 et 9, bornes comprises\n"); // le déroulement du code n'est plus controlé lorsque une entrée ne respectant pas les instructions est passée en argument 
		
	fgets(ligne, sizeof(ligne), stdin); // lis la première ligne entée par l'utilisateur
	ligne[1] = '\0'; // le dernier caracter de la ligne est le '\n', il est ici remplacé par un '\0' pour finir la ligne (cela suppose que la taille de la grille est un chiffre à un digit)
	taille = atoi(ligne);

	// initialisation de la grille (de zeros pour l'instant)
	int** grille = init_grille(taille);

	// insertion des deux premiers nombres de la grille 
	inserer_nombre(grille, taille);
	inserer_nombre(grille, taille);

	system(NETTOIE_TERMINAL); 
	// affichage de la prèmiere fenètre
	affiche_grille(grille, taille);

	while(!(situation_bloquee(grille, taille) || partie_gagnee(grille, taille)))
	{
		char commande; // Contiendra la commande entrée par l'utilisateur
		printf("Veuillez indiquer la direction du déplacement voulu (zqsd) (une seule lettre, puis entrer) : \n");
		fgets(ligne, sizeof(ligne), stdin); // lis la ligne entée par l'utilisateur
		commande = ligne[0]; // seul le premier caracter de la ligne est interessant (le seul de la ligne avec le '\n' en execution nominale)
		if(est_valide(grille, taille, commande))
		{
			// traitement de la commande de l'utilisateur
			switch (commande)
			{
				case 'z':
				{
					system(NETTOIE_TERMINAL);
					haut(grille, taille);
					inserer_nombre(grille, taille);
					affiche_grille(grille, taille);
					break;
				}
				case 'q':
				{
					system(NETTOIE_TERMINAL);
					gauche(grille, taille);
					inserer_nombre(grille, taille);
					affiche_grille(grille, taille);
					break;
				}
				case 's':
				{
					system(NETTOIE_TERMINAL);
					bas(grille, taille);
					inserer_nombre(grille, taille);
					affiche_grille(grille, taille);
					break;
				}
				case 'd':
				{
					system(NETTOIE_TERMINAL);
					droite(grille, taille);
					inserer_nombre(grille, taille);
					affiche_grille(grille, taille);
					break;
				}
			}
		}
		else
		{
			system(NETTOIE_TERMINAL);
			affiche_grille(grille, taille);
			printf("veuillez entrer une direction valide (faisant bouger des pièces)\n");
		}
	}

	// calcul du score
	int score = 0;
	for(int i = 0; i < taille; i++)
	{
		for(int j = 0; j < taille; j++)
		{
			score+=grille[i][j];
		}
	}

	// affichage de la victoire ou de la défaite
	if(partie_gagnee(grille, taille))
	{
		printf("Vous avez gagné, votre score est : %d\n", score);
	}
	else
	{
		printf("Vous avez perdu :'( votre score est : %d\n", score);
	}

	// proposer une nouvelle partie en retournant sur le menu
	bool rejouer;
	printf("Voulez vous rejouer ?\noui : O\nnon : N\n");

	fgets(ligne, sizeof(ligne), stdin); // lis la ligne entée par l'utilisateur
	if (ligne[0] == 'O')
		rejouer = true;
	else if(ligne[0] == 'N')
		rejouer = false;

	// libération de la mémoire occupée
	libere_grille(grille, taille);
	system(NETTOIE_TERMINAL);
	return rejouer;
}

bool duo()
{
	// La fonction retourne un booléen indiquant si le joueur 
	// ayant terminé sa partie veut rejouer

	// initialisation du générateur de nombres aléatoires
	srand(time(0));

	// Introduction à la partie
	system(NETTOIE_TERMINAL);
	printf("Vous êtes dans le mode duo\n");

	// Demande de la taille des grilles à l'utilisateur
	// les deux grilles auront les même dimensions

	int taille;
	char ligne[10]; // Contiendra les ligne entrées par l'utilisateur tout au long du mode normal

	printf("Veuillez entrer la taille des grilles sur lesquelles vous voulez jouer, un entier naturel entre 4 et 9, bornes comprises\n");
		
	fgets(ligne, sizeof(ligne), stdin); // lis la première ligne entée par l'utilisateur
	ligne[1] = '\0'; // le dernier caracter de la ligne est le '\n', il est ici remplacé par un '\0' pour finir la ligne (cela suppose que la taille de la grille est un chiffre à un digit)
	taille = atoi(ligne);

	// initialisation des grilles
	int** grille1 = init_grille(taille);
	int** grille2 = init_grille(taille);

	// Insertion des deux premiers nombres des grilles 
	inserer_nombre(grille1, taille);
	inserer_nombre(grille1, taille);
	inserer_nombre(grille2, taille);
	inserer_nombre(grille2, taille);

	system(NETTOIE_TERMINAL);
	// Affichage de la première fenètre
	affiche_2_grilles(grille1, grille2, taille);

	while(!(situation_bloquee(grille1, taille) || partie_gagnee(grille1, taille) || 
		    situation_bloquee(grille2, taille) || partie_gagnee(grille2, taille))) // la boucle principale et donc le jeu est quitté si l'une des deux grilles est bloquée ou est gagnante, c'est un choix d'implémentation
	{
		printf("Veuillez indiquer la direction du déplacement (zqsd) : \n");
		char commande;
		fgets(ligne, sizeof(ligne), stdin); // lis la ligne entée par l'utilisateur
		commande = ligne[0];
		if(est_valide(grille1, taille, commande) || est_valide(grille2, taille, commande)) // le mouvement doit être valide sur au moins une grille, c'est encore une fois un choix arbitraire
		{
			// Traitement de la commande de l'utilisateur
			switch (commande)
			{
				case 'z':
				{
					system(NETTOIE_TERMINAL);
					haut(grille1, taille);
					haut(grille2, taille);
					inserer_nombre(grille1, taille);
					inserer_nombre(grille2, taille);
					affiche_2_grilles(grille1, grille2, taille);
					break;
				}
				case 'q':
				{
					system(NETTOIE_TERMINAL);
					gauche(grille1, taille);
					gauche(grille2, taille);
					inserer_nombre(grille1, taille);
					inserer_nombre(grille2, taille);
					affiche_2_grilles(grille1, grille2, taille);
					break;
				}
				case 's':
				{
					system(NETTOIE_TERMINAL);
					bas(grille1, taille);
					bas(grille2, taille);
					inserer_nombre(grille1, taille);
					inserer_nombre(grille2, taille);
					affiche_2_grilles(grille1, grille2, taille);
					break;
				}
				case 'd':
				{
					system(NETTOIE_TERMINAL);
					droite(grille1, taille);
					droite(grille2, taille);
					inserer_nombre(grille1, taille);
					inserer_nombre(grille2, taille);
					affiche_2_grilles(grille1, grille2, taille);
					break;
				}
				default :
				{
					system(NETTOIE_TERMINAL);
					affiche_2_grilles(grille1, grille2, taille);
					printf("une entree valide est préférable au bon déroulement du jeu\n");
					break;
				}
			}
		}
		else
		{
			system(NETTOIE_TERMINAL);
			affiche_2_grilles(grille1, grille2, taille);
			printf("Veuillez entrer une direction valide (faisant bouger des pieces sur au moins une des deux grilles)\n");
		}
	}

	// Calcul du score
	int score1 = 0, score2 = 0;
	for(int i = 0; i < taille; i++)
	{
		for(int j = 0; j < taille; j++)
		{
			score1+=grille1[i][j];
			score2+=grille2[i][j];
		}
	}

	// Affichage de la victoire ou de la défaite
	if(partie_gagnee(grille1, taille) || partie_gagnee(grille2, taille))
	{
		printf("Vous avez gagné, les scores sont:\ngrille 1 : %d\ngrille 2 : %d\n", score1, score2);
	}
	else
	{
		printf("Vous avez perdu :(, les scores sont:\ngrille 1 : %d\ngrille 2 : %d\n", score1, score2);
	}

	// Proposer une nouvelle partie en retournant sur le menu
	bool rejouer;
	printf("Voulez vous rejouer ?\noui : O\nnon : N\n");
	fgets(ligne, sizeof(ligne), stdin); // lis la ligne entée par l'utilisateur
	if (ligne[0] == 'O')
		rejouer = true;
	else if(ligne[0] == 'N')
		rejouer = false;

	// libération de la mémoire occupée
	libere_grille(grille1, taille);
	libere_grille(grille2, taille);
	system(NETTOIE_TERMINAL);
	return rejouer;
}

bool puzzle()
{
	char nom_fichier[1000];
	printf("Veuillez entrer le nom du fichier dans lequel est stocké la grille.\nLe nom du fichier doit faire moins de 999 cracteres\n");
	fgets(nom_fichier, 1000, stdin);
	nom_fichier[strlen(nom_fichier)-1] = '\0'; // Pour n'avoir que le nom du fichier, sans le caracter \n de fin
	int taille = 0;
	int** grille = read_file(nom_fichier, &taille);
	if(grille == NULL)
	{
		// Peut être que le joueur a fait un faute de frappe, on lui propose de re essayer
		printf("Ce fichier n'a pas pu être detecté\n");

		// Proposer une nouvelle partie en retournant sur un menu
		printf("Voulez vous rejouer ?\noui : O\nnon : N\n");
		char choix[10];
		fgets(choix, 10, stdin);
		if (choix[0] == 'O')
		{
			return true; // ce return n'est pas sensé servir lors d'un bon déroulement du programme
		}
	}

	system(NETTOIE_TERMINAL);
	// affichage de la première fenêtre
	affiche_grille(grille, taille);

	while(!(situation_bloquee(grille, taille) || partie_gagnee(grille, taille)))
	{
		printf("Veuillez indiquer la direction du déplacement (zqsd) : \n");
		char commande;
		char ligne[10];
		fgets(ligne, 10, stdin);
		commande = ligne[0];
		if(est_valide(grille, taille, commande))
		{
			// traitement de la commande de l'utilisateur
			switch (commande)
			{
				case 'z':
				{
					system(NETTOIE_TERMINAL);
					haut(grille, taille);
					inserer_nombre(grille, taille);
					affiche_grille(grille, taille);
					break;
				}
				case 'q':
				{
					system(NETTOIE_TERMINAL);
					gauche(grille, taille);
					inserer_nombre(grille, taille);
					affiche_grille(grille, taille);
					break;
				}
				case 's':
				{
					system(NETTOIE_TERMINAL);
					bas(grille, taille);
					inserer_nombre(grille, taille);
					affiche_grille(grille, taille);
					break;
				}
				case 'd':
				{
					system(NETTOIE_TERMINAL);
					droite(grille, taille);
					inserer_nombre(grille, taille);
					affiche_grille(grille, taille);
					break;
				}
				default :
				{
					system(NETTOIE_TERMINAL);
					affiche_grille(grille, taille);
					printf("une entrée valide est préférable au bon déroulement du jeu\n");
					break;
				}
			}
		}
		else
		{
			system(NETTOIE_TERMINAL);
			affiche_grille(grille, taille);
			printf("veuillez entrer une direction valide (faisant bouger des pieces)\n");
		}
	}

	// calcul du score
	int score = 0;
	for(int i = 0; i < taille; i++)
	{
		for(int j = 0; j < taille; j++)
		{
			score+=grille[i][j];
		}
	}

	// affichage de la victoire ou de la défaite
	if(partie_gagnee(grille, taille))
	{
		printf("Vous avez gagné, votre score est : %d\n", score);
	}
	else
	{
		printf("Vous avez perdu :'( votre score est : %d\n", score);
	}

	// proposer une nouvelle partie en retournant sur le menu
	bool rejouer;
	printf("Voulez vous rejouer ?\noui : O\nnon : N\n");
	char ligne[10];
	fgets(ligne, sizeof(ligne), stdin); // lis la ligne entée par l'utilisateur
	if (ligne[0] == 'O')
		rejouer = true;
	else if(ligne[0] == 'N')
		rejouer = false;

	// libération de la mémoire occupée
	libere_grille(grille, taille);
	system(NETTOIE_TERMINAL);
	return rejouer;
}
