<?php

namespace IMPelevin\PSPShared\Filters\CastAttributes\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use IMPelevin\PSPShared\Filters\CastAttributes\CombinedField;

trait FullName
{
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => CombinedField::fullName($this),
        );
    }
}
