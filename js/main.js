$('.suggestion').click(function() {
    var suggestion = $(this).text();
    $url = $(this).attr("data-callback");
    var formData = new FormData();
    formData.append("suggestion", suggestion);

    $.ajax({
        url: $url,
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        complete: function (data) {
            $("#callback").append(data.responseText);
            location.reload();
        }
    });
});
