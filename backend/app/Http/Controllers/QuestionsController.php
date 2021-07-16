<?php

namespace App\Http\Controllers;

// use App\Models\Question;

use App\Models\Question;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionsController extends Controller
{

    public function index(Request $request)
    {
        // return response()->json($request->user()->questions);
    }


    public function store(Request $request)
    {
        $field = $request->validate([
            'title' => 'required|string|max:255',
            'question' => 'required|string',
            'tags' => 'required|string',
        ]);

        $request->user()->questions()->create([
            'title' => $field['title'],
            'question' => $field['question'],
            'tags' => $field['tags'],
        ]);
        return response()->json(['hello' => 'ok']);
    }


    public function show()
    {
        $q = DB::table('questions')
            ->join('users', 'questions.uid', '=', 'users.id')
            ->select(['questions.id', 'questions.title', 'questions.question', 'questions.tags', 'questions.created_at', 'users.id as uid', 'users.avatar', 'users.name']);

        return $q->paginate(8);
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy(Request $request, $id)
    {
        $field = $request->validate([
            'qid' => 'required',
        ]);

        return $request->user()->questions()->delete($field['qid']);
    }
}
