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
<!-- FORM -->
<form action="" method="post" data-callback="process.php">
    <label for="search">Search:</label>
    <input type="text" name="search" id="search" placeholder="Search..">
    <select name="lang">
        <option value="nl">Nederlands</option>
        <option value="en">English</option>
    </select>
    <input type="submit" value="submit">
    <div id="notification"></div>
</form>

<!-- SCRIPTS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
<script>
    /* ------------------------------------------------------
                            FORM SENDING
    ------------------------------------------------------ */
    $(document).ready(function () {
        //Form auto sending via ajax
        $(document).on("submit", "form", function (e) {
            if($(this).attr("data-callback") != null) {
                var form = $(this).get(0)
                var formData = new FormData(form)

                $url = $(this).attr("data-callback")

                e.preventDefault();

                $.ajax({
                    url: $url,
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    complete: function (data) {
                        $("#notification").html(data.responseText); // .html pour réécrire par dessus
                    }
                });
            }
        });
    });
</script>
</body>
</html>
