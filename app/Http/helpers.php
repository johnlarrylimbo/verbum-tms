<?php

  function makeImageFromName($name){
    $userImage = "";
    $shortName = "";
    $shortName1 = "";

    $names = explode(" ", $name);

    foreach($names as $name){
      $shortName .= $name[0];
      $shortName1 .= $name[1];
    }


    $userImage = '<div class="name-image bg-primary">' . $shortName . '' . $shortName1 . '</div>';
    return $userImage;
  }

  function loadingState($key, $text){
    $loader = '<div wire:loading wire:target='.$key.' wire:key='.$key.'><i class="fa fa-spinner fa-spin"></i></div> '.$text.'';

    return $loader;
}

?>