<?php
return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */
    'connection_type'=>'mysql',


    /*
    |--------------------------------------------------------------------------
    | Storage Type you prefer
    |--------------------------------------------------------------------------
    |
    | You can store your database backup to various platform either S3,gDrive
    | and you can also store database backup in a local folder of the server
    |
    */
    'storage_type'=>'local,gdrive',  // local,S3,gdrive

    /*
    |--------------------------------------------------------------------------
    | STORAGE CONFIG
    |--------------------------------------------------------------------------
    |
    | Here is the configuration part for various databases
    |
    |
    */
    'storage_configs'=>[

        'local'=>[
            'path'=>public_path('backup/')      // Full path of the directory
        ],
        'S3'=>[

        ],
        'gDrive'=>[

        ],
    ],
    'prefix_name'=>'backup',
    'log'=>true



];