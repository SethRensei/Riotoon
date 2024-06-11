<?php

/**
 * Creates and generates an HTML message for displaying flash alerts.
 * @param string $type_alert The type of alert (e.g., "success", "info", "warning", "danger").
 * @param string $message The message to be displayed in the alert.
 * @return string The HTML code for the flash alert message.
 */
function messageFlash(string $type_alert, string $message): string
{
    return <<<HTML
    <div class="alert alert-{$type_alert} alert-dismissible" role="alert">
        <strong class="ms-3">{$message}</strong>
        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
    </div>
HTML;
}