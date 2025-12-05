<?php
/**
 * Funciones para gestión de subida de archivos
 */

/**
 * Subir imagen
 */
function uploadImage($file, $directory = UPLOAD_PATH)
{
    // Verificar que se haya subido un archivo
    if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return ['success' => false, 'message' => 'No se ha seleccionado ningún archivo'];
    }

    // Verificar errores de subida
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'Error al subir el archivo'];
    }

    // Verificar tamaño del archivo
    if ($file['size'] > MAX_FILE_SIZE) {
        $maxSizeMB = MAX_FILE_SIZE / (1024 * 1024);
        return ['success' => false, 'message' => "El archivo es demasiado grande. Máximo {$maxSizeMB}MB"];
    }

    // Verificar tipo MIME
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mimeType, ALLOWED_MIME_TYPES)) {
        return ['success' => false, 'message' => 'Tipo de archivo no permitido. Solo se permiten imágenes'];
    }

    // Verificar extensión
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($extension, ALLOWED_EXTENSIONS)) {
        return ['success' => false, 'message' => 'Extensión de archivo no permitida'];
    }

    // Generar nombre único para el archivo
    $filename = generateUniqueFilename($extension);
    $destination = $directory . '/' . $filename;

    // Crear directorio si no existe
    if (!file_exists($directory)) {
        mkdir($directory, 0755, true);
    }

    // Mover archivo al destino
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return [
            'success' => true,
            'filename' => $filename,
            'path' => $destination,
            'message' => 'Archivo subido exitosamente'
        ];
    }

    return ['success' => false, 'message' => 'Error al mover el archivo'];
}

/**
 * Generar nombre único para archivo
 */
function generateUniqueFilename($extension)
{
    return uniqid('img_', true) . '.' . $extension;
}

/**
 * Eliminar archivo
 */
function deleteFile($filename, $directory = UPLOAD_PATH)
{
    $filepath = $directory . '/' . $filename;

    if (file_exists($filepath)) {
        return unlink($filepath);
    }

    return false;
}

/**
 * Validar imagen
 */
function validateImage($file)
{
    $errors = [];

    // Verificar que se haya subido un archivo
    if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        $errors[] = 'No se ha seleccionado ningún archivo';
        return $errors;
    }

    // Verificar errores de subida
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors[] = 'Error al subir el archivo';
        return $errors;
    }

    // Verificar tamaño
    if ($file['size'] > MAX_FILE_SIZE) {
        $maxSizeMB = MAX_FILE_SIZE / (1024 * 1024);
        $errors[] = "El archivo es demasiado grande. Máximo {$maxSizeMB}MB";
    }

    // Verificar tipo MIME
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mimeType, ALLOWED_MIME_TYPES)) {
        $errors[] = 'Tipo de archivo no permitido. Solo se permiten imágenes';
    }

    // Verificar extensión
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($extension, ALLOWED_EXTENSIONS)) {
        $errors[] = 'Extensión de archivo no permitida';
    }

    // Verificar que sea una imagen real
    if (empty($errors)) {
        $imageInfo = getimagesize($file['tmp_name']);
        if ($imageInfo === false) {
            $errors[] = 'El archivo no es una imagen válida';
        }
    }

    return $errors;
}

/**
 * Redimensionar imagen (opcional, requiere GD)
 */
function resizeImage($source, $destination, $maxWidth = 1200, $maxHeight = 1200)
{
    // Obtener información de la imagen
    list($width, $height, $type) = getimagesize($source);

    // Si la imagen ya es más pequeña, no redimensionar
    if ($width <= $maxWidth && $height <= $maxHeight) {
        return copy($source, $destination);
    }

    // Calcular nuevas dimensiones manteniendo proporción
    $ratio = min($maxWidth / $width, $maxHeight / $height);
    $newWidth = round($width * $ratio);
    $newHeight = round($height * $ratio);

    // Crear imagen desde el archivo
    switch ($type) {
        case IMAGETYPE_JPEG:
            $image = imagecreatefromjpeg($source);
            break;
        case IMAGETYPE_PNG:
            $image = imagecreatefrompng($source);
            break;
        case IMAGETYPE_GIF:
            $image = imagecreatefromgif($source);
            break;
        default:
            return false;
    }

    // Crear nueva imagen redimensionada
    $newImage = imagecreatetruecolor($newWidth, $newHeight);

    // Preservar transparencia para PNG y GIF
    if ($type === IMAGETYPE_PNG || $type === IMAGETYPE_GIF) {
        imagealphablending($newImage, false);
        imagesavealpha($newImage, true);
    }

    // Redimensionar
    imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    // Guardar imagen
    switch ($type) {
        case IMAGETYPE_JPEG:
            $result = imagejpeg($newImage, $destination, 90);
            break;
        case IMAGETYPE_PNG:
            $result = imagepng($newImage, $destination, 9);
            break;
        case IMAGETYPE_GIF:
            $result = imagegif($newImage, $destination);
            break;
        default:
            $result = false;
    }

    // Liberar memoria
    imagedestroy($image);
    imagedestroy($newImage);

    return $result;
}

/**
 * Obtener extensión de archivo
 */
function getFileExtension($filename)
{
    return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
}

/**
 * Formatear tamaño de archivo
 */
function formatFileSize($bytes)
{
    $units = ['B', 'KB', 'MB', 'GB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);

    return round($bytes, 2) . ' ' . $units[$pow];
}
