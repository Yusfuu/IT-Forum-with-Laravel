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
            'question' => 'required|string|max:1000',
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
            ->leftJoin('likes', 'likes.qid', '=', 'questions.id')
            ->leftJoin('responses', 'responses.qid', '=', 'questions.id')
            ->select([DB::raw('count(likes.qid) as likeCount'), 'questions.id', 'questions.title', 'questions.question', 'questions.tags', 'questions.created_at', 'users.id as uid', 'users.avatar', 'users.name', DB::raw('count(responses.qid) as count')])
            ->groupBy('questions.id');

        return $q->paginate(4);
    }


    public function update(Request $request, $id)
    {
        //
    }

    public function find(Request $request, $id)
    {
        $q = DB::table('questions')
            ->join('users', 'questions.uid', '=', 'users.id')
            ->leftJoin('likes', 'likes.qid', '=', 'questions.id')
            ->select(['questions.id', 'questions.title', 'questions.question', 'questions.tags', 'questions.created_at', 'users.id as uid', 'users.avatar', 'users.name', DB::raw('count(likes.qid) as likeCount')])->where('questions.id', '=', $id)
            ->groupBy('questions.id', 'likes.qid')
            ->get();

        $r = DB::table('questions')
            ->join('responses', 'responses.qid', '=', 'questions.id')
            ->join('users', 'responses.uid', '=', 'users.id')
            ->select(['users.avatar as ravatar', 'users.email as remail', 'users.name as rname', 'responses.response', 'users.id as ruid'])
            ->where('questions.id', '=', $id)->get();

        $q->push(['responses' => $r]);

        return $q;
    }


    public function destroy(Request $request, $id)
    {
        $field = $request->validate([
            'qid' => 'required',
        ]);

        return $request->user()->questions()->delete($field['qid']);
    }
}
