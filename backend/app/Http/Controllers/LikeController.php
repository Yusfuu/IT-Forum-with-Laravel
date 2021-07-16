<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use Illuminate\Support\Pluralizer;

class LikeController extends Controller
{



    public function store(Request $request)
    {
        $field = $request->validate([
            'qid' => 'required',
        ]);

        if (Question::find($field['qid'])->likedBy($request->user())) {
            return response()->json(['liky']);
        }

        return $request->user()->likes()->create([
            'qid' => $field['qid'],
        ]);
    }

    public function destroy(Request $request, $id)
    {
        return $request->user()->likes()->delete($id);
    }

    public function show(Request $request, $id)
    {
        return Question::find($id)->likes->count();
    }
}
