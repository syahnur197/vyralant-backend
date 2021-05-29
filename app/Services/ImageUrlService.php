<?php

namespace App\Services;

use App\Models\Post;
use Exception;
use spekulatius\phpscraper;

class ImageUrlService
{
    public function getImageUrl($link, $post_id)
    {
        $web = new phpscraper();
        $web->go($link);
        $images = $web->imagesWithDetails;

        list($is_youtube, $video_id) = $this->_isYoutubeVideo($link);

        if ($is_youtube) return "https://img.youtube.com/vi/$video_id/hqdefault.jpg";

        if (count($images) < 1) {
            // remove post if there is no picture
            Post::where('id', $post_id)->delete();
            throw new Exception('URL has no featured image!');
        }

        $current_max_height = 0;
        $current_image      = [];

        // grab the image with the highest height
        // assuming the featured image is the highest
        foreach ($images as $image) {
            if ($image['height'] < $current_max_height) continue;

            $current_max_height = $image['height'];
            $current_image = $image;
        }

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
