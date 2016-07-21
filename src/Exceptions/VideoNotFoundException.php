<?php

namespace Andrewhood125\Exceptions;

class VideoNotFoundException extends \Exception
{
    public function __construct($message) {
        parent::__construct($message);
    }
}
