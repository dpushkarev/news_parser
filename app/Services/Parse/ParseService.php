<?php


namespace App\Services\Parse;

class ParseService
{
    /**
     * @param ParserNewsInterface[] $parserNews
     * @return int
     */
    public function parseNews(array $parserNews): int
    {
        foreach ($parserNews as $parser) {
            $status = $parser->parse();
            /** Here u can write log or throw exceptions */
            if ($status) return 1;
        }

        return 0;
    }

}