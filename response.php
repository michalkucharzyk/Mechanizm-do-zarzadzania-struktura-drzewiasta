<?php

require_once("config.php");

$conn = connectDB();

$query = "SELECT * FROM struktura";

$results = queryDB($query);
while ($row = $results->fetch(PDO::FETCH_ASSOC)) {
    $data[] = $row;
}

$itemsByReference = array();

// Build array of item references:
foreach ($data as $key => &$item) {
    $itemsByReference[$item['id']] = &$item;
    // Children array:
    $itemsByReference[$item['id']]['children'] = array();
    // Empty data class (so that json_encode adds "data: {}" )
    $itemsByReference[$item['id']]['data'] = new StdClass();
}

// Set items as children of the relevant parent item.
foreach ($data as $key => &$item)
    if ($item['parent_id'] && isset($itemsByReference[$item['parent_id']]))
        $itemsByReference [$item['parent_id']]['children'][] = &$item;

// Remove items that were added to parents elsewhere:
foreach ($data as $key => &$item) {
    if ($item['parent_id'] && isset($itemsByReference[$item['parent_id']]))
        unset($data[$key]);
}
// Encode:
echo json_encode($data);

?>
