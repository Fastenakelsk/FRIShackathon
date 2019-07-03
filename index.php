<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Fris</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">
        <link href="toastr.css" rel="stylesheet"/>
    </head>
    <body>
        <div id="form" data-callback="process.php">
            <label for="search">Search:</label>
            <input type="text" name="search" id="search" placeholder="Search..">
        </div>

        <h4 style="margin-bottom: 5px">Result:</h4>
        <span id="result"></span>
        <!-- SCRIPTS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
        <script>
            search.oninput = function() {
                if($("#form").attr("data-callback") != null) {
                    var formData = new FormData();
                    formData.append("search", search.value);

                    $url = $('#form').attr("data-callback");

                    $.ajax({
                        url: $url,
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        complete: function (data) {
                            $("#result").html(data.responseText);
                        }
                    });
                }
            };
        </script>
    </body>
</html>
