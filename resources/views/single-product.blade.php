

@extends('layouts.master')

@section('title', $sku->product->name)  

@section('content')
    <h1>{{ $sku->product->__('name') }}</h1>
    <h2>{{ $sku->product->category->__('name') }}</h2>

    <img src="{{ Storage::url($sku->product->image) }}">
    <div class="labels">
        @if($sku->product->isNew())
            <span class="badge badge-succes">Новинка</span>
        @endif
        
        @if($sku->product->isRecommend())
            <span class="badge badge-warning">Рекомендуем</span>
        @endif
        
        @if($sku->product->isHit())
            <span class="badge badge-danger">Хит продаж!</span>
        @endif            
    </div>

    @if($sku->isAvailable()) 
        <p class="alert-success">В наличии</p> 
    @else
        <p class="alert-danger">Нет в наличии</p> 
        <p>
            <span>Сообщить мне, когда товар появится в наличии.</span> 

            @if($errors->get('email')) 
            <p class='alert alert-danger'>{!! $errors->get('email') !!}</p> 
            @endif

            <form action="{{ route('subscription', $sku) }}" method="POST">
                @csrf
                <input type="email" name="email" id="email"></input>
                <button type="submit" class="btn btn-success" role="button">Отправить</button>
            </form>
        </p>
    @endif

    <p>Цена: <b>{{ $sku->price }} ₽</b></p>
    @isset($sku->product->properties)
        @foreach ($sku->propertyOptions as $propertyOption)
            <div>{{ $propertyOption->property->__('name') }}:{{ $propertyOption->__('name') }}</div>
        @endforeach
    @endisset

    <p>{{ $sku->product->description }}</p>


    @if($sku->isAvailable()) 
        <form action="{{ route('basket-add', $sku) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-success" role="button">Добавить в корзину</button>
        </form>
    @endif
@endsection