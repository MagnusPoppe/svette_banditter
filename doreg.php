<?php
/******************************************************************
 *                                                                *
 *           @author Magnus Poppe Wang                            *
 *           @date: 31.07.2016                                    *
 *           @time: 13.04                                         *
 *                                                                *
 ******************************************************************/




/******************************************************************
 *                                                                *
 *                  SJEKKER BRUKERINFORMASJON                     *
 *                                                                *
 ******************************************************************/

if (isset($_POST['studnr'])) $studNr = $_POST['studnr'];
else die("StudentNummer ikke satt.");

if (isset($_POST['fnavn'])) $fnavn = $_POST['fnavn'];
else $fnavn = NULL;

if (isset($_POST['enavn'])) $enavn = $_POST['enavn'];
else $enavn = NULL;

if (isset($_POST['mail'])) $epost = $_POST['mail'];
else $epost = NULL;

if (isset($_POST['steam'])) $steam = $_POST['steam'];
else $steam = NULL;

if (isset($_POST['lol'])) $lol   = $_POST['lol'];
else $lol = NULL;

if (isset($_POST['bnet'])) $bnet = $_POST['bnet'];
else $bnet = NULL;

if (isset($_POST['origin'])) $origin = $_POST['origin'];
else $origin = NULL;

$dato = $_POST["landato"];

// TESTER STUDENTNUMMER MOT DATABASE:
$query = "SELECT Studentnr FROM Student WHERE Studentnr = ?";
$db = new mysqli("kroalan.no.mysql", "kroalan_no", "Q93LrYyb", "kroalan_no");
//$db = new mysqli("localhost", "root", "", "svette_no");
if ($stmt = $db->prepare($query))
{
    $stmt->bind_param("s", $studNr);
    $stmt->execute();
}
else die("    <head>
        <title>REGISTRERING FULLFØRT</title>
        <link rel=\"stylesheet\" href=\"styles.css\">
        <link href=\"https://fonts.googleapis.com/css?family=Open+Sans\" rel=\"stylesheet\">
    </head><body><h1 class='centermessage'>KUN PÅMELDTE STUDENTER KAN SE TURNERINGSOVERSIKTEN.</h1></body>");

if ( $stmt->fetch() ) { // OM STUDENTEN ER REGISTRERT I DATABASEN:
    $query = <<<_end
            UPDATE 
                Student 
            SET 
                Fornavn = ?,
                Etternavn = ?,
                Epost = ?,
                Steam = ?,
                lol = ?,
                Origin = ?,
                battlenet = ?
            WHERE
                Studentnr = ?
_end;

    $update = $db->prepare($query);
    $update->bind_param("ssssssss", $fnavn, $enavn, $epost, $steam, $lol, $origin, $bnet, $studNr);
    $update->execute();

    ?>
    <head>
        <title>REGISTRERING FULLFØRT</title>
        <link rel="stylesheet" href="styles.css">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    </head>
    <body>
    <h1 class='centermessage'>DU VAR ALLEREDE REGISTRERT. INFORMASJONEN OM DEG ER OPPDATERT.</h1>
    </body>
    <?php
}
else {
    /******************************************************************
     *                                                                *
     *      UTFØRER REGISTRERINGEN I TABELLEN STUDENT:                *
     *                                                                *
     *****************************************************************/
    $query = 'INSERT INTO Student VALUES(?,?,?,?,?,?,?,?)';
    $insert = $db->prepare($query);
    $insert->bind_param("ssssssss",$studNr, $fnavn, $enavn, $epost, $steam, $lol, $bnet, $origin);
    $insert->execute();
    $insert->close();


    /******************************************************************
     *                                                                *
     *          REGISTRERER STUDENTEN I LAN TABELLEN.                 *
     *                                                                *
     ******************************************************************/
    $query = 'INSERT INTO Lan VALUES(?,?)';
    $insertLan = $db->prepare($query);
    $insertLan->bind_param("ss",$dato,$studNr);
    $insertLan->execute();
    $insertLan->close();
    ?>
    <head>
        <title>REGISTRERING FULLFØRT</title>
        <link rel="stylesheet" href="styles.css">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    </head>
    <body>
        <h1 class='centermessage'>DU ER NÅ PÅMELDT TIL KROALAN 2016.</h1>
    </body>
    <?php
}


    /******************************************************************
     *                                                                *
     *              SJEKKER PÅMELDING TIL TURNERING                   *
     *                                                                *
     ******************************************************************/

    $insertTurnament = "";
    if (isset($_POST['CS']))
    {
        $rank = $_POST["CSrank"];
        $insertTurnament = "INSERT INTO Turnering VALUES( ?, ? , 'Counter Strike: Global Offensive' , ? );";
        $stmt1 = $db->prepare($insertTurnament);
        $stmt1->bind_param("sss", $studNr, $dato, $rank);
        $stmt1->execute();
        $stmt1->close();
    }

    if(isset($_POST['LOL']))
    {
        $rank = $_POST["LOLrank"];
        $insertTurnament = "INSERT INTO Turnering VALUES(? , ? , 'League Of Legends' , ? );";
        $stmt1 = $db->prepare($insertTurnament);
        $stmt1->bind_param("sss", $studNr, $dato, $rank);
        $stmt1->execute();
        $stmt1->close();
    }

    if(isset($_POST['rocket']))
    {
        $rank = $_POST["rocketrank"];
        $insertTurnament = "INSERT INTO Turnering VALUES(? , ? , 'Rocket League' , ?);";
        $stmt1 = $db->prepare($insertTurnament);
        $stmt1->bind_param("sss", $studNr, $dato, $rank);
        $stmt1->execute();
        $stmt1->close();
    }

    if(isset($_POST['smash']))
    {
        $rank = NULL;
        $insertTurnament = "INSERT INTO Turnering VALUES(?,? , 'Super Smash Bros' , ? );";
        $stmt1 = $db->prepare($insertTurnament);
        $stmt1->bind_param("sss", $studNr, $dato, $rank);
        $stmt1->execute();
        $stmt1->close();
    }

    $db->close();
    ?>
