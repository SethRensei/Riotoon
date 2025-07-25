<?php

/**
 * Cleans and sanitizes a string input.
 * @param mixed $string The string to be cleaned.
 * @return string|null The cleaned and sanitized string, or null if input is null.
 */
function clean(mixed $string): ?string
{
    return htmlentities(stripcslashes(trim($string)));
}

/**
 * Uncleans a string input
 * @param mixed $word The string to be uncleaned
 * @return string|null The uncleaned string, or null if input is null.
 */
function unClean(mixed $string): ?string
{
    return html_entity_decode($string);
}

/**
 * Replace specific characters in a string with a given replacement.
 * @param string $subject The string in which characters will be replaced.
 * @param string $replace (Optional) The replacement character (default is '_').
 * @return string|null The modified string with characters replaced, or null if input is invalid.
 */
function replace(string $subject, string $replace = '_'): ?string
{
    return str_replace(chars(), $replace, $subject);
}

/**
 * Get an array of characters that are to be replaced in a string.
 * @return array|null An array of characters to be replaced, or null if no characters defined.
 */
function chars(): ?array {
    return ['!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '+',
                    '=', '[', ']', '{', '|', '}', '\\', ';', ':', '\'', '"', ',', 
                    '.', '<', '>', '?', '/', ' '
                ];
}

/**
 * Upload and validate a file.
 * @param mixed $file The file data (ex : $_FILES['image']).
 * @param mixed $name The desired name for the uploaded image.
 * @param string $size (Optional) The maximum size of the image file in bytes (default: '5242880').
 * @param string $directory (Optional) The directory path where the image will be stored (default: '../public/images/cover/').
 * @param array $extension (Optional) Allowed file extensions (default: ['jpeg', 'png', 'jpg', 'gif', 'jfif']).
 * @return string|null The path to the uploaded image if successful, or null if there are errors.
 */
function uploadFile(
    $file,
    $name,
    string $size = '5242880', // 5Mo
    string $directory = '../public/images/cover/',
    array $extension = ['jpeg', 'png', 'jpg', 'gif', 'jfif', 'webp']
): ?string {
    $image_name = clean($file['name']);
    if ($file['size'] <= $size) {
        $ext_img = strtolower(substr(strchr($image_name, '.'), 1));
        if (in_array($ext_img, $extension)) {
            if (!is_dir($directory))
                mkdir($directory, 0777, true);

            return $directory . strtolower($name) . '.' . $ext_img;
        } else
            BuildErrors::setErrors('file', 'L\'extension doit être ' . implode(', ', $extension));
    } else
        BuildErrors::setErrors('file', 'Le fichier fait plus de ' . ceil((int) $size / 1048576) . 'Mo');

    return '';
}

/**
 * Generate an excerpt of a given content string with a specified character limit.
 * @param mixed $content The content string to generate an excerpt from.
 * @param int $limit (Optional) The character limit for the excerpt (default: 15).
 * @return string The excerpted content with an ellipsis (...) if truncated.
 */
function excerpt($content, int $limit = 15)
{
    $content = unClean($content);
    if (mb_strlen($content) <= $limit)
        return $content;
    return mb_substr($content, 0, $limit) . '...';
}

/**
 * Generate a URL-friendly string from a given word by converting to lowercase, replacing special characters with dashes, and encoding.
 * @param string $word The input word or string to generate a URL-friendly version of.
 * @return string|null The URL-friendly string, or null if input is invalid.
 */
function goodURL(string $word): ?string
{
    $word = unClean($word);
    return urlencode(strtolower(str_replace(chars(), '-', $word)));
}

/**
 * Read a comic archive file (ZIP or CBZ) and extract image files as base64 encoded data URIs.
 * @param string $target $target The path to the ZIP or CBZ archive containing comic images.
 * @return array An array of base64 encoded image data URIs extracted from the ZIP archive.
 */
function comicReader(string $target)
{

    $zip = new ZipArchive();

    // Vérification si l'ouverture à bien réussie
    if ($zip->open($target) === true) {
        $imgs = [];
        // On parcourt l'ensemble des fichiers
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $file_into = $zip->statIndex($i);
            $filename = $file_into['name']; // Récupération du nom
            $files[$i] = $filename;
        }
        sort($files); // On trie le tableau dans l'ordre croissant
        for ($i = 0; $i < count($files); $i++) {
            $file_extension = pathinfo($files[$i], PATHINFO_EXTENSION); // Récupération de l'extension
            // Vérifie si c'est une image (JPEG, JPG, PNG, GIF)
            if (in_array($file_extension, ['jpeg', 'jpg', 'png', 'gif'])) {
                $imgs[$i] = "data:image/jpeg;base64," . base64_encode($zip->getFromName($files[$i])); // On encode pour la visibilité des images
            }
        }
        $zip->close();
    } else
        echo "<script>console.log('Impossible d'ouvrir le fichier')</script>";

    return $imgs;
}

/**
 * Allows to download a comic
 * @param string $target The path of the comic
 * @param string $title The title of the file download
 * @return void
 */
function downloadComic(string $target, string $title)
{
    if (file_exists($target)) {
        header('Content-Transfer-Encoding: binary');
        header('Content-Disposition: attachment; filename="' . $title . '_' . basename($target) . '"');
        header('Content-Length: ' . filesize($target));

        readfile($target);
        exit();
    }
}