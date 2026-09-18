<?php

use CpmsFormTest\Bootstrap;

require_once __DIR__ . '/CpmsFormTest/Bootstrap.php';

$_SERVER['argv'] = ['test'];
$path = realpath(__DIR__);

chdir(dirname($path));
/** @var Bootstrap $bootstrap */
$bootstrap = Bootstrap::getInstance();
$bootstrap->init($path, array('CpmsFormTest'));
