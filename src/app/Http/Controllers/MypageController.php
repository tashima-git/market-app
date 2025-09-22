<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MypageController extends Controller
{
    public function index()
    {
        return view('mypage.index');
    }

    public function edit()
    {
        return view('mypage.profile');
    }

    public function update(Request $request)
    {
        // ダミー更新
        return redirect()->route('mypage.index');
    }

    public function purchases()
    {
        return view('mypage.index', ['tab' => 'buy']);
    }

    public function sales()
    {
        return view('mypage.index', ['tab' => 'sell']);
    }
}
