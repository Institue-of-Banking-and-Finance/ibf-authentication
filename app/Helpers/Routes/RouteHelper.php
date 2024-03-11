<?php
namespace  App\Helpers\Routes;

use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class RouteHelper{
    public static function IncludeRouteFiles(string $folder){
        $dirIterator = new RecursiveDirectoryIterator($folder);

        $it = new RecursiveIteratorIterator($dirIterator);
        /** @var RecursiveDirectoryIterator | RecursiveIteratorIterator $it*/

        $it = new RecursiveIteratorIterator($dirIterator);
        while ($it -> valid()) {
            if(!$it->isDot() && $it->isFile() && $it->isReadable() && $it->current()->getExtension() === 'php'){
                // require $it->key();
                require $it->current()->getPathname();
            }
            $it->next();
        }
    }
}
