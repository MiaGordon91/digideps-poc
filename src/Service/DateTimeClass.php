<?php

namespace App\Service;

class DateTimeClass
{
    public function now(): \DateTime
    {
        return new \DateTime('now');
    }
}
