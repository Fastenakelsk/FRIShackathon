function init() {
    /* ------------------------------------------------------
                            FORM SENDING
    ------------------------------------------------------ */
    $(document).ready(function () {
        $(document).on("submit", "form", function (e) {
            if($(this).attr("data-callback") != null) {
                var form = $(this).get(0);
                var formData = new FormData(form);
                $url = $(this).attr("data-callback");

                e.preventDefault();
                document.getElementById('loader').classList.toggle('hidden');
                document.getElementById('list').classList.toggle('hidden');
                $('#chosenWord').html(search.value);

                $.ajax({
                    url: $url,
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    complete: function (data) {
                        $("#notification").html(data.responseText);
                        document.getElementById('loader').classList.toggle('hidden');
                        document.getElementById('list').classList.toggle('hidden');
                    }
                });
            }
        });
    });
}

function selectionSuggestion() {
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
                $("#notification").append(data.responseText);
            }
        });
    });
}
