@component('mail::layout')
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            Сервис доставка
        @endcomponent
    @endslot

    <h2>Уведомление</h2>

    <p>{!! $body !!}</p>

    @slot('footer')
        @component('mail::footer')
            © {{ date('Y') }} Сервис доставка
        @endcomponent
    @endslot
@endcomponent
