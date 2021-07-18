<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class YoutubeService
{
    public function formatUrlIfYoutubeLink($link)
    {
        if ($link === null || $link === "") return $link;

        // if not youtube link
        if (!$this->_contains($link, ['youtube.com', 'youtu.be'])) return $link;

        // if youtube link and contain the word embed
        if ($this->_contains($link, ['embed'])) return $link;

        $query_params = parse_url($link, PHP_URL_QUERY);

        parse_str($query_params, $query);

        return "https://www.youtube.com/embed/" . $query['v'];
    }

    private function _contains($str, array $arr)
    {
        foreach ($arr as $a) {
            if (stripos($str, $a) !== false) return true;
        }
        return false;
    }
}
