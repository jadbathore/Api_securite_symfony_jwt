#### Démarrage Docker
```shell
docker compose build --no-cache
docker compose up --pull always --wait
```
Se référer à la [documentation relative à Docker](https://github.com/dunglas/symfony-docker)

#### Génération des clefs JWT
```shell
docker compose exec php bin/console lexik:jwt:generate-keypair
```

#### Chargement du jeu de tests
```shell
docker compose exec php bin/console doctrine:fixtures:load --no-interaction
```
### Commencement :

Pour tester après démarrage de docker accéder à [l'api](https://localhost/api)

### indetification:
Pour vous indetifier faite la requette (POST:[localhost/api/auth](https://localhost/api/auth)) 
avec le body :
```json
{
    "email":"user1@local.host",
    "password":"my_password"
}
```
cela vous donnera un token copier le ,sur PostMan aller dans la rubric header crée une nouvelle entête dont (keys=Authorization) et value (Bearer (le token  copier))
vous êtes desormé identifier grâce au JWT. 

## Request 
### login check
#### POST /api/auth
Creates a user token.
### Company 
#### GET /api/company/ 
Retrieves the collection of Company resources.
#### GET /api/company/{company_id}
Retrieves a Company resource.
### Projet
#### GET /api/projet/{company_id}
Retrieves a Projet resource.
#### POST /api/projet/{company_id}
Creates a Projet resource.
#### DELETE /api/projet/{company_id}/{id}
Removes the Projet resource.
#### PATCH /api/projet/{company_id}/{id}
Updates the Projet resource.
