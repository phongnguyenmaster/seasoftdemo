<?php

function debug($data = "")
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    die();
}

function logdata($data = "")
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}

?>
