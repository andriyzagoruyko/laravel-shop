Уважаемый клиент, товар {{ $sku->product->name }} появился в наличии.

<a href="{{ route('sku', [$sku->product->category->code, $sku->product->code, $sku->id]) }}">Узнать подробности</a>