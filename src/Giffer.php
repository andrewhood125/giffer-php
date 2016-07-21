<?php

namespace Andrewhood125;

use Symfony\Component\Process\Process;

class Giffer
{
    /**
     * Take 25 frames at 10fps from 20%
     * 40%, 60%, 80% of the video.
     *
     * @param string $video - path to video
     *
     * return string /tmp/path/to/gif
     */
    public static function intervalTimelapse($video) {
        $tmpDir = sys_get_temp_dir();
        $prefix = md5($video);
        $gifName = "$prefix.gif";

        // Create images
        (new Process("avconv -ss 20\% -i $video -vf fps=10 -vframes 25 $tmpDir/$prefix-1\%3d.jpg"))->run();
        (new Process("avconv -ss 40\% -i $video -vf fps=10 -vframes 25 $tmpDir/$prefix-2\%3d.jpg"))->run();
        (new Process("avconv -ss 60\% -i $video -vf fps=10 -vframes 25 $tmpDir/$prefix-3\%3d.jpg"))->run();
        (new Process("avconv -ss 80\% -i $video -vf fps=10 -vframes 25 $tmpDir/$prefix-4\%3d.jpg"))->run();

        // Create gif
        (new Process("convert -delay 10 $tmpDir/$prefix-* $tmpDir/$gifName"))->run();

        // remove images
        (new Process("rm $tmpDir/$prefix-*.jpg"))->run();

        return "$tmpDir/$gifName";
    }
}
