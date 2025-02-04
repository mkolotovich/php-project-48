<?php

namespace Gendiff\Parser;

function parse($data)
{
    if ($data !== false) {
        return(json_decode($data, true));
    }
}
