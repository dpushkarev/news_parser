<?php

namespace App\Services\Parse;

use App\Models\NewsSource;
use Illuminate\Support\Carbon;
use PHPHtmlParser\Dom\Node\HtmlNode;

/**
 * Interface ParserNewsInterface
 */
interface ParserNewsInterface
{
    /**
     * @return array
     */
    public function getParsedNews(): array;

}