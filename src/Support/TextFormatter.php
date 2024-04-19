<?php declare(strict_types=1);

namespace Simtabi\Laranail\Barua\Support;

use Illuminate\Support\Str;

class TextFormatter
{

    public const string FORMAT_SINGLE_BRACKET = "[%s]";
    public const string FORMAT_DOUBLE_BRACKET = "[[%s]]";
    public const string FORMAT_SINGLE_CURLY_BRACKET = "{%s}";
    public const string FORMAT_DOUBLE_CURLY_BRACKET = "{{%s}}";
    public const string FORMAT_TRIPLE_CURLY_BRACKET = "{{{%s}}}";
    public const string FORMAT_ANGLE_BRACKET = "<%s>";
    public const string FORMAT_DOUBLE_ANGLE = "<<%s>>";
    public const string FORMAT_SINGLE_PARENTHESIS = "(%s)";

    /**
     * Processes an array, replacing text wrapped in square brackets with a format defined by a constant.
     *
     * @param array $array Input array containing strings with square brackets.
     * @param string $format Format for replacing text, using '%s' as a placeholder.
     * @return array Processed array with replaced text.
     */
    public static function formatArrayContents(array $array, string $format = self::FORMAT_SINGLE_BRACKET): array {
        return array_map(function($item) use ($format) {
            // Match items enclosed in square brackets and replace according to the specified format
            if (preg_match('/^\[(.*)\]$/', $item, $matches)) {
                return sprintf($format, $matches[1]);
            }
            return $item; // Return the item unchanged if it does not match the expected pattern
        }, $array);
    }

    public static function extractEnclosedText(string $content): array
    {
        $optimizeExtract = function (array $results) {
            if (empty($results)) {
                return [];
            }

            $uniqueResults = [];  // To store unique results.

            foreach ($results as $item) {
                // Check if the item already has brackets, add them if not.
                if (strncmp($item, "[", 1) === 0 && substr_compare($item, "]", -1, 1) === 0) {
                    $formattedItem = $item;  // Item already has brackets.
                } else {
                    $formattedItem = "[$item]";  // Add brackets around the item.
                }

                // Use the formatted item as a key to prevent duplicates.
                if (!array_key_exists($formattedItem, $uniqueResults)) {
                    $uniqueResults[$formattedItem] = $formattedItem;
                }
            }

            // Use array_values to reset the indices of the array and extract only the values.
            return array_values($uniqueResults);
        };

        // Define the regular expression to match text within [], {{}}, or {}
        $pattern = '/\[([^\[\]]+)\]|\{\{([^{}]+)\}\}|\{([^{}]+)\}/';

        // Use preg_match_all to find all matches
        $matches = [];
        preg_match_all($pattern, $content, $matches);

        // Flatten the array and filter out empty entries
        $results = array_filter(array_merge(...$matches), fn($value) => !empty($value));

        // Remove duplicate entries and return
        $results = array_values(array_unique($results));

        // Optimize the results by adding brackets and removing duplicates
        return $optimizeExtract(results: $results);
    }

    public static function extractTextWithinBrackets(string $text): array
    {
        $results = [];
        $start   = 0;

        while ($start < strlen($text)) {
            // Find the next opening bracket from the current start position
            $openPos = strpos($text, '[', $start);
            // If there is no more opening bracket, break out of the loop
            if ($openPos === false) {
                break;
            }

            // Find the closing bracket for the current segment
            $closePos = strpos($text, ']', $openPos);
            // If there is no closing bracket, break out of the loop
            if ($closePos === false) {
                break;
            }

            // Extract the text between the brackets
            $results[] = substr($text, $openPos + 1, $closePos - $openPos - 1);

            // Move the start position past the current closing bracket for the next iteration
            $start = $closePos + 1;
        }

        return $results;
    }

    public static function commaSeparatedStringToArray(string $text, bool $removeEmptyStrings = true): array
    {
        // Trim the input text to remove whitespace from the beginning and end
        $text = trim($text);

        // Explode the string into an array using comma as the delimiter
        $items = explode(',', $text);

        // Use array_map with 'trim' to trim whitespace from each item
        $items = array_map('trim', $items);

        // Optionally, remove empty strings from the array
        if ($removeEmptyStrings) {
            $items = array_filter($items, function($value) {
                return $value !== "";
            });
        }

        return $items;
    }

    public static function text2array(string $text, string $delimiter = ','): array
    {
        // Split the string into an array based on commas
        $mimeTypes = explode(trim($delimiter), trim($text));

        // Filter out empty values
        $mimeTypes = array_filter($mimeTypes, function($value) {
            return !empty($value);
        });

        // Remove duplicates and Re-index the array (optional, if consistent indexing is needed)
        return array_values(array_unique($mimeTypes));
    }

    public static function extractHtmlAttributes(string $string): array
    {
        $attributes = [];

        // Match all attributes with either double or single quotes
        if (
            preg_match_all(
                '/([a-zA-Z-]+)="([^"]+)"|([a-zA-Z-]+)=\'([^\']+)\'/',
                $string,
                $matches,
                PREG_SET_ORDER
            )
        ) {
            foreach ($matches as $match) {
                // Determine which capture group matched and extract the attribute and value
                $attribute = $match[1] ?: $match[3];
                $value = $match[2] ?: $match[4];

                $attributes[$attribute] = $value;
            }
        }

        return $attributes;
    }

    public static function replacePlaceholders(string $string, array $data): string
    {
        if (empty($data)) {
            return $string;
        }

        // Generate translation table
        $translationTable = [];
        foreach ($data as $key => $value) {
            $translationTable[':' . $key] = $value;
        }

        // Perform replacements
        return strtr($string, $translationTable);
    }

}