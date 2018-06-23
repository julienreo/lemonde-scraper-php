<?php

require __DIR__ . '/../lib/simple_html_dom.php';

class LeMondeScraper {

    /**
     * Parser of http://www.lemonde.fr
     * 
     * @var simple_html_dom
     */
    private $parser;

    public function __construct() {
        $parser = new simple_html_dom;
        $parser->load_file('http://www.lemonde.fr/');
        $this->parser = $parser;
    }

    /**
     * @return string
     */
    public function getHeadlineId() {
        $headlineTag = $this->parser->find('.titre_une', 0);
        if (empty($headlineTag)) {
            throw new Exception("Error retrieving headline tag");
        }

        $headlineTagId = $headlineTag->getAttribute('data-back-id');
        if ($headlineTagId === false) {
            throw new Exception("Error retrieving headline tag ID");
        }

        return $headlineTagId;
    }

    /**
     * @return string
     */
    public function getHeadlineTitle() {
        $headlineLinkTitle = $this->parser->find('.titre_une a h1', 0);
        $headlineTitle = $this->parser->find('.titre_une h1', 0);
        
        if (empty($headlineLinkTitle) && empty($headlineTitle)) {
            throw new Exception("Error retrieving headline title");
        }

        $title = !empty($headlineLinkTitle) ? $headlineLinkTitle : $headlineTitle;

        // Retrieve texts contained in $title children
        $children = array_map(function($child) {
            return $child->plaintext;
        }, $title->children());

        $children = implode('', $children);

        // Remove them from $title
        $title = str_replace($children, '', $title->plaintext);

        return $title;
    }

    /**
     * @return string | null
     */
    public function getHeadlineImg() {
        $headlineImg = $this->parser->find('.titre_une a img', 0);
        if (empty($headlineImg)) {
            return null;
        }

        return $headlineImg;
    }


    /**
     * @return string | null
     */
    public function getHeadlineImgSrc() {
        $headlineImg = $this->getHeadlineImg();
        if (empty($headlineImg)) {
            return null;
        }

        $headlineImgSrc = $headlineImg->getAttribute('src');
        if ($headlineImgSrc === false) {
            return null;
        }

        return $headlineImgSrc;
    }

    /**
     * @return string | null
     */
    public function getHeadlineImgLegend() {
        $headlineImg = $this->getHeadlineImg();
        if (empty($headlineImg)) {
            return null;
        }

        $headlineImgLegend = $headlineImg->getAttribute('alt');
        if ($headlineImgLegend === false) {
            return null;
        }

        return $headlineImgLegend;
    }

    /**
     * @return string | null
     */
    public function getHeadlineArticleLink() {
        $headlineArticleLink = $this->parser->find('.titre_une a', 0)->getAttribute('href');
        if ($headlineArticleLink === false) {
            return null;
        }

        return $headlineArticleLink;
    }
}