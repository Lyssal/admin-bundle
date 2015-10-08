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

ainsi que les bundles requis :
```php
new Doctrine\Bundle\DoctrineCacheBundle\DoctrineCacheBundle(),
new Knp\Bundle\MenuBundle\KnpMenuBundle(),
new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
new FOS\UserBundle\FOSUserBundle(),
new Sonata\CoreBundle\SonataCoreBundle(),
new Sonata\BlockBundle\SonataBlockBundle(),
new Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),
new Sonata\AdminBundle\SonataAdminBundle(),
new Ivory\CKEditorBundle\IvoryCKEditorBundle(),
```
Reportez-vous aux documentations de chacun pour leur configuration et paramétrage.

Après ce paramétrage, vous devrez exécuter la commande suivante :
```sh
php app/console doctrine:phpcr:init:dbal
```

Vous devez ensuite créer un bundle héritant `FOSUserBundle` ou héritant `LyssalUtilisateurBundle` si vous l'avez installé.


## Exemple de configuration

Dans votre `config.yml` :
```yml
framework:
    translator: { fallbacks: ["%locale%"] }

doctrine_cache:
    providers:
        phpcr_meta:
            type: file_system
        phpcr_nodes:
            type: file_system

fos_user:
    db_driver: orm
    firewall_name: main
    user_class: FOS\UserBundle\Entity\User
    group:
        group_class: FOS\UserBundle\Entity\Group


sonata_block:
    default_contexts: [cms]
    blocks:
        sonata.admin.block.admin_list:
            contexts: [admin]
        # Pour faire fonctionner le moteur de recherche
        sonata.admin.block.search_result:
            contexts: [admin]
        sonata_admin_doctrine_phpcr.tree_block:
            settings:
                id: '/cms'
            contexts:   [admin]
        sonata.user.block.menu:    # used to display the menu in profile pages
        sonata.user.block.account: # used to display menu option (login option)
        sonata.block.service.text:

sonata_admin:
    title: Console administrative
    title_logo: favicon.png
    dashboard:
        # Liste des blocs à afficher sur le tableau de bord
        blocks:
            - { position: left, class: "col-md-12", type: sonata.block.service.text, settings: { content: "<div class='box'><div class='box-header'><h3 class='box-title'>Bienvenue</h3></div><div class='box-body'>Ceci est votre console administrative.</div></div>"} }
            #- { position: left, type: sonata_admin_doctrine_phpcr.tree_block } # Arbre Symfony CMF
            - { position: right, type: sonata.admin.block.admin_list }
    security:
        handler: sonata.admin.security.handler.role


ivory_ck_editor:
    configs:
        basic:
            toolbar: [ [ 'Bold', 'Italic', '-', 'Subscript', 'Superscript', '-', 'RemoveFormat', '-', 'NumberedList', 'BulletedList', '-', 'Blockquote', '-', 'Link', 'Unlink', '-', 'Undo', 'Redo' ] ]
```

Dans votre `routing.yml` :
```yml
fos_js_routing:
    resource: "@FOSJsRoutingBundle/Resources/config/routing/routing.xml"

admin:
    resource: '@SonataAdminBundle/Resources/config/routing/sonata_admin.xml'

_sonata_admin:
    resource: .
    type: sonata_admin
    prefix: /console

lyssal_admin:
    resource: "@LyssalAdminBundle/Resources/config/routing.yml"
    prefix: /console
```


