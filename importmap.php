<?php

/**
 * Returns the importmap for this application.
 *
 * - "path" is a path inside the asset mapper system. Use the
 *     "debug:asset-map" command to see the full list of paths.
 *
 * - "entrypoint" (JavaScript only) set to true for any module that will
 *     be used as an "entrypoint" (and passed to the importmap() Twig function).
 *
 * The "importmap:require" command can be used to add new entries to this file.
 */
return [
    'app' => [
        'path' => './assets/app.js',
        'entrypoint' => true,
    ],
    'admin-post-modals' => [
        'path' => './assets/js/admin-post-modals.js',
        'entrypoint' => true,
    ],
    'modeles-post' => [
        'path' => './assets/admin/modelepost/traitement.js',
        'entrypoint' => true,
    ],
    'modeles-template-page' => [
        'path' => './assets/admin/modeles-template-page/traitement.js',
        'entrypoint' => true,
    ],
    'acf' => [
        'path' => './assets/admin/acf/traitement.js',
        'entrypoint' => true,
    ],
    'mediatheque' => [
        'path' => './assets/admin/mediatheque/media.js',
        'entrypoint' => true,
    ],
    'post_type' => [
        'path' => './assets/admin/post_type/traitement.js',
        'entrypoint' => true,
    ],
    'post' => [
        'path' => './assets/admin/post/post.js',
        'entrypoint' => true,
    ],
    'taxonomy'=>[
        'path' => './assets/admin/taxonomy/traitement.js',
        'entrypoint' => true,
    ],
    'modele-term'=>[
        'path' => './assets/admin/term/traitement.js',
        'entrypoint' => true,
    ],
    'menu-module'=>[
        'path' => './assets/admin/menu/traitement.js',
        'entrypoint' => true,
    ],
    'emplacement'=>[
        'path' => './assets/admin/emplacement/traitement.js',
        'entrypoint' => true,
    ],
];
