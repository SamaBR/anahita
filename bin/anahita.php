<?php 

//@TODO teribble. should use autoload

set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__.'/../');

$files = array(
        __DIR__ . '/../vendor/autoload.php',
        __DIR__ . '/../../../autoload.php'
);

global $composerLoader, $console;

foreach($files as $file) 
{
    if ( file_exists($file) ) 
    {
        $composerLoader = require_once($file);
        define('COMPOSER_VENDOR_DIR', realpath(dirname($file)));
        define('COMPOSER_ROOT', realpath(dirname($file).'/../'));
        break;
    }
}

$composerLoader->add('', COMPOSER_ROOT.'/tasks');
$composerLoader->add('Console\\', __DIR__.'/..');

//check the tasks folder for any class

$console = new Console\Application(realpath(__DIR__.'/../'), COMPOSER_ROOT.'/www', array('Custom'=>COMPOSER_ROOT.'/packages'),COMPOSER_ROOT.'/config');

function include_tasks($directory) 
{
    global $console;    
    $files = new \DirectoryIterator($directory);
    foreach($files as $file) {
        if ( strpos($file, '.task.php') ) {
            require_once $file->getPathName();
        }
    }
}

include_tasks(__DIR__.'/../console/tasks');
include_tasks(COMPOSER_ROOT.'/tasks');

$console->run();
exit(0);