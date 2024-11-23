<html>
<head>
    <title>Kalorienbedarf Rechner</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>
<h1>Kalorienbedarf Rechner</h1>
<form method="post">
    <div class="container">

        <!-- container1 -->
        <div class="container1">
            <h2 class="section-title-left">Persönliche Daten</h2>
            <div class="input-group">
                <label for="gender">Geschlecht:</label>
                <select id="gender" name="gender" required>
                    <option value="mann" <?= isset($_POST['gender']) && $_POST['gender'] === 'mann' ? 'selected' : '' ?>>Mann</option>
                    <option value="frau" <?= isset($_POST['gender']) && $_POST['gender'] === 'frau' ? 'selected' : '' ?>>Frau</option>
                </select>
            </div>

            <div class="input-group">
                <label for="age">Alter (Jahre):</label>
                <input type="number" id="age" name="age" value="<?= $_POST['age'] ?? '' ?>" required>
            </div>

            <div class="input-group">
                <label for="weight">Gewicht (kg):</label>
                <input type="number" id="weight" name="weight" step="0.01" value="<?= $_POST['weight'] ?? '' ?>" required>
            </div>

            <div class="input-group">
                <label for="height">Größe (cm):</label>
                <input type="number" id="height" name="height" step="0.01" value="<?= $_POST['height'] ?? '' ?>" required>
            </div>
        </div>

        <!-- Container 2 -->
        <div class="container2">
            <h2 class="section-title-right">Tägliche Aktivitäten (in Stunden)</h2>
            <label for="sleep">Schlafen:</label>
            <input type="number" id="sleep" name="sleep" step="0.01" value="<?= $_POST['sleep'] ?? '' ?>" required>

            <label for="sitting">Sitzend/liegend:</label>
            <input type="number" id="sitting" name="sitting" step="0.01" value="<?= $_POST['sitting'] ?? '' ?>" required>

            <label for="light_activity">Vorwiegend sitzend:</label>
            <input type="number" id="light_activity" name="light_activity" step="0.01" value="<?= $_POST['light_activity'] ?? '' ?>" required>

            <label for="moderate_activity">Sitzend/gehend:</label>
            <input type="number" id="moderate_activity" name="moderate_activity" step="0.01" value="<?= $_POST['moderate_activity'] ?? '' ?>" required>

            <label for="high_activity">Stehend/gehend:</label>
            <input type="number" id="high_activity" name="high_activity" step="0.01" value="<?= $_POST['high_activity'] ?? '' ?>" required>

            <label for="very_high_activity">Körperlich anstrengend:</label>
            <input type="number" id="very_high_activity" name="very_high_activity" step="0.01" value="<?= $_POST['very_high_activity'] ?? '' ?>" required>

            <input class="berechnen-button" type="submit" value="Berechnen">
        </div>
    </div>
</form>


<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $gender = $_POST["gender"] ?? null;
    $age = intval($_POST["age"] ?? 0);
    $weight = floatval($_POST["weight"] ?? 0);
    $height = floatval($_POST["height"] ?? 0);

    // Stunden pro Aktivität
    $sleep = floatval($_POST["sleep"] ?? 0);
    $sitting = floatval($_POST["sitting"] ?? 0);
    $light_activity = floatval($_POST["light_activity"] ?? 0);
    $moderate_activity = floatval($_POST["moderate_activity"] ?? 0);
    $high_activity = floatval($_POST["high_activity"] ?? 0);
    $very_high_activity = floatval($_POST["very_high_activity"] ?? 0);

    //Überprüft ob die Summe der 24 ergibt
    $total_hours = $sleep + $sitting + $light_activity + $moderate_activity + $high_activity + $very_high_activity;
    if ($total_hours != 24) {
        echo "<div class='error'>";
        echo "<h3>Fehler:</h3>";
        echo "<p>Die Summe der Stunden muss genau 24 ergeben. Deine Eingabe: <strong>$total_hours Stunden</strong>.</p>";
        echo "</div>";
        exit;
    }

    // PAL Faktoren
    $pal_sleep = 0.95;
    $pal_sitting = 1.2;
    $pal_light = 1.45;
    $pal_moderate = 1.65;
    $pal_high = 1.85;
    $pal_very_high = 2.2;

    //Durchschnittlichen PAL Faktor berechnen
    $average_pal = (
            ($sleep * $pal_sleep) +
            ($sitting * $pal_sitting) +
            ($light_activity * $pal_light) +
            ($moderate_activity * $pal_moderate) +
            ($high_activity * $pal_high) +
            ($very_high_activity * $pal_very_high)) / 24;

    // Grundumsatz berechnen
    if ($gender === "mann") {
        $bmr = 66.47 + (13.7 * $weight) + (5 * $height) - (6.8 * $age);
    } elseif ($gender === "frau") {
        $bmr = 655.1 + (9.6 * $weight) + (1.8 * $height) - (4.7 * $age);
    } else {
        echo "Ungültiges Geschlecht.";
        exit;
    }

    // Gesamtumsatz berechnen
    $total_calories = $bmr * $average_pal;

    // Empfehlungen - Zum probieren
    $calories_to_lose_weight = $total_calories - 400;
    $calories_to_gain_weight = $total_calories + 400;

    //Ausgabe der Ergebnisse
    echo "<div class='result'>";
    echo "<h2>Ergebnisse:</h2>";
    echo "<p>Grundumsatz (BMR): " . round($bmr, 2) . " kcal</p>";
    echo "<p>Durchschnittlicher PAL-Faktor: " . round($average_pal, 2) . "</p>";
    echo "<p>Gesamtumsatz: " . round($total_calories, 2) . " kcal</p>";
    echo "<p>Zum Abnehmen: " . round($calories_to_lose_weight, 2) . " kcal</p>";
    echo "<p>Zum Zunehmen: " . round($calories_to_gain_weight, 2) . " kcal</p>";
    echo "</div>";
}
?>

</body>
</html>
