<?php

declare(strict_types=1);

namespace drupol\htmltag;

use drupol\htmltag\Tag\TagFactory;
use drupol\htmltag\Tag\TagInterface;

/**
 * Class HtmlBuilder.
 */
final class HtmlBuilder implements StringableInterface
{
    /**
     * The tag scope.
     *
     * @var \drupol\htmltag\Tag\TagInterface|null
     */
    private $scope;

    /**
     * The storage.
     *
     * @var \drupol\htmltag\Tag\TagInterface[]|string[]
     */
    private $storage;

    public function __call($name, array $arguments = [])
    {
        if ('c' === $name) {
            if (!isset($arguments[0])) {
                return $this;
            }

            return $this->update(
                HtmlTag::tag('!--', [], $arguments[0])
            );
        }

        if ('_' === $name) {
            $this->scope = null;

            return $this;
        }

        $tag = TagFactory::build($name, ...$arguments);

        return $this->update($tag, true);
    }

    public function __toString(): string
    {
        $output = '';

        foreach ($this->storage as $item) {
            $output .= $item;
        }

        return $output;
    }

    /**
     * Add the current tag to the stack.
     *
     * @param \drupol\htmltag\Tag\TagInterface $tag
     *   The tag
     * @param bool $updateScope
     *   True if the current scope needs to be updated
     *
     * @return \drupol\htmltag\HtmlBuilder
     *   The HTML Builder object
     */
    private function update(TagInterface $tag, $updateScope = false)
    {
        if (null !== $this->scope) {
            $this->scope->content($this->scope->getContentAsArray(), $tag);
        } else {
            $this->storage[] = $tag;
        }

        if (true === $updateScope) {
            $this->scope = $tag;
        }

        return $this;
    }
}
