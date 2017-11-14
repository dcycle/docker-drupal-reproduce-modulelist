<?php

/**
 * @file
 * Entrypoint for our application.
 */

require_once 'autoload.php';

use myproject\App;

App::instance()->printDrupalModules();
