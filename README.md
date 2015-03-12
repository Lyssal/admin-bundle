# LyssalAdminBundle

`LyssalAdminBundle` est une console administrative basée sur `SonataAdmin`, `FOSUser` et `Symfony CMF`.

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/9520370a-c925-4051-8edb-e48eeb0771c6/small.png)](https://insight.sensiolabs.com/projects/9520370a-c925-4051-8edb-e48eeb0771c6)

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
