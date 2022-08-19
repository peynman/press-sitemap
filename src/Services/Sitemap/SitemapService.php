<?php

namespace Larapress\Sitemap\Services\Sitemap;

use DOMDocument;
use Illuminate\Support\Facades\Storage;

class SitemapService implements ISitemapService
{
    protected $urlSets = [];
    protected $collectionsDic = [];

    public function generateSitemap()
    {
        $collections = config('larapress.sitemap.collections');
        if (is_array($collections)) {
            foreach ($collections as $collection => $options) {
                /** @var IEntityCollection */
                $entityCollection = new $collection($options);
                $this->collectionsDic[$collection] = $entityCollection;
                $entityCollection->registerEntityUrls($this);
            }
        }

        $this->generateSitemapIndex();
        foreach ($this->urlSets as $set => $entries) {
            /** @var IEntityCollection $collection */
            $collection = $this->collectionsDic[$set];
            $this->generateCollectionUrlsets($collection, $entries);
        }
    }

    /**
     * Undocumented function
     *
     * @param string $urlset
     * @param SitemapEntry $entry
     * @return void
     */
    public function addUrlToSet($urlset, SitemapEntry $entry)
    {
        if (!isset($this->urlSets[$urlset]) || !is_array($this->urlSets[$urlset])) {
            $this->urlSets[$urlset] = [];
        }

        $this->urlSets[$urlset][] = $entry;
    }

    /**
     * Undocumented function
     *
     * @param  $xml
     * @param  $path
     * @return void
     */
    protected function writeXmlToFile($xml, $filename)
    {
        Storage::disk(config('larapress.sitemap.output.disk'))->put(
            config('larapress.sitemap.output.path') . '/' . $filename,
            $xml,
        );
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    protected function generateSitemapIndex()
    {
        $root = new DOMDocument();
        $root->xmlVersion = '1.0';
        $root->encoding = 'UTF-8';
        $root->formatOutput = true;
        $sitemapindex = $root->createElementNS('http://www.sitemaps.org/schemas/sitemap/0.9', 'sitemapindex');
        foreach ($this->urlSets as $set => $entries) {
            /** @var IEntityCollection $collection */
            $collection = $this->collectionsDic[$set];
            $sitemap = $root->createElement('sitemap');
            $sitemap->appendChild($root->createElement('loc', url('/storage/sitemap/' . $collection->getUrlSetName() . '.xml')));
            $lastModified = $collection->getLastModifiedDate();
            if (!empty($lastModified)) {
                $sitemap->appendChild($root->createElement('lastmod', $lastModified));
            }
            $sitemapindex->appendChild($sitemap);
        }
        $root->appendChild($sitemapindex);
        $this->writeXmlToFile($root->saveXML(), 'index.xml');
    }

    protected function generateCollectionUrlsets(IEntityCollection $collection, $urlsets)
    {
        $root = new DOMDocument();
        $root->xmlVersion = '1.0';
        $root->encoding = 'UTF-8';
        $root->formatOutput = true;
        $sitemapindex = $root->createElementNS('http://www.sitemaps.org/schemas/sitemap/0.9', 'urlset');
        $sitemapindex->setAttribute('xmlns:image', 'http://www.google.com/schemas/sitemap-image/1.1');
        $sitemapindex->setAttribute('xmlns:video', 'http://www.google.com/schemas/sitemap-video/1.1');
        /** @var SitemapEntry $entry */
        foreach ($urlsets as $entry) {
            $sitemapindex->appendChild($root->importNode($entry->getNode(), true));
        }
        $root->appendChild($sitemapindex);
        $this->writeXmlToFile($root->saveXML(), $collection->getUrlSetName() . '.xml');
    }
}
