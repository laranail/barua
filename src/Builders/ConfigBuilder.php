<?php declare(strict_types=1);

namespace Simtabi\Laranail\Barua\Builders;

class ConfigBuilder
{

    protected array $options = [];

    public function __construct()
    {
    }

    public function setOptions(mixed $value, string $path, string $separator = '.'): static
    {
        $keys    = explode($separator, $path);
        $lastKey = array_pop($keys);
        $temp    =& $this->options;

        foreach ($keys as $key) {
            if (!isset($temp[$key]) || !is_array($temp[$key])) {
                $temp[$key] = [];
            }
            $temp =& $temp[$key];
        }

        // Check if the last key already exists and has data
        if (isset($temp[$lastKey]) && !empty($temp[$lastKey])) {
            // The key exists and has data, so we append the value to the existing array
            if (!is_array($temp[$lastKey])) {
                $temp[$lastKey] = [$temp[$lastKey]];
            }

            $temp[$lastKey][] = $value;
        } else {
            // The key does not exist or has no data, so we set a new value
            $temp[$lastKey] = $value;
        }

        return $this;
    }

    /**
     * Get a specific option by key, using dot notation for nested keys,
     * or return all options if no key is provided.
     *
     * @param string|null $path The key using dot notation for nested values, or null to get all options.
     * @return mixed
     */
    public function getOptions(?string $path = null): mixed
    {
        if ($path === null) {
            return $this->options;
        }

        $keys = explode('.', $path);
        $temp = $this->options;

        foreach ($keys as $key) {
            if (!isset($temp[$key])) {
                return null; // Key does not exist, return null
            }

            $temp = $temp[$key];
        }

        return $temp;
    }

}
