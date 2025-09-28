@extends('layouts.app')

@section('title', '商品出品')

@section('head')
<link rel="stylesheet" href="{{ asset('css/items.css') }}">
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

        {{-- 商品名 --}}
        <div class="form-group">
            <label for="name">商品名</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="例：Tシャツ">
            @error('name')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        {{-- 価格 --}}
        <div class="form-group">
            <label for="price">価格</label>
            <input type="number" name="price" id="price" value="{{ old('price') }}" placeholder="例：1000">
            @error('price')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        {{-- 商品説明 --}}
        <div class="form-group">
            <label for="description">商品の説明</label>
            <textarea name="description" id="description" rows="5" placeholder="商品の特徴や詳細を入力してください">{{ old('description') }}</textarea>
            @error('description')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        {{-- 商品画像 --}}
        <div class="form-group">
            <label for="image">商品画像</label>
            <input type="file" name="image" id="image" accept="image/jpeg,image/png">
            @error('image')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        {{-- カテゴリー（ボタン式複数選択） --}}
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

        <button type="submit" class="sell-btn">出品する</button>
    </form>
</div>
@endsection
