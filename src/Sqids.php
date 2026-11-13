<?php

namespace Guava\Sqids;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Sqids extends \Sqids\Sqids
{
    use Concerns\WithAlphabet;
    use Concerns\WithBlocklist;
    use Concerns\WithMinLength;
    use Concerns\WithSalt;

    /**
     * The alphabet as it was configured. The parent keeps only its shuffled form.
     */
    protected string $configuredAlphabet;

    public function __construct(
        string $alphabet = self::DEFAULT_ALPHABET,
        int $minLength = self::DEFAULT_MIN_LENGTH,
        array $blocklist = self::DEFAULT_BLOCKLIST,
    ) {
        parent::__construct($alphabet, $minLength, $blocklist);

        $this->configuredAlphabet = $alphabet === '' ? self::DEFAULT_ALPHABET : $alphabet;
    }

    public function encode(array | int $numbers): string
    {
        $numbers = Arr::wrap($numbers);

        return $this->withSaltedAlphabet(fn () => parent::encode($numbers));
    }

    public function decode(string $id): array
    {
        return $this->withSaltedAlphabet(fn () => parent::decode($id));
    }

    /**
     * The model whose ids this instance encodes. A salt() without a value is taken from it.
     */
    public function forModel(Model $model): static
    {
        $this->model = $model;

        return $this;
    }

    public static function make(array $parameters = []): static
    {
        return app(static::class, $parameters);
    }

    /**
     * Applies the configured options exactly as the constructor does: validation, the
     * shuffled alphabet and the blocklist filter. An instance configured fluently then
     * produces the same ids as one constructed with the same options.
     */
    protected function configure(string $alphabet, int $minLength, array $blocklist): static
    {
        self::__construct($alphabet, $minLength, $blocklist);

        return $this;
    }

    /**
     * Runs the callback with the salted alphabet and restores the original one afterwards,
     * so the instance stays unchanged and repeated calls return the same result.
     */
    protected function withSaltedAlphabet(callable $callback): mixed
    {
        $salt = $this->resolveSalt();

        if ($salt === null) {
            return $callback();
        }

        $alphabet = $this->alphabet;

        $this->alphabet = $this->shuffle($this->saltAlphabet($this->configuredAlphabet, $salt));

        try {
            return $callback();
        } finally {
            $this->alphabet = $alphabet;
        }
    }

    /**
     * Reorders the alphabet by the salt alone: a Fisher-Yates shuffle whose every swap is
     * picked by a hash of the salt. It depends on no random number generator, so a salt
     * gives the same alphabet on every PHP version and leaves rand() and friends untouched.
     */
    protected function saltAlphabet(string $alphabet, string $salt): string
    {
        for ($i = strlen($alphabet) - 1; $i > 0; $i--) {
            $j = hexdec(substr(hash('sha256', "{$salt}:{$i}"), 0, 8)) % ($i + 1);

            [$alphabet[$i], $alphabet[$j]] = [$alphabet[$j], $alphabet[$i]];
        }

        return $alphabet;
    }
}
