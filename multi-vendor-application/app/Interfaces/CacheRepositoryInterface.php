<?php

namespace App\Interfaces;

use Illuminate\Support\Facades\Cache;
use function now;

/**
 * Interface CacheRepositoryInterface
 *
 * Defines the contract for cache repositories.
 * This interface specifies the required methods for managing cache operations,
 * including storing, retrieving, checking existence, and removing cache entries.
 */
interface CacheRepositoryInterface
{
    /**
     * Store data in the cache with a time-to-live (TTL).
     *
     * This method is used to store a given set of data in the cache under the specified key,
     * for the given TTL (time-to-live) duration.
     *
     * @param  string  $key  The key under which the data will be stored in the cache.
     * @param  array  $data  The data to be stored in the cache.
     * @param  int  $ttl  The time-to-live (TTL) in seconds for the cache entry.
     * @return void
     */
    public function store(string $key, array $data, int $ttl): void;

    /**
     * Store a boolean flag in the cache with a time-to-live (TTL).
     *
     * This method stores a flag (e.g., cooldown or status flag) in the cache under the specified key,
     * for the given TTL (time-to-live) duration.
     *
     * @param  string  $key  The key under which the flag will be stored in the cache.
     * @param  bool  $value  The value of the flag (true or false).
     * @param  int  $ttl  The time-to-live (TTL) in seconds for the cache entry.
     * @return void
     */
    public function storeFlag(string $key, bool $value, int $ttl): void;

    /**
     * Retrieve data from the cache.
     *
     * This method retrieves the data stored under the specified key from the cache.
     * Returns null if no data is found for the given key.
     *
     * @param  string  $key  The key under which the data is stored in the cache.
     * @return array|null The cached data if found, otherwise null.
     */
    public function get(string $key): ?array;

    /**
     * Remove a key and its data from the cache.
     *
     * This method deletes the cache entry stored under the specified key.
     *
     * @param  string  $key  The key to remove from the cache.
     * @return void
     */
    public function forget(string $key): void;

    /**
     * Check if a key exists in the cache.
     *
     * This method checks if a cache entry exists for the specified key.
     *
     * @param  string  $key  The key to check for existence.
     * @return bool True if the key exists in the cache, false otherwise.
     */
    public function has(string $key): bool;

}
