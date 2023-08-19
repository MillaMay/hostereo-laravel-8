<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function showHome(Request $request, $token)
    {
        if ($request->ajax()) {
            $randomNumber = rand(1, 1000);
            $result = $randomNumber % 2 === 0 ? 'Win' : 'Lose';

            $winAmount = 0;
            if ($randomNumber > 900) {
                $winAmount = 0.7 * $randomNumber;
            } elseif ($randomNumber > 600) {
                $winAmount = 0.5 * $randomNumber;
            } elseif ($randomNumber > 300) {
                $winAmount = 0.3 * $randomNumber;
            } else {
                $winAmount = 0.1 * $randomNumber;
            }

            $user = DB::table('users')->where('access_token', $token)->first();

            DB::table('results')->insert([
                'user_id' => $user->id,
                'random_number' => $randomNumber,
                'result' => $result,
                'win_amount' => $winAmount,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json([
                'random_number' => $randomNumber,
                'result' => $result,
                'win_amount' => round($winAmount, 2)
            ]);
        }

        $user = DB::table('users')
            ->where('access_token', $token)
            ->where('access_expires_at', '>', Carbon::now())
            ->first();

        if ($user) {
            return view('home', ['user' => $user]);
        } else {
            return view('invalid_link');
        }
    }

    public function generateLink(Request $request, $token)
    {
        $newToken = Str::random(32);
        $expiresAt = Carbon::now()->addDays(7);

        DB::table('users')
            ->where('access_token', $token)
            ->update([
                'access_token' => $newToken,
                'access_expires_at' => $expiresAt
            ]);

        session()->flash('success', 'Your unique link has been updated successfully.');

        return redirect()->route('home', ['token' => $newToken]);
    }

    public function deactivateLink(Request $request, $token)
    {
        DB::table('users')
            ->where('access_token', $token)
            ->update(['access_expires_at' => Carbon::now()]);

        return redirect()->route('invalid_link');
    }

    public function getHistory(Request $request, $token)
    {
        if ($request->ajax()) {
            $user = DB::table('users')
                ->where('access_token', $token)
                ->first();

            $history = DB::table('results')
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get();

            return response()->json($history);
        }
    }
}
