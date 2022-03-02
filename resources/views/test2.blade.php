<!DOCTYPE html>
<html>
<head>
	<style type="text/css">
		    body {
        margin: 0;
        padding: 0;
        background-color: #FAFAFA;
        font: 10pt "Tahoma";
    }
    * {
        box-sizing: border-box;
        -moz-box-sizing: border-box;
    }
    .page {
        width: 21cm;
        min-height: 29.7cm;
        padding: 2cm;
        margin: 1cm auto;
        border: 1px #D3D3D3 solid;
        border-radius: 5px;
        background: white;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }
    h3{
    	font-size: 18px;
    }
    .subpage {
        padding: .3cm;
        border: 5px red solid;
        height: 256mm;
        outline: 2cm #FFEAEA solid;
    }
    
    @page {
        size: A4;
        margin: 0;
    }
    @media print {
        .page {
            margin: 0;
            border: initial;
            border-radius: initial;
            width: initial;
            min-height: initial;
            box-shadow: initial;
            background: initial;
            page-break-after: always;
        }
        
    }
    /* Float four columns side by side */
.column {
  float: left;
  width: 50%;
  padding: 0 10px;
  margin-top:10px;
}

/* Remove extra left and right margins, due to padding */
.row {margin: 0 -5px;}

/* Clear floats after the columns */
.row:after {
  content: "";
  display: table;
  clear: both;
}

/* Responsive columns */
@media screen and (max-width: 600px) {
  .column {
    width: 100%;
    display: block;
    margin-bottom: 20px;
  }
}

/* Style the counter cards */
.card {
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
  padding: 10px;
  text-align: left;
  background-color: #fff;
}
	</style>
	<title></title>
</head>
<body>
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
   '20'   => 'two',

];

$total=count($array);
	$cardsremaining=$total;
	$count=0;
	$totalcards=10;
	$totalpages=ceil($total/$totalcards);
	$remnant=$total%$totalcards;
	$limit=0;
for ($i=1; $i <=$totalpages ; $i++):
	?>
<div class="book">
    <div class="page">
        <div class="subpage"><?php  echo "Page ".$i."/".$totalpages; ?>
        <div class="row">
  
  			<?php
  				if ($i<$totalpages) {
			$limit=$i*$totalcards;
   
			for ($a=($limit-$totalcards); $a <$limit ; $a++) { 
			?>
			<div class="column">
		    <div class="card">
		      <h3>Peacefield Schools</h3>
		      <p>Pin:<span style="background: #000;color: #fff; padding:5px"><?php echo array_values($array)[$a]; ?></span></p>
		      <p>Valid for:</p>
		      <div style="text-align: center;">
		      	<span style="font-size: 10px;">Powered by Kings Crown Communication</span>
		      </div>
		      
		    </div>
		  </div>
			<?php
				
			}
		}elseif($i==$totalpages&&$remnant==0){
    
			$limit=$i*$totalcards;
    
			for ($b=($limit-$totalcards); $b <$limit ; $b++) { 
				?>
					<div class="column">
		    <div class="card">
		      <h3></h3>
		      <p>Pin:<?php echo array_values($array)[$a]; ?></p>
		      <p>Valid for:</p>
		      <div style="text-align: center;">
		      	<span style="font-size: 10px;">Powered by Kings Crown Communication</span>
		      </div>
		    </div>
		  </div>
			<?php
				
			}
		}elseif($i==$totalpages){
			$limit=(($i-1)*$totalcards)+$remnant;
     
			for ($c=($limit-$remnant); $c <$limit+$remnant-1 ; $c++) { 
				?>
				<div class="column">
		    <div class="card">
		      <h3><?php echo array_values($array)[$a]; ?></h3>
		      <p>Pin:<?php echo array_values($array)[$a]; ?></p>
		      <p>Valid for:</p>
		      <div style="text-align: center;">
		      	<span style="font-size: 10px;">Powered by Kings Crown Communication</span>
		      </div>
		    </div>
		  </div>
			<?php
				
			}
		}
  			 ?>
		  

		</div>
		</div>
	</div>
</div>

	<?php 

endfor;
	?>


<script type="text/javascript">
	window.print();
</script>
</body>
</html>