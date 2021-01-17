<?php

namespace JunaidQadirB\Cray;

use Illuminate\Support\Str;

class Cray
{
    public function generateFormFields($tableName): array
    {
        $fields = [];
        $table = $this->getTable($tableName);

        foreach ($table->getColumns() as $column) {
            $name = $column->getName();
            $label = Str::of($name)->replace('_', ' ')->title;

            switch ($column->getType()->getName()) {
                case 'string':
                    $fields[] = [
                        'component' => 'themes.default.input',
                        'type' => 'text',
                        'label' => $label,
                        'name' => $name,
                        'label_i18n' => __('labels.'.$name)
                    ];
                    break;
            }
        }

        return $fields;
    }

    private function getTable($name)
    {
        $schema = \DB::getDoctrineSchemaManager();

        return collect($schema->listTables())
            ->filter(function ($table) use ($name) {
                return $table->getName() === $name;
            })->first();
    }
}
