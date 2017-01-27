<?php

/**
 * @author: dyktus
 * @cretedAt 2017-01-04 21:21:22
 * @class AppManager
 */

namespace App\Middlewares;

use App\Type\AppManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class AppManager implements AppManagerInterface {

    private static $controllersDir = __DIR__ . '/../Controllers';

    public static function registerRoutes(&$app) {
        $files = scandir(self::$controllersDir);
        foreach ($files as $file) {
            if (is_dir($file)) {
                continue;
            }
            $controllerClass = '\App\Controllers\\' . substr($file, 0, -4);
            $routes = $controllerClass::$ROUTES;
            foreach ($routes as $route => $method) {
                $splitRoute = explode(' ', $route);
                if (count($splitRoute) !== 2) {
                    throw new \Exception("Wrong routing structure in " . $controllerClass);
                }
                $app->{$splitRoute[0]}('/' . $splitRoute[1], function(Request $request)
                        use($method, $controllerClass, $app) {
                    $obj = new $controllerClass();
                    $response = $obj->$method($request);
                    return $app->json($response['msg'], $response['http_code']);
                });
            }
        }
    }

    public static function authorizeRequest(Request $request, $app) {
        $expectedKey = '123qwe123';
        $userSecureKey = 'abcd123123';
        $userData = [
            'ts' => $request->request->get('ts'),
            'tms' => $request->request->get('tms'),
            'userKey' => $request->request->get('user_key'),
            'sec' => $request->request->get('sec'),
            'hash' => $request->request->get('hash')
        ];
        foreach ($userData as $k => $v) {
            if (empty($v)) {
                return $app->json(['code' => 100, 'msg' => 'You should provide hash,ts,tms,sec and user_key parameters. You missed '.$k.' parameter.'], 401);
            }
        }
        if((time()-$userData['ts'])/60 < 5) {
            return $app->json(['code' => 101, 'msg' => 'Secure key expired']);
        }
        $countSec = sha1($userSecureKey.$userData['ts']);
        
        $countHash = sha1($expectedKey . $userData['ts'] . $userData['tms']);
        if (strcmp($countHash, $userData['hash']) !== 0 || strcmp($countSec, $userData['sec']) !== 0) {
            return $app->json(['code' => 102, 'msg' => 'Hash or sec is incorrect. Could not authorize user'], 401);
        }
    }

}
