<?php
$base_n_n = 2945.23;

$base_n = number_format(((float)$base_n_n),2);

$f = new NumberFormatter('es_ES', NumberFormatter::SPELLOUT);


$base_n_init = explode(".", $base_n_n);
$base_n_int_l = $f->format($base_n_init[0]);
$base_n_cent_l = $f->format(number_format($base_n_init[1],2));

echo $base_n;
echo "<br>";
echo var_dump($base_n_init);
echo "<br>";
echo $base_n_int_l;
echo "<br>";
echo $base_n_cent_l;
echo "<br>";
?>