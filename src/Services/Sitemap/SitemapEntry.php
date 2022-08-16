<?php

namespace Larapress\Sitemap\Services\Sitemap;

use DOMDocument;
use DOMElement;

class SitemapEntry
{
    protected $images = [];
    protected $videos = [];

    public function __construct(
        public string $url,
        public ?string $lastModified = null,
        public ?string $priority = null,
        public ?string $changefreq = null,
    ) {
    }


    public function addImage($loc): SitemapEntry
    {
        $this->images[] = [
            'loc' => $loc,
        ];
        return $this;
    }

    public function addVideo(
        $loc,
        $thumbnail_loc,
        $title,
        $description,
        $content_loc
    ): SitemapEntry {
        $this->videos[] = [
            'loc' => $loc,
            'thumbnail_loc' => $thumbnail_loc,
            'title' => $title,
            'description' => $description,
            'content_loc' => $content_loc,
        ];
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return DOMElement
     */
    public function getNode(): DOMElement
    {
        $dom = new DOMDocument();
        $root = $dom->createElement('url');
        $root->appendChild($dom->createElement('loc', $this->url));
        if (!is_null($this->lastModified)) {
            $root->appendChild($dom->createElement('lastmod', $this->lastModified));
        }
        if (!is_null($this->changefreq)) {
            $root->appendChild($dom->createElement('changefreq', $this->changefreq));
        }
        if (!is_null($this->priority)) {
            $root->appendChild($dom->createElement('priority', $this->priority));
        }
        if (count($this->images) > 0) {
            foreach($this->images as $image) {
                $img = $dom->createElement('image:image');
                $img->appendChild($dom->createElement('image:loc', $image['loc']));
                $root->appendChild($img);
            }
        }
        if (count($this->videos) > 0) {
            foreach($this->videos as $video) {
                $vid = $dom->createElement('video:video');
                $vid->appendChild($dom->createElement('video:loc', $video['loc']));
                $vid->appendChild($dom->createElement('video:thumbnail_loc', $video['thumbnail_loc']));
                $vid->appendChild($dom->createElement('video:title', $video['title']));
                $vid->appendChild($dom->createElement('video:description', $video['description']));
                $vid->appendChild($dom->createElement('video:content_loc', $video['content_loc']));
                $root->appendChild($vid);
            }
        }

        return $root;
    }
}
