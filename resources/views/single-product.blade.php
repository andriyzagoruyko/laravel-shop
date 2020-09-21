

@extends('layouts.master')

@section('title', '{{ $product->name }}')  

@section('content')
    <h1>{{ $product->name }}</h1>
    <h2>{{ $category->name }}</h2>
    <p>Цена: <b>{{ $product->price }} ₽</b></p>
    <img src="{{ Storage::url($product->image) }}">
    <div class="labels">
        @if($product->isNew())
            <span class="badge badge-succes">Новинка</span>
        @endif
        
        @if($product->isRecommend())
            <span class="badge badge-warning">Рекомендуем</span>
        @endif
        
        @if($product->isHit())
            <span class="badge badge-danger">Хит продаж!</span>
        @endif            
    </div>
    <p>{{ $product->description }}</p>

    <form action="{{ route('basket-add', $product) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-success" role="button">Добавить в корзину</button>
    </form>
@endsection