# FORCENTO ReNUTS — site vitrine

Petit site vitrine PHP multilingue (FR / EN / DE / IT), sans framework ni base de données.

## Lancer en local dans Visual Studio Code

Ouvrir un terminal à la racine du projet puis :

```bash
php -S localhost:8000 router.php
```

Ouvrir ensuite :

- http://localhost:8000/fr/
- http://localhost:8000/en/
- http://localhost:8000/de/
- http://localhost:8000/it/

## Déploiement

Le site peut être placé directement dans le dossier web du domaine / sous-domaine.
`.htaccess` gère les URLs multilingues propres sur Apache.

Si le serveur utilise Nginx, faire pointer toutes les routes qui ne correspondent pas
à un fichier existant vers `index.php`.

## À faire avant mise en ligne

Dans `config/site.php`, renseigner :

```php
'contact_email' => 'votre@email.ch',
```

Le bouton "Parlons du projet" utilisera alors automatiquement cette adresse.

## Couleurs

- FORCENTO green: `#2FAC66`
- FORCENTO charcoal: `#1D1D1B`
- Paper: `#F4F3EC`

Le vert et le noir sont repris directement du logo fourni.

## Structure

- `index.php` : page unique et routing langue
- `lang/*.php` : textes FR / EN / DE / IT
- `assets/css/app.css` : design
- `assets/js/app.js` : menu mobile + animations
- `assets/img/forcento-logo.png` : logo fourni, recadré
- `config/site.php` : société / contact / langues
- `router.php` : serveur PHP local
- `.htaccess` : rewrite Apache
