<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Blade Views
    |--------------------------------------------------------------------------
    |
    | Here you may specify the Blade Views should create or not
    |
    */

    "blade_views" => true,

    /*
    |--------------------------------------------------------------------------
    | VueJs Views
    |--------------------------------------------------------------------------
    |
    | Here you may specify the VueJs Views should create or not
    |
    */

    "js_views" => true,

    /*
    |--------------------------------------------------------------------------
    | Map VueJs Components
    |--------------------------------------------------------------------------
    |
    | Here you may specify the Map VueJs Components should create or not
    |
    */


    "map_js_component" => true,

    /*
    |--------------------------------------------------------------------------
    |Livewire Components
    |--------------------------------------------------------------------------
    |
    | Here you may specify the Livewire Components should create or not
    |
    */


    "livewire_component" => true,

    /*
    |--------------------------------------------------------------------------
    |Model Factory Migration Controller
    |--------------------------------------------------------------------------
    |
    | Here you may specify the Model Factory Migration Controller should create or not
    |
    */


    "mcfm" => true,

    /*
    |--------------------------------------------------------------------------
    |Paths
    |--------------------------------------------------------------------------
    |
    | Here you may specify the Paths
    |
    */

    "paths" => [
        "blade_views" => "views/admin/",
        "js_views" => "js/components/backend/",
        "map_js_component" => "js/chunk.js",
        "controller" => ""
    ]
];
