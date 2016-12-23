<?php

function nb ($nb, $c) {
    if ($nb == 0) return "";
    return $c.nb($nb-1, $c);
}

echo nb(8,'v');

