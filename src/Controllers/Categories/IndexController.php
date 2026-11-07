<?php

namespace App\Controllers\Categories;

use Smarty\Exception;
use Smarty\Smarty;

class IndexController extends BaseController
{
    /**
     * @throws Exception
     */
    public function __invoke(): void
    {
        $categories = $this->service->getPostsInCategory(3);

        $smarty = new Smarty();

        $root = dirname(__DIR__, 3);

        $smarty->setTemplateDir($root . '/templates');
        $smarty->setCompileDir($root . '/templates_c');

        $smarty->assign([
            'pageTitle' => 'Explore by Category',
            'pageDescription' => 'Discover the latest articles across development topics.',
            'categories' => $categories,
        ]);

        header('Content-Type: text/html; charset=utf-8');

        $smarty->display('categories/index.tpl');
    }
}