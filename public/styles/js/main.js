$(document).ready(() => {
    // Navbar and back to top
    const nav_bar = $(".nav_bar"),
        header2 = $(".header-2"),
        menu = $("#menu");
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
    
    // SEE MORE TO SYNOPSIS FOR SHOW PAGE
    let is_open = false;
    $(".one-webt #toggle-show").click(function () {
        is_open = !is_open;
        $("#synop-wrapper").toggleClass("expanded");
        $(this).text(is_open ? "Voir moins ▲" : "Voir plus ▼");
    })
    $(".last-chapters #toggle-show2").click(function () {
        is_open = !is_open;
        $("#table-wrapper").toggleClass("expanded");
        $(this).text(is_open ? "Voir moins ▲" : "Voir plus ▼");
    });

    // PROCESSING ON PASSWORD DISPLAY
    const eye_open = $(".view .fa-eye"),
        eye_slash = $(".view .fa-eye-slash"),
        mdp = $("#password");
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

    // PASSWORD VALIDATION RULES
    $("#password").keyup(function () {
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
                            $(".r-error")
                                .show()
                                .text("Minimun 8 et maximun 30 caractères");
                    } else
                        $(".r-error")
                            .show()
                            .text("Doit contenir au moins un caratère spécial");
                } else
                    $(".r-error")
                        .show()
                        .text("Doit contenir au moins un chiffre");
            } else $(".r-error").show().text("Avoir au moins une lettre");
        } else $(".r-error").hide();
    });

    // Voting system
    let vote = $("#vote");
    let url_vote = vote.data("ref");
    $(".like", vote).click(function (e) {
        e.preventDefault();
        votes(1);
    });
    $(".dislike", vote).click(function (e) {
        e.preventDefault();
        votes(-1);
    });

    function votes(value) {
        $.post(url_vote, {
            id: vote.data("id"),
            vote: value,
        })
        .done(function (data, textStatus, jqXHR) {
            $("#like-count").text(data.likes);
            $("#dislike-count").text(data.dislikes);
            vote.removeClass("is-like is-dislike");
            if (data.success) {
                if (value == 1) {
                    vote.addClass("is-like");
                } else vote.addClass("is-dislike");
            }
        })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
        });
    }
})