Dans votre `security.yml` :
```yml
security:
    encoders:
        FOS\UserBundle\Model\UserInterface: sha512

    role_hierarchy:
        ROLE_ADMIN:       [ROLE_USER, ROLE_SONATA_ADMIN]
        ROLE_SUPER_ADMIN: [ROLE_ADMIN, ROLE_ALLOWED_TO_SWITCH]
        SONATA:
            - ROLE_SONATA_PAGE_ADMIN_PAGE_EDIT

    providers:
        fos_userbundle:
            id: fos_user.user_manager

    firewalls:
        dev:
            pattern:  ^/(_(profiler|wdt|error)|css|images|js)/
            security: false


        #<-- Console administrative
        admin:
            pattern: /console/(.*)
            context: user
            form_login:
                provider: fos_userbundle
                login_path: /console/login
                use_forward: false
                check_path: /console/login_check
                failure_path: null
            logout:
                path: /console/logout
            anonymous: true
        #-->
        #<-- Front
        main:
            pattern: .*
            context: user
            form_login:
                provider: fos_userbundle
                login_path: fos_user_security_login
                use_forward: false
                check_path: fos_user_security_check
                failure_path: null
            logout:
                path: /deconnexion
            anonymous: true
            switch_user: true
        #-->

    access_control:
        #<-- Connexion utilisateur (FOSUserBundle)
        - { path: ^/login$, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/register, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/resetting, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/console/login$, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/console/logout$, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/console/login_check$, role: IS_AUTHENTICATED_ANONYMOUSLY }
        #-->
        
        - { path: ^/efconnect, role: ROLE_USER }
        - { path: ^/elfinder, role: ROLE_USER }

        - { path: ^/console/, role: [ROLE_ADMIN, ROLE_SONATA_ADMIN] }
        - { path: ^/.*, role: IS_AUTHENTICATED_ANONYMOUSLY }
```


## Ajouter Symfony CMF

Dans votre `AppKernel.php` :
```php
new Doctrine\Bundle\PHPCRBundle\DoctrinePHPCRBundle(),
new Sonata\DoctrinePHPCRAdminBundle\SonataDoctrinePHPCRAdminBundle(),
new Symfony\Cmf\Bundle\CoreBundle\CmfCoreBundle(),
new Symfony\Cmf\Bundle\ContentBundle\CmfContentBundle(),
new Symfony\Cmf\Bundle\RoutingBundle\CmfRoutingBundle(),
new Symfony\Cmf\Bundle\SimpleCmsBundle\CmfSimpleCmsBundle(),
new Symfony\Cmf\Bundle\TreeBrowserBundle\CmfTreeBrowserBundle(),
new Lyssal\SymfonyCmf\SimpleCmsBundle\LyssalSymfonyCmfSimpleCmsBundle(),
```

Dans votre `config.yml` :
```yml
doctrine_phpcr:
    session:
        backend:
            type: doctrinedbal
            connection: default
            caches:
                meta: doctrine_cache.providers.phpcr_meta
                nodes: doctrine_cache.providers.phpcr_nodes
        workspace: "default"
        username: "admin"
        password: "admin"
    odm:
        auto_mapping: true
        auto_generate_proxy_classes: "%kernel.debug%"

sonata_doctrine_phpcr_admin:
    document_tree_defaults: [locale]
    # Définir les arbres à afficher (pour le choix des parents par exemple dans la gestion de Symfony CMF)
    document_tree:
        # Arbre générique de Symfony CMF, préférer utiliser l'entité pour les éléments dont on va se servir dans l'application
        Doctrine\ODM\PHPCR\Document\Generic:
            valid_children:
                - all
        # Menus
        Symfony\Cmf\Bundle\MenuBundle\Doctrine\Phpcr\Menu:
            valid_children:
                - all
        # Pages de SimpleCMS
        Symfony\Cmf\Bundle\SimpleCmsBundle\Doctrine\Phpcr\Page:
            valid_children:
                - all

cmf_core:
    persistence:
        phpcr: true
    publish_workflow: false

cmf_routing:
    dynamic:
        templates_by_class:
            Symfony\Cmf\Bundle\SimpleCmsBundle\Doctrine\Phpcr\Page: LaVendeeAppBundle:CMF:index.html.twig

cmf_simple_cms:
    persistence:
        phpcr:
            # Pour l'affichage dans la liste des pages par défaut
            basepath: /cms/simple
            document_class: Symfony\Cmf\Bundle\SimpleCmsBundle\Doctrine\Phpcr\Page
            use_sonata_admin: true
```

Dans votre `routing.yml` :
```yml
cmf_tree:
    resource: .
    type: 'cmf_tree'
```
