<?php

if (isset($_SESSION['error401']))
    http_response_code(401);
else
    http_response_code(404);

$navbar = '';

$pg_title = 'Erreur 404 | RioToon';
$pg_desc = 'Page non Trouvée ou Accès refusé | RioToon Erreur 404';

?>

<style>
    @-moz-keyframes rocket-movement {
        100% {
            -moz-transform: translate(1200px, -600px);
        }
    }

    @-webkit-keyframes rocket-movement {
        100% {
            -webkit-transform: translate(1200px, -600px);
        }
    }

    @keyframes rocket-movement {
        100% {
            transform: translate(1200px, -600px);
        }
    }

    @-moz-keyframes spin-earth {
        100% {
            -moz-transform: rotate(-360deg);
            transition: transform 20s;
        }
    }

    @-webkit-keyframes spin-earth {
        100% {
            -webkit-transform: rotate(-360deg);
            transition: transform 20s;
        }
    }

    @keyframes spin-earth {
        100% {
            -webkit-transform: rotate(-360deg);
            transform: rotate(-360deg);
            transition: transform 20s;
        }
    }

    @-moz-keyframes move-astronaut {
        100% {
            -moz-transform: translate(-160px, -160px);
        }
    }

    @-webkit-keyframes move-astronaut {
        100% {
            -webkit-transform: translate(-160px, -160px);
        }
    }

    @keyframes move-astronaut {
        100% {
            -webkit-transform: translate(-160px, -160px);
            transform: translate(-160px, -160px);
        }
    }

    @-moz-keyframes rotate-astronaut {
        100% {
            -moz-transform: rotate(-720deg);
        }
    }

    @-webkit-keyframes rotate-astronaut {
        100% {
            -webkit-transform: rotate(-720deg);
        }
    }

    @keyframes rotate-astronaut {
        100% {
            -webkit-transform: rotate(-720deg);
            transform: rotate(-720deg);
        }
    }

    @-moz-keyframes glow-star {
        40% {
            -moz-opacity: 0.3;
        }

        90%,
        100% {
            -moz-opacity: 1;
            -moz-transform: scale(1.2);
        }
    }

    @-webkit-keyframes glow-star {
        40% {
            -webkit-opacity: 0.3;
        }

        90%,
        100% {
            -webkit-opacity: 1;
            -webkit-transform: scale(1.2);
        }
    }

    @keyframes glow-star {
        40% {
            -webkit-opacity: 0.3;
            opacity: 0.3;
        }

        90%,
        100% {
            -webkit-opacity: 1;
            opacity: 1;
            -webkit-transform: scale(1.2);
            transform: scale(1.2);
            border-radius: 999999px;
        }
    }

    h1 {
        color: #45F3FF;
        position: absolute;
        top: 70px;
        left: 50%;
        transform: translateX(-50%);
    }

    p {
        color: #FFF;
        position: absolute;
        font-size: var(--font-p);
        font-family: 'Times New Roman', Times, serif;
        word-spacing: 4px;
        letter-spacing: 2px;
        top: 140px;
        left: 50%;
        transform: translateX(-50%);
    }

    .spin-earth-on-hover {

        transition: ease 200s !important;
        transform: rotate(-3600deg) !important;
    }

    .custom-navbar {
        padding-top: 15px;
    }

    .brand-logo {
        margin-left: 25px;
        margin-top: 5px;
        display: inline-block;
    }

    .central-body {
        padding: 17% 5% 10% 5%;
        text-align: center;
    }

    .objects img {
        z-index: 90;
        pointer-events: none;
    }

    .object_rocket {
        z-index: 95;
        position: absolute;
        transform: translateX(-50px);
        top: 75%;
        pointer-events: none;
        animation: rocket-movement 200s linear infinite both running;
    }

    .object_earth {
        position: absolute;
        top: 20%;
        left: 15%;
        z-index: 90;
    }

    .object_moon {
        position: absolute;
        top: 12%;
        left: 25%;
    }

    .object_astronaut {
        animation: rotate-astronaut 200s infinite linear both alternate;
    }

    .box_astronaut {
        z-index: 110 !important;
        position: absolute;
        top: 60%;
        right: 20%;
        will-change: transform;
        animation: move-astronaut 50s infinite linear both alternate;
    }

    .image-404 {
        position: relative;
        z-index: 100;
        pointer-events: none;
    }

    .stars {
        background: url("<?= BASE_URL ?>images/favicons/overlay_stars.svg");
        background-repeat: repeat;
        background-size: contain;
        background-position: left top;
    }

    .glowing_stars .star {
        position: absolute;
        border-radius: 100%;
        background-color: #fff;
        width: 3px;
        height: 3px;
        opacity: 0.3;
        will-change: opacity;
    }

    .glowing_stars .star:nth-child(1) {
        top: 80%;
        left: 25%;
        animation: glow-star 2s infinite ease-in-out alternate 1s;
    }

    .glowing_stars .star:nth-child(2) {
        top: 20%;
        left: 40%;
        animation: glow-star 2s infinite ease-in-out alternate 3s;
    }

    .glowing_stars .star:nth-child(3) {
        top: 25%;
        left: 25%;
        animation: glow-star 2s infinite ease-in-out alternate 5s;
    }

    .glowing_stars .star:nth-child(4) {
        top: 75%;
        left: 80%;
        animation: glow-star 2s infinite ease-in-out alternate 7s;
    }

    .glowing_stars .star:nth-child(5) {
        top: 90%;
        left: 50%;
        animation: glow-star 2s infinite ease-in-out alternate 9s;
    }

    @media only screen and (max-width: 600px) {

        .box_astronaut {
            top: 66%;
        }

        .central-body {
            padding-top: 25%;
        }
    }
</style>

<div class="stars">
    <div class="custom-navbar">
        <div class="brand-logo">
            <img src="<?= BASE_URL ?>images/favicons/logo.svg" width="230px" height="75px">
        </div>
    </div>
    <div class="central-body">
        <?php if (isset($_SESSION['error401'])): ?>
            <h1>Non Authentifié</h1>
            <p><?= $_SESSION['error401']?></p>
        <?php else: ?>
            <h1>Page non trouvée</h1>
            <?php if (isset($_SESSION['error404'])): ?>
                <p><?= $_SESSION['error404']?></p>
            <?php $_SESSION['error404'] = null; endif?>
        <?php endif?>
        <img class="image-404" src="<?= BASE_URL ?>images/favicons/<?= isset($_SESSION['error401']) ? '401' : '404'?>.svg" width="300px">
        <?php $_SESSION['error401'] = null; ?>
    </div>
    <div class="objects">
        <img class="object_rocket" src="<?= BASE_URL ?>images/favicons/rocket.svg" width="40px">
        <div class="earth-moon">
            <img class="object_earth" src="<?= BASE_URL ?>images/favicons/earth.svg" width="100px">
            <img class="object_moon" src="<?= BASE_URL ?>images/favicons/moon.svg" width="80px">
        </div>
        <div class="box_astronaut">
            <img class="object_astronaut" src="<?= BASE_URL ?>images/favicons/astronaut.svg" width="140px">
        </div>
    </div>
    <div class="glowing_stars">
        <div class="star"></div>
        <div class="star"></div>
        <div class="star"></div>
        <div class="star"></div>
        <div class="star"></div>
    </div>
</div>