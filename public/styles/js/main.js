$(document).ready(() => {
    $('button.btn-close').click(() => { 
        $('div.alert-dismissible').fadeOut();
    });

    //TRAITEMENT SUR LA BAR DE RECHERCHE DYNAMIQUE
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
});