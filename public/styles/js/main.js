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
});