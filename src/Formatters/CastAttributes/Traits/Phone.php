<?php

namespace IMPelevin\PSPShared\Formatters\CastAttributes\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use IMPelevin\PSPShared\Formatters\CastAttributes\CombinedField;

trait Phone
{
    protected function uiPhones(): Attribute
    {
        return Attribute::make(
            get: fn () => CombinedField::phonesCollection($this->getAttributes()),
        );
    }
}
