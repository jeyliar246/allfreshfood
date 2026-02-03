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

            // Fetch products from DB with all relevant fields
            $products = [];
            try {
                $products = Product::select(
                    'id', 'name', 'description', 'cuisine', 'price', 'image', 'stock',
                    'category', 'halal', 'vegan', 'gluten_free', 'organic', 'fair_trade', 'non_GMO'
                )
                    ->where('status', 'active')
                    // ->where('stock', '>', 0)
                    ->get()
                    ->map(function ($product) {
                        return [
                            'id' => $product->id,
                            'name' => $product->name,
                            'description' => $product->description ?? '',
                            'cuisine' => $product->cuisine ?? '',
                            'category' => $product->category ?? '',
                            'price' => $product->price ?? 0,
                            'image' => $product->image ? asset('uploads/' . $product->image) : null,
                            'stock' => $product->stock ?? 0,
                            'halal' => $product->halal ?? false,
                            'vegan' => $product->vegan ?? false,
                            'gluten_free' => $product->gluten_free ?? false,
                            'organic' => $product->organic ?? false,
                            'fair_trade' => $product->fair_trade ?? false,
                            'non_GMO' => $product->non_GMO ?? false,
                        ];
                    })
                    ->toArray();
            } catch (\Exception $e) {
                Log::warning('Could not fetch products: ' . $e->getMessage());
                $products = [];
            }

            // Get Claude AI response with recipe details
            $aiResponse = $this->getClaudeResponse($message, $products);

            // Extract ingredients from AI response
            $ingredientsList = $this->extractIngredientsFromResponse($aiResponse);

            // Find relevant products based on query AND ingredients
            $relevantProducts = $this->findRelevantProducts($message, $products, $ingredientsList);

            // Get YouTube videos
            $youtubeVideos = $this->searchYouTubeVideos($message);

            // Format complete response
            $completeResponse = $this->formatCompleteResponse(
                $aiResponse,
                $youtubeVideos,
                $relevantProducts,
                $message
            );

            return response()->json([
                'reply' => $completeResponse,
                'products' => $relevantProducts,
                'ingredients' => $ingredientsList,
            ]);
        } catch (\Exception $e) {
            Log::error('AI Chat Error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'reply' => 'I apologize, but I\'m experiencing technical difficulties. Please try again in a moment.',
            ]);
        }
    }

    private function getClaudeResponse($message, $products)
    {
        try {
            $apiKey = env('ANTHROPIC_API_KEY');

            if (empty($apiKey)) {
                Log::warning('Anthropic API key not configured');
                return $this->generateFallbackResponse($message, $products);
            }

            // Build detailed product context
            $productContext = $this->buildProductContext($products);

            // Enhanced system prompt for detailed recipe responses
            $systemPrompt = "You are an expert culinary AI assistant for an online food marketplace. Your role is to provide detailed recipe information and match ingredients with available products.

Available Products in Store:
{$productContext}

When a user asks for a recipe, you MUST provide:

1. **Recipe Introduction**: Brief overview of the dish (2-3 sentences)

2. **Complete Ingredients List**: List ALL ingredients with exact measurements in this format:
   INGREDIENTS:
   - [Quantity] [Unit] [Ingredient name]
   Example:
   - 2 cups long-grain rice
   - 3 tablespoons tomato paste
   - 1 large onion, diced
   
3. **Cooking Instructions**: Step-by-step preparation method (numbered steps)

4. **Cooking Tips**: 1-2 helpful tips for success

5. **Dietary Information**: Note if the recipe is naturally vegan, halal, gluten-free, etc.

IMPORTANT FORMATTING RULES:
- Use clear section headers with ** for bold
- List each ingredient on a new line with a dash (-)
- Include precise measurements (cups, tablespoons, teaspoons, pounds, etc.)
- Be specific about ingredient preparation (diced, minced, crushed, etc.)
- Keep the total response under 600 words
- Focus on practical, authentic recipes";

            $response = Http::withHeaders([
                'x-api-key' => $apiKey,
                'Content-Type' => 'application/json',
                'anthropic-version' => '2023-06-01',
            ])
                ->timeout(30)
                ->retry(3, 200)
                ->post('https://api.anthropic.com/v1/messages', [
                    'model' => 'claude-sonnet-4-20250514',
                    'max_tokens' => 2000,
                    'system' => $systemPrompt,
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => trim($message)
                        ],
                    ],
                ]);

            if ($response->successful()) {
                $result = $response->json();

                if (isset($result['content'][0]['text'])) {
                    return trim($result['content'][0]['text']);
                } else {
                    Log::error('Claude API returned unexpected structure: ' . $response->body());
                    return $this->generateFallbackResponse($message, $products);
                }
            } else {
                Log::error('Claude API Error: ' . $response->status() . ' - ' . $response->body());
                return $this->generateFallbackResponse($message, $products);
            }
        } catch (\Exception $e) {
            Log::error('Claude API Exception: ' . $e->getMessage());
            return $this->generateFallbackResponse($message, $products);
        }
    }

    private function buildProductContext($products)
    {
        if (empty($products)) {
            return "No products currently available in inventory.";
        }

        $context = "We have " . count($products) . " products available:\n\n";
        
        foreach (array_slice($products, 0, 50) as $product) {
            $tags = [];
            if ($product['halal']) $tags[] = 'Halal';
            if ($product['vegan']) $tags[] = 'Vegan';
            if ($product['gluten_free']) $tags[] = 'Gluten-Free';
            if ($product['organic']) $tags[] = 'Organic';
            
            $tagsStr = !empty($tags) ? ' [' . implode(', ', $tags) . ']' : '';
            
            $context .= "- {$product['name']} ({$product['category']} - {$product['cuisine']}): {$product['description']} - £{$product['price']}{$tagsStr}\n";
        }

        return $context;
    }

    private function extractIngredientsFromResponse($aiResponse)
    {
        $ingredients = [];
        
        // Look for ingredient section in the response
        if (preg_match('/INGREDIENTS?:?\s*(.*?)(?=\n\n|INSTRUCTIONS?:|STEPS?:|COOKING|$)/is', $aiResponse, $matches)) {
            $ingredientSection = $matches[1];
            
            // Extract individual ingredients (lines starting with -, •, or numbers)
            preg_match_all('/[-•\d]+\.?\s*(.+?)(?:\n|$)/m', $ingredientSection, $ingredientMatches);
            
            foreach ($ingredientMatches[1] as $ingredient) {
                $ingredient = trim($ingredient);
                if (!empty($ingredient)) {
                    // Clean up the ingredient (remove measurements for matching)
                    $cleanIngredient = $this->cleanIngredientForMatching($ingredient);
                    if (!empty($cleanIngredient)) {
                        $ingredients[] = [
                            'full' => $ingredient,
                            'clean' => $cleanIngredient
                        ];
                    }
                }
            }
        }

        return $ingredients;
    }

    private function cleanIngredientForMatching($ingredient)
    {
        // Remove common measurements and numbers
        $ingredient = preg_replace('/\d+(\.\d+)?/', '', $ingredient);
        $ingredient = preg_replace('/(cups?|tablespoons?|teaspoons?|tbsp|tsp|oz|ounces?|pounds?|lbs?|grams?|kg|ml|liters?)/i', '', $ingredient);
        
        // Remove preparation instructions in parentheses
        $ingredient = preg_replace('/\([^)]*\)/', '', $ingredient);
        $ingredient = preg_replace('/\b(diced|chopped|minced|sliced|crushed|fresh|dried|ground|whole|large|small|medium)\b/i', '', $ingredient);
        
        // Clean up extra spaces and common words
        $ingredient = trim($ingredient);
        $ingredient = preg_replace('/\s+/', ' ', $ingredient);
        
        return strtolower($ingredient);
    }

    private function findRelevantProducts($query, $products, $ingredientsList = [])
    {
        if (empty($products)) {
            return [];
        }

        $query = strtolower($query);
        $relevantProducts = [];

        // Extract key ingredients for matching
        $ingredientKeywords = [];
        foreach ($ingredientsList as $ingredient) {
            $words = explode(' ', $ingredient['clean']);
            $ingredientKeywords = array_merge($ingredientKeywords, array_filter($words, function($word) {
                return strlen($word) > 3; // Only use words longer than 3 chars
            }));
        }
        $ingredientKeywords = array_unique($ingredientKeywords);

        foreach ($products as $product) {
            $productName = strtolower($product['name'] ?? '');
            $productDesc = strtolower($product['description'] ?? '');
            $productCuisine = strtolower($product['cuisine'] ?? '');
            $productCategory = strtolower($product['category'] ?? '');

            $score = 0;

            // 1. Check if product name matches ingredient keywords (HIGH PRIORITY)
            foreach ($ingredientKeywords as $keyword) {
                if (strpos($productName, $keyword) !== false) {
                    $score += 15; // High score for ingredient match
                }
                if (strpos($productDesc, $keyword) !== false) {
                    $score += 8;
                }
                if (strpos($productCategory, $keyword) !== false) {
                    $score += 10;
                }
            }

            // 2. Check for exact matches in product name with query
            if (strpos($productName, $query) !== false) {
                $score += 10;
            }

            // 3. Check for keyword matches from query
            $queryWords = explode(' ', $query);
            foreach ($queryWords as $word) {
                $word = trim($word);
                if (strlen($word) < 3) continue;

                if (strpos($productName, $word) !== false) {
                    $score += 5;
                }
                if (strpos($productDesc, $word) !== false) {
                    $score += 3;
                }
                if (strpos($productCuisine, $word) !== false) {
                    $score += 4;
                }
            }

            // 4. Cuisine matching
            $cuisineKeywords = ['italian', 'chinese', 'indian', 'mexican', 'thai', 'japanese', 'mediterranean', 'american', 'african', 'nigerian', 'caribbean'];
            foreach ($cuisineKeywords as $cuisine) {
                if (strpos($query, $cuisine) !== false && strpos($productCuisine, $cuisine) !== false) {
                    $score += 8;
                }
            }

            // 5. Category-based matching for common ingredients
            $categoryKeywords = [
                'rice' => ['grain', 'rice', 'staple'],
                'tomato' => ['vegetable', 'produce', 'tomato'],
                'onion' => ['vegetable', 'produce', 'onion'],
                'spice' => ['spice', 'seasoning', 'condiment'],
                'oil' => ['oil', 'cooking oil', 'fat'],
                'meat' => ['meat', 'protein', 'chicken', 'beef', 'lamb'],
                'fish' => ['fish', 'seafood', 'protein'],
            ];

            foreach ($categoryKeywords as $keyword => $categories) {
                if (strpos($query, $keyword) !== false) {
                    foreach ($categories as $category) {
                        if (strpos($productCategory, $category) !== false || strpos($productName, $category) !== false) {
                            $score += 6;
                        }
                    }
                }
            }

            // 6. Dietary preferences boost
            if (strpos($query, 'vegan') !== false && $product['vegan']) {
                $score += 5;
            }
            if (strpos($query, 'halal') !== false && $product['halal']) {
                $score += 5;
            }
            if (strpos($query, 'gluten free') !== false && $product['gluten_free']) {
                $score += 5;
            }

            if ($score > 0) {
                $product['relevance_score'] = $score;
                $relevantProducts[] = $product;
            }
        }

        // Sort by relevance score and return top 8
        usort($relevantProducts, function ($a, $b) {
            return $b['relevance_score'] <=> $a['relevance_score'];
        });

        return array_slice($relevantProducts, 0, 8);
    }

    private function searchYouTubeVideos($query)
    {
        try {
            return $this->youtubeService->searchCookingVideos($query, 3);
        } catch (\Exception $e) {
            Log::error('YouTube search error: ' . $e->getMessage());

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

        $recipeKeywords = ['recipe', 'cook', 'make', 'prepare', 'how to', 'ingredients for'];

        $cleanQuery = $query;
        foreach ($recipeKeywords as $keyword) {
            $cleanQuery = str_replace($keyword, '', $cleanQuery);
        }

        $commonWords = ['for', 'a', 'an', 'the', 'some', 'tonight', 'today', 'dinner', 'lunch', 'breakfast'];
        $words = explode(' ', trim($cleanQuery));
        $filteredWords = array_filter($words, function ($word) use ($commonWords) {
            return !in_array(trim($word), $commonWords) && strlen(trim($word)) > 2;
        });

        $result = implode(' ', array_slice($filteredWords, 0, 3));
        return !empty($result) ? $result : 'cooking';
    }

    private function formatCompleteResponse($aiResponse, $youtubeVideos, $relevantProducts, $originalQuery)
    {
        $response = $aiResponse;

        // Add relevant products section
        if (!empty($relevantProducts)) {
            $response .= "\n\n---\n\n🛒 **Available Products from Our Store**\n\n";
            $response .= "Here are ingredients and products we have that match your recipe:\n\n";
            
            foreach (array_slice($relevantProducts, 0, 6) as $index => $product) {
                $tags = $this->formatProductTags($product);
                $response .= ($index + 1) . ". **{$product['name']}** - £{$product['price']}{$tags}\n";
                $response .= "   _{$product['category']}";
                if (!empty($product['cuisine'])) {
                    $response .= " • {$product['cuisine']}";
                }
                $response .= "_\n";
                if (!empty($product['description'])) {
                    $response .= "   " . substr($product['description'], 0, 100);
                    if (strlen($product['description']) > 100) {
                        $response .= "...";
                    }
                    $response .= "\n";
                }
                $response .= "\n";
            }
            $response .= "_You can add these items to your cart from the browse page!_";
        }

        // Add YouTube video suggestions
        if ($youtubeVideos && isset($youtubeVideos['videos']) && !empty($youtubeVideos['videos'])) {
            $response .= "\n\n---\n\n🎥 **Video Tutorials**\n\n";
            foreach (array_slice($youtubeVideos['videos'], 0, 2) as $index => $video) {
                $response .= "• [" . $video['title'] . "](" . $video['url'] . ")\n";
            }
        }

        return $response;
    }

    private function formatProductTags($product)
    {
        $tags = [];
        if ($product['halal']) $tags[] = 'Halal';
        if ($product['vegan']) $tags[] = 'Vegan';
        if ($product['gluten_free']) $tags[] = 'Gluten-Free';
        if ($product['organic']) $tags[] = 'Organic';
        
        return !empty($tags) ? ' _[' . implode(', ', $tags) . ']_' : '';
    }

    private function generateFallbackResponse($message, $products)
    {
        $message = strtolower($message);

        // Check if it's a recipe request
        if (strpos($message, 'recipe') !== false || strpos($message, 'cook') !== false || strpos($message, 'make') !== false) {
            $dishName = $this->extractDishName($message);
            
            return "🍳 **Recipe Request: " . ucwords($dishName) . "**\n\n" .
                   "I'd love to help you with a detailed recipe for " . ucwords($dishName) . "! " .
                   "However, I'm currently experiencing difficulties connecting to my recipe database.\n\n" .
                   "**Here's what I can help you with:**\n" .
                   "• Complete ingredient lists with measurements\n" .
                   "• Step-by-step cooking instructions\n" .
                   "• Matching products from our store\n" .
                   "• Cooking tips and techniques\n\n" .
                   "Please try your request again in a moment, or browse our available products below!";
        }

        return "👋 **Welcome to Your AI Cooking Assistant!**\n\n" .
               "I'm here to help you discover amazing recipes with detailed ingredient lists and cooking instructions!\n\n" .
               "**Try asking me:**\n" .
               "• \"Give me a recipe for jollof rice\"\n" .
               "• \"How do I make pasta carbonara?\"\n" .
               "• \"Show me a vegetarian curry recipe\"\n" .
               "• \"What can I cook with chicken tonight?\"\n\n" .
               "I'll provide complete ingredients, cooking steps, and match them with products from our store!";
    }
}