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