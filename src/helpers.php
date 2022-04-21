<?php

if (! function_exists('className')) {

    /**
     * Get instance class name without namespace.
     *
     * @param $instance
     * @return string
     */
    function className($instance)
    {
        return (new ReflectionClass($instance))->getShortName();
    }
}

if (! function_exists('str_to_words')) {
    function str_to_words($input)
    {
        $re = '/(?#! splitCamelCase Rev:20140412)
    # Split camelCase "words". Two global alternatives. Either g1of2:
      (?<=[a-z])      # Position is after a lowercase,
      (?=[A-Z])       # and before an uppercase letter.
    | (?<=[A-Z])      # Or g2of2; Position is after uppercase,
      (?=[A-Z][a-z])  # and before upper-then-lower case.
    /x';
        $result = preg_split($re, $input);

        return implode(' ', $result);
    }
}

if (! function_exists('studly_to_words')) {
    function studly_to_words($text)
    {
        $data = preg_split('/(?=[A-Z])/', class_basename($text));

        return trim(implode(' ', $data));
    }
}

if (! function_exists('has_permission')) {
    function has_permission(\App\User $user, string $ability): bool
    {
        $userRoles = $user->roles;
        $hasPermission = false;

        foreach ($userRoles as $role) {
            foreach ($role->permissions as $permission) {
                if ($permission->name == $ability) {
                    $hasPermission = true;
                    break;
                }
            }
        }

        return $hasPermission;
    }
}
