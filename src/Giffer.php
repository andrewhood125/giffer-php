<?php

namespace Andrewhood125;

use Symfony\Component\Process\Process;
use Andrewhood125\Exceptions\VideoNotFoundException;
use Symfony\Component\Process\Exception\ProcessFailedException;

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
        if(!file_exists($video)) throw new VideoNotFoundException("File \"$video\" does not exist");

        $tmpDir = sys_get_temp_dir();
        $prefix = md5($video);
        $gifName = "$prefix.gif";

        // Create images
        self::process("avconv -ss 20\% -i $video -vf fps=10 -vframes 25 $tmpDir/$prefix-1\%3d.jpg");
        self::process("avconv -ss 40\% -i $video -vf fps=10 -vframes 25 $tmpDir/$prefix-2\%3d.jpg");
        self::process("avconv -ss 60\% -i $video -vf fps=10 -vframes 25 $tmpDir/$prefix-3\%3d.jpg");
        self::process("avconv -ss 80\% -i $video -vf fps=10 -vframes 25 $tmpDir/$prefix-4\%3d.jpg");

        // Create gif
        self::process("convert -delay 10 $tmpDir/$prefix-* $tmpDir/$gifName");

        // remove images
        self::process("rm $tmpDir/$prefix-*.jpg");

        return "$tmpDir/$gifName";
    }

    private static function process($cmd) {
        $process = new Process($cmd);
        $process->run();
        if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
        }
    }
}
