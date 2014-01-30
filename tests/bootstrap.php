<?php

error_reporting(-1);

$loader = require __DIR__ . '/../src/bootstrap.php';
$loader->add('Gobie\Test', __DIR__);
$loader->add('Gobie\Bench', __DIR__);
