<?php

function getFirstFriday($year, $month) {
    $date = new DateTime("$year-$month-01");
    // Первая пятница месяца
    $firstFriday = $date->modify('first friday');
    return $firstFriday;
}

function isEvenDay($date) {
    return $date->format('j') % 2 == 0;
}

function formatOutput($date) {
    $monthNames = [
        1 => "янв.", 2 => "фев.", 3 => "мар.", 4 => "апр.", 5 => "мая",
        6 => "июн.", 7 => "июл.", 8 => "авг.", 9 => "сен.", 10 => "окт.",
        11 => "ноя.", 12 => "дек."
    ];
    $day = $date->format('j-\\е');
    $month = $monthNames[(int)$date->format('n')];
    $year = $date->format('Y');
    return "$day $month $year";
}

function generateActionDates($endYear) {
    $startYear = 2000;
    $tablesCount = 0;
    $chairsCount = 0;
    $switchToTables = false;
    $switchToChairs = false;
    $actionDates = [];

    for ($year = $startYear; $year <= $endYear; $year++) {
        for ($month = 1; $month <= 12; $month++) {
            $firstFriday = getFirstFriday($year, $month);
            if (isEvenDay($firstFriday)) {
                if ($switchToChairs) {
                    $chairsCount++;
                } else {
                    $actionDates[] = formatOutput($firstFriday);
                    $tablesCount++;
                }
            } else {
                if ($switchToTables) {
                    $tablesCount++;
                } else {
                    $chairsCount++;
                }
            }
        }

        if ($tablesCount > $chairsCount) {
            $switchToChairs = true;
            $switchToTables = false;
        } elseif ($chairsCount > $tablesCount) {
            $switchToTables = true;
            $switchToChairs = false;
        } else {
            $switchToTables = false;
            $switchToChairs = false;
        }
    }

    return $actionDates;
}

$endYear = isset($_POST['year']) ? (int)$_POST['year'] : date('Y');

// Get All Promotion Dates For Table
$actionDates = generateActionDates($endYear);

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Акционные дни для столов</title>
</head>
<body>
    <form method="post" action="">
        <label for="year">Введите год:</label>
        <input type="number" id="year" name="year" value="<?php echo $endYear; ?>" required>
        <button type="submit">ОК</button>
    </form>
    <?php if (!empty($actionDates)): ?>
        <h2>Акционные дни на столы до <?php echo $endYear; ?> года</h2>
        <ul>
            <?php foreach ($actionDates as $date): ?>
                <li style="list-style: square"><?php echo $date; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>
</html>
