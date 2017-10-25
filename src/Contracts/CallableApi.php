<?php

namespace Language\Contracts;


interface CallableApi
{
    public function call($target, $mode, $getParameters, $postParameters);
}