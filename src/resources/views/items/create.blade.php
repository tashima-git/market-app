@extends('layouts.app')

@section('title', '商品出品')

@section('css')
<link rel="stylesheet" href="{{ asset('css/create.css') }}">
@endsection

@section('content')
<div class="sell-container">
    <h1>商品の出品</h1>

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
            <label>商品画像</label>
            <div class="image-upload">
                <label for="image" class="file-upload-label">画像を選択する</label>
                <input type="file" name="image" id="image" accept="image/jpeg,image/png">
            </div>
            @error('image')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        {{-- 商品詳細 --}}
        <h2 class="section-title">商品詳細</h2>

        {{-- カテゴリー --}}
        <div class="form-group">
            <label>カテゴリー</label>
            <div class="categories-checkboxes">
                @foreach($categories as $category)
                    <label class="category-btn">
                        <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                            {{ (collect(old('categories'))->contains($category->id)) ? 'checked' : '' }}>
                        <span>{{ $category->name }}</span>
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
            <select name="condition_id" id="condition_id" class="condition-select" required>
                <option value="">選択してください</option>
                @foreach($conditions as $condition)
                    <option value="{{ $condition->id }}" {{ old('condition_id') == $condition->id ? 'selected' : '' }}>
                        {{ $condition->name }}
                    </option>
                @endforeach
            </select>
            @error('condition_id')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        {{-- 商品名と説明 --}}
        <h2 class="section-title">商品名と説明</h2>

        <div class="form-group">
            <label for="name">商品名</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}">
            @error('name')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="brand">ブランド名</label>
            <input type="text" name="brand" id="brand" value="{{ old('brand') }}">
            @error('brand')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">商品の説明</label>
            <textarea name="description" id="description" rows="5">{{ old('description') }}</textarea>
            @error('description')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        {{-- 価格 --}}
        <div class="form-group price-input">
            <label for="price">販売価格</label>
            <div class="price-wrapper">
                <input type="number" name="price" id="price" value="{{ old('price') }}">
            </div>
            @error('price')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="create-sell-btn">出品する</button>
    </form>
</div>
@endsection
