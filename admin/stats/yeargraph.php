<?php
    // include graphpite classes
    include("Image/Graph.php");
		include("../../inc/connection.inc");

		# The X-Axis Labels
		# Get last 7 days of work with their data
		$open = array();
		$closed = array();
		$tempdate = array();
		$tempopen = array();
		$tempclosed = array();
		
		$query = "SELECT date, name, value FROM stats ORDER BY date DESC LIMIT 730;";
		$result = pg_query($query);

		while ($row = pg_fetch_assoc($result)) {

			$curdate = trim($row['date']);
			$name = trim($row['name']);
			$value = trim($row['value']);

			# add element to the date array, used for the X-Axis labels
			if (!in_array($curdate, $tempdate)) {
				array_push($tempdate, $curdate);
			}
			# if it is open or closed, add it to the proper array
			if ($name == "open") {
				array_push($tempopen, $value);
			}
			else {
				array_push($tempclosed, $value);
			}

	}

		$date = array_reverse($tempdate);
		$open = array_reverse($tempopen);
		$closed = array_reverse($tempclosed);

		# make a new graph
		$Graph =& new Image_Graph(800, 500);

		# create two new font types
		$Arial =& $Graph->addFont(new Image_Graph_Font_TTF("arial.ttf"));
		$Arial2 =& $Graph->addFont(new Image_Graph_Font_TTF("arial.ttf"));

		# set the font size of the two font types
		$Arial->setSize(12);
		$Arial2->setSize(10);

		# add a new graph setup
		$Graph->add(
			# set up new graph attributes
			new Image_Graph_Layout_Vertical(
				# set the title of the graph
				new Image_Graph_Title("Yearly Residential Support Input/Output", $Arial),
				new Image_Graph_Layout_Horizontal(
						$PlotArea = new Image_Graph_Plotarea(),
						# add a legend
						$Legend_Line = new Image_Graph_Legend(),
						90	
				),
				15
			),
			100
		);

		$Legend_Line->setPlotArea($PlotArea);

		# set some static colors
		$RED =& $Graph->newColor(IMAGE_GRAPH_RED, 100);
		$GREEN =& $Graph->newColor(IMAGE_GRAPH_GREEN, 100);
		$BLUE =& $Graph->newColor(IMAGE_GRAPH_BLUE, 100);
		$WHITE =& $Graph->newColor(IMAGE_GRAPH_WHITE, 25);

		# add grid lines
		$Grid_PlotArea =& $PlotArea->addGridY(new Image_Graph_Grid_Bars());
		$Grid_PlotArea->setFillStyle(new Image_Graph_Fill_Gradient(IMAGE_GRAPH_GRAD_VERTICAL, IMAGE_GRAPH_LIGHTGRAY, IMAGE_GRAPH_LIGHTGRAY, 100));

		# create a new plot area for the "open" tickets
		$Plot =& $PlotArea->addPlot(new Image_Graph_Plot_Line(new Image_Graph_Dataset_Sequential($open)));
		# set the line color for the "open" tickets
		$Plot->setLineColor(IMAGE_GRAPH_BLUE);
		# create markers so we can see the value at each point
#		$ValueMarker2 =& $Plot->setMarker(new Image_Graph_Marker_Asterisk());
#		$ValueMarker2->setSize(3);
		# create box and pointer for value
#		$ValueMarker =& new Image_Graph_Marker_Value(IMAGE_GRAPH_VALUE_Y);
#		$ValueMarker->setFont($Arial2);
#		$ValueMarker->setFillColor(IMAGE_GRAPH_WHITE);
		# set the pointer and box to point at the marker
#		$ValueMarker2->setSecondaryMarker(new Image_Graph_Marker_Pointing_Angular(20, $ValueMarker));
		$Plot->setTitle("Open");

		# create a new plot area for the "closed" tickets
		$Plot =& $PlotArea->addPlot(new Image_Graph_Plot_Line(new Image_Graph_Dataset_Sequential($closed)));
		# set the line color for the "closed" tickets
		$Plot->setLineColor(IMAGE_GRAPH_RED);
		# create markers so we can see the value at each point
#		$ValueMarker2 =& $Plot->setMarker(new Image_Graph_Marker_Asterisk());
#		$ValueMarker2->setSize(3);
		# create box and pointer for value
#		$ValueMarker =& new Image_Graph_Marker_Value(IMAGE_GRAPH_VALUE_Y);
#		$ValueMarker->setFont($Arial2);
#		$ValueMarker->setFillColor(IMAGE_GRAPH_WHITE);
		# set the pointer and box to point at the marker
#		$ValueMarker2->setSecondaryMarker(new Image_Graph_Marker_Pointing_Angular(20, $ValueMarker));
		$Plot->setTitle("Closed");

		# build the X-Axis
		$AxisX =& $PlotArea->getAxis(IMAGE_GRAPH_AXIS_X);
		# set the values of the X-Axis to be the dates of each entry
		$AxisX->setDataPreprocessor(new Image_Graph_DataPreprocessor_Array($date));
		# change the font of the X-Axis labels
		$AxisFontX =& $Graph->addFont(new Image_Graph_Font_TTF("arial.ttf"));
		$AxisFontX->setSize(8);
		# set the angle of the entries so they are easier to read
		$Angle = 45;
		$AxisFontX->setAngle($Angle);
		$AxisX->setFont($AxisFontX);
		$AxisX->showArrow();

		# set up the Y-Axis with general numbers to follow
		$AxisY = $PlotArea->getAxis(IMAGE_GRAPH_AXIS_Y);
		$AxisY->showArrow();

		# add the legend
		$Legend_Line->setFillColor(IMAGE_GRAPH_WHITE);
		$Legend_Line->setAlignment(IMAGE_GRAPH_ALIGN_TOP);
		$Legend_Line->setFont($Arial2);

		# submit the graph
		$Graph->showShadow();
		$Graph->setBorderColor(IMAGE_GRAPH_BLACK);
		$Graph->add(
			new Image_Graph_Logo("logo/logo.jpg", IMAGE_GRAPH_ALIGN_TOP_RIGHT)
		);
		$Graph->done();

?>
