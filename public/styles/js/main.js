$(document).ready(() => {
    $('button.btn-close').click(() => { 
        $('div.alert-dismissible').fadeOut();
    });

    //TRAITEMENT SUR LA BAR DE RECHERCHE DYNAMIQUE
    $('.s form').submit(function (e) {
        e.preventDefault();
    });
    $("#search").keyup(function () {
        let search = $(this).val();
        search = $.trim(search);

        if (search !== "") {
            $.post('/', { search: search }, function (data) {
                $("#resultat ul").html(data);

                $("#resultat").show();
            });
        } else $("#resultat").hide();
    });

    $("html body").click(function () {
        $("#resultat").hide();
    });
    
    const nav_bar = $(".nav_bar");
    const header2 = $(".header-2");
    const menu = $("#menu");
    $("#menu").click(function () {
        $(this).toggleClass("fa-times");
        nav_bar.toggleClass("nav-toggle");
    });
    $(window).on("scroll", function () {
        nav_bar.removeClass("nav-toggle");

        if ($(window).scrollTop() > 30) {
            header2.addClass("sticky");
            menu.addClass("menu");
            $(".back-to-top").fadeIn();
        } else {
            header2.removeClass("sticky");
            menu.removeClass("menu");
            $(".back-to-top").fadeOut();
        }
    });

    $(".back-to-top").click(function () {
        $("html, body").animate({ scrollTop: 0 }, 500);
    });

    //TRAITEMENT SUR L'AFFICHAGE DU MOT DE PASSE
    const eye_open = $(".view .fa-eye");
    const eye_slash = $(".view .fa-eye-slash");
    const mdp = $("#password");
    eye_slash.click(function () {
        eye_open.show();
        eye_slash.hide();
        mdp.attr("type", "text");
    });
    eye_open.click(function () {
        eye_slash.show();
        eye_open.hide();
        mdp.attr("type", "password");
    });

    // REGLES DE VALIDATION DU MOT DE PASSE
    $(".password-v").keyup(function () {
        $(".r-error").hide();
        let $password = $(this).val();
        $password = $.trim($password);
        let letter = /[a-zA-Z]/;
        let numberL = /[0-9]/;
        let symbol = /[-~&?!^*#£%µ¤«§<>_@=$€»+]/;

        if ($password.length != 0) {
            if ($password.match(letter)) {
                $(".r-error").hide();
                if ($password.match(numberL)) {
                    $(".r-error").hide();
                    if ($password.match(symbol)) {
                        if ($password.length >= 8 && $password.length <= 30) {
                            $(".r-error").hide();
                        } else
                            $(".r-error").show().text("Minimun 8 et maximun 30 caractères");
                    } else $(".r-error").show().text("Avoir au moins un caratère spécial");
                } else $(".r-error").show().text("Avoir au moins un chiffre");
            } else
                $(".r-error")
                    .show()
                    .text("Avoir au moins une lettre");
        } else $(".r-error").hide();
    });

    //TRAITEMENT SUR L'AFFICHAGE DE LA PAGE DE CONNEXION
    const wrapper = $(".wrapper");
    const login = $(".btn-login");
    const icon_close = $(".icon-close");
    const links = $(".header-2 .nav_bar ul li a");
    login.click(function () {
        wrapper.toggleClass("active-popup");
    });
    icon_close.click(function () {
        wrapper.removeClass("active-popup");
    });
    links.click(function () {
        wrapper.removeClass("active-popup");
    });
});