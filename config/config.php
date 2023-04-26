<?php

return [
    'stubs_dir' => resource_path('stubs'),
    /**
     * When generating views, the views will be generated in the directory specified by the views_template key.
     * The views can be found in [stubs_dir]/views.
     */
    'views_template' => 'bootstrap5',

    'fields' => [
        /**
         * When enabled, when running cray ModelName will generate form fields from its migration.
         */
        'generate' => false,

        /**
         * Component paths for each field type.
         * For example, `components.text` looks for a file in resources\views\components\text.blade.php.
         */
        'component_paths' => [
            'input_text' => 'components.text',
            'input_number' => 'components.number',
            'input_checkbox' => 'components.checkbox',
            'input_radio' => 'components.radio',
            'input_file' => 'components.file',
            'input_date' => 'components.text',
            'textarea' => 'components.textarea',
        ],

        /**
         * Fields under the ignore_fields key will not be generated.
         */
        'ignore' => [
            'id',
            'created_at',
            'updated_at',
            'deleted_at',
        ],

        'localization' => [
            /**
             * When enabled, labels will be generated with __() method.
             */
            'enabled' => true,

            /**
             * /resources/lang/en/messages.php.
             */
            'key_container' => 'messages',

            /**
             * When true, the evaluated value returned from the __() function will be used.
             */
            'render' => false,
        ],
    ],
];
