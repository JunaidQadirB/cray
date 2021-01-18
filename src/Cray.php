<?php

namespace JunaidQadirB\Cray;

use Illuminate\Support\Str;

class Cray
{
    public static function fields($tableName, $model = null, $markup = false)
    {
        $fields = [];
        $table = static::getTable($tableName);

        foreach ($table->getColumns() as $column) {
            $name = $column->getName();
            $label = Str::of($name)->replace('_', ' ')->title;

            switch ($column->getType()->getName()) {
                case 'string':
                    $fields[] = [
                        'component' => config('component_paths.input.text'),
                        'type' => 'text',
                        'label' => $label,
                        'name' => $name,
                        'label_i18n' => __('labels.'.$name),
                        'value' => $model->$name ?? null,
                    ];
                    break;
                case 'text':
                    if ($name == 'photo') {
                        $fields[] = [
                            'component' => config('component_paths.input.file'),
                            'type' => 'text',
                            'label' => $label,
                            'name' => $name,
                            'label_i18n' => __('labels.'.$name),
                            'value' => $model->$name ?? null,
                        ];
                    }
                    break;
            }
        }

        if ($markup) {
            return static::generateMarkup($fields);
        }
        return $fields;
    }

    private static function getTable($name)
    {
        $schema = \DB::getDoctrineSchemaManager();

        return collect($schema->listTables())
            ->filter(function ($table) use ($name) {
                return $table->getName() === $name;
            })->first();
    }

    public static function generateMarkup($fields)
    {
        $markup = '';

        foreach ($fields as $field) {
            $markup .= <<<ENDL
<x-dynamic-component
        component="{$field['component']}"
        name="{$field['name']}"
        label="{$field['label_i18n']}"
        type="{$field['type']}"
        value="{$field['value']}" />
ENDL;
            $markup .= "\n";
        }

        return $markup;
    }
}
