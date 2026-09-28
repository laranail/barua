<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Barua\Builders;

use Illuminate\Support\Facades\Request;
use Simtabi\Laranail\Barua\Support\Helpers;
use Simtabi\Laranail\Barua\Support\TextFormatter;

class DataBuilder
{
    /**
     * @var array<string, mixed>
     */
    protected array $data = [];

    /**
     * @var list<list<string>|string>
     */
    protected array|string $variables = [];

    /**
     * @param list<string>|string $variables names such as "[name], [email]", or a list of them
     */
    public function setVariables(array|string $variables): static
    {
        // string can be [kimani], or {{kimani}}
        if (is_string($variables)) {
            $variables = TextFormatter::commaSeparatedStringToArray($variables);
        }

        $this->variables[] = $variables;

        return $this;
    }

    /**
     * @return list<string>
     */
    public function getVariables(): array
    {
        $result = [];

        // Recursive function to flatten the array
        $flatten = function ($items) use (&$result, &$flatten): void {
            foreach ($items as $item) {
                if (is_array($item)) {
                    $flatten($item);
                } else {
                    $result[] = trim($item);
                }
            }
        };

        // Apply flattening to $this->variables
        $flatten($this->variables);

        return $result;
    }

    /**
     * @return list<string>
     */
    public function getFormattedVariables(): array
    {
        return TextFormatter::extractEnclosedText(implode(',', $this->getVariables()));
    }

    /**
     * Set or update data in the builder, using optional dot-notated keys for nested arrays.
     * Ensures uniqueness of values at the specified level or appends new non-existing values.
     *
     * @param array $data The data to be set, either as a key-value pair or a list of values.
     * @param string|null $keyPath The dot-notated path to place the data (e.g., 'key1.key2').
     * @param array<string, mixed> $data
     */
    public function setData(array $data, ?string $keyPath = null): static
    {

        if (! empty($keyPath)) {
            $this->data = Helpers::setNestedData(data: $this->data, keys: explode('.', $keyPath), value: $data);
        } else {
            $this->data = array_merge($this->data, $data);
        }

        return $this;
    }

    /**
     * Retrieve data from a nested array using a key path in dot notation.
     * Returns null if any part of the path is invalid.
     *
     * @param string|null $keyPath Dot-notated path for the data (e.g., 'key1.key2').
     *
     * @return mixed Data at the specified path or null if path is invalid.
     */
    public function getData(?string $keyPath = null): mixed
    {
        $mapVariables = function (array $data): array {
            $variables = $this->getFormattedVariables();
            $result = [];

            // Validate that getVariables returned a non-empty array of strings
            if ($variables === []) {
                return $result;
            }

            // Iterate through the list of variable names and add valid entries from $data
            foreach ($variables as $variable) {
                if (array_key_exists($variable, $data) || isset($data[$variable])) {
                    $result[$variable] = $data[$variable];
                }
            }

            return $result;
        };

        $this->setLogoPath();

        if (empty($keyPath)) {
            return $this->data;
        }

        $keys = explode('.', $keyPath);
        $temp = $this->data;

        foreach ($keys as $key) {
            if (! isset($temp[$key])) {
                return null; // Return null if the key is not found at any level.
            }

            $temp = $temp[$key];
        }

        return $mapVariables(data: $temp);
    }

    private function setLogoPath(): void
    {
        $this->data['logo']['path'] = str_replace(
            search: '%PUBLIC%',
            replace: Request::getSchemeAndHttpHost(),
            subject: $this->data['logo']['path'] ?? '',
        );
    }
}
