<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function index(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        if ($user->banned) {
            return response()->json([
                'message' => 'this Account in Blacklist cicada 3301'
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'role' => $user['role'],
            'token_type' => 'Bearer',
        ]);
    }

    public function store(Request $request)
    {
        $attr = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6'
        ]);

        $user = User::create([
            'name' => $attr['name'],
            'password' => Hash::make($attr['password']),
            'email' => $attr['email']
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function show(Request $request)
    {
        return $request->user();
    }

    public function destroy(Request $request)
    {
        $request->user()->tokens()->delete();

        return [
            'message' => 'Tokens Revoked'
        ];
    }


    public function avatar(Request $request)
    {
        if ($request->file('avatar')) {
            $request->validate(['avatar' => 'image|mimes:jpg,png,jpeg']);
            $image_name = time() . '-' . $request->user()->id . '.' . $request->avatar->extension();
            if ($request->user()->avatar !== 'default.png') {
                if (File::exists(public_path('avatars/' .  $request->user()->avatar))) {
                    File::delete(public_path('avatars/' .  $request->user()->avatar));
                }
                $request->avatar->move(public_path('avatars'), $image_name);
            }
        }
        return response()->json(Auth::user());
    }

    public function question(Request $request)
    {

        return DB::table('questions')
            ->join('users', 'questions.uid', '=', 'users.id')
            ->leftJoin('responses', 'responses.qid', '=', 'questions.id')
            ->select(['questions.id', 'questions.title', 'questions.question', 'questions.created_at', DB::raw('count(responses.qid) as count')])
            ->where('users.id', '=', $request->user()->id)
            ->groupBy('questions.id', 'users.id', 'responses.qid')->get();
    }
}
