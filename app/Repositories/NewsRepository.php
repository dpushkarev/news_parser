<?php


namespace App\Repositories;


use App\Models\News;
use Illuminate\Support\Collection;

class NewsRepository
{
    public function getAll(): ?Collection
    {
        return News::all();
    }

    public function getById($id)
    {
        return News::findOrFail($id);
    }
}