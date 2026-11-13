<?php

declare(strict_types=1);

namespace Studeo\UiSpec\Tests\Support;

use LogicException;
use Opis\JsonSchema\Errors\ErrorFormatter;
use Opis\JsonSchema\Validator;

/**
 * Reads the files of the specification, checks a document against the schema that belongs to it,
 * and changes a copy of a document at a path so that a test can seed a mistake.
 */
final class Spec
{
    public static function root(): string
    {
        return dirname(__DIR__, 2);
    }

    /**
     * @return array<string, mixed>
     */
    public static function decode(string $relativePath): array
    {
        $decoded = self::keepEmptyObjects(json_decode((string) file_get_contents(self::root().'/'.$relativePath), false, 512, JSON_THROW_ON_ERROR));

        return is_array($decoded) ? $decoded : [];
    }

    /**
     * Turns objects into arrays, except an empty object, which stays an object so that it is written
     * back as `{}` and not as `[]`.
     */
    private static function keepEmptyObjects(mixed $value): mixed
    {
        if (is_array($value)) {
            return array_map(self::keepEmptyObjects(...), $value);
        }

        if ($value instanceof \stdClass) {
            $properties = get_object_vars($value);

            return $properties === [] ? $value : array_map(self::keepEmptyObjects(...), $properties);
        }

        return $value;
    }

    /**
     * @return array<string, mixed>
     */
    public static function manifest(): array
    {
        return self::decode('spec.json');
    }

    /**
     * The path, relative to the root of the package, of the data file or the schema of a key of the manifest.
     */
    public static function pathOf(string $key, string $kind): string
    {
        return self::manifest()['files'][$key][$kind];
    }

    /**
     * @return array<string, mixed>
     */
    public static function data(string $key): array
    {
        return self::decode(self::pathOf($key, 'data'));
    }

    /**
     * Every problem that the schema of the key finds in the document, one line each in the form
     * `<pointer>: <message>`, where the pointer is the place in the document (`/components/0/name`);
     * empty when the document is valid. The key `spec` stands for the manifest, whose schema is not
     * named by the manifest.
     *
     * @param  array<string, mixed>  $document
     * @return list<string>
     */
    public static function errors(string $key, array $document): array
    {
        $schemaPath = $key === 'spec' ? 'schemas/spec.schema.json' : self::pathOf($key, 'schema');
        $schema = json_decode((string) file_get_contents(self::root().'/'.$schemaPath), false, 512, JSON_THROW_ON_ERROR);
        $data = json_decode(json_encode($document, JSON_THROW_ON_ERROR), false, 512, JSON_THROW_ON_ERROR);
        $result = (new Validator)->validate($data, $schema);

        if ($result->isValid()) {
            return [];
        }

        $keyed = (new ErrorFormatter)->formatKeyed($result->error() ?? throw new LogicException('An invalid result has an error.'));
        $lines = [];

        foreach ($keyed as $pointer => $messages) {
            foreach ($messages as $message) {
                $lines[] = "{$pointer}: {$message}";
            }
        }

        return $lines;
    }

    /**
     * A copy of the document with the value set at the path, in dot notation (`components.0.name`).
     *
     * @param  array<string, mixed>  $document
     * @return array<string, mixed>
     */
    public static function set(array $document, string $path, mixed $value): array
    {
        return self::change($document, explode('.', $path), $value, false);
    }

    /**
     * A copy of the document without the value at the path.
     *
     * @param  array<string, mixed>  $document
     * @return array<string, mixed>
     */
    public static function forget(array $document, string $path): array
    {
        return self::change($document, explode('.', $path), null, true);
    }

    /**
     * @param  array<array-key, mixed>  $node
     * @param  list<string>  $segments
     * @return array<array-key, mixed>
     */
    private static function change(array $node, array $segments, mixed $value, bool $remove): array
    {
        $key = array_shift($segments);
        $key = ctype_digit($key) ? (int) $key : $key;

        if ($segments === []) {
            return self::apply($node, $key, $value, $remove);
        }

        $node[$key] = self::change((array) ($node[$key] ?? []), $segments, $value, $remove);

        return $node;
    }

    /**
     * @param  array<array-key, mixed>  $node
     * @return array<array-key, mixed>
     */
    private static function apply(array $node, int|string $key, mixed $value, bool $remove): array
    {
        if (! $remove) {
            $node[$key] = $value;

            return $node;
        }

        unset($node[$key]);

        return array_is_list($node) ? array_values($node) : $node;
    }
}
