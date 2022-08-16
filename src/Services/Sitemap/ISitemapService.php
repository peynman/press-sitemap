<?php

namespace Larapress\Sitemap\Services\Sitemap;

interface ISitemapService {
    /**
     * Undocumented function
     *
     * @return void
     */
    public function generateSitemap();

    /**
     * Undocumented function
     *
     * @param IEntityCollection $urlset
     * @param SitemapEntry $entry
     *
     * @return void
     */
    public function addUrlToSet($urlset, SitemapEntry $entry);
}
