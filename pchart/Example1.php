<?php   
 /*
     Example1 : A simple line chart折线图
 */

 // Standard inclusions      
 include("pChart/pData.class.php");   
 include("pChart/pChart.class.php");   
 // Dataset definition    数据填入
 $DataSet = new pData;   
 $DataSet->AddPoint(array(1,4,3,2,3,3,5,1,4,7,4,3,8,3,3,5,1,2,7),"Serie1");//自己传入数据
 $DataSet->AddPoint(array(2,3,4,5,6,7,8,8,1,2,3,4,5,6,7,8,8,1,1),"Serie2");
 $DataSet->AddPoint(array(1,9,7,4,3,3,2,9,3,1,4,3,2,2,9,1,1,4,7),"Serie3");
 //$DataSet->ImportFromCSV("Sample/bulkdata.csv",",",array(1,2,3),FALSE,0); //从csv导入数据
 $DataSet->AddAllSeries();   
 $DataSet->SetAbsciseLabelSerie();   
 $DataSet->SetSerieName("January","Serie1");   //设置图例
 $DataSet->SetSerieName("February","Serie2");   
 $DataSet->SetSerieName("March","Serie3");   
 $DataSet->SetYAxisName("Average age"); 
 $DataSet->SetYAxisUnit("u");#设置单位

 // Initialise the graph   图片生成
 $Test = new pChart(700,230);
 $Test->setFontProperties("Fonts/tahoma.ttf",8);   //字体  
 $Test->setGraphArea(70,30,680,200); //表格大小  距左 距顶 表格宽 表格高  
 $Test->drawFilledRoundedRectangle(7,7,693,223,5,240,240,240);   //整体背景色   距左 距顶 宽度 高度 圆角 颜色的三个参数
 $Test->drawRoundedRectangle(5,5,695,225,5,230,230,230);   //边框 距左 距顶 宽度 高度 颜色参数
 $Test->drawGraphArea(255,255,255,TRUE); //表格内颜色
 $Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,150,150,150,TRUE,0,2);   
 $Test->drawGrid(9,TRUE,230,230,230,50);
 $Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie1");//在曲线上设定显示 数值 
 $Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie2");//在曲线上设定显示 数值 
 $Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie3");//在曲线上设定显示 数值 
 // Draw the 0 line   
 $Test->setFontProperties("Fonts/tahoma.ttf",6);   
 $Test->drawTreshold(0,143,55,72,TRUE,TRUE);   
  
 // Draw the line graph
 $Test->drawLineGraph($DataSet->GetData(),$DataSet->GetDataDescription());   
 $Test->drawPlotGraph($DataSet->GetData(),$DataSet->GetDataDescription(),3,2,255,255,255);   
  
 // Finish the graph   
 $Test->setFontProperties("Fonts/tahoma.ttf",8);   
 $Test->drawLegend(75,35,$DataSet->GetDataDescription(),255,255,255);   
 $Test->setFontProperties("Fonts/tahoma.ttf",10);   
 $Test->drawTitle(60,22,"example 1",50,50,50,585);   //标题 距左 距顶  文字  颜色
 $Test->Render("example1.png");
 echo "<img src='example1.png'>";
?>