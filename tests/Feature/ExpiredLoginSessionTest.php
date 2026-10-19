<?php

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;

it('redirects an expired login submission to a fresh login form', function () {
    $request = Request::create('/login', 'POST');
    $session = $this->app['session']->driver();
    $request->setLaravelSession($session);

    $response = $this->app->make(ExceptionHandler::class)->render(
        $request,
        new TokenMismatchException
    );

    expect($response->getStatusCode())->toBe(302)
        ->and($response->headers->get('Location'))->toBe(route('login'))
        ->and($session->get('message'))->toBe('Сесија је истекла. Пријавите се поново.');

    $this->withSession(['message' => $session->get('message')])
        ->get(route('login'))
        ->assertOk()
        ->assertSee('Сесија је истекла. Пријавите се поново.')
        ->assertSee('name="_token"', false);
});
