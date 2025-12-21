<?php

namespace Spanvel\Support\Form;

use JsonSerializable;

class Field implements JsonSerializable
{
    /**
     * The fields keyed by name.
     *
     * @var array<string, array{value:mixed, options:array}>
     */
    protected array $fields = [];

    /**
     * Create a new element.
     */
    public static function make(): static
    {
        return new static;
    }

    /**
     * Add a field value and optional options.
     */
    public function field(string $name, mixed $value = null, array $options = []): static
    {
        $this->fields[$name] = [
            'value' => $value,
            'options' => $options,
        ];

        return $this;
    }

    /**
     * Get data payload (values only).
     */
    public function toData(): array
    {
        $data = [];

        foreach ($this->fields as $name => $field) {
            $data[$name] = $field['value'];
        }

        return $data;
    }

    /**
     * Get options payload (only fields that have options).
     */
    public function toOptions(): array
    {
        $options = [];

        foreach ($this->fields as $name => $field) {
            if (! empty($field['options'])) {
                $options[$name] = $field['options'];
            }
        }

        return $options;
    }

    /**
     * JSON response shape.
     */
    public function jsonSerialize(): array
    {
        return [
            'data' => $this->toData(),
            'options' => $this->toOptions(),
        ];
    }
}
