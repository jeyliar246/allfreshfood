<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\YouTubeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiController extends Controller
{
    protected $youtubeService;

    public function __construct(YouTubeService $youtubeService)
    {
        $this->youtubeService = $youtubeService;
    }

    public function ai()
    {
        return view('home.ai');
    }

    public function chat(Request $request)
    {
        try {
            $message = $request->input('message');

            if (empty($message)) {
                return response()->json([
                    'reply' => 'Please provide a message.',
                ]);
            }

            // Fetch products from DB with error handling
            $products = [];
            try {
                $products = Product::select('id', 'name', 'description', 'cuisine', 'price', 'image', 'stock')
                    ->where('status', 'active')
                    ->get()
                    ->map(function ($product) {
                        return [
                            'id' => $product->id,
                            'name' => $product->name,
                            'description' => $product->description ?? '',
                            'cuisine' => $product->cuisine ?? '',
                            'price' => $product->price ?? 0,
                            'image' => $product->image ? asset('uploads/' . $product->image) : null,
                            'stock' => $product->stock ?? 0,
                        ];
                    })
                    ->toArray();
            } catch (\Exception $e) {
                Log::warning('Could not fetch products: ' . $e->getMessage());
                $products = [];
            }

            // Get OpenAI response
            $aiResponse = $this->getOpenAIResponse($message, $products);

            // Get YouTube videos for cooking tutorials
            $youtubeVideos = $this->searchYouTubeVideos($message);

            // Find relevant products based on the query
            $relevantProducts = $this->findRelevantProducts($message, $products);

            // Combine all responses
            $completeResponse = $this->formatCompleteResponse($aiResponse, $youtubeVideos, $relevantProducts, $message);

            return response()->json([
                'reply' => $completeResponse,
                // Expose relevant products so the UI can render add-to-cart buttons
                'products' => $relevantProducts,
            ]);
        } catch (\Exception $e) {
            Log::error('AI Chat Error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'reply' => 'I apologize, but I\'m experiencing technical difficulties. Please try again in a moment.',
            ]);
        }
    }

    private function getOpenAIResponse($message, $products)
    {
        try {
            $apiKey = env('OPEN_AI_KEY');

            if (empty($apiKey)) {
                Log::warning('OpenAI API key not configured');
                return $this->generateFallbackResponse($message, $products);
            }

            // Build product context - handle empty products array
            $productContext = '';
            if (!empty($products)) {
                foreach ($products as $product) {
                    $productContext .= "- {$product['name']} ({$product['cuisine']}): {$product['description']} - \${$product['price']}\n";
                }
            } else {
                $productContext = "No products currently available in inventory.";
            }

            // Create enhanced system prompt for recipe assistance
            $systemPrompt = "You are a professional culinary AI assistant for an online food marketplace. Your role is to:

            1. Help users with recipes, cooking techniques, and meal planning
            2. Provide detailed ingredient lists with quantities for requested recipes
            3. Suggest cooking methods and preparation tips
            4. Recommend products from our available inventory that match user needs

            Available Products in our store:
            {$productContext}

            Guidelines:
            - Always provide complete ingredient lists with measurements when discussing recipes
            - Give helpful cooking tips and techniques
            - Be enthusiastic and encouraging about cooking
            - Keep responses informative but concise (under 400 words)
            - Focus on practical, achievable recipes
            - Suggest alternatives when specific ingredients aren't available in our store

            User Query: {$message}

            Please provide a helpful response that includes:
            1. A brief introduction addressing their query
            2. If it's a recipe request: complete ingredient list with quantities
            3. Basic preparation steps or cooking tips
            4. Any relevant suggestions from our available products";

            // $response = Http::timeout(30)
            //     ->withHeaders([
            //         'Authorization' => 'Bearer ' . $apiKey,
            //         'Content-Type' => 'application/json',
            //     ])
            //     ->post('https://api.openai.com/v1/chat/completions', [
            //         'model' => 'gpt-5',
            //         'messages' => [
            //             [
            //                 'role' => 'system',
            //                 'content' => $systemPrompt
            //             ],
            //             [
            //                 'role' => 'user',
            //                 'content' => $message
            //             ]
            //         ],
            //         'max_tokens' => 500,
            //         'temperature' => 0.7,
            //     ]);
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                'Content-Type' => 'application/json',
            ])
                ->timeout(30) // Prevent hanging requests
                ->retry(3, 200) // Retry failed requests up to 3 times with 200ms delay
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o', // Use GPT-4o (faster + cheaper + highly capable)
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => $systemPrompt ?? 'You are a helpful and intelligent AI assistant.'
                        ],
                        [
                            'role' => 'user',
                            'content' => trim($message)
                        ],
                    ],
                    'max_tokens' => 800, // More room for responses
                    'temperature' => 0.6, // Slightly lower for more consistent answers
                    'top_p' => 1,
                    'frequency_penalty' => 0.2, // Reduce repetition
                    'presence_penalty' => 0.2, // Encourage topic diversity
                ]);


            if ($response->successful()) {
                $result = $response->json();

                if (isset($result['choices'][0]['message']['content'])) {
                    return trim($result['choices'][0]['message']['content']);
                } else {
                    Log::error('OpenAI API returned unexpected structure: ' . $response->body());
                    return $this->generateFallbackResponse($message, $products);
                }
            } else {
                Log::error('OpenAI API Error: ' . $response->status() . ' - ' . $response->body());
                return $this->generateFallbackResponse($message, $products);
            }
        } catch (\Exception $e) {
            Log::error('OpenAI API Exception: ' . $e->getMessage());
            return $this->generateFallbackResponse($message, $products);
        }
    }

    private function searchYouTubeVideos($query)
    {
        try {
            return $this->youtubeService->searchCookingVideos($query, 3);
        } catch (\Exception $e) {
            Log::error('YouTube search error: ' . $e->getMessage());

            // Fallback YouTube search URLs
            $searchTerm = $this->extractDishName($query);
            $searchQuery = urlencode($searchTerm . ' recipe cooking tutorial');

            return [
                'search_url' => "https://www.youtube.com/results?search_query=" . $searchQuery,
                'videos' => [
                    [
                        'title' => 'Search: ' . ucwords($searchTerm) . ' Recipe',
                        'url' => "https://www.youtube.com/results?search_query=" . urlencode($searchTerm . ' recipe'),
                        'channel' => 'YouTube Search',
                        'description' => 'Click to search for ' . $searchTerm . ' recipe videos'
                    ]
                ]
            ];
        }
    }

    private function extractDishName($query)
    {
        $query = strtolower($query);

        // Common recipe keywords to help extract the dish name
        $recipeKeywords = ['recipe', 'cook', 'make', 'prepare', 'how to', 'ingredients for'];

        // Remove common words and extract the main dish name
        $cleanQuery = $query;
        foreach ($recipeKeywords as $keyword) {
            $cleanQuery = str_replace($keyword, '', $cleanQuery);
        }

        // Clean up extra spaces and common words
        $commonWords = ['for', 'a', 'an', 'the', 'some', 'tonight', 'today', 'dinner', 'lunch', 'breakfast'];
        $words = explode(' ', trim($cleanQuery));
        $filteredWords = array_filter($words, function ($word) use ($commonWords) {
            return !in_array(trim($word), $commonWords) && strlen(trim($word)) > 2;
        });

        $result = implode(' ', array_slice($filteredWords, 0, 3));
        return !empty($result) ? $result : 'cooking';
    }

    private function findRelevantProducts($query, $products)
    {
        if (empty($products)) {
            return [];
        }

        $query = strtolower($query);
        $relevantProducts = [];

        foreach ($products as $product) {
            $productName = strtolower($product['name'] ?? '');
            $productDesc = strtolower($product['description'] ?? '');
            $productCuisine = strtolower($product['cuisine'] ?? '');

            // Calculate relevance score
            $score = 0;

            // Check for exact matches in product name
            if (strpos($productName, $query) !== false) {
                $score += 10;
            }

            // Check for keyword matches
            $queryWords = explode(' ', $query);
            foreach ($queryWords as $word) {
                $word = trim($word);
                if (strlen($word) < 3) continue;

                if (strpos($productName, $word) !== false) {
                    $score += 3;
                }
                if (strpos($productDesc, $word) !== false) {
                    $score += 2;
                }
                if (strpos($productCuisine, $word) !== false) {
                    $score += 2;
                }
            }

            // Check for cuisine-specific queries
            $cuisineKeywords = ['italian', 'chinese', 'indian', 'mexican', 'thai', 'japanese', 'mediterranean', 'american'];
            foreach ($cuisineKeywords as $cuisine) {
                if (strpos($query, $cuisine) !== false && strpos($productCuisine, $cuisine) !== false) {
                    $score += 5;
                }
            }

            if ($score > 0) {
                $product['relevance_score'] = $score;
                $relevantProducts[] = $product;
            }
        }

        // Sort by relevance score and return top 6
        usort($relevantProducts, function ($a, $b) {
            return $b['relevance_score'] <=> $a['relevance_score'];
        });

        return array_slice($relevantProducts, 0, 6);
    }

    private function formatCompleteResponse($aiResponse, $youtubeVideos, $relevantProducts, $originalQuery)
    {
        $response = $aiResponse;

        // Add YouTube video suggestions
        if ($youtubeVideos && isset($youtubeVideos['videos']) && !empty($youtubeVideos['videos'])) {
            $response .= "\n\n🎥 **Cooking Video Tutorials:**\n";
            $response .= "Here are some helpful YouTube videos to guide you:\n";
            foreach (array_slice($youtubeVideos['videos'], 0, 2) as $index => $video) {
                $response .= "• [" . $video['title'] . "](" . $video['url'] . ")\n";
            }
        }

        // Add relevant products
        if (!empty($relevantProducts)) {
            $response .= "\n\n🛒 **Recommended Products from Our Store:**\n";
            foreach (array_slice($relevantProducts, 0, 3) as $product) {
                $response .= "• **{$product['name']}** ({$product['cuisine']}) - \${$product['price']}\n";
                if (!empty($product['description'])) {
                    $response .= "  {$product['description']}\n\n";
                }
            }
            $response .= "*You can add these items to your cart from our browse page!*";
        }

        return $response;
    }

    private function generateFallbackResponse($message, $products)
    {
        $message = strtolower($message);

        // Enhanced fallback responses with ingredients and cooking tips
        if (strpos($message, 'dinner') !== false || strpos($message, 'tonight') !== false) {
            if (!empty($products)) {
                $randomProduct = collect($products)->random();
                return "🍽️ **Perfect for Tonight's Dinner!**\n\nI recommend **{$randomProduct['name']}** - a delicious {$randomProduct['cuisine']} dish for \${$randomProduct['price']}.\n\n{$randomProduct['description']}\n\n**Basic Ingredients You'll Need:**\n• Main protein or base ingredient\n• Fresh herbs and spices\n• Vegetables for flavor and nutrition\n• Cooking oil or butter\n• Salt and pepper to taste\n\n**Quick Cooking Tip:** Start by preparing all ingredients before cooking (mise en place) for the best results!";
            }
        }

        if (strpos($message, 'vegetarian') !== false || strpos($message, 'vegan') !== false) {
            if (!empty($products)) {
                $vegProducts = collect($products)->filter(function ($product) {
                    return stripos($product['description'], 'vegetarian') !== false ||
                        stripos($product['description'], 'vegan') !== false ||
                        stripos($product['description'], 'vegetables') !== false;
                });

                if ($vegProducts->count() > 0) {
                    $randomVegProduct = $vegProducts->random();
                    return "🌱 **Great Vegetarian Choice!**\n\n**{$randomVegProduct['name']}** is perfect for you! Available for \${$randomVegProduct['price']}.\n\n{$randomVegProduct['description']}\n\n**Essential Vegetarian Ingredients:**\n• Fresh seasonal vegetables\n• Plant-based proteins (tofu, legumes, nuts)\n• Whole grains and pasta\n• Herbs and spices for flavor\n• Healthy fats (olive oil, avocado)\n\n**Cooking Tip:** Layer flavors by sautéing aromatics first!";
                }
            }
        }

        if (strpos($message, 'recipe') !== false || strpos($message, 'cook') !== false) {
            if (!empty($products)) {
                $randomProduct = collect($products)->random();
                return "👨‍🍳 **Let's Cook Something Amazing!**\n\nHow about trying **{$randomProduct['name']}**? This {$randomProduct['cuisine']} dish is perfect for cooking and costs \${$randomProduct['price']}.\n\n{$randomProduct['description']}\n\n**General Cooking Ingredients:**\n• Main ingredient (protein/base)\n• Aromatics (onion, garlic, ginger)\n• Seasonings and spices\n• Liquid (broth, water, wine)\n• Fat for cooking (oil, butter)\n\n**Pro Tip:** Taste as you go and adjust seasoning throughout the cooking process!";
            }
        }

        // Default response with cooking guidance
        if (!empty($products)) {
            $randomProduct = collect($products)->random();
            return "👋 **Welcome to Your AI Cooking Assistant!**\n\nI'd be happy to help you create delicious meals! Here's a popular item from our selection:\n\n**{$randomProduct['name']}** - {$randomProduct['description']}\nAvailable for \${$randomProduct['price']}\n\n**I can help you with:**\n• Complete recipes with ingredient lists\n• Cooking techniques and tips\n• Meal planning and preparation\n• Finding the right ingredients in our store\n\nWhat specific cuisine or dish are you interested in cooking today?";
        }

        return "🍳 **Your Personal Cooking Assistant**\n\nI'm here to help you discover amazing recipes, provide detailed ingredient lists, and guide you through cooking techniques! I can also recommend products from our store and suggest helpful cooking videos.\n\nWhat would you like to cook today? Just tell me the dish name or cuisine type!";
    }
}
