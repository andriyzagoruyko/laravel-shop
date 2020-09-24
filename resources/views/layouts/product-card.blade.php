<div class="col-sm-6 col-md-4">
    <div class="thumbnail">
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
        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->__('name') }}">
        <div class="caption">
            <h3>{{ $product->__('name') }}</h3>
            
            @if($product->isAvailable()) 
                <span class="alert-success">В наличии</span> 
            @else
                <span class="alert-danger">Нет в наличии</span> 
            @endif

            <p>{{ $product->price }} {{ App\Services\CurrencyConvertion::getCurrencySymbol() }}</p>
            <form action="{{ route('basket-add', $product) }}" method="POST">
                <button type="submit" class="btn btn-primary" role="button" @if(!$product->isAvailable()) disabled @endif>В корзину</button>
                <a href="{{ route('product', [isset($category) ? $category->code : $product->category->code, $product->code]) }}" class="btn btn-default" role="button">Подробнее</a>
                @csrf
            </form>
        </div>
    </div>
</div> 