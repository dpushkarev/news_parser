<?php


namespace App\Repositories;


use App\Models\NewsSource;

class NewsSourceRepository
{
    public function getByCode($code): NewsSource
    {
        return NewsSource::where('code', $code)->with('news')->first();
    }
}