<?php

namespace Juanber84\Services;


class ApplicationService
{
    public function currentTimeVersion()
    {
        return getVersion();
    }
}