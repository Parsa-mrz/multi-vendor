<?php

namespace App\Repositories;

use App\Interfaces\CacheRepositoryInterface;
use Illuminate\Support\Facades\Cache;

/**
 * Class CacheRepository
 *
 * Repository for managing cache operations.
 * Implements the CacheRepositoryInterface to handle caching logic for various data types,
 * including storing, retrieving, checking existence, and removing cache entries.
 */
class CacheRepository implements CacheRepositoryInterface
{
    /**
     * Store data in the cache with a time-to-live (TTL).
     *
     * This method stores the provided data under the specified key in the cache for a given TTL.
     *
     * @param  string  $key  The key under which the data will be stored.
     * @param  array  $data  The data to store in the cache.
     * @param  int  $ttl  The time-to-live (TTL) in seconds for the cache entry.
     * @return void
     */
    public function store(string $key, array $data, int $ttl): void
    {
        Cache::put($key, $data, now()->addSeconds($ttl));
    }

    /**
     * Store a boolean flag in the cache with a time-to-live (TTL).
     *
     * This method stores a boolean flag (true or false) under the specified key in the cache
     * for the given TTL duration.
     *
     * @param  string  $key  The key under which the flag will be stored.
     * @param  bool  $value  The value of the flag (true or false).
     * @param  int  $ttl  The time-to-live (TTL) in seconds for the cache entry.
     * @return void
     */
    public function storeFlag(string $key, bool $value, int $ttl): void
    {
        Cache::put($key, $value, now()->addSeconds($ttl));
    }

    /**
     * Retrieve data from the cache.
     *
     * This method retrieves the data stored under the specified key in the cache.
     * Returns null if no data is found for the given key.
     *
     * @param  string  $key  The key to retrieve the data for.
     * @return array|null The cached data if found, otherwise null.
     */
    public function get(string $key): ?array
    {
        return Cache::get($key);
    }

    /**
     * Remove a key and its associated data from the cache.
     *
     * This method removes the cache entry stored under the specified key.
     *
     * @param  string  $key  The key to remove from the cache.
     * @return void
     */
    public function forget(string $key): void
    {
        Cache::forget($key);
    }

    /**
     * Check if a key exists in the cache.
     *
     * This method checks if there is a cache entry for the specified key.
     *
     * @param  string  $key  The key to check for existence.
     * @return bool True if the key exists in the cache, false otherwise.
     */
    public function has(string $key): bool
    {
        return Cache::has($key);
    }
}
