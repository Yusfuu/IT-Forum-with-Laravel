<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
