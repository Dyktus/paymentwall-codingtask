<?php
namespace App\Type;

use Symfony\Component\HttpFoundation\Request;
interface AppManagerInterface {
    
    public static function registerRoutes(&$app);
    public static function authorizeRequest(Request $request, $app);
}
