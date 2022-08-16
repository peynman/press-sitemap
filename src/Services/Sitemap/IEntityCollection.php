<?php

namespace Larapress\Sitemap\Services\Sitemap;

use Illuminate\Database\Eloquent\Model;

interface IEntityCollection {
    /**
     * Undocumented function
     *
     * @param ISitemapService $service
     *
     * @return void
     */
    public function registerEntityUrls(ISitemapService $service);

    /**
     * Undocumented function
     *
     * @return integer
     */
    public function getChunkSize(): int;

    /**
     * Undocumented function
     *
     * @param Model $entity
     * @return string
     */
    public function getEntityPageLocation(Model $entity): string;

    /**
     * Undocumented function
     *
     * @return string
     */
    public function getUrlSetName(): string;

    /**
     * Undocumented function
     *
     * @return string
     */
    public function getLastModifiedDate(): string;
}
