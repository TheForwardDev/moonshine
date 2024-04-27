<?php

declare(strict_types=1);

namespace MoonShine\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\ComponentSlot;
use MoonShine\Components\ActionButtons\ActionButton;
use MoonShine\Support\Condition;

/**
 * @method static static make(Closure|string $title, Closure|View|string $content, Closure|View|ActionButton|string $outer = '', Closure|string|null $asyncUrl = '', iterable $components = [])
 */
final class Modal extends AbstractWithComponents
{
    protected string $view = 'moonshine::components.modal';

    protected bool $open = false;

    protected bool $closeOutside = true;

    protected bool $wide = false;

    protected bool $auto = false;

    protected bool $autoClose = true;

    protected array $outerAttributes = [];

    public function __construct(
        protected Closure|string $title = '',
        protected Closure|View|string $content = '',
        protected Closure|View|ActionButton|string $outer = '',
        protected Closure|string|null $asyncUrl = null,
        iterable $components = [],
        // anonymous component variables
        string $name = 'default'
    ) {
        parent::__construct($components);
    }


    public function open(Closure|bool|null $condition = null): self
    {
        $this->open = is_null($condition) || Condition::boolean($condition, false);

        return $this;
    }

    public function closeOutside(Closure|bool|null $condition = null): self
    {
        $this->closeOutside = is_null($condition) || Condition::boolean($condition, false);

        return $this;
    }

    public function wide(Closure|bool|null $condition = null): self
    {
        $this->wide = is_null($condition) || Condition::boolean($condition, false);

        return $this;
    }

    public function auto(Closure|bool|null $condition = null): self
    {
        $this->auto = is_null($condition) || Condition::boolean($condition, false);

        return $this;
    }

    public function autoClose(Closure|bool|null $autoClose = null): self
    {
        $this->autoClose = is_null($autoClose) || Condition::boolean($autoClose, false);

        return $this;
    }

    public function outerAttributes(array $attributes): self
    {
        $this->outerAttributes = $attributes;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    protected function viewData(): array
    {
        $componentsHtml = $this->getComponents()->isNotEmpty()
            ? Components::make($this->getComponents())
            : '';

        $outer = value($this->outer, $this);

        if ($outer instanceof ActionButton) {
            $outer->openModal();
        }

        return [
            'isWide' => $this->wide,
            'isOpen' => $this->open,
            'isAuto' => $this->auto,
            'isAutoClose' => $this->autoClose,
            'isCloseOutside' => $this->closeOutside,
            'async' => ! empty($this->asyncUrl),
            'asyncUrl' => value($this->asyncUrl, $this) ?? '',
            'title' => value($this->title, $this),
            'slot' => new ComponentSlot(value($this->content, $this) . $componentsHtml),
            'outerHtml' => new ComponentSlot($outer, $this->outerAttributes),
        ];
    }
}
