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
                    @if($order->hasCoupon())
                        <td><del>{{ $order->getFullSum(false) }} {{ $currencySymbol }}</del> <b>{{ $order->getFullSum() }} {{ $currencySymbol }}</b></td>
                    @else
                        <td>{{ $order->getFullSum() }} {{ $currencySymbol }}</td>
                    @endif
                </tr>
            </tbody>
        </table>
        <div class="row">
            @if(!$order->hasCoupon())
                <div class="form-inline pull-right">
                    <form method="POST" action="{{ route('set-coupon') }}">
                        @csrf
                        <label for="coupon">Добавить купон:</label>
                        <input class="form-control" type="text" name="coupon">
                        <button type="submit" class="btn btn-success">Применить</button>
                    </form>
                    @error('coupon')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            @else
                <div>Использован купон {{ $order->coupon->code }}</div> 
            @endif 
        </div>
    </div>
    <br>
        <br>
        <div class="btn-group pull-right" role="group">
            <a type="button" class="btn btn-success" href="{{ route('basket-place') }}">Оформить заказ</a>
        </div>
    </div>

@endsection