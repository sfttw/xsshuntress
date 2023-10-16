<?php
// Database file
$db_file = '/var/www/sf.ix.tc/data.sqlite';

// Create a new database connection
$db = new PDO('sqlite:' . $db_file);

// Get the page number and results per page from the request, or set default values
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$results_per_page = isset($_GET['results']) ? (int)$_GET['results'] : 10;
$order = isset($_GET['order']) && $_GET['order'] == 'desc' ? 'ASC' : 'DESC';

// Calculate the offset for the SQL query
$offset = ($page - 1) * $results_per_page;

// Prepare SQL statement
$stmt = $db->prepare("SELECT * FROM data ORDER BY id $order LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $results_per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

// Execute SQL statement
$stmt->execute();

// Fetch all results
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Output results in HTML
echo "
<html>
<title>XSS Huntress</title>
<style type='text/css'>
	* { margin: 0; padding: 0; }
	body { 	font-family: Courier New,Courier,Lucida Sans Typewriter,Lucida Typewriter,monospace;  
	font-size: 9pt; }
	.info { margin-top:1em; 
	border: 1px #eee solid;
	padding-right: 1em; 
	}
	dt, dd { margin: 1em; }
	dt { font-weight: bold; } 
</style>
";

// Output order form
echo '<form method="get">';
echo '<input type="hidden" name="page" value="' . $page . '">';
echo '<input type="hidden" name="results" value="' . $results_per_page . '">';
echo '<select name="order">';
echo '<option value="asc"' . ($order == 'ASC' ? ' selected' : '') . '>Ascending</option>';
echo '<option value="desc"' . ($order == 'DESC' ? ' selected' : '') . '>Descending</option>';
echo '</select>';
echo '<input type="submit" value="Change order">';
echo '</form>';

echo '<ol id="main">';
foreach ($results as $i => $row) {
    $bg_color = $i % 2 == 0 ? '#ffffff' : '#f5f5f5';
    echo '<div class="info" style="background-color:' . $bg_color . ';">';
    echo '<li>';
    echo '<dt>Time:</dt> <dd>' . htmlspecialchars($row['time']) . '</dd>';
    echo '<dt>URL:</dt> <dd> ' . htmlspecialchars($row['url']) . '</dd>';
    echo '<dt>Referrer:</dt> <dd> ' . htmlspecialchars($row['referrer']) . '</dd>';
    echo '<dt>IP:</dt> <dd> ' . htmlspecialchars($row['ip']) . '</dd>';
    echo '<dt>User Agent:</dt> <dd> ' . htmlspecialchars($row['user_agent']) . '</dd>';
    echo '<dt>Cookies:</dt> <dd> ' . htmlspecialchars($row['cookies']) . '</dd>';
    echo '<dt>Local Storage:</dt> <dd> ' . htmlspecialchars($row['local_storage']) . '</dd>';
    echo '<dt>HTML:</dt> <dd> ' . htmlspecialchars($row['html']) . '</dd>';
    echo '</li>';
    echo '</div>';
}
echo '</ol>';

?>
