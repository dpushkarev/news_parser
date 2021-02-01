<?php


namespace App\Services\Parse;

use App\Models\News;

/**
 * Class ParseService
 * @package App\Services\Parse
 */
class ParseService
{
    /**
     * @param ParserNewsInterface $parser
     * @return int
     */
    public function parseNews(ParserNewsInterface $parser): int
    {
        $newsCollection = $parser->getParsedNews();

        if (!empty($newsCollection)) {
            try {
                News::insert($newsCollection);
            } catch (\Exception $exception) {
                /** Here u can write log or throw exceptions */
                return 1;
            }
        }

        return 0;
    }

}