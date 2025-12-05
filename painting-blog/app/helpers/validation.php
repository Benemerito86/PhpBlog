<?php
/**
 * Funciones de validación y saneamiento de datos
 */

/**
 * Validar email
 */
function validateEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validar longitud de string
 */
function validateLength($string, $min, $max = null)
{
    $length = strlen($string);

    if ($length < $min) {
        return false;
    }

    if ($max !== null && $length > $max) {
        return false;
    }

    return true;
}

/**
 * Validar username (solo letras, números y guiones bajos)
 */
function validateUsername($username)
{
    return preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username);
}

/**
 * Sanitizar string (eliminar espacios y caracteres especiales)
 */
function sanitizeString($string)
{
    $string = trim($string);
    $string = stripslashes($string);
    return $string;
}

/**
 * Escapar HTML para prevenir XSS
 */
function escapeHtml($string)
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Validar que un campo no esté vacío
 */
function validateRequired($value)
{
    return !empty(trim($value));
}

/**
 * Validar número entero
 */
function validateInteger($value)
{
    return filter_var($value, FILTER_VALIDATE_INT) !== false;
}

/**
 * Validar URL
 */
function validateUrl($url)
{
    return filter_var($url, FILTER_VALIDATE_URL) !== false;
}

/**
 * Validar contraseña (mínimo 6 caracteres)
 */
function validatePassword($password)
{
    return strlen($password) >= 6;
}

/**
 * Limpiar array de datos
 */
function sanitizeArray($data)
{
    $cleaned = [];

    foreach ($data as $key => $value) {
        if (is_array($value)) {
            $cleaned[$key] = sanitizeArray($value);
        } else {
            $cleaned[$key] = sanitizeString($value);
        }
    }

    return $cleaned;
}

/**
 * Validar CSRF token
 */
function validateCsrfToken($token)
{
    if (!isset($_SESSION['csrf_token'])) {
        return false;
    }

    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Generar CSRF token
 */
function generateCsrfToken()
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

/**
 * Validar datos de formulario
 */
function validateFormData($data, $rules)
{
    $errors = [];

    foreach ($rules as $field => $fieldRules) {
        $value = $data[$field] ?? '';

        foreach ($fieldRules as $rule => $param) {
            switch ($rule) {
                case 'required':
                    if (!validateRequired($value)) {
                        $errors[$field][] = "El campo {$field} es requerido";
                    }
                    break;

                case 'email':
                    if (!empty($value) && !validateEmail($value)) {
                        $errors[$field][] = "El email no es válido";
                    }
                    break;

                case 'min':
                    if (!validateLength($value, $param)) {
                        $errors[$field][] = "El campo {$field} debe tener al menos {$param} caracteres";
                    }
                    break;

                case 'max':
                    if (strlen($value) > $param) {
                        $errors[$field][] = "El campo {$field} no puede tener más de {$param} caracteres";
                    }
                    break;

                case 'username':
                    if (!validateUsername($value)) {
                        $errors[$field][] = "El username solo puede contener letras, números y guiones bajos (3-20 caracteres)";
                    }
                    break;

                case 'password':
                    if (!validatePassword($value)) {
                        $errors[$field][] = "La contraseña debe tener al menos 6 caracteres";
                    }
                    break;
            }
        }
    }

    return $errors;
}
