<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Smarty\Smarty;

$smarty = new Smarty();

$smarty->setTemplateDir(__DIR__ . '/../templates');
$smarty->setCompileDir(__DIR__ . '/../templates_c');

$smarty->assign('pageTitle', 'PHP Blog');

$smarty->display('home.tpl');