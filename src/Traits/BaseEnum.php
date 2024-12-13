<?php

namespace LaravelActivityLogs\Traits;

use Illuminate\Support\Str;

trait BaseEnum
{
    // * GENERIC METHODS

    /**
     * Get enum value.
     *
     * @return string|integer
     */
    public function value(): string|int
    {
        return self::toValues()[$this->name];
    }

    /**
     * Get enum label.
     *
     * @return string
     */
    public function label(): string
    {
        return self::toLabels()[$this->name];
    }

    /**
     * Get enum name.
     *
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    // * STATIC FETCH METHODS

    /**
     * Get enum labels.
     *
     * @return array<string>
     */
    public static function toLabels(): array
    {
        if (empty(self::getCache('labels'))) {
            $labels = self::getLabelsConstant();
            self::setCache('labels', \collect(self::cases())
                ->mapWithKeys(function ($enum) use ($labels) {
                    if (!\is_null($labels) and !isset($labels[$enum->name])) {
                        throw new \Error('Missing definition label ' . self::class . " for {$enum->name}");
                    }
                    return [$enum->name => \trans(
                        !\is_null($labels) ? $labels[$enum->name] : Str::of($enum->name)->ucfirst()->value()
                    )];
                })->all());
        }
        return self::getCache('labels');
    }

    /**
     * Get enum values.
     *
     * @return array<integer|string>
     */
    public static function toValues(): array
    {
        if (empty(self::getCache('values'))) {
            self::setCache('values', \collect(self::cases())
                ->mapWithKeys(fn($enum) => [$enum->name => $enum->value])->all());
        }
        return self::getCache('values');
    }

    /**
     * Get enums.
     *
     * @return array
     */
    public static function all(): array
    {
        return self::cases();
    }

    /**
     * Get enums.
     *
     * @return array<self>
     */
    public static function toArray(): array
    {
        if (empty(self::getCache('enums'))) {
            $definitions = self::getCustomDefinitions();
            self::setCache('enums', \collect(self::cases())
                ->mapWithKeys(function ($enum) use ($definitions) {
                    $baseArray = [
                        'name'  => $enum->name,
                        'label' => $enum->label(),
                        'value' => $enum->value,
                    ];
                    foreach ($definitions as $index => $elements) {
                        $index = Str::of($index)->lower()->singular()->value();
                        if (!isset($elements[$enum->name])) {
                            throw new \Error("Missing definition {$index} " . self::class . " for {$enum->name}");
                        }
                        $baseArray[$index] = $elements[$enum->name];
                    }
                    return [$enum->name => (object)$baseArray];
                })->all());
        }
        return self::getCache('enums');
    }

    /**
     * Fetch enum using value.
     *
     * @param self|integer|string $value
     * @param string              $field
     * @return self|null
     */
    public static function make(self|int|string $value, string $field = 'value'): self|null
    {
        if ($field === "label") {
            $enumLabels = array_change_key_case(array_flip(self::toLabels()), CASE_LOWER);
            $value      = (isset($enumLabels[strtolower($value)])) ? $enumLabels[strtolower($value)] : false;
            $field      = 'name';
        }
        return !\is_object($value) ?
            \collect(self::cases())->keyBy($field)->get($value) : $value;
    }

    /**
     * Test if 2 enum are equals.
     *
     * @param object ...$others
     * @return boolean
     * @throws \Error If provided element are not enums that extends BaseEnum.
     */
    public function equals(object ...$others): bool
    {
        foreach ($others as $other) {
            if (!\in_array(BaseEnum::class, \class_uses_recursive($other))) {
                throw new \Error(__METHOD__ .
                    ' requires $enum to use ' . BaseEnum::class);
            }
            if (
                get_class($this) === get_class($other)
                && $this->value === $other->value
            ) {
                return true;
            }
        }
        return false;
    }

    /**
     * Serialize to json.
     *
     * @return mixed
     */
    public function jsonSerialize(): mixed
    {
        return $this->value;
    }


    // * CACHE

    /**
     * Get cache array.
     *
     * @param string $index
     * @return array
     */
    private static function getCache(string $index): array
    {
        self::initCache();
        if (!isset($GLOBALS[self::class]['props'][$index])) {
            $GLOBALS[self::class]['props'][$index] = [];
        }
        return $GLOBALS[self::class]['props'][$index];
    }

    /**
     * Set cache array.
     *
     * @param string $index
     * @param array  $items
     * @return void
     */
    private static function setCache(string $index, array $items): void
    {
        self::initCache();
        if (!isset($GLOBALS[self::class]['props'][$index])) {
            $GLOBALS[self::class]['props'][$index] = [];
        }
        $GLOBALS[self::class]['props'][$index] = $items;
    }

    /**
     * Init cache if needed.
     *
     * @return void
     */
    private static function initCache(): void
    {
        if (!isset($GLOBALS[self::class])) {
            $GLOBALS[self::class] = [
                'props' => [
                    'enums'  => [],
                    'values' => [],
                    'labels' => []
                ],
                'customProps' => null
            ];
        }
    }

    // * PRIVATE

    /**
     * Fetch the LABELS constant if exists.
     *
     * @return array|null
     */
    private static function getLabelsConstant(): array|null
    {
        $class_name = self::class;
        try {
            $constant_reflex = new \ReflectionClassConstant($class_name, 'LABELS');
            return $constant_reflex->getValue();
        } catch (\ReflectionException $e) {
            return null;
        }
    }

    /**
     * Get custom constants.
     *
     * @return array
     */
    private static function getCustomDefinitions(): array
    {
        // ? Has custom props in cache ?
        if (!isset($GLOBALS[self::class]) or \is_null($GLOBALS[self::class]['customProps'])) {
            $names = \collect(self::cases())->keyBy('name');

            $GLOBALS[self::class]['customProps'] = \collect((new \ReflectionClass(self::class))->getConstants())
                ->filter(function (mixed $constant, string $key) use ($names) {
                    if ($key === 'LABELS' or !\is_array($constant)) {
                        return false;
                    }
                    return \collect($constant)->keys()
                        ->filter(fn(string $name) => $names->has($name))->count();
                })->all();
        }
        return $GLOBALS[self::class]['customProps'];
    }
}
