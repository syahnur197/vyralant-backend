<?php

namespace App\Services;

use App\Exceptions\NoValidImageFound;
use App\Models\Post;
use Exception;
use Illuminate\Support\Facades\Log;
use spekulatius\phpscraper;

class ImageUrlService
{
    public function getImageUrl($link)
    {
        $web = new phpscraper();
        $web->go($link);
        $images = $web->imagesWithDetails;

        list($is_youtube, $video_id) = $this->_isYoutubeVideo($link);

        if ($is_youtube) return "https://img.youtube.com/vi/$video_id/hqdefault.jpg";

        // Get the OG Image first

        $tags = get_meta_tags($link);

        if (array_key_exists("og:image", $tags)) return $tags["og:image"];
        
        if (array_key_exists("twitter:image:src", $tags)) return $tags["twitter:image:src"];

        if (array_key_exists("twitter:image", $tags)) return $tags["twitter:image"];

        $current_max_area   = 0;
        $current_image      = [];

        // grab the image with the highest height
        // assuming the featured image is the highest
        foreach ($images as $image) {
            $contains_jpg = str_contains($image['url'], "jpg");
            $contains_jpeg = str_contains($image['url'], "jpeg");
            $contains_png = str_contains($image['url'], "png");

            if (!$contains_jpeg && !$contains_jpg && !$contains_png) continue;

            $image_area = (int) $image['height'] * (int) $image['width'];

            Log::info("URL: " . $image["url"]);
            Log::info("Area: " . $image_area);
            
            if ($image_area < $current_max_area) continue;


            $current_max_area = $image_area;
            $current_image = $image;
        }

        if ($current_max_area === 0) throw new NoValidImageFound("No valid featured image found in: " . $link);


        return $current_image['url'];
    }

    private function _isYoutubeVideo($link)
    {
        $parsed = parse_url($link);
        $is_youtube = in_array($parsed['host'], ['www.youtube.com', 'youtube.com', 'youtu.be']);
        $video_id = null;

        if ($is_youtube) {
            parse_str($parsed['query'], $output);
            $video_id = $output['v'];
        }

        return [$is_youtube, $video_id];
    }
}
