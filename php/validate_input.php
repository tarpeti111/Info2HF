<?php
function validateInput($data, $type = 'string', $maxLength = null) {
    $data = trim($data);
    if ($type === 'email') {
        return filter_var($data, FILTER_VALIDATE_EMAIL) ? $data : null;
    }
    if ($type === 'string') {
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        return $maxLength ? substr($data, 0, $maxLength) : $data;
    }
    if ($type === 'int') {
        return filter_var($data, FILTER_VALIDATE_INT) ? intval($data) : null;
    }
    if ($type === 'date') {
        $format = 'Y-m-d';
        $dateTime = DateTime::createFromFormat($format, $data);
        return ($dateTime && $dateTime->format($format) === $data) ? $data : null;
    }
    return $data;
}