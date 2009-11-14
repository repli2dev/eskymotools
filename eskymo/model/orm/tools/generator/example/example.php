#!/usr/bin/php -q 

<?php
define ("CURRENT_DIR", dirname(__FILE__));
define ("APP_DIR", CURRENT_DIR);
define ("LIBS_DIR", CURRENT_DIR . "/../../../../../../libs");

require_once(LIBS_DIR . "/Nette/loader.php");

$loader = new RobotLoader();
$loader->addDirectory(LIBS_DIR);
$loader->addDirectory(LIBS_DIR . "/../eskymo");
$loader->register();

$arguments = Console::loadArguments($argv);

$generator = new EntityGenerator(dibi::connect($arguments));
echo $generator->generate("opinion");
