<?php


namespace App\Services\Parse;


use App\Models\News;
use App\Models\NewsSource;
use App\Repositories\NewsSourceRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use PHPHtmlParser\Dom;
use PHPHtmlParser\Dom\Node\HtmlNode;

/**
 * Class ParserNewsAbstract
 * @package App\Services\Parse
 */
abstract class ParserNewsAbstract implements ParserNewsInterface
{
    static $NEWS_LIMIT = 15;

    protected $domObject;
    protected $newsSourceRepository;

    public function __construct(NewsSourceRepository $newsSourceRepository)
    {
        $this->domObject = new Dom();
        $this->newsSourceRepository = $newsSourceRepository;
    }

    public function parse(): int
    {
        $newsCollection = [];
        $newsSource = $this->getNewsSource();
        $existingNews = $newsSource->news->pluck('id', 'external_id');

        foreach ($this->getNewsIterator() as $block) {
            try {
                $externalId = $this->getExternalId($block);
                if ($existingNews->has($externalId)) {
                    continue;
                }

                $isPartner = $this->isPartner($block);

                $news = [
                    'news_source_id' => $newsSource->id,
                    'external_id' => $this->getExternalId($block),
                    'url' => $this->getLink($block),
                    'title' => $this->getTitle($block),
                    'by_partner' => $isPartner,
                    'published_time' => $this->getPublishedTime($block),
                    'image_url' => $this->getImageUrl($block),
                    'description' => $this->getDescription($block),
                    'updated_at' => Carbon::now(),
                    'created_at' => Carbon::now(),
                ];

                $newsCollection[] = $news;

                if (sizeof($newsCollection) >= static::$NEWS_LIMIT) {
                    break;
                }
            } catch (\Exception $exception) {
                /** write into log */
                Log::error($exception->getMessage());
                continue;
            }
        }

        try {
            News::insert($newsCollection);
        } catch (\Exception $exception) {
            /** write into log */
            Log::error($exception->getMessage());
            return 1;
        }

        return 0;
    }

    /**
     * @param HtmlNode $block
     * @return HtmlNode|null
     */
    abstract protected function getDetailNews(HtmlNode $block): ?HtmlNode;

    /**
     * @param HtmlNode $block
     * @return string|null
     */
    abstract protected function getImageUrl(HtmlNode $block): ?string;

    /**
     * @param HtmlNode $block
     * @return string
     */
    abstract protected function getDescription(HtmlNode $block): string;

    /**
     * @param HtmlNode $node
     * @return Carbon
     */
    abstract protected function getPublishedTime(HtmlNode $node): Carbon;

    /**
     * @param HtmlNode $node
     * @return string
     */
    abstract protected function getTitle(HtmlNode $node): string;

    /**
     * @param HtmlNode $node
     * @return string
     */
    abstract protected function getLink(HtmlNode $node): string;


    /**
     * @param HtmlNode $node
     * @return string
     */
    abstract protected function getExternalId(HtmlNode $node): string;

    /**
     * @param HtmlNode $block
     * @return bool
     */
    abstract protected function isPartner(HtmlNode $block): bool;

    /**
     * @return NewsSource
     */
    abstract protected function getNewsSource(): NewsSource;

}