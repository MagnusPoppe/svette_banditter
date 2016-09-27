<?php
/**
 * Created by PhpStorm.
 * User: MagnusPoppe
 * Date: 04.08.2016
 * Time: 20.19
 */

?>

<head>
    <title>Oppgi studentnummer</title>
    <link href="styles.css" type="text/css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script>
        function checkstudent() {
            var student = prompt("Oppgi ditt studentnummer for å se turneringsdeltakere.");
            $.ajax({
                url:"check.php",
                method: "POST",
                data:{"student":student},
                success: function (result) {
                    $("main").html(result);
                }
            });
        };
    </script>
</head>

<body onload="checkstudent()">
    <main>
    </main>
</body>
