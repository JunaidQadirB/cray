<?php

namespace JunaidQadirB\Cray;

use Illuminate\Support\Str;

class Cray
{
    public static function fields($tableName, $model = null, $markup = false)
    {
        $fields = [];
        $table = static::getTable($tableName);

        if (!$table) {
            return $fields;
        }

        foreach ($table->getColumns() as $column) {
            $name = $column->getName();

            if (in_array($name, config('cray.fields.ignore'))) {
                continue;
            }

            $label = Str::of($name)->replace('_', ' ')->title;

            switch ($column->getType()->getName()) {
                case 'string':
                    $fields[] = [
                        'component' => config('cray.fields.component_paths.input_text'),
                        'type' => 'text',
                        'label' => $label,
                        'name' => $name,
                        'value' => $model->$name ?? null,
                    ];
                    break;
                case 'text':
                    if ($name == 'photo') {
                        $fields[] = [
                            'component' => config('cray.fields.component_paths.input_file'),
                            'type' => 'text',
                            'label' => $label,
                            'name' => $name,
                            'value' => $model->$name ?? null,
                        ];
                    }
                    break;
                case 'bool':
                    $fields[] = [
                        'component' => config('cray.fields.component_paths.input_checkbox'),
                        'type' => 'text',
                        'label' => $label,
                        'name' => $name,
                        'label_i18n' => __('labels.'.$name),
                        'value' => $model->$name ?? null,
                    ];
                    break;
                case 'date':
                    $fields[] = [
                        'component' => config('cray.fields.component_paths.input_date'),
                        'type' => 'date',
                        'label' => $label,
                        'name' => $name,
                        'label_i18n' => __('labels.'.$name),
                        'value' => $model->$name ?? null,
                    ];
                    break;
                case 'datetime':
                    $fields[] = [
                        'component' => config('cray.fields.component_paths.input_datetime'),
                        'type' => 'date',
                        'label' => $label,
                        'name' => $name,
                        'label_i18n' => __('labels.'.$name),
                        'value' => $model->$name ?? null,
                    ];
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
            $valueField = ":value=\"old('{$field['name']}')\"";

            if (trim($field['value']) != '') {
                $valueField = ":value=\"old('{$field['name']}'), '{$field['value']}')\"";
            }

            $markup .= <<<ENDL
<x-dynamic-component
        component="{$field['component']}"
        name="{$field['name']}"
        label="{$field['label_i18n']}"
        type="{$field['type']}"
        {$valueField} />
ENDL;
            $markup .= "\n\n";
        }

        return $markup;
    }
}
