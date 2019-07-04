<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Fris</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">
    <link href="main.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
<!-- FORM -->
<div class="container">
    <a class="btn btn-info" href="charts.html" role="button">Charts</a>
</div>
<div class="container col-12 my-5 text-center">

    <div class="mx-auto col-12 col-md-3">
        <form data-callback="process.php">
            <div class="form-group">
                <input type="text" class="form-control" name="search" id="search" placeholder="Search..">
            </div>
            <div class="form-group">
                <select class="form-control" name="lang" id="exampleFormControlSelect1">
                    <option value="nl">Nederlands</option>
                    <option value="en">English</option>
                </select>
            </div>
            <button type="submit" value="submit" class="btn btn-primary col-12">Submit</button>
        </form>
    </div>
    <hr/>
    <div class="col-12 col-md-3 mx-auto">
        <div id="loader" class="loader hidden mx-auto"></div>
        <div id="list">
            <ul id="synonymList" class="list-group">
            </ul>
        </div>
    </div>
</div>

<div id="notification"></div>
<!--
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
-->

<!-- SCRIPTS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
<script>


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
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</body>
</html>
