<?php

use Riotoon\Service\BuildErrors;

/**
 * Cleans and sanitizes a string input.
 * @param mixed $word The string to be cleaned.
 * @return string|null The cleaned and sanitized string, or null if input is null.
 */
function clean($word): ?string
{
    // Trim leading and trailing whitespace, remove backslashes, and encode special characters
    return htmlentities(htmlspecialchars(stripslashes(trim($word))));
}

function excerpt($content, int $limit = 15)
{
    $content = html_entity_decode($content);
    if (mb_strlen($content) <= $limit)
        return $content;
    return mb_substr($content, 0, $limit) . '...';
}

function goodURL(string $word): ?string
{
    return urlencode(strtolower(str_replace(chars(), '-', $word)));
}

function uploadFile(
    $image,
    $name,
    string $directory = '../public/images/cover/',
    string $size = '5242880',
    array $extension = ['jpeg', 'png', 'jpg', 'gif', 'jfif']
): ?string {
    $image_name = clean($image['name']);
    if ($image['size'] <= $size) {
        $ext_img = strtolower(substr(strchr($image_name, '.'), 1));
        if (in_array($ext_img, $extension)) {
            if (!is_dir($directory))
                mkdir($directory, 0777, true);
            
            return $directory . strtolower($name) . '.' . $ext_img;
        } else
            BuildErrors::setErrors('image', 'L\'extension doit être ' . implode(', ', $extension));
    } else
        BuildErrors::setErrors('image', 'L\'image fait plus de 5Mo');

    return '';
}

function chars(): ?array
{
    return ['!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '+',
                    '=', '[', ']', '{', '|', '}', '\\', ';', ':', '\'', '"', ',', 
                    '.', '<', '>', '?', '/', ' '
                ];
}