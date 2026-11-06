<?php

namespace App\Controllers\Posts;


use Smarty\Smarty;

class IndexController extends BaseController
{
    public function __invoke(): void
    {
        $smarty = new Smarty();

        $smarty->setTemplateDir(dirname(__DIR__, 3) . '/templates');
        $smarty->setCompileDir(dirname(__DIR__, 3) . '/templates_c');

        $smarty->assign('pageTitle', 'PHP Blog');

        header('Content-Type: text/html; charset=utf-8');

        $smarty->display('home.tpl');
    }
}