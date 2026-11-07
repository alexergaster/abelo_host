<?php

namespace App\Controllers\Categories;

use App\DTO\PostFilter;
use App\Requests\PaginationRequest;
use Smarty\Smarty;

class ShowController extends BaseController
{
    /**
     * @throws \Exception
     */
    public function __invoke(string $slug): void
    {
        $filter = PostFilter::fromQuery($_GET);
        $pagination = new PaginationRequest($_GET);

        $data = $this->service->getPostsByCategory($slug, $filter, $pagination->getPage(), $pagination->getPerPage());
        $categoryUrl = '/categories/' . rawurlencode($data['category']['slug']);

        $filterQuery = http_build_query(
            array_filter([
                'views_min' => $filter->viewsMin,
                'views_max' => $filter->viewsMax,
                'date_from' => $filter->dateFrom,
                'date_to' => $filter->dateTo,
                'sort' => $filter->sort,
                'perPage' => $data['pagination']['perPage'],
            ], static fn($value) => $value !== null && $value !== '')
        );

        $paginationBase = $categoryUrl . '?' . ($filterQuery !== '' ? $filterQuery . '&' : '');

        $smarty = new Smarty();

        $root = dirname(__DIR__, 3);

        $smarty->setTemplateDir($root . '/templates');
        $smarty->setCompileDir($root . '/templates_c');

        $smarty->assign([
            'pageTitle' => $data['category']['name'] . ' Articles',
            'pageDescription' => $data['category']['description'],
            'category' => $data['category'],
            'posts' => $data['posts'],
            'pagination' => $data['pagination'],
            'filter' => $filter,
            'filterAction' => $categoryUrl,
            'paginationBase' => $paginationBase,
        ]);


        header('Content-Type: text/html; charset=utf-8');

        $smarty->display('pages/categories/show.tpl');
    }
}