<?php

declare(strict_types=1);

namespace drupol\htmltag;

/**
 * Interface RenderableInterface.
 */
interface RenderableInterface
{
    /**
     * Render the object.
     *
     * @return string
     *   The object rendered in a string
     */
    public function render(): string;
}
