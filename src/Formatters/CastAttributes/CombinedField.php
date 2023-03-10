<?php

namespace IMPelevin\PSPShared\Formatters\CastAttributes;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

class CombinedField
{
    private static $fullNameFields = [
        'name_title',
        'first_name',
        'middle_name',
        'last_name',
        'name_suffix',
    ];

    private static $fullAddressFields = [
        'address_1',
        'address_2',
        'city',
        'state',
        'zip',
        'country',
    ];

    private static $phoneFields = [
        'mobile_phone',
        'office_phone',
        'fax',
    ];

    /**
     * Get full name
     *
     * @param Arrayable|array $data
     * @return string
     */
    public static function fullName(Arrayable|array $data): string
    {
        if ($data instanceof Arrayable)
            $data = $data->toArray();

        return self::extract($data, self::$fullNameFields)->filter()->implode(' ');
    }

    /**
     * Get full address
     *
     * @param Arrayable|array $data
     * @return string
     */
    public static function fullAddress(Arrayable|array $data): string
    {
        if ($data instanceof Arrayable)
            $data = $data->toArray();

        $collect = self::extract($data, self::$fullAddressFields);

        $stateZip = collect([
            $collect->pull('state'),
            $collect->pull('zip'),
        ])->filter()->implode(' ');

        $country = $collect->pull('country');

        return $collect
            ->values()
            ->push($stateZip)
            ->push($country)
            ->filter()
            ->implode(', ');
    }

    public static function phonesCollection(Arrayable|array $data): Collection
    {
        if ($data instanceof Arrayable)
            $data = $data->toArray();

        $collect = self::extract($data, self::$phoneFields);

        return $collect->filter()->map(function ($phone, $key) {
            switch ($key) {
                case 'mobile_phone':
                    $prefix = 'Mobile: ';
                    break;
                case 'office_phone':
                    $prefix = 'Office: ';
                    break;
                case 'fax':
                    $prefix = 'Fax: ';
                    break;
                default:
                    $prefix = '';
            }

            return $prefix.$phone;
        })->values();
    }

    /**
     * Extract the source array from the field map
     *
     * @param array $original
     * @param array $map
     * @return Collection
     */
    private static function extract(array $original, array $map): Collection
    {
        $collect = collect();

        foreach ($map as $field) {
            if (!empty($original[$field]))
                $collect[$field] = $original[$field];
        }

        return $collect;
    }

}
