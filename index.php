<?php

function update_inventory(array $current_inventory, array $new_delivery): array
{
	// We normalize the arrays in order to simplify the algorithm
	$inventory = format($current_inventory);
	$delivery = format($new_delivery);

	// We update the inventory
	foreach($delivery as $item => $quantity) {
		if(isset($inventory[$item])) {
			$inventory[$item] += $quantity;
		} else {
			$inventory[$item] = $quantity;
		}
	}

	// We are forced to order the inventory by product name
	ksort($inventory);

	// And finally we return the inventory in the same weird format the client expects ^^U
	return unformat($inventory);
}

// Formats the array in order to work easier with it
function format(array $elements): array
{
	$normalized = [];

	foreach($elements as $element) {
		$normalized[$element[1]] = $element[0];
	}

	return $normalized;
}

// Converts the array to its weird original format
function unformat(array $elements): array
{
	$normalized = [];

	foreach($elements as $element => $quantity) {
		$normalized[] = [$quantity, $element];
	}

	return $normalized;

}

// Let's try the algorithm with some tests!
try {
	evaluate([
	[88, "Bowling Ball"], 
	[2, "Dirty Sock"], 
	[3, "Hair Pin"], 
	[3, "Half-Eaten Apple"], 
	[5, "Microphone"], 
	[7, "Toothpaste"]
	], update_inventory([
		[21, "Bowling Ball"], 
		[2, "Dirty Sock"], 
		[1, "Hair Pin"], 
		[5, "Microphone"]
		], [
			[2, "Hair Pin"], 
			[3, "Half-Eaten Apple"], 
			[67, "Bowling Ball"], 
			[7, "Toothpaste"]
		]));
	
	evaluate([
    [21, "Bowling Ball"],
    [2, "Dirty Sock"],
    [1, "Hair Pin"],
    [5, "Microphone"]
], update_inventory([
    [21, "Bowling Ball"],
    [2, "Dirty Sock"],
    [1, "Hair Pin"],
    [5, "Microphone"]
], []));
evaluate([
    [67, "Bowling Ball"],
    [2, "Hair Pin"],
    [3, "Half-Eaten Apple"],
    [7, "Toothpaste"]
], update_inventory([], [
    [2, "Hair Pin"],
    [3, "Half-Eaten Apple"],
    [67, "Bowling Ball"],
    [7, "Toothpaste"]
]));
evaluate([
    [1, "Bowling Ball"],
    [0, "Dirty Sock"],
    [1, "Hair Pin"],
    [1, "Half-Eaten Apple"],
    [0, "Microphone"],
    [1, "Toothpaste"]
], update_inventory([
    [0, "Bowling Ball"],
    [0, "Dirty Sock"],
    [0, "Hair Pin"],
    [0, "Microphone"]
], [
    [1, "Hair Pin"],
    [1, "Half-Eaten Apple"],
    [1, "Bowling Ball"],
    [1, "Toothpaste"]
]));
	
	echo "All tests passed!";


} catch(Exception $e) {
	echo $e->getMessage()."<br>";
}

/*
 * The purpose of this function is only to make sure the algorithm works as expected.
*/
function evaluate($expected, $value): bool
{
	if($expected !== $value) {
		throw new Exception('ERROR: Expecting ['.implode(',', $expected).'], got ['.implode(',', $value).']');
	}

	return true;
}
