<?php declare(strict_types=1);

namespace Simtabi\Laranail\Barua\Builders;

use Illuminate\Support\Facades\Log;
use Simtabi\Laranail\Barua\Exceptions\BaruaException;
use Simtabi\Laranail\Barua\Support\Helpers;

class ErrorBuilder
{

    protected array $errors = [];

    public function __construct()
    {
        $this->errors = [];
    }

    /**
     * @param string|null $error
     * @param string $key
     * @return static
     * @throws BaruaException
     */
    public function setErrors(?string $error, string $key): static
    {
        if (!empty($error)) {
            // Check if the key already exists and has data
            if (isset($this->errors[$key]) && !empty($this->errors[$key])) {
                // The key exists and has data, so we append the error to the existing array
                $this->errors[$key][] = $error;
            } else {
                // The key does not exist or has no data, so we set a new array with the error
                $this->errors[$key] = [$error];
            }

            Log::warning($error);

            if (Helpers::isThrowErrors()) {
                throw new BaruaException($error);
            }
        }

        return $this;
    }

    public function getErrors(?string $key = null): array
    {
        if (!empty($key)) {
            return $this->errors[$key] ?? [];
        }

        return $this->errors;
    }

}
