@extends('layouts.master')

@section('title', 'Корзина')  

@section('content')  
    <h1>Корзина</h1>
    <p>Оформление заказа</p>
    <div class="panel">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Название</th>
                <th>Кол-во</th>
                <th>Цена</th>
                <th>Стоимость</th>
            </tr>
            </thead>
            <tbody>
                @foreach ($order->skus as $sku)
                    <tr>
                        <td>
                            <a href="{{ route('sku', [
                                $sku->product->category->code, 
                                $sku->product->code, 
                                $sku->id
                                ]) }}">
                                <img height="56px" src="{{ Storage::url($sku->product->image) }}">
                                {{ $sku->product->name }}
                            </a>
                        </td>
                        <td><span class="badge">{{ $sku->countInOrder }}</span>
                            <div class="btn-group form-inline">
                                <form action="{{ route('basket-remove', $sku->id) }}" method="POST">
                                    <button type="submit" class="btn btn-danger">
                                        <span class="glyphicon glyphicon-minus" aria-hidden="true"></span>
                                    </button>
                                    @csrf                      
                                </form>
                                <form action="{{ route('basket-add', $sku->id) }}" method="POST">
                                    <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                                    </button>
                                    @csrf
                                </form>
                            </div>
                        </td>
                        <td>{{ $sku->price }} {{ $currencySymbol }}</td>
                        <td>{{ $sku->price * $sku->countInOrder }} {{ $currencySymbol }}</td>
                    </tr>
                @endforeach

                <tr>
                    <td colspan="3">Общая стоимость:</td>
                    <td>{{ $order->getFullSum() }} {{ $currencySymbol }}</td>
                </tr>
            </tbody>
        </table>
        <br>
        <div class="btn-group pull-right" role="group">
            <a type="button" class="btn btn-success" href="{{ route('basket-place') }}">Оформить заказ</a>
        </div>
    </div>

@endsection