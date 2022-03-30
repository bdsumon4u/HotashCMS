<?php

namespace Tests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\ParallelTesting;
use Stancl\Tenancy\Facades\Tenancy;

trait RefreshDatabaseWithTenant
{
    use RefreshDatabase;

    /**
     * The database connections that should have transactions.
     *
     * `null` is the default landlord connection
     * `tenant` is the tenant connection
     */
    protected array $connectionsToTransact = [null, 'tenant'];

    /**
     * Perform any work that should take place once the database has finished refreshing.
     *
     * @return void
     * @throws \Stancl\Tenancy\Exceptions\TenantCouldNotBeIdentifiedById
     */
    protected function afterRefreshingDatabase()
    {
        $this->initializeTenant();
    }

    /**
     * @throws \Stancl\Tenancy\Exceptions\TenantCouldNotBeIdentifiedById
     */
    protected function initializeTenant(): ?Model
    {
        $this->tenant = Tenancy::model()::firstOr(function () {
            return $this->createTenant('test', 'test');
        });

        tenancy()->initialize($this->tenant);
        $this->setDomain();

        return $this->tenant;
    }

    /**
     * @param string $id
     * @param string $domain
     * @return Model
     */
    protected function createTenant(string $id, string $domain): Model
    {
        if ($token = ParallelTesting::token()) {
            config(['tenancy.database.prefix' => config('tenancy.database.prefix') . "{$token}_"]);
        }

        $tenant = Tenancy::model()->create(compact('id'));

        if (!$tenant->domains()->count()) {
            $tenant->domains()->create(compact('domain'));
        }

        return $tenant;
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        if ($this->tenant) {
            tenancy()->model()->all()->each->delete();
        }

        parent::tearDown();
    }
}
