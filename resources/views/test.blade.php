<?php
	$array = [
   '0'     => 'Zero',
   '1'   => 'One',
   '2' => 'Two',
   '3'     => 'eZero',
   '4'   => 'One',
   '5' => 'Two',
   '6'     => 'Zero',
   '7'   => 'One',
   '8' => 'Two',
   '9'     => 'Zero',
   '10'   => 'One',
   '11' => 'Two',
   '12'     => 'Zero',
   '13'   => 'One',
   '14' => 'Two',
   '15'     => 'Zero',
   '16'   => 'One',
   '17' => 'Two',
   '18'     => 'Zero',
   '19'   => 'One',

];

	$total=count($array);
	$cardsremaining=$total;
	$count=0;
	$totalcards=10;
	$totalpages=ceil($total/$totalcards);
	$remnant=$total%$totalcards;
	$limit=0;
	for ($i=1; $i <=$totalpages ; $i++) { 
		echo "page".$i."<br>";
		if ($i<$totalpages) {
			$limit=$i*$totalcards;
    echo "limit".$limit."<br>";
			for ($a=($limit-$totalcards); $a <$limit ; $a++) { 
				
				echo array_values($array)[$a]."<br>";
			}
		}elseif($i==$totalpages&&$remnant==0){
    
			$limit=$i*$totalcards;
     echo "limit".$limit."<br>";
			for ($b=($limit-$totalcards); $b <$limit ; $b++) { 
				
				echo array_values($array)[$b]."<br>";
			}
		}elseif($i==$totalpages){
			$limit=(($i-1)*$totalcards)+$remnant;
     echo "limit".$limit."<br>";
			for ($c=($limit-$remnant); $c <$limit+$remnant-1 ; $c++) { 
				
				echo array_values($array)[$c]."<br>";
			}
		}

		
	}