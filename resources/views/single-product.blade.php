

@extends('layouts.master')

@section('title', '{{ $product->name }}')  

@section('content')
    <h1>{{ $product->name }}</h1>
    <h2>{{ $category->name }}</h2>


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

    @if($product->isAvailable()) 
        <p class="alert-success">В наличии</p> 
    @else
        <p class="alert-danger">Нет в наличии</p> 
    @endif

    <p>Цена: <b>{{ $product->price }} ₽</b></p>
    <p>{{ $product->description }}</p>

    <form action="{{ route('basket-add', $product) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-success" role="button" @if(!$product->isAvailable()) disabled @endif>Добавить в корзину</button>
    </form>
@endsection