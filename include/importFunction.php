<?php
function import($inputFileName, $columns, $table_name, $rows, $rowsOffSet)
{
    //configurations 
//    $inputFileName = './gdb.xls';
//    $columns = array(
//        "student_id"            => "A",
//        "student_first_name"    => "B",
//        "student_middle_name"   => "C",
//        "student_last_name"     => "D"    
//    );
//    $table_name = 'student';
//    $rows = 1000;
//    $rowsOffSet = 5500;

    //working
    $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
    $highestRow = $objPHPExcel->getActiveSheet()->getHighestRow();

    $data = array();

    $columnName = array_keys($columns);
    $columnIndex = array_values($columns);
    $columnsNames = implode (', ', $columnName);

    $allValues = array();
    $aValues = array();
    for ($i=$rowsOffSet;$i<$rowsOffSet+$rows and $i<=$highestRow;$i++){
        $records_string = array();
        foreach ($columnIndex as $data){
            $record_string = $objPHPExcel->getActiveSheet()->getCell($data.$i)->getValue();
            array_push($records_string,$record_string);
        }
        $values = '"'.implode ('", "', $records_string).'"';
        array_push($aValues,$values);
        $allValues = array_unique($aValues);
    }

    $sql = "INSERT INTO ".$table_name;
    $sql.= "(".$columnsNames.")";
    $added=0;
    $duplicated=0;
    foreach ($allValues as $value){
        $sqlValues = " VALUES(".$value.");";
//        echo $sql.$sqlValues.'<br>';
        $result = mysql_query($sql.$sqlValues);
        
        if($result){
            $added++;
//                echo '<p><b>YOU HAVE INSERTED YOUR DATA SUCCESSFULY.</b></p>';
        }
        else{
            $duplicated++;
//                echo '<p>You could not insert your data due to a system error!.</p>';
        }
    }    
    $rValue = array ($added,$duplicated);
    return $rValue;
}
?>