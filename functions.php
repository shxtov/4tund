<?php

    function sum ($x, $y){


        return $x + $y;
    }


    echo sum(5467473247,123141248123);
    echo "<br>";
    $answer = sum(10,15);
    echo $answer;
    echo "<br>";


    function hello ($name,$surname){
        return ("Tere tulemast ".$name." ".$surname."!");
    }

    echo hello('Vladislav','Sutov');