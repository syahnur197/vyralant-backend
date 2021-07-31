<?php

namespace App\Services;

class YoutubeService
{
    public function formatUrlIfYoutubeLink($link)
    {
        if ($link === null || $link === "") return $link;

        // if not youtube link
        if (!$this->isYoutubeLink($link)) return $link;

        // if youtube link and contain the word embed
        if ($this->_contains($link, ['embed'])) return $link;

        return $this->_convertYoutube($link);
    }

    public function isYoutubeLink($link)
    {
        return $this->_contains($link, ['youtube.com', 'youtu.be']);
    }

    private function _convertYoutube($string)
    {
        return preg_replace(
            "/\s*[a-zA-Z\/\/:\.]*youtu(be.com\/watch\?v=|.be\/)([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i",
            "https://www.youtube.com/embed/$2",
            $string
        );
    }

    private function _contains($str, array $arr)
    {
        foreach ($arr as $a) {
            if (stripos($str, $a) !== false) return true;
        }
        return false;
    }
}
