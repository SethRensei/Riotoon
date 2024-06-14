<?php

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
    return urlencode(strtolower(str_replace(' ', '-', $word)));
}