<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsSource extends Model
{
    const RBK_CODE = 'rbk';

    public function news()
    {
        return $this->hasMany(News::class, 'news_source_id', 'id');
    }

}
