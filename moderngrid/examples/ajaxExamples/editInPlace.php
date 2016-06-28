<?php

require_once('../../dbConfig.php'); // this file is used to access database

$direction = $_REQUEST['order_dir'];
$currentPage = $_REQUEST['current_page'];
$recordsPerPage = $_REQUEST['records_per_page'];
$orderBy = $_REQUEST['order_by'];
$searchTerm = $_REQUEST['search_term'];

if (is_array($orderBy)) {
	$orderBy = implode(',', $orderBy);
}

if ($orderBy == '') {
	$orderBy = 'id';
}

if ($direction == '') {
	$direction = 'asc';
}

$offset = ($recordsPerPage * $currentPage) - $recordsPerPage;

$sql = "SELECT * FROM modernGrid.numbers n";

if ($searchTerm != '') {

		$sql .= ' WHERE n.item LIKE "%'.$searchTerm.'%"';

	}

	$sql .= " ORDER BY ".$orderBy." ".$direction;
	$sql .= " LIMIT ".$offset.','.$recordsPerPage;

	$result = $conn->query($sql);
	if($result === false) {
		trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $conn->error, E_USER_ERROR);		
	} else {
		while ($row = $result->fetch_assoc()) {
			$data[$row['id']] = $row;
		}
	}

$columns = array_keys($data[1]);

?>
<table>
	<thead>
		<tr>
		<?php foreach($columns as $colName) { ?>
			<th class="<?= $colName; ?>"><?= ucfirst(parseCamelCase($colName)); ?></th>
		<?php } ?>
		</tr>
	</thead>
	<tbody>
		<?php foreach($data as $key=>$value) { ?>
			<tr data-id="<?= $key; ?>">
				<?php foreach($value as $col=>$details) { 
					if ($col == 'inStock') { ?>
						<td><span class="editYesList" id="<?= $key.'-'.$col; ?>">
							<?php if ($details == 0) { ?>
								No
							<?php } else { ?>
								Yes
							<?php } ?>
						</span></td>
					<? } else { ?>
						<td><span class="edit" id="<?= $key.'-'.$col; ?>"><?= $details; ?></span></td>
					<?php } 
				}?>
			</tr>
		<?php } ?>
	</tbody>
</table>

<?php 

function parseCamelCase($str)
{
    return preg_replace('/(?!^)[A-Z]{2,}(?=[A-Z][a-z])|[A-Z][a-z]|[0-9]{1,}/', ' $0', $str);
}

?>