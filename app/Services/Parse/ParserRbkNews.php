<?php


namespace App\Services\Parse;


use App\Models\NewsSource;
use Illuminate\Support\Carbon;
use PHPHtmlParser\Dom\Node\HtmlNode;

/**
 * Class ParserRbkNews
 * @package App\Services\Parse
 */
class ParserRbkNews extends ParserNewsAbstract
{
    const SOURCE_URL = 'https://www.rbc.ru';

    protected static $allowHosts = [
        'sportrbc.ru',
        'www.rbc.ru'
    ];

    protected function getNewsIterator(): \ArrayIterator
    {
        $loadedData = $this->domObject->loadFromUrl(static::SOURCE_URL);
        $newsBlock = $loadedData->find('.js-news-feed-list .news-feed__item');

        return $newsBlock->getIterator();
    }

    protected function getDetailNews(HtmlNode $block): ?HtmlNode
    {
        static $detail;

        $id = $this->getExternalId($block);

        if (!isset($detail[$id])) {
            $newsBody = $this->domObject->loadFromUrl($this->getLink($block));
            $detail[$id] = $newsBody->find('[data-id="' . $id . '"]')->offsetGet(0);
        }

        return $detail[$id];
    }

    protected  function getImageUrl(HtmlNode $block):?string
    {
        if (!$this->isPartner($block)) {
            $detail = $this->getDetailNews($block);

            if($detail) {
                $image = $detail->find('.article__main-image__image') ?? null;

                if($image->count()){
                    return $image->getAttribute('src') ?? null;
                }
            }

        }

        return null;
    }

    protected function getDescription(HtmlNode $block): string
    {
        $description = '';

        if (!$this->isPartner($block)) {
            $detail = $this->getDetailNews($block);

            if ($detail) {
                foreach ($detail->find('p') as $p){
                    $description .= $p->text . ' ';
                }
            }
        }

        return trim($description);
    }

    protected function getPublishedTime(HtmlNode $node): Carbon
    {
        return Carbon::createFromTimestamp($node->getAttribute('data-modif'));
    }

    /**
     * @param HtmlNode $node
     * @return string
     * @throws \PHPHtmlParser\Exceptions\ChildNotFoundException
     */
    protected function getTitle(HtmlNode $node): string
    {
        return $node->find('.news-feed__item__title')->text;
    }

    /**
     * @param HtmlNode $node
     * @return string
     */
    protected function getLink(HtmlNode $node): string
    {
        return $node->getAttribute('href');
    }

    /**
     * @param HtmlNode $node
     * @return string
     */
    protected function getExternalId(HtmlNode $node): string
    {
        return explode('id_newsfeed_', $node->getAttribute('id'))[1];
    }

    /**
     * @param HtmlNode $block
     * @return bool
     */
    protected function isPartner(HtmlNode $block): bool
    {
        $newsSource = $block->getAttribute('href');

        return  !in_array(parse_url($newsSource)['host'], static::$allowHosts);
    }

    /**
     * @return NewsSource
     */
    protected function getNewsSource(): NewsSource
    {
        return $this->newsSourceRepository->getByCode(NewsSource::RBK_CODE);
    }
}