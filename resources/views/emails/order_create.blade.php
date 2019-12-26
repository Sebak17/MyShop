@component('mail::message')
# Dziękujemy za złożenie zamówienia nr. {{ $order->id }}

<p style="text-align: justify;">
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec et dui aliquam, maximus orci a, blandit ante. Aenean ultricies sem eros, vitae mollis leo tempus id. Praesent tempor, massa eget ornare cursus, lectus sem ullamcorper mauris, eget blandit dui urna vel tellus. Mauris id diam placerat, viverra augue vitae, pellentesque mi.
</p>


@component('mail::table')
| Produkt       | Ilość         | Cena  |
| ------------- |:-------------:| --------:|
@foreach($order->products as $product)
| {{ $product->name }} | {{ $product->amount }} | {{ number_format((float) ($product->price * $product->amount), 2, '.', '') }} {{ config('site.currency') }} |
@endforeach
| <p style="font-size: 95%;">Cena końcowa</p> | | <p style="font-size: 115%; text-align: right; font-weight: bold;">{{ number_format((float) $order->cost, 2, '.', '') }} {{ config('site.currency') }}</p> |
@endcomponent

@component('mail::button', ['url' => route('orderIDPage', $order->id)])
Przejdź do zamówienia
@endcomponent



Pozdrawiamy!<br>
{{ config('app.name') }}
@endcomponent
