<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StoryLink;
use App\Models\Story;
use App\Models\Game;

class StoryController extends Controller {
    public function index($gameId, Request $request){
        if($request->input('play') == true) {
            $title = Game::findOrFail($gameId)->title;
            // $start = Story::whereDoesntHave('linkedFrom')->where('game_id', $gameId)->get();
            $start = Story::where([['game_id', $gameId], ['completed', 1]])->get();

            return view('game.story', compact('start', 'title'));
        }

        $start = Story::whereDoesntHave('linkedFrom')
            ->where('game_id', $gameId)
            ->get();

        $links = [];
        $blocks = [];

        if(count($start) > 0){
            $game = Game::findOrFail($gameId);
            $raw = $game->story()->with('linkedTo')->orderBy('id', 'desc')->get();

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
                    'text' => $block->text,
                    'completed' => $block->completed
                ];
            }
        }

        return view('game.storyedit', compact('start', 'links', 'blocks', 'gameId'));
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

    function create(Request $request) {
        try{
            $validated = $request->validate([
                'title' => 'required|string|max:50',
                'text' => 'required|string|max:300',
            ]);

            $block = Story::create([
                'game_id' => $request->input('game_id'),
                'title' => $validated['title'],
                'text' => $validated['text'],
                'completed' => 0
            ]);

            if($request->input('id_from') != 0){
                StoryLink::create([
                    'story_from_id' => $request->input('id_from'),
                    'story_to_id' => $block->id
                ]);
            } else {
                $block->completed = 1;
                $block->update();
            }

            return response()->json(['block' => $block]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'errors' => $e
            ], 422);
        }
    }

    function update(Request $request){
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:50',
                'text' => 'required|string|max:300',
            ]);

            $story_id = $request->id;
            $story = Story::findOrFail($story_id);

            $story->title = $validated['title'];
            $story->text = $validated['text'];
            $story->save();

            $add_links = $request->input('add_links', []);
            $del_links = $request->input('del_links', []);
            if(count($add_links) > count($del_links)){
                for($i = 0; $i < count($del_links); $i++){
                    $to_edit = StoryLink::where([
                            ['story_from_id', '=', $story_id],
                            ['story_to_id', '=', $del_links[$i]]
                        ])->first();
                    $to_edit->story_to_id = $add_links[$i];
                    $to_edit->save();
                    unset($add_links[$i]);
                }

                foreach($add_links as $add)
                    StoryLink::create([
                            'story_from_id' => $story_id,
                            'story_to_id' => $add
                        ]);
            } else {
                for($i = 0; $i < count($add_links); $i++){
                    $to_edit = StoryLink::where([
                            ['story_from_id', '=', $story_id],
                            ['story_to_id', '=', $del_links[$i]]
                        ])->first();
                    $to_edit->story_to_id = $add_links[$i];
                    $to_edit->save();
                    unset($del_links[$i]);
                }

                foreach($del_links as $del)
                    StoryLink::where([
                            ['story_from_id', '=', $story_id],
                            ['story_to_id', '=', $del]
                        ])->delete();
            }

            return response()->json(['success' => true]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        }
    }

    function destroy($id) {
        $story = Story::findOrFail($id);
        if ($story) {
            $story->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }

    function pause(Request $request){
        try {
            $story1 = Story::findOrFail($request->id_from);
            $story1->completed = 0;
            $story1->save();

            $story2 = Story::findOrFail($request->id_to);
            $story2->completed = 1;
            $story2->save();

            return response()->json(['success' => true]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        }
    }
}