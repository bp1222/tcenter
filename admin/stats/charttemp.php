		$query = "SELECT date, name, value FROM stats ORDER BY date DESC LIMIT 14;";
		$result = pg_query($query);

		while ($row = pg_fetch_assoc($result)) {

			$curdate = trim($row['date']);
			$name = trim($row['name']);
			$value = trim($row['value']);

			# add element to the date array, used for the X-Axis labels
			if (!in_array($curdate, $date)) {
				array_push($date, $curdate);
			}
			# if it is open or closed, add it to the proper array
			if ($name == "open") {
				array_push($open, $value);
			}
			else {
				array_push($closed, $value);
			}

	}
