

@extends('layouts.master')

@section('title', '{{ $category->name }}')  

@section('content')
    <h1>iPhone X 256GB</h1>
    <h2>Мобильные телефоны</h2>
    <p>Цена: <b>89990 ₽</b></p>
    <img src="http://internet-shop.tmweb.ru/storage/products/iphone_x_silver.jpg">
    <p>Отличный продвинутый телефон с памятью на 256 gb</p>

    <form action="http://internet-shop.tmweb.ru/basket/add/2" method="POST">
        <button type="submit" class="btn btn-success" role="button">Добавить в корзину</button>
    </form>
@endsection