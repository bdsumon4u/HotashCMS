<?php

namespace Tests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\URL;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected ?string $guard = null;
    protected ?Model $tenant = null;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->setDomain();
    }

    /**
     * @return void
     */
    protected function setDomain() {
        $app_url = config('app.url', 'localhost');
        $domain = parse_url($app_url, PHP_URL_SCHEME).'://';
        if ($this->guard) $domain .= "{$this->guard}.";
        if ($id = $this->tenant?->id) $domain .= "{$id}.";
        $domain .= parse_url($app_url, PHP_URL_HOST);
        URL::formatHostUsing(fn () => $domain);
    }
}
