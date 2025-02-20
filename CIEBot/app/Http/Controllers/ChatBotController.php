<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Query;

use OpenAI\Laravel\Facades\OpenAI;

class ChatBotController extends Controller
{

    public function LandingPage()
    {
        return Inertia::render('LandingPage');
    }

    public function ChatPage()
    {

        return Inertia::render('Chatbot');
    }

    public function ContactPage()
    {
        return Inertia::render('Contact');
    }

    public function AboutPage()
    {
        return Inertia::render('About');
    }

    public function applyQueryRules($searchTerm)
    {
        // List of common stop words to remove from the search term
        $stopWords = ["what", "is", "the", "a", "an", "in", "on", "of"];
        $searchWords = explode(' ', strtolower($searchTerm));
        $filteredWords = array_diff($searchWords, $stopWords);
        $modifiedSearchTerm = implode(' ', $filteredWords);

        return $modifiedSearchTerm;
    }

    public function sendChat(Request $request)
        {
            $searchTerm = $request->input('input');
            $searchTermLower = strtolower($searchTerm);

            if ($searchTermLower == "hello") {
                return ['type' => 'text', 'content' => 'Hi! Welcome! How can I help you today?'];
            } elseif ($searchTermLower == "hi") {
                return ['type' => 'text', 'content' => 'Hello there! How can I assist you today?'];
            } elseif ($searchTermLower == null) {
                return ['type' => 'text', 'content' => 'It seems like your message is empty.'];
            }

            $modifiedSearchTerm = $this->applyQueryRules($searchTerm);
            $queryResults = Query::search($modifiedSearchTerm)->get();

            if ($queryResults->isEmpty()) {
                // No matching result found in Algolia, use OpenAI as a fallback
                return $this->useOpenAI($request->input);
            }

            $answerQuery = $queryResults->first()->answer_query;

            return ['type' => 'text', 'content' => $answerQuery];
        }

        private function useOpenAI($prompt)
        {
            // Check if the prompt contains keywords related to IT
            $itKeywords = ['programming', 'coding', 'IT', 'technology', 'software', 'computer'];
            $containsITKeywords = $this->containsKeywords($prompt, $itKeywords);
        
            if (!$containsITKeywords) {
                return ['type' => 'text', 'content' => 'I can only respond to IT-related questions. Please ask something related to programming, technology, or IT.'];
            }

            $result = OpenAI::completions()->create([
                'max_tokens' => 100,
                'model' => 'text-davinci-003',
                'prompt' => $prompt,
            ]);
        
            $response = array_reduce(
                $result->toArray()['choices'],
                fn(String $result, array $choice) => $result . $choice['text'], ""
            );
        
            return ['type' => 'text', 'content' => $response];
        }
        
        private function containsKeywords($haystack, $keywords)
        {
            foreach ($keywords as $keyword) {
                if (stripos($haystack, $keyword) !== false) {
                    return true;
                }
            }
            return false;
        }
        

    // public function theme(Request $request)
    // {
    //     $selectedTheme = $request->input('theme');
    //     dd($selectedTheme);
    //     return response()->json(['message' => 'Theme received successfully', 'theme' => $selectedTheme]);
    // }

}
