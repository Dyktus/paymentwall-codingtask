<?php
namespace App;

require_once __DIR__ . '/../vendor/autoload.php';

use App\Middlewares\AppManager;
use Symfony\Component\HttpFoundation\Request;

/*Aura autoloader*/
$loader = new \Aura\Autoload\Loader;
$loader->addPrefix('App', __DIR__);
$loader->register();

/*Silex App */
$app = new \Silex\Application();
$app->before(function(Request $request, \Silex\Application $app) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
    if(0 === strpos($request->headers->get('Content-Type'), 'application/xml')) {
        $data = json_decode(json_encode((array)simplexml_load_string($request->getContent())),1);
        $request->request->replace(is_array($data) ? $data : array());
    }
    return AppManager::authorizeRequest($request, $app);
});
AppManager::registerRoutes($app);

$app->run();
