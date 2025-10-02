@extends('layouts.app')

@section('title', 'お届け先変更')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('content')
<div class="container">
    <h2 class="page-title">住所の変更</h2>

    <form method="POST" action="{{ route('purchase.address.store', $item->id) }}">
        @csrf

        {{-- 郵便番号 --}}
        <div class="form-group">
            <label for="postal_code">郵便番号</label>
            <input type="text" id="postal_code" name="postal_code" required>
            @error('postal_code')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        {{-- 住所 --}}
        <div class="form-group">
            <label for="address">住所</label>
            <input type="text" id="address" name="address" required>
            @error('address')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        {{-- 建物名 --}}
        <div class="form-group">
            <label for="building_name">建物名</label>
            <input type="text" id="building_name" name="building_name">
            @error('building_name')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        {{-- 更新ボタン --}}
        <button type="submit" class="update-button">更新する</button>
    </form>
</div>
@endsection
