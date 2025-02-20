<?php


namespace {
    if (!function_exists('get_env')) {

        function get_env(string $name, #[SensitiveParameter] mixed $default): string|int|float|bool|null
        {
            $env = $_ENV[$name] ?? getenv($name);

            if ($env === false || $env === '') {
                return $default;
            }
            if (is_numeric($env)) {
                return (int)$env == $env ? (int)$env : (float)$env;
            }
            return match ($env) {
                'true', 'TRUE' => true,
                'false', 'FALSE' => false,
                'null', 'NULL' => null,
                default => $env,
            };
        }
    }

    if (!function_exists('hl_get_env')) {

        function hl_get_env(string $name, #[SensitiveParameter] mixed $default): string|int|float|bool|null
        {
            return get_env($name, $default);
        }
    }

    if (!function_exists('env')) {

        function env(string $name, #[SensitiveParameter] string $default): string
        {
            $env = $_ENV[$name] ?? getenv($name);

            return  $env === false || $env === '' ? $default : (string)$env;
        }
    }

    if (!function_exists('hl_env')) {

        function hl_env(string $name, #[SensitiveParameter] string $default): string
        {
            return env($name, $default);
        }
    }

    if (!function_exists('env_bool')) {

        function env_bool(string $name, #[SensitiveParameter] bool $default): bool
        {
            $env = $_ENV[$name] ?? getenv($name);
            return match ($env) {
                'true', 'TRUE', '1' => true,
                'false', 'FALSE', '0' => false,
                false, '' => $default,
                default => (bool)$env,
            };
        }
    }

    if (!function_exists('hl_env_bool')) {

        function hl_env_bool(string $name, #[SensitiveParameter] bool $default): bool
        {
            return env_bool($name, $default);
        }
    }

    if (!function_exists('env_int')) {

        function env_int(string $name, #[SensitiveParameter] int $default): int
        {
            $env = $_ENV[$name] ?? getenv($name);
            $env = match ($env) {
                'true', 'TRUE', '1' => 1,
                'false', 'FALSE', '0' => 0,
                default => $env,
            };
            if ($env && !is_numeric($env)) {
                throw new RuntimeException("The value of the environment variable `{$name}` is expected to be an integer!");
            }
            return $env === false || $env === '' ? $default : (int)$env;
        }
    }

    if (!function_exists('hl_env_int')) {

        function hl_env_int(string $name, #[SensitiveParameter] int $default): int
        {
            return env_int($name, $default);
        }
    }

    if (!function_exists('env_array')) {

        function env_array(string $name, #[SensitiveParameter] array $default): array
        {
            $env = $_ENV[$name] ?? getenv($name);
            if ($env === false || $env === '') {
                return $default;
            }
            if (str_starts_with($env, '{') && str_ends_with($env,'}')) {
                return json_decode($env, true, JSON_THROW_ON_ERROR);
            }
            throw new RuntimeException("The value of the environment variable `{$name}` is expected to be an JSON string!");
        }
    }

    if (!function_exists('hl_env_array')) {

        function hl_env_array(string $name, #[SensitiveParameter] array $default): array
        {
            return env_array($name, $default);
        }
    }

    if (!function_exists('_e')) {

        function _e(#[SensitiveParameter] mixed $value): string
        {
            return htmlentities((string)$value, ENT_QUOTES, 'UTF-8');
        }
    }

    if (!function_exists('get_constant')) {

        function get_constant(string $name, mixed $default = null): mixed
        {
            return defined($name) ? constant($name) : $default;
        }
    }

    if (!function_exists('redefine')) {

        function redefine(string $name, mixed $value = null): void
        {
            defined($name) or define($name, $value);
        }
    }
}
