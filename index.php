<?php
    // All classes & managers
    spl_autoload_register(function($class) {
        if(strpos($class, "Manager") !== false)
            include __DIR__ . "../manager/" . $class . ".php";
        else
            include __DIR__ . "../class/" . $class . ".php";
    });
    ob_start();
    session_start();

    if(isset($_SESSION['search']) && !empty($_SESSION['search'])) {
        $search = str_replace('%20', ' ', $_SESSION['search']);
        unset($_SESSION['search']);
    }
    if(isset($_SESSION['suggestion']) && !empty($_SESSION['suggestion'])) {
        $search = str_replace('%20', ' ', $_SESSION['suggestion']);
        unset($_SESSION['suggestion']);
    }
    if(isset($_SESSION['suggestions']) && !empty($_SESSION['suggestions'])) {
        $suggestions = unserialize($_SESSION['suggestions']);
        unset($_SESSION['suggestions']);
    }
    if(isset($_SESSION['lang']) && !empty($_SESSION['lang'])) {
        $lang = $_SESSION['lang'];
        unset($_SESSION['lang']);
    }
    else {
        $lang = 'nl';
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Fris</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">
        <link href="css/main.css" rel="stylesheet"/>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    </head>
    <body>
        <!-- FORM -->
        <div class="container">
            <a class="btn btn-info" href="charts.html" role="button">Charts</a>
            <a class="btn btn-info" href="wordclouds.html" role="button">Word Clouds</a>
        </div>  
        <div class="container col-12 my-5 text-center">
            <div class="mx-auto col-3">
                <form action="process/process.php" method="post">
                    <div class="form-group">
                        <input type="text" class="form-control" name="search" id="search" placeholder="Search.." value="<?php if(isset($search) && !empty($search)) echo $search; ?>">
                    </div>
                    <div class="form-group">
                        <select class="form-control" name="lang" id="lang">
                            <option value="nl" <?php if($lang == 'nl') echo 'selected'; ?>>Nederlands</option>
                            <option value="en" <?php if($lang == 'en') echo 'selected'; ?>>English</option>
                        </select>
                    </div>
                    <button type="submit" value="submit" class="btn btn-primary col-12">Submit</button>
                </form>
            </div>
            <hr/>
            <div class="col-3 mx-auto">
                <div id="loader" class="loader hidden mx-auto"></div>
                <div id="list">
                    <ul id="synonymList" class="list-group">
                        <?php
                        if(isset($search) && !empty($search) && isset($suggestions) && !empty($suggestions)) {
                            ?>
                            <li id="chosenWord" class="list-group-item active h4 uppercase"><?= $search ?></li>
                            <?php
                            foreach ($suggestions as $suggestion) {
                                ?>
                                <li id="<?= $suggestion->word ?>" class="list-group-item suggestion" data-callback="process/suggestion.php" title="<?= $suggestion->clicks ?>"><?= $suggestion->word ?></li>
                                <?php
                            }
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>

        <div id="callback"></div>

        <!-- SCRIPTS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <script src="js/main.js"></script>
    </body>
</html>
