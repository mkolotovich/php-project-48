<?php

namespace Gendiff\Parser;

function parse($file) {
    $content = file_get_contents($file);
    if ($content !== false) {
        return(json_decode($content));
    }
}