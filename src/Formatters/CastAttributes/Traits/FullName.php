<?php

namespace IMPelevin\PSPShared\Formatters\CastAttributes\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use IMPelevin\PSPShared\Formatters\CastAttributes\CombinedField;

trait FullName
{
    protected function uiFullName(): Attribute
    {
        return Attribute::make(
            get: fn () => CombinedField::fullName($this->getAttributes()),
        );
    }
}
