<?php

namespace App\Traits;

trait HasScaledAttributes
{
    /**
     * Масив полів, які зберігаються у форматі ×100
     * Встановлюється в моделі.
     *
     * Example:
     * protected array $scaled = ['stock_quantity', 'cost_per_unit'];
     */
    //protected array $scaled = [];

    /**
     * ACCESSOR — ділимо на 100 для відображення
     */
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        if (in_array($key, $this->scaled) && $value !== null) {
            return $value / 100;
        }

        return $value;
    }

    /**
     * MUTATOR — множимо на 100 при збереженні
     */
    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->scaled) && $value !== null) {
            $value = (int) round($value * 100);
        }

        return parent::setAttribute($key, $value);
    }

    /**
     * Отримати сире значення з бази (без /100)
     */
    public function raw(string $key): mixed
    {
        return $this->getRawOriginal($key);
    }

    /**
     * Повертає сире значення scaled-атрибутів
     * Наприклад: $material->rawScaled('stock_quantity')
     */
    public function rawScaled(string $key): mixed
    {
        if (!in_array($key, $this->scaled)) {
            throw new \LogicException("{$key} is not a scaled attribute");
        }

        return $this->getRawOriginal($key);
    }
}
