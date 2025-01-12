<?php

namespace App\Services;

use App\Models\Article;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class ArticleAggregatorService
{
    public function fetchFromNewsAPI()
    {
        $url = 'https://newsapi.org/v2/top-headlines';
        $response = Http::get($url, [
            'apiKey' => env('NEWS_API_KEY'),
            'country' => 'us',
            'pageSize' => 10,
        ]);
        \Log::info('Fetching articles from NewsAPI...');
    
        if (!$response->successful()) {
            \Log::error('NewsAPI Fetch Failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return;
        }
    
        $this->storeArticles($response->json()['articles'], 'NewsAPI');
    }

    // public function fetchFromGuardian()
    // {
    //     $url = 'https://content.guardianapis.com/search';
    //     $response = Http::get($url, [
    //         'api-key' => env('GUARDIAN_API_KEY'),
    //         'page-size' => 10,
    //     ]);

    //     if ($response->successful()) {
    //         $articles = collect($response->json()['response']['results'])->map(function ($article) {
    //             return [
    //                 'title' => $article['webTitle'],
    //                 'content' => null,
    //                 'source' => 'The Guardian',
    //                 'category' => null,
    //                 'author' => null,
    //                 'published_at' => $article['webPublicationDate'],
    //             ];
    //         });
    //         $this->storeArticles($articles->toArray(), 'The Guardian');
    //     }
    // }

    // public function fetchFromNYTimes()
    // {
    //     $url = 'https://api.nytimes.com/svc/topstories/v2/home.json';
    //     $response = Http::get($url, [
    //         'api-key' => env('NYT_API_KEY'),
    //     ]);

    //     if ($response->successful()) {
    //         $articles = collect($response->json()['results'])->map(function ($article) {
    //             return [
    //                 'title' => $article['title'],
    //                 'content' => $article['abstract'],
    //                 'source' => 'New York Times',
    //                 'category' => $article['section'],
    //                 'author' => $article['byline'],
    //                 'published_at' => $article['published_date'],
    //             ];
    //         });
    //         $this->storeArticles($articles->toArray(), 'New York Times');
    //     }
    // }

    private function storeArticles(array $articles, $source)
    {
        foreach ($articles as $article) {
            Article::updateOrCreate(
                ['title' => $article['title'], 'source' => $source],
                [
                    'content' => $article['content'] ?? 'No content available',
                    'category' => $article['category'] ?? 'General',
                    'author' => $article['author'] ?? 'Unknown',
                    'published_at' => Carbon::parse($article['published_at']),
                ]
            );
        }
    }
}
