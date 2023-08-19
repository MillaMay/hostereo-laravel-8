<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255|unique:users|regex:/^\+?[0-9\-]+$/'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $token = Str::random(32);
        $expiresAt = Carbon::now()->addDays(7);

        DB::table('users')->insert([
            'name' => $request->name,
            'phone' => $request->phone,
            'access_token' => $token,
            'access_expires_at' => $expiresAt
        ]);

        return redirect()->route('home', ['token' => $token]);
    }
}
