<?php

namespace App\Providers;

use App\Mail\TextNoticeMail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Request::setTrustedProxies(
            ['*'],
            Request::HEADER_X_FORWARDED_FOR |
            Request::HEADER_X_FORWARDED_HOST |
            Request::HEADER_X_FORWARDED_PORT |
            Request::HEADER_X_FORWARDED_PROTO |
            Request::HEADER_X_FORWARDED_AWS_ELB,
        );

        URL::forceScheme('https');

        Carbon::setLocale(config('app.locale'));

        Queue::failing(function (JobFailed $event): void {
            $body = '<p>Очередь: '.$event->job->getQueue().'</p>';
            $body .= '<p>Задание: '.$event->job->getRawBody().'</p>';
            $body .= '<p>Ошибка: '.$event->exception->getMessage().'</p>';
            $body .= '<p>Трассировка: '.$event->exception->getTraceAsString().'</p>';

            Mail::to(config('app.admin_email'))->send(new TextNoticeMail('Задание в очереди '.($event->job->getQueue()).' завершилось с ошибкой', $body));
        });
    }
}
