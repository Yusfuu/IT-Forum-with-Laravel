<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{

    public function index(Request $request)
    {
        if ($request->user()->role === 0) {
            return response()->json([
                "message" => "unauthorized."
            ]);
        } else {
            return Question::paginate(8);
        }
    }


    public function _delete(Request $request)
    {
        if ($request->user()->role === 0) {
            return response()->json([
                "message" => "unauthorized."
            ]);
        } else {
            return Question::destroy($request['id']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        if ($request->user()->role === 0) {
            return response()->json([
                "message" => "unauthorized."
            ]);
        } else {
            $u = User::where('role', '!=', 1);
            return $u->paginate(8);
        }
    }


    public function activate(Request $request)
    {
        if ($request->user()->role === 0) {
            return response()->json([
                "message" => "unauthorized."
            ]);
        } else {
            return User::where('id', $request['id'])->where('role', '!=', 1)->update(['banned' => 0]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if ($request->user()->role === 0) {
            return response()->json([
                "message" => "unauthorized."
            ]);
        } else {
            return User::where('id', $request['id'])->where('role', '!=', 1)->update(['banned' => 1]);
        }
    }
}
