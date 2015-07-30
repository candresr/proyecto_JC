<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once ('jpgraph/jpgraph.php');
class Jpgraph {   
    function pieChart($dataPieChart){            
            require_once ('jpgraph/jpgraph_pie.php');

            $data   = $dataPieChart['data'];
            $width  = $dataPieChart['width'];
            $height = $dataPieChart['height'];
            $title  = $dataPieChart['title'];
            $legend = $dataPieChart['legend'];
            $imgName= $dataPieChart['imgName'];
            $pieColor=$dataPieChart['pieColor'];
            $legendShow = $dataPieChart['legendShow'];

            $graph = new PieGraph($width,$height);
//            $graph->SetShadow();
            $graph->ClearTheme(); // Eliminamos el thema para asignarles colores personalizados
            $graph->SetFrame(false); //Borde de la imagen
            $graph->title->Set($title); // Definimos el titulo
            $graph->title->SetColor("#333333"); // Color del titulo
//            $graph->title->SetFont(FF_ARIAL, FS_BOLD, 14);
            $graph->legend->SetLayout(LEGEND_HOR); // Leyenda si la tuviera con posicion horizontal
            $graph->legend->SetColumns(1); // Cantidad de columnas para la leyenda
            $graph->legend->SetPos(0.0,0.0,'left','top'); // posicion de la leyenda
            $graph->legend->SetShadow(); // Sombra bajo cuadro de leyenda
            $graph->legend->SetMarkAbsSize(7); //Tamaño de los cuadritos de colores de las leyendas
            $graph->legend->SetLineWeight(5);
            if($legendShow == TRUE){}else{
                $graph->legend->Hide(); // Define si es visible o no la leyenda
            }

            $p1 = new PiePlot($data);
            $p1->SetCenter(0.5,0.4);
            $p1->SetSize(0.28); // Tamaño del grafico Importante !!!!!!!!
            $p1->SetGuideLines(true,false); // Lineas apuntando al grafico
            $p1->SetGuideLinesAdjust(1.0); // Ajuste de posicion de las lineas
            $p1->SetLabelType(PIE_VALUE_PER); // 
            $p1->SetSliceColors($pieColor); // Arreglo de colores a mostrar
            $p1->value->Show();	// Muestra los valores de el grafico			
            $p1->value->SetFormat('%2.1f%%');	// Formato de los valores en este caso porcentual de dos decimales
            $p1->value->SetColor("#333333"); // Color de los numeros
//            $p1->value->SetFont(FF_ARIAL, FS_BOLD, 10);
            $p1->SetLegends($legend); // Arreglo de leyendas
            
            $graph->Add($p1);
            $dir = getcwd();

            $graph->Stroke($dir.'/tmp/'.$imgName);
    }
    
    
    function lineChart(){
            require_once ('jpgraph/jpgraph_line.php');

            $datay1 = array(20,15,23,15);
            $datay2 = array(12,9,42,8);
            $datay3 = array(5,17,32,24);

            // Setup the graph
            $graph = new Graph(300,250);
            $graph->SetScale("textlin");

            $theme_class=new UniversalTheme;

            $graph->SetTheme($theme_class);
            $graph->img->SetAntiAliasing(false);
            $graph->title->Set('Filled Y-grid');
            $graph->SetBox(false);

            $graph->img->SetAntiAliasing();

            $graph->yaxis->HideZeroLabel();
            $graph->yaxis->HideLine(false);
            $graph->yaxis->HideTicks(false,false);

            $graph->xgrid->Show();
            $graph->xgrid->SetLineStyle("solid");
            $graph->xaxis->SetTickLabels(array('A','B','C','D'));
            $graph->xgrid->SetColor('#E3E3E3');
            /* $graph->SetBackgroundImage("tiger_bkg.png",BGIMG_FILLPLOT); */

            // Create the first line
            $p1 = new LinePlot($datay1);
            $graph->Add($p1);
            $p1->SetColor("#6495ED");
            $p1->SetLegend('Line 1');

            // Create the second line
            $p2 = new LinePlot($datay2);
            $graph->Add($p2);
            $p2->SetColor("#B22222");
            $p2->SetLegend('Line 2');

            // Create the third line
            $p3 = new LinePlot($datay3);
            $graph->Add($p3);
            $p3->SetColor("#FF1493");
            $p3->SetLegend('Line 3');

            $graph->legend->SetFrameWeight(1);

            // Output line
            $graph->Stroke('/home/jclg/public_html/cantv/tmp/'.$imgName);
    }
}


?>
