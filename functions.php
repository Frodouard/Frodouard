<?php

function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}

function redirect($path): void
{
    header("Location: " . $path);
    exit;
}

function require_login($login_path): void
{
    if (!isset($_SESSION["user_id"])) {
        redirect($login_path);
    }
}

function csrf_token(): string
{
    if (empty($_SESSION["csrf_token"])) {
        $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
    }

    return $_SESSION["csrf_token"];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="'
        . e(csrf_token()) . '">';
}

function csrf_verify(): void
{
    $token = $_POST["csrf_token"] ?? "";

    if (!hash_equals(csrf_token(), $token)) {
        http_response_code(403);
        exit("Invalid form session. Please go back and try again.");
    }
}

function flash_set($type, $message): void
{
    $_SESSION["flash"] = ["type" => $type, "message" => $message];
}

function flash_get(): array
{
    $flash = $_SESSION["flash"] ?? [];

    unset($_SESSION["flash"]);

    return $flash;
}

function flash_render(): void
{
    $flash = flash_get();

    if (!empty($flash["message"])) {
        $class = ($flash["type"] === "error") ? "error" : "message";

        echo '<p class="' . $class . '">'
            . e($flash["message"]) . "</p>";
    }
}

function is_valid_month($month): bool
{
    return (bool)preg_match("/^\d{4}-(0[1-9]|1[0-2])$/", (string)$month);
}

function is_valid_date($date): bool
{
    $d = DateTime::createFromFormat("Y-m-d", (string)$date);

    return $d && $d->format("Y-m-d") === $date;
}

?>
