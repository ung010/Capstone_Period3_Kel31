<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Survey;

class Survey_Controller extends Controller
{
    function mahasiswa()
    {
        return view('survey.mahasiswa');
    }

    function mahasiswa_create(Request $request)
    {
        $request->validate([
            'rating' => 'required|in:sangat_puas,puas,netral,kurang_puas,tidak_puas',
            'feedback' => 'nullable|string|max:255',
        ]);

        $userId = Auth::id();

        $existingSurvey = Survey::where('users_id', $userId)->first();

        if ($existingSurvey) {
            return redirect()->back()->withErrors('Anda sudah mengisi survei sebelumnya.');
        }

        $survey = new Survey();
        $survey->users_id = $userId;
        $survey->prd_id = Auth::user()->prd_id;
        $survey->tanggal_survey = Carbon::now()->toDateString();

        $survey->rating = $request->input('rating');

        $survey->feedback = $request->input('feedback');

        $survey->save();

        return redirect()->route('mahasiswa.index')->with('success', 'Terima kasih atas partisipasi Anda dalam survei ini.');
    }


    function admin()
    {
        $sangat_puas = DB::table('survey')->where('rating', 'sangat_puas')->count();
        $puas = DB::table('survey')->where('rating', 'puas')->count();
        $netral = DB::table('survey')->where('rating', 'netral')->count();
        $kurang_puas = DB::table('survey')->where('rating', 'kurang_puas')->count();
        $tidak_puas = DB::table('survey')->where('rating', 'tidak_puas')->count();

        $feedbacks = DB::table('survey')
        ->join('users', 'survey.users_id', '=', 'users.id')
        ->select('feedback', 'users.nama as nama_mhw', 'tanggal_survey')
        ->get();

        return view('survey.admin', compact('sangat_puas', 'puas', 'netral', 'kurang_puas', 'tidak_puas', 'feedbacks'));
    }

    function sv_akd()
    {
        $sangat_puas = DB::table('survey')->where('rating', 'sangat_puas')->count();
        $puas = DB::table('survey')->where('rating', 'puas')->count();
        $netral = DB::table('survey')->where('rating', 'netral')->count();
        $kurang_puas = DB::table('survey')->where('rating', 'kurang_puas')->count();
        $tidak_puas = DB::table('survey')->where('rating', 'tidak_puas')->count();

        $feedbacks = DB::table('survey')
        ->join('users', 'survey.users_id', '=', 'users.id')
        ->select('feedback', 'users.nama as nama_mhw', 'tanggal_survey')
        ->get();

        return view('survey.supervisor_akd', compact('sangat_puas', 'puas', 'netral', 'kurang_puas', 'tidak_puas', 'feedbacks'));
    }

    function sv_sd()
    {
        $sangat_puas = DB::table('survey')->where('rating', 'sangat_puas')->count();
        $puas = DB::table('survey')->where('rating', 'puas')->count();
        $netral = DB::table('survey')->where('rating', 'netral')->count();
        $kurang_puas = DB::table('survey')->where('rating', 'kurang_puas')->count();
        $tidak_puas = DB::table('survey')->where('rating', 'tidak_puas')->count();

        $feedbacks = DB::table('survey')
        ->join('users', 'survey.users_id', '=', 'users.id')
        ->select('feedback', 'users.nama as nama_mhw', 'tanggal_survey')
        ->get();

        return view('survey.supervisor_sd', compact('sangat_puas', 'puas', 'netral', 'kurang_puas', 'tidak_puas', 'feedbacks'));
    }

    function manajer()
    {
        $sangat_puas = DB::table('survey')->where('rating', 'sangat_puas')->count();
        $puas = DB::table('survey')->where('rating', 'puas')->count();
        $netral = DB::table('survey')->where('rating', 'netral')->count();
        $kurang_puas = DB::table('survey')->where('rating', 'kurang_puas')->count();
        $tidak_puas = DB::table('survey')->where('rating', 'tidak_puas')->count();

        $feedbacks = DB::table('survey')
        ->join('users', 'survey.users_id', '=', 'users.id')
        ->select('feedback', 'users.nama as nama_mhw', 'tanggal_survey')
        ->get();

        return view('survey.manajer', compact('sangat_puas', 'puas', 'netral', 'kurang_puas', 'tidak_puas', 'feedbacks'));
    }

    function wd1()
    {
        $sangat_puas = DB::table('survey')->where('rating', 'sangat_puas')->count();
        $puas = DB::table('survey')->where('rating', 'puas')->count();
        $netral = DB::table('survey')->where('rating', 'netral')->count();
        $kurang_puas = DB::table('survey')->where('rating', 'kurang_puas')->count();
        $tidak_puas = DB::table('survey')->where('rating', 'tidak_puas')->count();

        $feedbacks = DB::table('survey')
        ->join('users', 'survey.users_id', '=', 'users.id')
        ->select('feedback', 'users.nama as nama_mhw', 'tanggal_survey')
        ->get();

        return view('survey.wd1', compact('sangat_puas', 'puas', 'netral', 'kurang_puas', 'tidak_puas', 'feedbacks'));
    }

    function wd2()
    {
        $sangat_puas = DB::table('survey')->where('rating', 'sangat_puas')->count();
        $puas = DB::table('survey')->where('rating', 'puas')->count();
        $netral = DB::table('survey')->where('rating', 'netral')->count();
        $kurang_puas = DB::table('survey')->where('rating', 'kurang_puas')->count();
        $tidak_puas = DB::table('survey')->where('rating', 'tidak_puas')->count();

        $feedbacks = DB::table('survey')
        ->join('users', 'survey.users_id', '=', 'users.id')
        ->select('feedback', 'users.nama as nama_mhw', 'tanggal_survey')
        ->get();

        return view('survey.wd2', compact('sangat_puas', 'puas', 'netral', 'kurang_puas', 'tidak_puas', 'feedbacks'));
    }
}
