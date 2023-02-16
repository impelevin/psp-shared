<?php

namespace IMPelevin\PSPShared\Formatters\CastAttributes\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

trait Gender
{
    protected function uiGender(): Attribute
    {
        return Attribute::make(
            get: fn () => Str::ucfirst($this->gender),
        );
    }
}
