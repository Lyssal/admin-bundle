# LyssalAdminBundle

`LyssalAdminBundle` est une console administrative basée sur `SonataAdmin`, `FOSUser` et `Symfony CMF`.


## Ajouts

* Connexion utilisateur à la console (basé sur le code de `SonataUserAdmin`)

## Installation

1. Mettez à jour votre `composer.json` :
```json
"require": {
    "lyssal/admin-bundle": "*"
}
```
2. Installez le bundle :
```sh
php composer.phar update
```
3. Mettez à jour `AppKernel.php` :
```php
new Lyssal\AdminBundle\LyssalAdminBundle(),
```


## Configuration

### CKEditor

`LyssalAdminBundle` se base sur `IvoryCKEditorBundle` pour afficher l'éditeur graphique.

Dans votre `config.yml` :

```yml
ivory_ck_editor:
    configs:
        basic:
            toolbar: [ [ 'Bold', 'Italic', '-', 'Subscript', 'Superscript', '-', 'RemoveFormat', '-', 'NumberedList', 'BulletedList', '-', 'Blockquote', '-', 'Link', 'Unlink', '-', 'Undo', 'Redo' ] ]
```
