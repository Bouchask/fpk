<?php
// backend/api/validator.php

function validate(array $data, array $rules): array {
    $errors = [];
    foreach ($rules as $field => $ruleString) {
        $rulesArray = explode('|', $ruleString);
        foreach ($rulesArray as $rule) {
            $value = $data[$field] ?? null;
            if ($rule === 'required' && empty($value)) {
                $errors[$field][] = "Le champ $field est requis.";
            }
            if ($rule === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $errors[$field][] = "Le champ $field doit être un email valide.";
            }
            // Ajoutez d'autres règles ici (numeric, min:length, etc.)
        }
    }
    return $errors;
}
?>