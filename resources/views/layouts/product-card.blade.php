<div class="col-sm-6 col-md-4">
    <div class="thumbnail">
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
        <img src="{{ Storage::url($sku->product->image) }}" alt="{{ $sku->product->__('name') }}">
        <div class="caption">
            <h3>{{ $sku->product->__('name') }}</h3>

            @isset($sku->product->properties)
                <h3>Properties:</h3>
                @foreach ($sku->propertyOptions as $propertyOption)
                    <div>{{ $propertyOption->property->__('name') }}:{{ $propertyOption->__('name') }}</div>
                @endforeach
            @endisset

            @if($sku->isAvailable()) 
                <span class="alert-success">В наличии</span> 
            @else
                <span class="alert-danger">Нет в наличии</span> 
            @endif

            <p>{{ $sku->price }} {{ $currencySymbol }}</p>
            <form action="{{ route('basket-add', $sku) }}" method="POST">
                <button type="submit" class="btn btn-primary" role="button" @if(!$sku->isAvailable()) disabled @endif>В корзину</button>
                <a href="{{ route('sku', [
                    $sku->product->category->code, 
                    $sku->product->code, 
                    $sku->id
                    ]) }}" class="btn btn-default" role="button">Подробнее</a>
                @csrf
            </form>
        </div>
    </div>
</div> 