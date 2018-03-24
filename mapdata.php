<?php

include_once 'deebee.php';

function parseToXML($htmlStr) {
    $xmlStr = str_replace('<', '&lt;', $htmlStr);
    $xmlStr = str_replace('>', '&gt;', $xmlStr);
    $xmlStr = str_replace('"', '&quot;', $xmlStr);
    $xmlStr = str_replace("'", '&#39;', $xmlStr);
    $xmlStr = str_replace("&", '&amp;', $xmlStr);
    return $xmlStr;
}

/*
 * Collect/clean input parameters
 */
$yearStart = filter_input(INPUT_GET, 'yearStart', FILTER_VALIDATE_INT, array('options' => array('default' => 1900, 'min_range' => 1900, 'max_range' => 2099)));
$yearEnd = filter_input(INPUT_GET, 'yearEnd', FILTER_VALIDATE_INT, array('options' => array('default' => 2099, 'min_range' => 1900, 'max_range' => 2099)));
$excludeTypes = filter_input(INPUT_GET, 'excludeTypes');
error_log($excludeTypes);
/*
 * Prepare the SQL statement
 */
$query = "SELECT * FROM v_iaf_markers WHERE year between " . $yearStart . " AND " . $yearEnd;

/* If excludeTypes is blank, prepare SQL NOT IN list */
if ($excludeTypes != null && strlen($excludeTypes) > 1) {
    $types = explode(',', $excludeTypes);
    $query_append = " AND type NOT IN(";
    foreach ($types as $val) {
        $query_append = $query_append . "'" . $val . "',";
    }
    $query_append = substr($query_append, 0, strlen($query_append) - 1); /* Remove trailing comma */
    $query_append = $query_append . ")"; /* Close NOT IN list */
    $query = $query . $query_append;
}

$result = pg_query($connection, $query);
if (!$result) {
    die('Invalid query: ' . pg_last_error());
}

/*
 * BEGIN OUTPUT OF XML
 */

header("Content-type: text/xml");

// Start XML file, echo parent node
echo '<markers>';

// Iterate through the rows, printing XML nodes for each
while ($row = @pg_fetch_assoc($result) ) {
    // Add to XML document node
    echo '<marker ';
    echo 'id="' . $row['id'] . '" ';
    echo 'name="' . parseToXML($row['title']) . '" ';
    echo 'address="' . parseToXML($row['url']) . '" ';
    echo 'lat="' . $row['lat'] . '" ';
    echo 'lng="' . $row['lng'] . '" ';
    echo 'type="' . $row['type'] . '" ';
    echo 'icon="' . $row['typeimage'] . '" ';
    echo 'year="' . $row['year'] . '" ';
    echo '/>';
}
// End XML file
echo '</markers>';
?>

