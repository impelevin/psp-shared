<?php

namespace IMPelevin\PSPShared\Formatters\CastAttributes\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use IMPelevin\PSPShared\Formatters\CastAttributes\CombinedField;

trait FullAddress
{
    protected function uiFullAddress(): Attribute
    {
        return Attribute::make(
            get: fn () => CombinedField::fullAddress($this->getAttributes()),
        );
    }
}
