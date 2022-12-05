<?php

use BradyRenting\FilamentPasswordless\MagicLink;
use BradyRenting\FilamentPasswordless\Tests\Database\Factories\UserFactory;
use Carbon\Carbon;

test('it can generate a magic link', function () {
    $user = UserFactory::new()->create();

    $magicLink = MagicLink::create(
        model: $user,
        remember: true
    );

    expect($magicLink->getUrl())->toBeString();
});

test('it can generate a magic link with configurable expiration', function () {
    Carbon::setTestNow(now());

    $user = UserFactory::new()->create();

    $magicLink = MagicLink::create(
        model: $user,
        remember: true
    );

    expect($magicLink->getExpiry())->toBe(now()
        ->addMinutes(config('filament-passwordless.magic_link_expiry'))
        ->diffInMinutes()
    );
});

test('it can generate a magic link with the route model key', function () {
    $user = UserFactory::new()->create();

    $magicLink = MagicLink::create(
        model: $user,
        remember: true
    );

    expect($magicLink->getUrl())->toContain("{$user->getRouteKey()}");
});

test('it can generate a magic link and remember me', function () {
    $user = UserFactory::new()->create();

    $magicLink = MagicLink::create(
        model: $user,
        remember: true
    );

    expect($magicLink->getUrl())->toContain('/1?expires=');
});
