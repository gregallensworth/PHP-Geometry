/*
 * Some code for processing polygons, e.g. finding the centroid
 * This is largely tuned to our use cases, such as finding the centroid of park areas and city bounds,
 * so won't handle odd cases such as the multiple rings overlapping (it'll double count area, throw off the center a bit).
 * 
 * Expected format of a polygon/multipolygon is like that returned by ArcGIS REST:
 * - $geometry has a "rings" attribute, this being a list of rings
 * - a ring is a list of 2-element lists, each one being a vertex in X,Y order
 * 
 * Example:
 * $polygon = new stdClass();
 * $polygon->rings = array(
 *   array( array(-93.3934,45.07184), array(-93.3935,45.07173), array(-93.393183,45.071321), array(-93.3934,45.07184) ),
 *   array( .. next ring ...),
 * );
 * 
 * Source: http://en.wikipedia.org/wiki/Centroid#Centroid_of_polygon
 */

/*
 * calculate the area of a polygon, in whatever units it's in
 */
public function getAreaOfPolygon($geometry) {
    $area = 0;
    for ($ri=0, $rl=sizeof($geometry->rings); $ri<$rl; $ri++) {
        $ring = $geometry->rings[$ri];

        for ($vi=0, $vl=sizeof($ring); $vi<$vl; $vi++) {
            $thisx = $ring[ $vi ][0];
            $thisy = $ring[ $vi ][1];
            $nextx = $ring[ ($vi+1) % $vl ][0];
            $nexty = $ring[ ($vi+1) % $vl ][1];
            $area += ($thisx * $nexty) - ($thisy * $nextx);
        }
    }

    // done with the rings: "sign" the area and return it
    $area = abs(($area / 2));
    return $area;
}

/*
 * calculate the centroid of a polygon
 * return a 2-element list: array($x,$y)
 */
public function getCentroidOfPolygon($geometry) {
    $cx = 0;
    $cy = 0;

    for ($ri=0, $rl=sizeof($geometry->rings); $ri<$rl; $ri++) {
        $ring = $geometry->rings[$ri];

        for ($vi=0, $vl=sizeof($ring); $vi<$vl; $vi++) {
            $thisx = $ring[ $vi ][0];
            $thisy = $ring[ $vi ][1];
            $nextx = $ring[ ($vi+1) % $vl ][0];
            $nexty = $ring[ ($vi+1) % $vl ][1];

            $p = ($thisx * $nexty) - ($thisy * $nextx);
            $cx += ($thisx + $nextx) * $p;
            $cy += ($thisy + $nexty) * $p;
        }
    }

    // last step of centroid: divide by 6*A
    $area = $this->getAreaOfPolygon($geometry);
    $cx = -$cx / ( 6 * $area);
    $cy = -$cy / ( 6 * $area);

    // done!
    return array($cx,$cy);
}
