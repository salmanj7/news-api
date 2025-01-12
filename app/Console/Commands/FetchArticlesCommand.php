<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ArticleAggregatorService;

class FetchArticlesCommand extends Command
{
    protected $signature = 'articles:fetch';
    protected $description = 'Fetch articles from news APIs and store them locally';
    protected $articleAggregatorService;

    public function __construct(ArticleAggregatorService $articleAggregatorService)
    {
        parent::__construct();
        $this->articleAggregatorService = $articleAggregatorService;
    }

    public function handle()
    {
        try {
            // Call the methods to fetch articles from the APIs
            $this->articleAggregatorService->fetchFromNewsAPI();
            // $this->articleAggregatorService->fetchFromGuardian();
            // $this->articleAggregatorService->fetchFromNYTimes();
            
            \Log::info('Articles fetched successfully!');
        } catch (\Exception $e) {
            \Log::error('Error fetching articles: ' . $e->getMessage());
        }
    }
}

