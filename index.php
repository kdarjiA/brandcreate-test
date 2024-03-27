<?php

$base_url = "http://$_SERVER[SERVER_NAME]";

$carData = [
	[
		"car_name" => "Tesla Model S",
		"price" => 79999,
		"discount" => 5000,
		"hand" => 4,
		"availability" => "In Stock",
		"color" => "Red"
	],
	[
		"car_name" => "Toyota Prius",
		"price" => 24999,
		"discount" => 2000,
		"hand" => 2,
		"availability" => "Out of Stock",
		"color" => "Blue"
	],
	[
		"car_name" => "Ford Mustang",
		"price" => 55999,
		"hand" => 3,
		"discount" => 3000,
		"availability" => "In Stock",
		"color" => "Black"
	],
	[
		"car_name" => "Audi A4",
		"price" => 39999,
		"discount" => 4500,
		"hand" => 1,
		"availability" => "In Stock",
		"color" => "White"
	],
	[
		"car_name" => "BMW 3 Series",
		"price" => 41999,
		"hand" => 1,
		"discount" => 4000,
		"availability" => "Out of Stock",
		"color" => "Silver"
	]
];
$sortedCarData = json_encode($carData);

if (isset($_GET['sort_price'])) {
	if ($_GET['sort_price'] == 'asc') {
		usort($carData, 'sortByPriceAsc');
	} else {
		usort($carData, 'sortByPriceDesc');
	}

	$sortedCarData = json_encode($carData);
}

function sortByPriceAsc($a, $b)
{
	return $a['price'] - $b['price'];
}
function sortByPriceDesc($a, $b)
{
	return $b['price'] - $a['price'];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Car Inventory</title>
	<style>
		body {
			font-family: Arial, Helvetica, sans-serif;
		}

		table {
			border-collapse: collapse;
			width: 100%;
		}

		th,
		td {
			border: 1px solid #dddddd;
			text-align: left;
			padding: 8px;
		}

		th {
			background-color: #f2f2f2;
		}
	</style>
</head>

<body>
	<input type="hidden" name="colorSortValue" id="colorSortValue" value="asc">
	<h2>Car Inventory</h2>
	<form method="get" style="margin: 15px 0; float:right;">
		<label for="sort">Sort by Price:</label>
		<select name="sort_price" id="sort">
			<option value="asc" <?php if (isset($_GET['sort_price']) && $_GET['sort_price'] == 'asc') { ?> selected <?php  } ?>> Low to High </option>
			<option value="desc" <?php if (isset($_GET['sort_price']) && $_GET['sort_price'] == 'desc') { ?> selected <?php  } ?>> High to Low </option>
		</select>
		<input type="submit" value="Sort">
	</form>

	<table id="carTable">
		<tr>
			<th>Car Name</th>
			<th>Price</th>
			<th>Discount</th>
			<th>Availability</th>
			<th>Color</th>
		</tr>
	</table>



	<script type="text/javascript">
		const currentUrl = window.location.href;
		const $baseUrl = '<?php echo $base_url; ?>';

		// Create a URL object from the current URL
		const url = new URL(currentUrl);

		// Get the search parameters from the URL
		const urlParams = new URLSearchParams(window.location.search);
		const sortColor = urlParams.get('sort_color');

		carsList = <?php echo json_encode($sortedCarData); ?>;
		renderTable(carsList);

		function renderTable(data) {
			const table = document.getElementById('carTable');

			var $linkUrl = currentUrl;
			if (sortColor != "") {
				$linkUrl = $baseUrl + "/index.php?sort_color=" + (sortColor == 'asc' ? 'desc' : 'asc');
				$imageUrl = (sortColor == 'asc' ? 'down-arrow.png' : 'up-arrow.png');
			} else {
				$linkUrl = $baseUrl + "/index.php?sort_color=asc";
				$imageUrl = 'up-arrow.png';
			}

			table.innerHTML = `<tr>
									<th>Car Name</th>
									<th>Price</th>
									<th>Discount</th>
									<th>Availability</th>
									<th>Color
									<a href=${$linkUrl}>
										<img src="${$imageUrl}" height="16" width="16" />
									</a>
									</th>
								</tr>`;

				if (typeof data != "object") {
					data = JSON.parse(data);
				}

				data.forEach(car => {
					const row = document.createElement('tr');
					row.innerHTML = `<td>${car.car_name}</td>
								<td>$ ${car.price}</td>
								<td>$ ${car.discount}</td>
								<td>${car.availability}</td>
								<td>${car.color}</td>`;
					table.appendChild(row);
				});
			}

			if (sortColor != "") {
				if (sortColor == 'asc') {
					const sortedByColorAsc = sortByKey(carsList, 'color', 'asc');
					renderTable(sortedByColorAsc);
				} else if (sortColor == 'desc') {
					const sortedByColorDesc = sortByKey(carsList, 'color', 'desc');
					renderTable(sortedByColorDesc);
				}
			}

			// Function to sort car data by a particular key and order
			function sortByKey(data, key, order = 'asc') {
				if (typeof data != "object") {
					data = JSON.parse(data);
				}
				return data.sort((a, b) => {
					let comparison = 0;
					if (a[key] > b[key]) {
						comparison = 1;
					} else if (a[key] < b[key]) {
						comparison = -1;
					}
					return (order === 'desc') ? comparison * -1 : comparison;
				});
			}
	</script>

</body>

</html>