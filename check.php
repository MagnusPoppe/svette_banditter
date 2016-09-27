<?php
/**
 * Created by PhpStorm.
 * User: MagnusPoppe
 * Date: 04.08.2016
 * Time: 20.28
 */

// TESTER STUDENTNUMMER MOT DATABASE:
$query = "SELECT Studentnr FROM Lan WHERE Studentnr = ? AND Dato = '2016-09-2'";
$db = new mysqli("kroalan.no.mysql", "kroalan_no", "Q93LrYyb", "kroalan_no");
//$db = new mysqli("localhost", "root", "", "svette_no");
if ($stmt = $db->prepare($query))
{
    $stmt->bind_param("s", $_POST["student"]);
    $stmt->execute();
}
else die("<h1 class='centermessage'>KUN PÅMELDTE STUDENTER KAN SE TURNERINGSOVERSIKTEN.</h1>");

if ( $stmt->fetch() ) { // OM STUDENTEN ER REGISTRERT I DATABASEN:

    // OPPKOBLINGSVARABLER:
    $gamequery = "
        SELECT
            Student.Studentnr   AS studnr, 
            Student.Steam       AS steam, 
            Student.lol         AS lol, 
            Student.Origin      AS origin, 
            Student.battlenet   AS battlenet,
            Turnering.Spill     AS spill,
            Turnering.Skill     AS skill,
            Turnering.Dato      AS dato
            
        FROM
            Student INNER JOIN Turnering ON Student.Studentnr = Turnering.Studentnr 
            
        WHERE
            dato = ?
            
        ORDER BY 
            spill";
    $dato = '2016-09-2';

    // HENTER UT TURNERINGSINFORMASJON FRA DATABASEN:
    $db2 = new mysqli("kroalan.no.mysql", "kroalan_no", "Q93LrYyb", "kroalan_no");
    $game = $db2->prepare($gamequery);
    $game->bind_param("s", $dato);
    $game->execute();
    $game->bind_result($studnr, $steam, $lol, $origin, $bnet, $spill, $skill, $dato);

    // NØDVENDIGE VARIABLER FOR SORTERINGSARBEID:
    $cs = $league = $rocket = $smash = "";
    $cscount = $lolcount = $rocketcount = $smashcount = 0;

    // UTFØRER SORTERINGEN:
    while ($game->fetch())
    {
        switch ($spill)
        {
            case "Counter Strike: Global Offensive":
                $cs .= "<tr><td>$steam</td><td>$skill</td></tr>";
                $cscount++;
            break;

            case "League Of Legends":
                $league .= "<tr><td>$lol</td><td>$skill</td></tr>";
                $lolcount++;
            break;

            case "Rocket League":
                $rocket .= "<tr><td>$steam</td><td>$skill</td></tr>";
                $rocketcount++;
            break;

            case "Super Smash Bros":
                $smash .= "<tr><td>Studentnr: </td><td>$studnr</td></tr>";
                $smashcount++;
            break;
        }
    }

    // SKRIVER UT RESULTAT:
    echo "<div><h2 style='text-align:left !important;'>Counter Strike: Global Offensive</h2><table>$cs</table>";
    echo "<br>Antall deltakere: $cscount</div>";

    echo "<div><h2 style='text-align:left !important;'>League Of Legends</h2><table>$league</table>";
    echo "<br>Antall deltakere: $lolcount</div>";

    echo "<div><h2 style='text-align:left !important;'>Rocket League</h2><table>$rocket</table>";
    echo "<br>Antall deltakere: $rocketcount</div>";

    echo "<div><h2 style='text-align:left !important;'>Super Smash Bros</h2><table>$smash</table>";
    echo "<br>Antall deltakere: $smashcount</div>";

    $db2->close();
}
else { // OM STUDENTNUMMERET IKKE BLE FUNNET I REGISTERET:
    echo "<h1 class='centermessage'>KUN PÅMELDTE STUDENTER KAN SE TURNERINGSOVERSIKTEN.</h1>";
    echo "<a href='reg.php'><button class='spec'>TIL REGISTRERING</button></a>";
}

// AVSLUTTER OG LUKKER TILKOBLINGER MOT DATABASE
$stmt->close();
$db->close();
