# CSS Generator

License

Bienvenue dans le référentiel du projet CSS Generator (cssgenerator). Ce projet vise à développer un programme en PHP qui permet de comprendre le traitement des images et la gestion des fichiers. Il inclut des fonctionnalités telles que la génération de spritesheets CSS et le développement d'un programme en ligne de commande UNIX.
Langage utilisé

    PHP

Fonctionnalités

    PHP
    Gestion des fichiers en PHP
    Spritesheets CSS
    Développer un programme en ligne de commande UNIX

Installation

    Cloner le référentiel
    Accéder au répertoire : cd cssgenerator

Utilisation

Assurez-vous d'avoir PHP installé sur votre machine.

Exécutez le programme en utilisant la commande suivante :

css

php css_generator.php [OPTIONS] assets_folder

Options :

    -r, --recursive : Rechercher des images dans le dossier assets_folder et ses sous-répertoires.
    -i, --output-image=IMAGE : Nom de l'image générée. Si vide, le nom par défaut est "sprite.png".
    -s, --output-style=STYLE : Nom de la feuille de style générée. Si vide, le nom par défaut est "style.css".

Options bonus :

    -p, --padding=NUMBER : Ajouter un espacement de NUMBER pixels entre les images.
    -o, --override-size=SIZE : Forcer chaque image du sprite à avoir une taille de SIZExSIZE pixels.
    -c, --columns_number=NUMBER : Le nombre maximum d'éléments à générer horizontalement.

Exemple d'utilisation

bash

php css_generator.php -r -i mysprite.png -s mystyle.css assets/images

Contribution

Les contributions sont les bienvenues ! Si vous souhaitez améliorer ce projet, veuillez suivre les étapes suivantes :

    Forker le référentiel
    Créer une branche : git checkout -b feature/NouvelleFonctionnalite
    Effectuer les modifications et valider les changements : git commit -m "Ajouter une nouvelle fonctionnalité"
    Pousser les modifications vers la branche : git push origin feature/NouvelleFonctionnalite
    Ouvrir une pull request
