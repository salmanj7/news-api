<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\UserPreference;
use Illuminate\Http\Request;

class UserPreferenceController extends Controller
{
    public function setPreferences(Request $request)
    {
        $validated = $request->validate([
            'sources' => 'array',
            'categories' => 'array',
            'authors' => 'array',
        ]);

        $user = $request->user();

        $preference = UserPreference::updateOrCreate(
            ['user_id' => $user->id],
            $validated
        );

        return $preference;
    }

    public function getFeed(Request $request)
    {
        $user = $request->user();

        $preferences = $user->preferences;
        if (!$preferences) {
            return response()->json(['message' => 'No preferences found'], 404);
        }

        $articles = Article::query()
            ->when($preferences->sources, fn($q) => $q->whereIn('source', $preferences->sources))
            ->when($preferences->categories, fn($q) => $q->whereIn('category', $preferences->categories))
            ->paginate(10);

        return $articles;
    }
}
