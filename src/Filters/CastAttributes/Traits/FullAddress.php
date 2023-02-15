<?php

namespace IMPelevin\PSPShared\Filters\CastAttributes\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use IMPelevin\PSPShared\Filters\CastAttributes\CombinedField;

trait FullAddress
{
    protected function fullAddress(): Attribute
    {
        return Attribute::make(
            get: fn () => CombinedField::fullAddress($this),
        );
    }
}
