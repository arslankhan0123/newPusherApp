<?php

function makeImageFromName($name) {
    $userImage = "";
    $shortName = "";

    $names = explode(" ", $name);
    // dd($names);
    foreach ($names as $name) {
        $shortName .= $name[0];
    }
    $userImage = $shortName;
    // dd($userImage);
    return $userImage;
}