<?php
echo "<b>Project pages:</b><br/>";
foreach (glob("*.html") as $filename) {
    echo "<a href=\"$filename\">$filename</a><br>";
}
?>