<?php

namespace Larapress\Sitemap\Services\Sitemap\EntityCollections;

use Illuminate\Database\Eloquent\Model;
use Larapress\Pages\Models\Page;
use Larapress\Sitemap\Services\Sitemap\IEntityCollection;
use Larapress\Sitemap\Services\Sitemap\ISitemapService;
use Larapress\Sitemap\Services\Sitemap\SitemapEntry;

class PagesEntityCollection implements IEntityCollection
{
    protected $chunkCount;
    protected $lastModified;

    public function __construct($options)
    {
        $this->chunkCount = $options['chunk'] ?? 100;
        $this->priority = $options['priority'] ?? 0.5;
        $this->changeFreq = $options['changefreq'] ?? 'weekly';
    }

    /**
     * Undocumented function
     *
     * @param ISitemapService $service
     * @return void
     */
    public function registerEntityUrls(ISitemapService $service)
    {
        Page::chunk($this->getChunkSize(), function ($pages) use ($service) {
            /** @var Page $page */
            foreach ($pages as $page) {
                // if (isset($page->options['sitemap']) && $page->options['sitemap']) {
                $service->addUrlToSet(self::class, new SitemapEntry(
                    $this->getEntityPageLocation($page),
                    $page->updated_at->format(config('larapress.sitemap.dateformat')),
                    $page->options['sitemapPriority'] ?? $this->priority,
                    $page->options['sitemapChangeFreq'] ?? $this->changeFreq,
                ));
                if ($page->updated_at > $this->lastModified) {
                    $this->lastModified = $page->updated_at;
                }
                // }
            }
        });
    }

    /**
     * Undocumented function
     *
     * @return integer
     */
    public function getChunkSize(): int
    {
        return $this->chunkCount;
    }

    /**
     * Undocumented function
     *
     * @param Model $entity
     * @return string
     */
    public function getEntityPageLocation(Model $entity): string
    {
        return url($entity->slug);
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function getUrlSetName(): string
    {
        return 'pages';
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function getLastModifiedDate(): string
    {
        if (!is_null($this->lastModified)) {
            return $this->lastModified->format(config('larapress.sitemap.dateformat'));
        }

        return '';
    }
}
