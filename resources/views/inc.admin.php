<?php

foreach ($urls as $val) {
    echo 'The link ending in "' . $val['url'] . '" ';
    if ($val['visited'] == 0) {
        echo 'nobody came.';
    }
    if ($val['visited'] == 1) {
        echo 'they came at ' . $val['at'] . '. Now it is not available.';
    }
    echo '<br>';
}

?>

<button type="submit" id="submit">Get yet another link</button>
<div id="result"></div>
<br>