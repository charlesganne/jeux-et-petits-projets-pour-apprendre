#include <stdio.h>
#include <stdlib.h>
#include <stdbool.h>
#include "fonctions_generales.h"

int main(void)
{
	// Toute l'exécution de ce programme admet que l'entrée de l'utilisateur est conforme aux consignes 
	// affichées à l'écran, et toute exécution avec une entrée invalide n'est pas prise en compte dans ce code, 
	// ce n'est pas son but.
	bool jouer = true;
	while(jouer)
	{
		// Demande à l'utilisateur le mode de jeu dans lequel il veut jouer
		printf("MENU PRINCIPAL\n");
		printf("Dans quel mode de jeu voulez vous jouer :\n");
		printf("\"n\" pour normal, \"d\" pour duo, \"p\" pour puzzle :\n");

		char ligne[10]; // Contiendra la ligne entrée par l'utilisateur
		char reponse; // Y sera placé le premier caracter de la ligne (le seul en exécution nominale)
		fgets(ligne, sizeof(ligne), stdin); // lis la première ligne entée par l'utilisateur, jusqu'au retour à la ligne, le '\n', juste avant le '\0'
		reponse = ligne[0];
		if(reponse == 'n')
			jouer = normal();
		if(reponse == 'd')
			jouer = duo();
		if(reponse == 'p')
			jouer = puzzle();
	}
	printf("Au revoir\n");
	return 0;
}
