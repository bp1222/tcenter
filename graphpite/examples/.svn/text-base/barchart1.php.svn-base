<?php
	include("Image/Graph.php");
	
	// create the graph
	$Graph =& new Image_Graph(400, 300);

    // add a TrueType font
    $Arial =& $Graph->addFont(new Image_Graph_Font_TTF("arial.ttf"));
    // set the font size to 15 pixels
    $Arial->setSize(11);
    // add a title using the created font    
		
	// create the plotarea
	$Graph->add(
        new Image_Graph_Layout_Vertical(
            new Image_Graph_Title("German Car Popularity", $Arial),
            $Plotarea = new Image_Graph_Plotarea(),
            5            
        )
    );
			
	// create the dataset
	$Dataset =& new Image_Graph_Dataset_Trivial();
	$Dataset->addPoint(1, 100);
	$Dataset->addPoint(2, 41);
	$Dataset->addPoint(3, 78);
	$Dataset->addPoint(4, 12);
    
    $GridY =& $Plotarea->addGridY(new Image_Graph_Grid_Bars());  
    $GridY->setFillStyle(new Image_Graph_Fill_Gradient(IMAGE_GRAPH_GRAD_VERTICAL, IMAGE_GRAPH_WHITE, IMAGE_GRAPH_LIGHTGRAY, 100));
    
	// create the plot as bar chart using the dataset
	$Plot =& $Plotarea->addPlot(new Image_Graph_Plot_Bar($Dataset));
		
	$FillArray =& new Image_Graph_Fill_Array();
	$Plot->setFillStyle($FillArray);
    $FillArray->add(new Image_Graph_Fill_Gradient(IMAGE_GRAPH_GRAD_RADIAL, IMAGE_GRAPH_GREEN, IMAGE_GRAPH_WHITE, 200));
    $FillArray->add(new Image_Graph_Fill_Gradient(IMAGE_GRAPH_GRAD_RADIAL, IMAGE_GRAPH_BLUE, IMAGE_GRAPH_WHITE, 200));
    $FillArray->add(new Image_Graph_Fill_Gradient(IMAGE_GRAPH_GRAD_RADIAL, IMAGE_GRAPH_YELLOW, IMAGE_GRAPH_WHITE, 200));
    $FillArray->add(new Image_Graph_Fill_Gradient(IMAGE_GRAPH_GRAD_RADIAL, IMAGE_GRAPH_RED, IMAGE_GRAPH_WHITE, 200));
    $FillArray->add(new Image_Graph_Fill_Gradient(IMAGE_GRAPH_GRAD_RADIAL, IMAGE_GRAPH_ORANGE, IMAGE_GRAPH_WHITE, 200));
    
    $Marker =& new Image_Graph_Marker_Array();
    $Graph->add($Marker);
    $Marker->add(new Image_Graph_Marker_Icon("./images/audi.png"));
    $Marker->add(new Image_Graph_Marker_Icon("./images/mercedes.png"));
    $Marker->add(new Image_Graph_Marker_Icon("./images/porsche.png"));
    $Marker->add(new Image_Graph_Marker_Icon("./images/bmw.png"));
    
    $Plot->setMarker($Marker);
    
    $AxisX =& $Plotarea->getAxis(IMAGE_GRAPH_AXIS_X);
    $AxisX->setDataPreprocessor(
        new Image_Graph_DataPreprocessor_Array(
            array(
                1=>"Audi",
                2=>"Mercedes",
                3=>"Porsche",
                4=>"BMW"
            )
        )
    ); 
    $AxisX->setLabelInterval(1);
    $AxisY =& $Plotarea->getAxis(IMAGE_GRAPH_AXIS_Y);
    $AxisY->setDataPreprocessor(new Image_Graph_DataPreprocessor_Formatted("%0.0f%%"));
    $AxisY->forceMaximum(105);
    $AxisY->showArrow();
		
	// output the Graph
	$Graph->done();
?>
