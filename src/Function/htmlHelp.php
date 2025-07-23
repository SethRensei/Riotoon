<?php


/**
 * Creates and generates an HTML message for displaying flash alerts.
 * @param string $type The type of alert (e.g. "success", "primary", "warning", "danger").
 * @param string $message The message to be displayed in the alert.
 * @return string The HTML code for the flash alert message.
 */
function messageFlash(string $type, string $message): string
{
    $message = nl2br(trim($message));
    return <<<HTML
    <div class="alert alert-{$type} alert-dismissible mt-1 fs-6" role="alert">
        <div>{$message}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
HTML;
}

function tableStyle()
{
    return <<<HTML
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            border: 2px solid black;
        }

        th {
            background-color: #342628;
            color: white;
        }

        tr {
            background-color: #c0c0c0;
        }

        tr td a {
            text-decoration: none;
        }

        tr form {
            display: inline;
        }

        tr:nth-child(odd) {
            background-color: #A3B4C8;
        }

        .table-responsive td #add {
            color: #61a476;
        }

        .table-responsive td #edit {
            color: #FFA101;
        }

        .table-responsive td #remove {
            color: #F21137;
        }

        @media only screen and (max-width: 878px) {

            .table-responsive table,
            .table-responsive thead,
            .table-responsive tbody,
            .table-responsive tr,
            .table-responsive th,
            .table-responsive td {
                display: block;
            }

            .table-responsive thead {
                display: none;
            }

            .table-responsive td {
                padding-left: 150px;
                position: relative;
                margin-top: -1;
                background-color: #fff;
                font-size: .8rem !important;
                font-weight: 800;
            }

            .table-responsive td:nth-child(odd) {
                background-color: #cdcAd6;
            }

            .table-responsive td::before {
                content: attr(data-label);
                position: absolute;
                top: 0;
                left: 0;
                width: 130px;
                bottom: 0;
                color: white;
                background-color: #342628;
                display: flex;
                justify-content: center;
                padding: 10px;
                font-weight: bold;
            }

            .table-responsive tr {
                margin-bottom: 1.2rem;
            }
        }
    </style>
HTML;
}