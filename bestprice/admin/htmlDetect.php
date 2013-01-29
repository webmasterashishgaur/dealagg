<html>
<head>
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
	<script type="text/javascript">

	$(document).ready(function(){
		$("#bus table tbody tr td table td").attr("align","center");
		$("#bus table").attr("border","1");
		$(".form").attr("border","0");

		$("#bus table tbody tr td table td:nth-child(3)").each(function(i){
			var url=$(this).text();
			$(this).html("<a href="+url+" target=_blank>"+url+"</a>");
		});
	});
	</script>	
</head>


<?php
require_once 'couponClass.php';
require_once '../smartmodel/UI.php';

$user = new html_detect();
$data=$user->read(null,null);

$usersTable=new TableUI($user,UI::STYLE_LIGHT_GREY);

//code starts here for adding a new column to a table
$array=array('Html'=>'view_html');
$usersTable->addCustomColumn($array);
Function view_html($row)
{
	Return "<a href=# class='add_coupon'>View Html</a>";
}
//code ends here for adding a new column to a table

//code starts here for sorting the column ID in desc order
$usersTable->sortCol='priority'; 
$usersTable->sorting='desc';
//code ends here for sorting the column ID in desc order

$table=$usersTable->generateTable($user);
echo $table;
?>
</html>