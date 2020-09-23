

@extends('layouts.master')

@section('title', $product->name)  

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
        <p>
            <span>Сообщить мне, когда товар появится в наличии.</span> 

            @if($errors->get('email')) 
            <p class='alert alert-danger'>{!! $errors->get('email')[0] !!}</p> 
            @endif

            <form action="{{ route('subscription', $product) }}" method="POST">
                @csrf
                <input type="email" name="email" id="email"></input>
                <button type="submit" class="btn btn-success" role="button">Отправить</button>
            </form>
        </p>
    @endif

    <p>Цена: <b>{{ $product->price }} ₽</b></p>
    <p>{{ $product->description }}</p>

    @if($product->isAvailable()) 
        <form action="{{ route('basket-add', $product) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-success" role="button">Добавить в корзину</button>
        </form>
    @endif
@endsection