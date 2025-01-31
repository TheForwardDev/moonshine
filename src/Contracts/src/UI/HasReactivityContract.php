<?php

declare(strict_types=1);

namespace MoonShine\Contracts\UI;

use Closure;
use MoonShine\Contracts\Core\DependencyInjection\FieldsContract;

/**
 * @template TFields of FieldsContract
 */
interface HasReactivityContract
{
    public function isReactive(): bool;

    public function isReactivitySupported(): bool;

    public function prepareReactivityValue(mixed $value, mixed &$casted, array &$except): mixed;

    public function getReactiveValue(): mixed;

    /**
     * @param  TFields  $fields
     *
     * @return TFields
     */
    public function getReactiveCallback(FieldsContract $fields, mixed $value, array $values): FieldsContract;

    /**
     * @param  ?Closure(TFields $fields, mixed $value, static $ctx, array $values): TFields  $callback
     */
    public function reactive(
        ?Closure $callback = null,
        bool $lazy = false,
        int $debounce = 0,
        int $throttle = 0,
    ): static;
}
