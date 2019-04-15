<?php
namespace Db2Cloud\Adapters;
use Illuminate\Support\ServiceProvider;
use Google_Client;
class GoogleDriveServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        \Storage::extend('gdrive', function($app, $config) {

            $client = new Google_Client();
            $client->setClientId($config['clientId']);
            $client->setClientSecret($config['clientSecret']);
//            $client->addScope()
dd($client->fetchAccessTokenWithRefreshToken("1/76fXICyoLiQsqn49bIlONBLN9NMw8jvPzc9XEKJ0bC0"));
            $client->setAccessToken();


            $service = new \Google_Service_Drive($client);
            $adapter = new \Hypweb\Flysystem\GoogleDrive\GoogleDriveAdapter($service, $config['folderId']);
            return new \League\Flysystem\Filesystem($adapter);
        });
    }
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}