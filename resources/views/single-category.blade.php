@extends('layouts.master')

@section('title', $category->name )  

@section('content')
    <h1>{{ $category->name }} ({{ $category->products->count() }})</h1>
    
    <p>{{ $category->description }}</p>

    <div class="row">
        @foreach($category->products->map->skus->flatten() as $sku)
            @include('layouts.product-card', compact('sku'))
        @endforeach
    </div>
@endsection