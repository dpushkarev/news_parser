<?php
namespace App\Http\Controllers;

use App\Repositories\NewsRepository;
use Illuminate\Routing\Controller as BaseController;

class NewsController extends BaseController
{
    protected $newsRepository;

    public function __construct(NewsRepository $repository)
    {
        $this->newsRepository = $repository;
    }

    public function index()
    {
        $news = $this->newsRepository->getAll();

        return view('news.index', ['news' => $news]);
    }

    public function detail($id)
    {
        $news = $this->newsRepository->getById($id);

        return view('news.detail', ['news' => $news]);
    }

}
