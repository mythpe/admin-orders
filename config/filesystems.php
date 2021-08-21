<?php

$storagePublicRoot = 'app/public';

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => env('FILESYSTEM_CLOUD', 's3'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'app' => [
            'driver' => 'local',
            'root'   => app_path(),
        ],

        'documentation' => [
            'driver' => 'local',
            'root'   => resource_path('documentation'),
        ],

        'local' => [
            'driver' => 'local',
            'root'   => storage_path('app'),
        ],

        'public' => [
            'driver'     => 'local',
            'root'       => storage_path($storagePublicRoot),
            'url'        => rtrim(env('APP_URL'), '/') . '/storage',
            'visibility' => 'public',
        ],

        's3' => [
            'driver'   => 's3',
            'key'      => env('AWS_ACCESS_KEY_ID'),
            'secret'   => env('AWS_SECRET_ACCESS_KEY'),
            'region'   => env('AWS_DEFAULT_REGION'),
            'bucket'   => env('AWS_BUCKET'),
            'url'      => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
        ],

        'media' => [
            'driver'     => 'local',
            'root'       => storage_path('app/public/media'),
            'url'        => env('APP_URL') . '/storage/media',
            'visibility' => 'public',
        ],

        'excel' => [
            'driver'     => 'local',
            'root'       => storage_path("{$storagePublicRoot}/excel"),
            'url'        => rtrim(env('APP_URL'), '/') . "/storage/excel",
            'visibility' => 'public',
        ],

        'pdf' => [
            'driver'     => 'local',
            'root'       => storage_path("{$storagePublicRoot}/pdf"),
            'url'        => rtrim(env('APP_URL'), '/') . "/storage/pdf",
            'visibility' => 'public',
        ],

        'json' => [
            'driver' => 'local',
            'root'   => resource_path('json'),
        ],

        'setup' => [
            'driver' => 'local',
            'root'   => resource_path('setup'),
        ],

        'qr' => [
            'driver'     => 'local',
            'root'       => storage_path("{$storagePublicRoot}/qr"),
            'url'        => rtrim(env('APP_URL'), '/') . "/storage/qr",
            'visibility' => 'public',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
        public_path('build')   => storage_path('app/build'),
    ],

];
