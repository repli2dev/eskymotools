<?php

// Step 1: Load Nette Framework
// this allows Nette to load classes automatically so that
// you don't have to litter your code with 'require' statements
require_once LIBS_DIR . '/Nette/loader.php';

// Loader
$loader = new RobotLoader();
$loader->addDirectory(APP_DIR);
$loader->addDirectory(LIBS_DIR);
$loader->addDirectory(ESKYMO_DIR);
$loader->register();

Environment::loadConfig(APP_DIR . '/config.ini');

// Step 2: Enable Nette\Debug
// for better exception and error visualisation
$debug = Environment::getConfig('debug');
Debug::enable(Debug::DEVELOPMENT, $debug->log, $debug->email);

// Step 3: Get the front controller
$application = Environment::getApplication();

// Step 4: Run the application!
$application->run();