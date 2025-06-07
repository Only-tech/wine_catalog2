# Catalogue de Vins - L’art de savourer le temps

Ce projet est une application web simple de type catalogue de vins intitulé **L’art de savourer le temps**, développée en utilisant HTML, CSS, PHP et PostgreSQL. Elle permet d'afficher une liste de vins, de consulter les détails de chaque vin et d'ajouter les vins au panier, Elle dispose également d'une interface d'administration pour ajouter, modifier et supprimer des produits.

## Fonctionnalités

- **Page d'accueil (Catalogue)**:

  - Affiche la liste de tous les vins disponibles avec image, nom, prix et un lien "Voir le produit".

- **Page de détail produit**:

  - Affiche les informations détaillées d'un vin spécifique (nom du produit, description, image, prix).

- **Interface d'administration (Back-office)**:

  - Accessible via `/admin.php`.
  - Permet d'ajouter un nouveau vin.
  - Permet de modifier un vin existant.
  - Permet de supprimer un vin.

- **Interface pour valider la commande (Panier)**:
  - Affiche les vin (nom du produit, description, image, prix) ajoutés au panier.

## Implémentations

- **Modale Confirmation avant suppression**: Une boîte de dialogue de confirmation apparaît avant la suppression d'un produit dans l'interface d'administration, l'interface du panier et l'ajout d'un produit dans le panier.

## Structure du projet

    ```
    wine_catalog2/
    ├── docker-compose.yml
    ├── Dockerfile
    ├── data.sql
    ├── servers.json
    ├── php/
    │ ├── connect.php
    │ ├── disconnect.php
    │ ├── index.php ➜ Page d’accueil (Catalogue)
    │ ├── admin.php ➜ Page d’administration pour gérer les produits
    │ ├── add-product.php ➜ Ajout d’un produit
    │ ├── delete-product.php ➜ Suppression d’un produit
    │ ├── update-product.php ➜ Suppression d’un produit
    │ ├── product.php ➜ Détail du produit
    │ ├── cart.php ➜ Page du panier
    │ ├── checkout.php ➜ Page de commande
    │ ├── add-to-cart.php ➜ Ajout d’un produit au panier
    │ ├── remove-cart.php ➜ Suppression d’un produit du panier
    │ ├── header.php
    │ ├── footer.php
    │ ├── assets/
    │ │ ├── css/styles.css
    │ │ ├── fonts/
    │ │ ├── images/
    │ │ ├── js/

    ```

## Technologies

- **Docker**: L'environnement est configuré via Docker Compose, incluant un serveur web Apache/PHP et une base de données **PostgreSQL**.

## Installation et Lancement du Projet

Docker et Docker Compose

1.  **Cloner le dépôt (ou créer les fichiers manuellement)**:
    Si vous avez un dépôt Git:

    ```bash
    git clone <https://github.com/Only-tech/wine_catalog2.git/>
    cd <wine_catalog2>
    ```

    Sinon, créez la structure de fichiers et placez les codes fournis dans les fichiers correspondants.

2.  **Construire et Lancer les conteneurs Docker**:
    Dans le répertoire racine du projet où se trouve `docker-compose.yml`, exécutez la commande suivante :

    ```bash
    docker-compose up --build -d
    ```

    - `--build`: Reconstruit les images si elles n'existent pas ou si le `Dockerfile` a changé.
    - `-d`: Lance les conteneurs en mode détaché (en arrière-plan).

3.  **Accéder à l'application**:

    - Le catalogue de vins sera accessible à l'adresse : `http://localhost:8090/`
    - L'interface administrateur du site : `http://localhost:8091/`
    - L'interface d'administration pgAdmin : `http://localhost:8091/`

4.  **Arrêter les conteneurs Docker**:
    Pour arrêter et supprimer les conteneurs (sans supprimer les données de la base de données persistantes), exécutez :

    ```bash
    docker-compose down

    ou Ctrl + C
    ```

    Pour arrêter et supprimer les conteneurs ainsi que les volumes de données (ce qui effacera toutes les données de votre base de données), exécutez :

    ```bash
    docker-compose down -v
    ```

## Utilisation

1.  **Ajouter des vins**: Rendez-vous sur l'interface d'administration (`http://localhost/admin/add_product.php`) pour ajouter vos premiers vins au catalogue.
2.  **Parcourir le catalogue**: Une fois les vins ajoutés, retournez à la page d'accueil (`http://localhost/`) pour les visualiser.
3.  **Voir les détails**: Cliquez sur "Voir le produit" sur une carte de vin pour afficher sa page de détail.
4.  **Gérer les vins**: Dans l'interface d'administration, vous pouvez modifier ou supprimer les vins existants.
