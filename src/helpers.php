<?php

if ( ! function_exists('className')) {

    /**
     * Get instance class name without namespace
     *
     * @param $instance
     *
     * @return string
     */
    function className($instance)
    {
        return (new \ReflectionClass($instance))->getShortName();
    }

}

if ( ! function_exists('str_to_words')) {
    function str_to_words($input)
    {
        $re     = '/(?#! splitCamelCase Rev:20140412)
    # Split camelCase "words". Two global alternatives. Either g1of2:
      (?<=[a-z])      # Position is after a lowercase,
      (?=[A-Z])       # and before an uppercase letter.
    | (?<=[A-Z])      # Or g2of2; Position is after uppercase,
      (?=[A-Z][a-z])  # and before upper-then-lower case.
    /x';
        $result = preg_split($re, $input);

        return implode(" ", $result);
    }

}