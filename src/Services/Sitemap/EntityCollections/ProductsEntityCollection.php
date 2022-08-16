<?php

namespace Larapress\Sitemap\Services\Sitemap\EntityCollections;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Larapress\ECommerce\Models\Product;
use Larapress\Sitemap\Services\Sitemap\IEntityCollection;
use Larapress\Sitemap\Services\Sitemap\ISitemapService;
use Illuminate\Support\Str;
use Larapress\Sitemap\Services\Sitemap\SitemapEntry;

class ProductsEntityCollection implements IEntityCollection
{
    protected $chunkCount;
    protected $pathParameterCallback;
    protected $pathPlaceholder;
    protected $path;
    protected $lastModified;

    public function __construct($options)
    {
        if (!isset($options['path']) || is_null($options['path'])) {
            throw new Exception("Invalid config. ProductsEntityCollection needs 'path' option.");
        }

        $this->chunkCount = $options['chunk'] ?? 100;
        $this->priority = $options['priority'] ?? 0.5;
        $this->changeFreq = $options['changefreq'] ?? 'weekly';
        $this->pathPlaceholder = $options['pathPlaceholder'] ?? '{id}';
        $this->path = $options['path'];
    }

    /**
     * Undocumented function
     *
     * @param ISitemapService $service
     * @return void
     */
    public function registerEntityUrls(ISitemapService $service)
    {
        Product::chunk($this->getChunkSize(), function ($products) use ($service) {
            /** @var Product $page */
            foreach ($products as $product) {
                $entry = new SitemapEntry(
                    $this->getEntityPageLocation($product),
                    $product->updated_at->format(config('larapress.sitemap.dateformat')),
                    $this->priority,
                    $this->changeFreq,
                );
                $prodImages = $product->data['types']['images']['slides'] ?? [];
                foreach ($prodImages as $pImage) {
                    $entry->addImage(url($pImage['image']));
                }
                $service->addUrlToSet(self::class, $entry);
                if ($product->updated_at > $this->lastModified) {
                    $this->lastModified = $product->updated_at;
                }
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
        return Str::replace(
            $this->pathPlaceholder,
            $entity->id,
            url($this->path)
        );
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function getUrlSetName(): string
    {
        return 'products';
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
