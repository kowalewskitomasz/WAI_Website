$(document).ready(function() {
    $("#search_input").on("input", function(){
        var wpisano = $(this).val();
        $.post("/search", {
            zapytanie: wpisano
        }, function(odpowiedz) {
            $("#wyniki").html(odpowiedz);
        });
    });
});