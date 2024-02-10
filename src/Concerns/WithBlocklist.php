<?php

namespace Guava\Sqids\Concerns;

use Sqids\Sqids;

trait WithBlocklist
{
    public function blocklist(array $blocklist): static
    {
        $filteredBlocklist = [];
        $alphabetChars = str_split(strtolower($this->getAlphabet()));
        foreach ($blocklist as $word) {
            if (strlen((string) $word) >= 3) {
                $wordLowercased = strtolower($word);
                $wordChars = str_split($wordLowercased);
                $intersection = array_filter($wordChars, fn ($c) => in_array($c, $alphabetChars));
                if (count($intersection) == count($wordChars)) {
                    $filteredBlocklist[] = strtolower($wordLowercased);
                }
            }
        }
        $this->blocklist = $filteredBlocklist;

        return $this;
    }

    public function getBlocklist(): array
    {
        return $this->blocklist ?? Sqids::DEFAULT_BLOCKLIST;
    }
}
