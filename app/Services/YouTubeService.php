<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class YouTubeService
{
    private $apiKey;
    private $baseUrl = 'https://www.googleapis.com/youtube/v3';

    public function __construct()
    {
        $this->apiKey = env('YOUTUBE_API_KEY'); // Add this to your .env file
    }

    /**
     * Search for YouTube videos related to cooking/recipes
     */
    public function searchCookingVideos($query, $maxResults = 3)
    {
        try {
            if (empty($this->apiKey)) {
                // Fallback to search URLs if no API key
                return $this->generateSearchUrls($query);
            }

            // Clean and enhance the search query for cooking content
            $searchQuery = $this->enhanceSearchQuery($query);

            $response = Http::timeout(10)->get($this->baseUrl . '/search', [
                'part' => 'snippet',
                'q' => $searchQuery,
                'type' => 'video',
                'maxResults' => $maxResults,
                'order' => 'relevance',
                'videoCategoryId' => '26', // Howto & Style category
                'key' => $this->apiKey,
                'safeSearch' => 'strict',
                'relevanceLanguage' => 'en'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $this->formatVideoResults($data['items'] ?? []);
            }

            Log::warning('YouTube API request failed: ' . $response->body());
            
        } catch (\Exception $e) {
            Log::error('YouTube API error: ' . $e->getMessage());
        }

        // Fallback to search URLs
        return $this->generateSearchUrls($query);
    }

    /**
     * Enhance search query for better cooking video results
     */
    private function enhanceSearchQuery($query)
    {
        $query = strtolower(trim($query));
        
        // Add cooking-related terms if not present
        $cookingTerms = ['recipe', 'cooking', 'how to make', 'tutorial'];
        $hasCookingTerm = false;
        
        foreach ($cookingTerms as $term) {
            if (strpos($query, $term) !== false) {
                $hasCookingTerm = true;
                break;
            }
        }
        
        if (!$hasCookingTerm) {
            $query .= ' recipe cooking tutorial';
        }
        
        return $query;
    }

    /**
     * Format YouTube API results into a structured array
     */
    private function formatVideoResults($items)
    {
        $videos = [];
        
        foreach ($items as $item) {
            $videoId = $item['id']['videoId'] ?? null;
            if (!$videoId) continue;
            
            $videos[] = [
                'title' => $item['snippet']['title'] ?? 'Cooking Video',
                'url' => "https://www.youtube.com/watch?v={$videoId}",
                'thumbnail' => $item['snippet']['thumbnails']['medium']['url'] ?? null,
                'channel' => $item['snippet']['channelTitle'] ?? 'Unknown',
                'description' => $this->truncateDescription($item['snippet']['description'] ?? ''),
                'published' => $item['snippet']['publishedAt'] ?? null
            ];
        }
        
        return [
            'videos' => $videos,
            'search_url' => "https://www.youtube.com/results?search_query=" . urlencode($this->enhanceSearchQuery($query))
        ];
    }

    /**
     * Generate fallback search URLs when API is not available
     */
    private function generateSearchUrls($query)
    {
        $enhancedQuery = $this->enhanceSearchQuery($query);
        $baseSearchUrl = "https://www.youtube.com/results?search_query=";
        
        return [
            'videos' => [
                [
                    'title' => 'Search: ' . ucwords($query) . ' Recipe',
                    'url' => $baseSearchUrl . urlencode($query . ' recipe'),
                    'channel' => 'YouTube Search',
                    'description' => 'Click to search for ' . $query . ' recipe videos'
                ],
                [
                    'title' => 'Search: How to Make ' . ucwords($query),
                    'url' => $baseSearchUrl . urlencode('how to make ' . $query),
                    'channel' => 'YouTube Search',
                    'description' => 'Click to search for step-by-step cooking tutorials'
                ],
                [
                    'title' => 'Search: ' . ucwords($query) . ' Cooking Tutorial',
                    'url' => $baseSearchUrl . urlencode($query . ' cooking tutorial'),
                    'channel' => 'YouTube Search',
                    'description' => 'Click to search for detailed cooking tutorials'
                ]
            ],
            'search_url' => $baseSearchUrl . urlencode($enhancedQuery)
        ];
    }

    /**
     * Truncate video description for display
     */
    private function truncateDescription($description, $maxLength = 100)
    {
        if (strlen($description) <= $maxLength) {
            return $description;
        }
        
        return substr($description, 0, $maxLength) . '...';
    }

    /**
     * Get popular cooking channels for recommendations
     */
    public function getPopularCookingChannels()
    {
        return [
            'Tasty' => 'UCJFp8uSYCjXOMnkUyb3CQ3Q',
            'Bon Appétit' => 'UCbpMy0Fg74eXXkvxJrtEn3w',
            'Food Wishes' => 'UCRIZtPl9nb9RiXc9btSTQNw',
            'Joshua Weissman' => 'UChBEbMKI1eCcejTtmI32UEw',
            'Binging with Babish' => 'UCJHA_jMfCvEnv-3kRjTCQXw'
        ];
    }
}