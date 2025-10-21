<?php

namespace App\Http\Codemate\Codelines\CommentProcessing;

class CommentSyntax
{
    public static function php(): array
    {
        return [
            'single_line' => ['//'],
            'multiline' => [
                '/*' => '*/'
            ]
        ];
    }

    public static function python(): array
    {
        return [
            'single_line' => ['#'],
            'multiline' => [
                '"""' => '"""',
                "'''" => "'''"
            ]
        ];
    }

    public static function html(): array
    {
        return [
            'single_line' => [],
            'multiline' => [
                '<!--' => '-->'
            ]
        ];
    }

    public static function javascript(): array
    {
        return [
            'single_line' => ['//'],
            'multiline' => [
                '/*' => '*/'
            ]
        ];
    }

    public static function css(): array
    {
        return [
            'single_line' => [],
            'multiline' => [
                '/*' => '*/'
            ]
        ];
    }

    public static function vue(): array
    {
        return [
            'single_line' => ['//'],
            'multiline' => [
                '<!--' => '-->',
                '/*' => '*/'
            ]
        ];
    }

}