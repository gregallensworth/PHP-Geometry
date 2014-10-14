<?php
// an example of simplifying a multiline using Line_DouglasPeucker.php

// this multiline was decoded from some JSON from ArcGIS, and is a trail in Colorado
// it's expressed here in PHP literal syntax, but more realistically you'd get something 
// much like this after using json_decode() from ArcGIS, or by decoding a Leaflet feature's coordinates

$before = array(
    array(
        array(-106.92121, 39.29517 ),
        array(-106.92121, 39.29527 ),
        array(-106.92127, 39.29536 ),
        array(-106.92215, 39.29605 ),
        array(-106.92277, 39.29652 ),
        array(-106.92344, 39.29691 ),
        array(-106.92408, 39.29725 ),
        array(-106.92463, 39.29748 ),
        array(-106.92543, 39.29775 ),
        array(-106.92623, 39.29801 ),
    ),
    array(
        array(-106.93027, 39.29995 ),
        array(-106.93004, 39.29975 ),
        array(-106.92957, 39.29941 ),
        array(-106.92902, 39.29909 ),
        array(-106.92824, 39.29884 ),
        array(-106.92804, 39.29875 ),
        array(-106.92784, 39.29859 ),
        array(-106.92766, 39.29849 ),
        array(-106.92645, 39.29808 ),
        array(-106.92623, 39.29801 ),
    ),
    array(
        array(-106.92623, 39.29801 ),
        array(-106.92601, 39.29842 ),
        array(-106.92591, 39.29845 ),
        array(-106.92562, 39.29847 ),
        array(-106.92534, 39.29849 ),
        array(-106.92518, 39.29848 ),
        array(-106.92512, 39.29845 ),
    )
);

// simplify to a 30-meter tolerance at this latitude (Colorado, USA)
require_once('douglaspeuker.php');
$tolerance = 0.00002;
$after = simplify_RDP($before,$tolerance);

// print some results
$length_before = strlen( json_encode($before) );
$length_after = strlen( json_encode($after) );
print "Before: $length bytes as JSON\n";
print "\n";
print "After: $length bytes as JSON\n";
