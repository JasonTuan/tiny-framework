<?php

namespace TinyFramework\Support;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

class Collection implements ArrayAccess, IteratorAggregate, Countable
{
    protected array $items = [];

    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    // Create a new collection instance
    public static function make($items = []): self
    {
        return new static($items);
    }

    // Get all items in the collection
    public function all(): array
    {
        return $this->items;
    }

    // Get the first item in the collection
    public function first(): mixed
    {
        return reset($this->items);
    }

    // Get the last item in the collection
    public function last(): mixed
    {
        return end($this->items);
    }

    public function add(mixed $item): void
    {
        $this->items[] = $item;
    }

    public function unique(): self
    {
        return new static(array_unique($this->items));
    }

    public function diff(Collection $collection): self
    {
        return new static(array_diff($this->items, $collection->all()));
    }

    public function contains(mixed $value): bool
    {
        return in_array($value, $this->items);
    }

    public function merge(Collection $collection): self
    {
        return new static(array_merge($this->items, $collection->all()));
    }

    // Filter the collection using a callback
    public function filter(callable $callback): self
    {
        return new static(array_filter($this->items, $callback));
    }

    // Map over the collection using a callback
    public function map(callable $callback): self
    {
        return new static(array_map($callback, $this->items));
    }

    // Reduce the collection to a single value
    public function reduce(callable $callback, $initial = null): mixed
    {
        return array_reduce($this->items, $callback, $initial);
    }

    // Check if the collection is empty
    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    // Count the number of items in the collection
    public function count(): int
    {
        return count($this->items);
    }

    // Convert the collection to an array
    public function toArray(): array
    {
        return $this->items;
    }

    // Convert the collection to JSON
    public function toJson($options = 0): string
    {
        return json_encode($this->items, $options);
    }

    // Implement ArrayAccess methods
    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->items);
    }

    public function offsetGet($offset): mixed
    {
        return $this->items[$offset];
    }

    public function offsetSet($offset, $value): void
    {
        if (is_null($offset)) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    public function offsetUnset($offset): void
    {
        unset($this->items[$offset]);
    }

    // Implement IteratorAggregate method
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }

    public function pluck(...$keys): self
    {
        $tmp = array_map(static function ($item) use ($keys) {
            $columns = [];
            foreach ($keys as $key) {
                $parts = explode('.', $key);
                $field = count($parts) > 1 ? $parts[count($parts) - 1] : $parts[0];
                $columns[$field] = Collection::getArrayValue($key, $item);
            }
            return $columns;
        }, $this->items);
        return new static($tmp);
    }

    public function groupBy($key): self
    {
        $groups = [];

        foreach ($this->items as $item) {
            $groups[$item[$key]][] = $item;
        }

        return new static($groups);
    }

    public function sortBy($key, $order = 'asc'): self
    {
        usort($this->items, function ($a, $b) use ($key, $order) {
            if ($order === 'desc') {
                return $b[$key] <=> $a[$key];
            }

            return $a[$key] <=> $b[$key];
        });

        return new static($this->items);
    }

    public static function getArrayValue($path, array $items = array(), $default = null, $delimiter = '.')
    {
        $keysName = explode($delimiter, $path);
        $value = $items;
        if (empty($value)) {
            return $default;
        }
        foreach ($keysName as $key) {
            if (isset($value[$key])) {
                $value = $value[$key];
            } else {
                return $default;
            }
        }

        return $value;
    }
}
