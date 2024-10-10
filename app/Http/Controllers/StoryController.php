<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StoryLink;
use App\Models\Story;
use App\Models\Game;

class StoryController extends Controller {
    public function index($gameId) {
        $game = Game::findOrFail($gameId);
        $blocks = $game->story()->with('linkedTo')->get();
        return view('game.storyedit', compact( 'game', 'blocks'));
    }

    public function create(Request $request, $gameId) {
        $request->validate([ 
            'title' => 'required|string|max:50',
            'text' => 'required|string|max:300',
        ]);

        $newstory = Story::create([
            'title' => $request->input('title'),
            'text' => $request->input('text'),
            'game_id' => $gameId
        ]);

        if($request->input('next_stories') != null) {
            foreach($request->input('next_stories') as $next_story){
                StoryLink::create([
                    'story_from_id' => $newstory->id,
                    'story_to_id' => $next_story
                ]);
            }
        }

        return back()->withErrors(['msg' => 'Частиннку додано']);
    }

    function update(Request $request, $id) {
        $story = Story::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:50',
            'text' => 'required|string|max:300',
        ]);

        $story->title = $validated['title'];
        $story->text = $validated['text'];
        $story->save();

        StoryLink::where('story_from_id', '=', $story->id)->delete();
        if($request->input('next_stories') != null) {
            foreach($request->input('next_stories') as $next_story){
                StoryLink::create([
                    'story_from_id' => $story->id,
                    'story_to_id' => $next_story
                ]);
            }
        }

        return back()->withErrors(['msg' => 'Частинку оновленно']);
    }

    public function destroy($id) {
        $story = Story::findOrFail($id);

        $story->delete();
        return back()->withErrors(['msg' => 'Блок видалена']);
    }

    public function story($gameId) {
        $start = Story::whereDoesntHave('linkedFrom')->where('game_id', $gameId)->get();

        return view('game.story', compact('start'));
    }

    public function next(Request $request) {
        $storyId = $request->input('story_id');

        $nextStories = Story::whereHas('linkedFrom', function($query) use ($storyId) {
            $query->where('story_from_id', $storyId);
        })->get();
    
        if ($nextStories->isEmpty())
            return response()->json(['message' => 'Кінець']);
    
        return response()->json(['next_stories' => $nextStories]);
    }

    public function test($gameId){
        $start = Story::whereDoesntHave('linkedFrom')->where('game_id', $gameId)->get()[0];
        $game = Game::findOrFail($gameId);
        $raw = $game->story()->with('linkedTo')->orderBy('id', 'desc')->get();

        $links = [];
        $blocks = [];

        foreach($raw as $block){
            foreach($block->linkedTo as $link){
                $links[] = [
                    'a' => $block->id, 
                    'b' => $link->id
                ];
            }

            $blocks[] = [
                'id' => $block->id,
                'title' => $block->title,
                'text' => $block->text
            ];
        }

        return view('game.storytest', compact('start', 'links', 'blocks', 'raw'));
    }


    function updateajax(Request $request){
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:50',
                'text' => 'required|string|max:300',
            ]);

            $story = Story::findOrFail($request->id);

            $story->title = $validated['title'];
            $story->text = $validated['text'];
            $story->save();

            return response()->json(['success' => true]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        }
    }

}