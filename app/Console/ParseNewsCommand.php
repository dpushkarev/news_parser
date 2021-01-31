<?php


namespace App\Console;


use App\Services\Parse\ParserRbkNews;
use App\Services\Parse\ParseService;
use Illuminate\Console\Command;

class ParseNewsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "parse:news";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Parse news";

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param ParseService $parseService
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function handle(ParseService $parseService)
    {
        $parsers = [
            app()->make(ParserRbkNews::class),
        ];

        $status =  $parseService->parseNews($parsers);

        $this->comment(sprintf('Status: %d', $status));
    }
}