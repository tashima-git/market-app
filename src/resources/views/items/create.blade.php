@extends('layouts.app')

@section('title', '商品出品')

@section('css')
<link rel="stylesheet" href="{{ asset('css/create.css') }}">
@endsection

@section('content')
<div class="sell-container">
    <h1>商品を出品する</h1>

    {{-- バリデーションエラー表示 --}}
    @if ($errors->any())
        <div class="error-messages">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('items.store') }}" enctype="multipart/form-data">
        @csrf

        {{-- 商品画像 --}}
        <div class="form-group">
            <label for="image">商品画像</label>
            <input type="file" name="image" id="image" accept="image/jpeg,image/png">
            @error('image')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        {{-- 商品詳細（小見出し） --}}
        <h2 class="section-title">商品詳細</h2>

        {{-- カテゴリー --}}
        <div class="form-group">
            <label>カテゴリー</label>
            <div class="categories-checkboxes">
                @foreach($categories as $category)
                    <label class="category-btn">
                        <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                            {{ (collect(old('categories'))->contains($category->id)) ? 'checked' : '' }}>
                        {{ $category->name }}
                    </label>
                @endforeach
            </div>
            @error('categories')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        {{-- 商品の状態 --}}
        <div class="form-group">
            <label for="condition_id">商品の状態</label>
            <select name="condition_id" id="condition_id" required>
                <option value="">選択してください</option>
                @foreach($conditions as $condition)
                    <option value="{{ $condition->id }}"
                        {{ old('condition_id') == $condition->id ? 'selected' : '' }}>
                        {{ $condition->name }}
                    </option>
                @endforeach
            </select>
            @error('condition_id')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        {{-- 商品名と説明（小見出し） --}}
        <h2 class="section-title">商品名と説明</h2>

        {{-- 商品名 --}}
        <div class="form-group">
            <label for="name">商品名</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}">
            @error('name')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        {{-- ブランド名 --}}
        <div class="form-group">
            <label for="brand">ブランド名</label>
            <input type="text" name="brand" id="brand" value="{{ old('brand') }}">
            @error('brand')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        {{-- 商品の説明 --}}
        <div class="form-group">
            <label for="description">商品の説明</label>
            <textarea name="description" id="description" rows="5">{{ old('description') }}</textarea>
            @error('description')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        {{-- 販売価格 --}}
        <div class="form-group">
            <label for="price">販売価格</label>
            <div class="price-input">
                <input type="number" name="price" id="price" value="{{ old('price') }}">
            </div>
            @error('price')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="sell-btn">出品する</button>
    </form>
</div>
@endsection
