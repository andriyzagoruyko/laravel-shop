<p>Уважаемый {{ $name }},</p>

<p>Ваш заказ на сумму {{ $fullSum }} создан</p>

<table>
    <tbody>
        @foreach($order->skus as $sku) 
        <tr>
            <td>
                <a href="{{ route('sku', [$sku->product->category->code, $sku->product->code, $sku->id]) }}">
                    <img height="56px" src="{{ Storage::url($sku->product->image) }}">
                    {{ $sku->product->name }}
                </a>
            </td>
            <td><span class="badge">{{ $sku->countInOrder }}</span></td>
            <td>{{ $sku->price }} {{ $currencySymbol }}</td>
            <td>{{ $sku->getPriceForCount() }} {{ $currencySymbol }}</td>
        </tr>
        @endforeach
    </tbody>
</table>