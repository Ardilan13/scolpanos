<?php

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
class spn_rapport_by_student_8
{



  function createrapport($schoolid,$schooljaar,$studentid, $generate_all_rapporten){

    require_once ("../classes/3rdparty/PHPExcel/PHPExcel/IOFactory.php");
    // Create new PHPExcel object
    $objPHPExcel = PHPExcel_IOFactory::load("../templates/wittekaart_cococo.xlsx");


    // $objPHPExcel->setActiveSheetIndex(0)->getActiveSheet()->setTitle('Simple');
    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);
    // Redirect output to a client’s web browser (Excel2007)


    $this->write_cijfer_data($schoolid,$schooljaar,$studentid, $objPHPExcel);


    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="wittekaart_cococo.xlsx"');
    header('Cache-Control: max-age=0');
    // If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');
    // If you're serving to IE over SSL, then the following may be needed
    header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
    header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header ('Pragma: public'); // HTTP/1.0
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    exit;
  }


  function write_cijfer_data($schoolid,$schooljaar_rapport,$id_student, $objPHPExcel)
  {

    $_default_period_1 = "B";
    $_default_period_2 = "C";
    $_default_period_3 = "D";
    $_colum_rapport = "";

    require_once("../classes/DBCreds.php");

    if($schooljaar_rapport == "All"){

      // print('ALL');
      $rapport_query = "SELECT distinct concat(s.firstname, ' ', s.lastname), c.gemiddelde,
      v.volledigenaamvak, v.y_index, c.rapnummer, c.klas, c.schooljaar
      FROM
      le_cijfers c
      inner join
      le_vakken v on
      c.vak = v.id
      inner join
      students s on
      s.id = c.studentid
      WHERE c.studentid = $id_student order by c.schooljaar, c.rapnummer, v.y_index asc;";

      $_default_scooljaar = "B3";
      $_default_klas = "B4";
      $_default_docent = "B5";

      try
      {
        $DBCreds = new DBCreds();
        $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
        $mysqli->set_charset('utf8');
        if($select=$mysqli->prepare($rapport_query)) {
          if($select->execute())
          {
            $select->store_result();
            $this->error = false;
            if($select->num_rows > 0)
            {
              $result=1;
              $select->bind_result($studentName ,$gemiddelde, $volledigenaamvak, $y_index,$rapnummer, $klas, $schooljaar);

              $c = 0;
              $counter_while = 0;
              $current_schooljaar = "";
              $last_schooljaar ="";

              while($select->fetch())
              {
                if ($counter_while == 0 ){

                  $current_schooljaar = $schooljaar;

                  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($_default_scooljaar,$schooljaar);
                  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($_default_klas, $klas );
                  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($_default_docent, "DOCENT" );
                  $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B2", $studentName);

                  $this->write_verzuim_data($objPHPExcel,$id_student, $schooljaar, "B");
                  $this->write_houding_data($objPHPExcel,$id_student, $schooljaar, "B",$klas);
                  // $this->write_avi_data($id_student,$schooljaar, "B");

                  if ($rapnummer == "1" ){
                    $colum_rapport ="B";
                  }
                  else if ($rapnummer == "2" ){
                    $colum_rapport ="C";
                  }
                  else{
                    $colum_rapport ="D";
                  }
                  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_rapport.(string)$y_index, $gemiddelde);

                }

                $last_schooljaar = $schooljaar;

                if($current_schooljaar != $last_schooljaar)
                {
                  $c++;
                  if ( $c==1 ){
                    $_default_scooljaar = "F3";
                    $_default_klas = "F4";
                    $_default_docent = "F5";

                    $this->write_verzuim_data($objPHPExcel,$id_student, $schooljaar, "F");
                    $this->write_houding_data($objPHPExcel,$id_student, $schooljaar, "F", $klas);


                  }
                  if ( $c==2 ){

                    $_default_scooljaar = "J3";
                    $_default_klas = "J4";
                    $_default_docent = "J5";

                    $this->write_verzuim_data($objPHPExcel,$id_student, $schooljaar, "J");
                    $this->write_houding_data($objPHPExcel,$id_student, $schooljaar, "J", $klas);
                    // $this->write_avi_data($id_student, $schooljaar,"J");

                  }
                  if ( $c==3 ){
                    $_default_scooljaar = "N3";
                    $_default_klas = "N4";
                    $_default_docent = "N5";
                    $this->write_verzuim_data($objPHPExcel,$id_student, $schooljaar, "N");
                    $this->write_houding_data($objPHPExcel,$id_student, $schooljaar, "N", $klas);
                    // $this->write_avi_data($id_student, $schooljaar, "K");

                  }
                  if ( $c==4 ){
                    $_default_scooljaar = "R3";
                    $_default_klas = "R4";
                    $_default_docent = "R5";
                    $this->write_verzuim_data($objPHPExcel,$id_student, $schooljaar, "R");
                    $this->write_houding_data($objPHPExcel,$id_student, $schooljaar, "R", $klas);
                    // $this->write_avi_data($id_student, $schooljaar,"N");

                  }
                  if ( $c==5 ){
                    $_default_scooljaar = "V3";
                    $_default_klas = "V4";
                    $_default_docent = "V5";
                    $this->write_verzuim_data($objPHPExcel,$id_student, $schooljaar, "V");
                    $this->write_houding_data($objPHPExcel,$id_student, $schooljaar, "V", $klas);
                    // $this->write_avi_data($id_student, $schooljaar,"Q");

                  }
                  if ( $c==6 ){
                    $_default_scooljaar = "Z3";
                    $_default_klas = "Z4";
                    $_default_docent = "Z5";
                    $this->write_verzuim_data($objPHPExcel,$id_student, $schooljaar, "Z");
                    $this->write_houding_data($objPHPExcel,$id_student, $schooljaar, "Z", $klas);
                    // $this->write_avi_data($id_student, $schooljaar,"T");

                  }

                  $current_schooljaar = $schooljaar;
                }

                // CHECK DE RAPPORT NUMBER AND SCHOOLJAAR NUMBER

                if ($c==0){
                  if ($rapnummer == "1" ){
                    $colum_rapport ="B";
                  }
                  else if ($rapnummer == "2" ){
                    $colum_rapport ="C";
                  }
                  else{
                    $colum_rapport ="D";
                  }
                }

                if ($c==1){
                  if ($rapnummer == "1" ){
                    $colum_rapport ="F";
                  }
                  else if ($rapnummer == "2" ){
                    $colum_rapport ="G";
                  }
                  else{
                    $colum_rapport ="H";
                  }
                }
                if ( $c==2 ){
                  if ($rapnummer == "1" ){
                    $colum_rapport = "J";
                  }
                  else if ($rapnummer == "2" ){
                    $colum_rapport ="K";
                  }
                  else{
                    $colum_rapport ="L";
                  }

                }
                if ( $c==3 ){
                  if ($rapnummer == "1" ){
                    $colum_rapport ="N";
                  }
                  else if ($rapnummer == "2" ){
                    $colum_rapport ="O";
                  }
                  else{
                    $colum_rapport ="P";
                  }

                }
                if ( $c==4 ){
                  if ($rapnummer == "1" ){
                    $colum_rapport ="R";
                  }
                  else if ($rapnummer == "2" ){
                    $colum_rapport ="S";
                  }
                  else{
                    $colum_rapport ="T";
                  }

                }
                if ( $c==5 ){

                  if ($rapnummer == "1" ){
                    $colum_rapport ="V";
                  }
                  else if ($rapnummer == "2" ){
                    $colum_rapport ="W";
                  }
                  else{
                    $colum_rapport ="X";
                  }

                }
                if ( $c==6 ){

                  if ($rapnummer == "1" ){
                    $colum_rapport ="Z";
                  }
                  else if ($rapnummer == "2" ){
                    $colum_rapport ="AA";
                  }
                  else{
                    $colum_rapport ="AB";
                  }

                }

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($_default_scooljaar,$schooljaar);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($_default_klas, $klas );
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($_default_docent, "DOCENT" );
                if ($y_index != null){
                  // print($colum_rapport);
                  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_rapport.(string)$y_index, $gemiddelde);
                }

                $counter_while++;

              }

            }
          }
        }
      }
      catch (Exception $e){
        $error = $e->getMessage();
        echo $error;
      }
    }
    else{

      $rapport_query = "SELECT distinct concat(s.firstname, ' ',  s.lastname) as studentname, c.gemiddelde,
      v.volledigenaamvak, v.y_index, c.rapnummer, c.klas, c.schooljaar
      FROM
      le_cijfers c
      inner join
      le_vakken v on
      c.vak = v.id
      inner join
      students s on
      s.id = c.studentid
      WHERE c.studentid = $id_student and schooljaar = '$schooljaar_rapport' order by c.schooljaar, c.rapnummer, v.y_index asc;";

      $_default_scooljaar = "B3";
      $_default_klas = "B4";
      $_default_docent = "B5";

      try{
        $DBCreds = new DBCreds();
        $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
        // $mysqli->set_charset('utf8');
        if($select=$mysqli->prepare($rapport_query)) {
          if($select->execute())
          {
            $select->store_result();
            $this->error = false;
            if($select->num_rows > 0)
            {
              $result=1;
              $select->bind_result($studentName, $gemiddelde, $volledigenaamvak, $y_index,$rapnummer, $klas, $schooljaar);

              $objPHPExcel->setActiveSheetIndex(0)->setCellValue($_default_scooljaar,$schooljaar_rapport);
              $objPHPExcel->setActiveSheetIndex(0)->setCellValue($_default_docent, "DOCENT" );


              $c = 0;

              while($select->fetch())
              {
                if ($c==0){

                  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($_default_klas, $klas );
                  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', $studentName);

                }

                if ($rapnummer == "1" ){
                  $colum_rapport ="B";
                }
                else if ($rapnummer == "2" ){
                  $colum_rapport ="C";
                }
                else{
                  $colum_rapport ="D";
                }

                if ($y_index != null){
                  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_rapport.(string)$y_index, $gemiddelde);
                }


                $c++;
              }
              $this->write_verzuim_data($objPHPExcel,$id_student, $schooljaar_rapport, "B");
              $this->write_houding_data($objPHPExcel,$id_student, $schooljaar_rapport, "",$klas);
              // $this->write_avi_data($id_student,$schooljaar_rapport, "");
            }
          }
        }
      }
      catch (Exception $e){
        $error = $e->getMessage();
        echo $error;
      }
    }
  }
  function write_verzuim_data($objPHPExcel,$id_student, $schooljaar, $colum_schooljaar){
    require_once("DBCreds.php");

    $verzuim_query = "SELECT SUM(telaat) AS telaat, SUM(absentie) AS absentie, SUM(LP) as lp, SUM(toetsinhalen) as toetsinhalen,
    SUM(uitsturen) AS uitsturen, SUM(huiswerk) AS huiswerk
    FROM le_verzuim where studentid = $id_student and schooljaar = '$schooljaar';";

    try{
      $DBCreds = new DBCreds();
      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      $mysqli->set_charset('utf8');
      if($select=$mysqli->prepare($verzuim_query)) {
        if($select->execute())
        {
          $select->store_result();
          $this->error = false;
          if($select->num_rows > 0)
          {
            $result=1;
            $select->bind_result($telaat, $absentie, $lp,$toetsinhalen, $uitsturen, $huiswerk);

            while($select->fetch())
            {
              $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_schooljaar."64",$huiswerk);
              $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_schooljaar."65", $telaat );
              $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_schooljaar."66", $absentie );
            }
          }
        }

      }
    }
    catch (Exception $e){
      $error = $e->getMessage();
      echo $error;
    }

  }
  function write_houding_data($objPHPExcel,$id_student, $schooljaar, $colum_schooljaar, $klas_in){

    $houding_query = "SELECT id,
    schooljaar,	rapnummer,klas, h1, h2, h3, h4,	h5, h6, h7,	h8,	h9, h10, h11, h12, h13, h14,
    h15, h16, h17, h18, h19, h20, h21, h22,	h23, h24, h25 FROM le_houding WHERE studentid = $id_student and schooljaar = '$schooljaar' order by schooljaar, rapnummer asc ;";

    try
    {
      $DBCreds = new DBCreds();
      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      $mysqli->set_charset('utf8');
      if($select=$mysqli->prepare($houding_query)) {

        if($select->execute())
        {
          $select->store_result();
          $this->error = false;
          if($select->num_rows > 0)
          {
            $result=1;
            $select->bind_result($studentid, $schooljaar, $rapnummer, $klas_out, $h1, $h2, $h3, $h4, $h5, $h6, $h7, $h8,
            $h9, $h10, $h11, $h12, $h13, $h14,$h15, $h16, $h17, $h18, $h19, $h20, $h21, $h22, $h23, $h24, $h25);
            $c = 0;

            $counter_while = 0;
            $current_schooljaar = "";
            $last_schooljaar ="";

            $level_klas= substr($klas_in,0,1);

            // print($klas_in);

            if ($level_klas <= 5){
              $Ingles = "35";
              $Spano = "36";
              $Actitud_positivo= "42";
              $Mantene_palabracion= "47";
              $Disponilidad_pa_yuda= "48";
              $Comunicacion_social= "51";
              $Cortesia= "52";
              $Participacion_activo="55";
              $Confiansa_propio= "56";
              $Independencia= "58";
              $Responsabilidad= "59";
              $Perseverancia= "60";
              $Liheresa= "62";
              $Concentracion= "63";
              $Inzicht_Rek= "25";
              $Precision= "57";
              $Scucha_y_Papia= "21";
              $Papia_y_Combersa = "70";
            }
            else{
              $Leerhouding = "54";
              $Sociaal_gedrag = "51";
              $Concentratie = "63";
              $Werktempo = "62";
              $Nauwkeurigheid = "57";
              $Zelfvertrouwen = "56";
              $Doorzetting_svermogen = "60";
              $Zelfstandigheid = "56";
              $Verzorging = "70";
              $Inzicht_Rekenen = "25";
            }

            // print($level_klas);

            while($select->fetch())
            {

              if ($h1==10 || !isset($h1)){$_h1="B";}if ($h1==11){$_h1="S";}if ($h1==12){$_h1="I";}
              if ($h2==10 || !isset($h2)){$_h2="B";}if ($h2==11){$_h2="S";}if ($h2==12){$_h2="I";}
              if ($h3==10 || !isset($h3)){$_h3="B";}if ($h3==11){$_h3="S";}if ($h3==12){$_h3="I";}
              if ($h4==10 || !isset($h4)){$_h4="B";}if ($h4==11){$_h4="S";}if ($h4==12){$_h4="I";}
              if ($h5==10 || !isset($h5)){$_h5="B";}if ($h5==11){$_h5="S";}if ($h5==12){$_h5="I";}
              if ($h6==10 || !isset($h6)){$_h6="B";}if ($h6==11){$_h6="S";}if ($h6==12){$_h6="I";}
              if ($h7==10 || !isset($h7)){$_h7="B";}if ($h7==11){$_h7="S";}if ($h7==12){$_h7="I";}
              if ($h8==10 || !isset($h8)){$_h8="B";}if ($h8==11){$_h8="S";}if ($h8==12){$_h8="I";}
              if ($h9==10 || !isset($h9)){$_h9="B";}if ($h9==11){$_h9="S";}if ($h9==12){$_h9="I";}
              if ($h10==10 || !isset($h10)){$_h10="B";}if ($h10==11){$_h10="S";}if ($h10==12){$_h10="I";}
              if ($h11==10 || !isset($h11)){$_h11="B";}if ($h11==11){$_h11="S";}if ($h11==12){$_h11="I";}
              if ($h12==10 || !isset($h12)){$_h12="B";}if ($h12==11){$_h12="S";}if ($h12==12){$_h12="I";}
              if ($h13==10 || !isset($h13)){$_h13="B";}if ($h13==11){$_h13="S";}if ($h13==12){$_h13="I";}
              if ($h14==10 || !isset($h14)){$_h14="B";}if ($h14==11){$_h14="S";}if ($h14==12){$_h14="I";}
              if ($h15==10 || !isset($h15)){$_h15="B";}if ($h15==11){$_h15="S";}if ($h15==12){$_h15="I";}
              if ($h16==10 || !isset($h16)){$_h16="B";}if ($h16==11){$_h16="S";}if ($h16==12){$_h16="I";}
              if ($h17==10 || !isset($h17)){$_h17="B";}if ($h17==11){$_h17="S";}if ($h17==12){$_h17="I";}
              if ($h18==10 || !isset($h18)){$_h18="B";}if ($h18==11){$_h18="S";}if ($h18==12){$_h18="I";}
              if ($h19==10 || !isset($h19)){$_h19="B";}if ($h19==11){$_h19="S";}if ($h19==12){$_h19="I";}
              if ($h20==10 || !isset($h20)){$_h20="B";}if ($h20==11){$_h20="S";}if ($h20==12){$_h20="I";}
              if ($h21==10 || !isset($h21)){$_h21="B";}if ($h21==11){$_h21="S";}if ($h21==12){$_h21="I";}
              if ($h22==10 || !isset($h22)){$_h22="B";}if ($h22==11){$_h22="S";}if ($h22=12){$_h22="I";}
              if ($h23==10 || !isset($h23)){$_h23="B";}if ($h23==11){$_h23="S";}if ($h23==12){$_h23="I";}
              if ($h24==10 || !isset($h24)){$_h24="B";}if ($h24==11){$_h24="S";}if ($h24==12){$_h24="I";}
              if ($h25==10 || !isset($h25)){$_h25="B";}if ($h25==11){$_h25="S";}if ($h25==12){$_h25="I";}

              if ($colum_schooljaar != "" ){

                if ($colum_schooljaar=="B"){
                  $current_schooljaar = $schooljaar;

                  if ($rapnummer == "1" ){
                    $colum_rapport ="B";
                  }
                  else if ($rapnummer == "2" ){
                    $colum_rapport ="C";
                  }
                  else{
                    $colum_rapport ="D";
                  }
                }
                if ($colum_schooljaar=="F"){
                  $current_schooljaar = $schooljaar;

                  if ($rapnummer == "1" ){
                    $colum_rapport ="F";
                  }
                  else if ($rapnummer == "2" ){
                    $colum_rapport ="G";
                  }
                  else{
                    $colum_rapport ="H";
                  }
                }
                if ($colum_schooljaar=="J"){
                  $current_schooljaar = $schooljaar;

                  if ($rapnummer == "1" ){
                    $colum_rapport ="J";
                  }
                  else if ($rapnummer == "2" ){
                    $colum_rapport ="K";
                  }
                  else{
                    $colum_rapport ="L";
                  }
                }
                if ($colum_schooljaar=="N"){
                  $current_schooljaar = $schooljaar;

                  if ($rapnummer == "1" ){
                    $colum_rapport ="N";
                  }
                  else if ($rapnummer == "2" ){
                    $colum_rapport ="O";
                  }
                  else{
                    $colum_rapport ="P";
                  }
                }
                if ($colum_schooljaar=="R"){
                  $current_schooljaar = $schooljaar;

                  if ($rapnummer == "1" ){
                    $colum_rapport ="R";
                  }
                  else if ($rapnummer == "2" ){
                    $colum_rapport ="S";
                  }
                  else{
                    $colum_rapport ="T";
                  }
                }
                if ($colum_schooljaar=="V"){
                  $current_schooljaar = $schooljaar;

                  if ($rapnummer == "1" ){
                    $colum_rapport ="V";
                  }
                  else if ($rapnummer == "2" ){
                    $colum_rapport ="W";
                  }
                  else{
                    $colum_rapport ="X";
                  }
                }
                if ($colum_schooljaar=="Z"){
                  $current_schooljaar = $schooljaar;

                  if ($rapnummer == "1" ){
                    $colum_rapport ="Z";
                  }
                  else if ($rapnummer == "2" ){
                    $colum_rapport ="AA";
                  }
                  else{
                    $colum_rapport ="AB";
                  }
                }

              }
              else{

                if ($rapnummer == "1" ){
                  $colum_rapport ="B";
                }
                else if ($rapnummer == "2" ){
                  $colum_rapport ="C";
                }
                else{
                  $colum_rapport ="D";
                }

              }

              if ($level_klas <= 5){

                // print(' | Este es _H1: '.$_h1);
                // print(' | Este es colum rapport: '.$colum_rapport.(string)$Ingles);
                // print($colum_schooljaar);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_rapport.(string)$Ingles,$_h1);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_rapport.(string)$Spano,$_h2);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_rapport.(string)$Actitud_positivo,$_h3);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_rapport.(string)$Mantene_palabracion,$_h4);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_rapport.(string)$Disponilidad_pa_yuda,$_h5);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_rapport.(string)$Comunicacion_social,$_h6);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_rapport.(string)$Cortesia,$_h7);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_rapport.(string)$Participacion_activo,$_h8);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_rapport.(string)$Confiansa_propio,$_h9);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_rapport.(string)$Independencia,$_h10);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_rapport.(string)$Responsabilidad,$_h11);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_rapport.(string)$Perseverancia,$_h12);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_rapport.(string)$Liheresa,$_h13);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_rapport.(string)$Concentracion,$_h14);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_rapport.(string)$Inzicht_Rek,$_h15);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_rapport.(string)$Precision,$_h16);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_rapport.(string)$Scucha_y_Papia,$_h17);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_rapport.(string)$Papia_y_Combersa,$_h18);

              }
              else{
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_rapport.(string)$Leerhouding,$_h1);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_rapport.(string)$Sociaal_gedrag,$_h2);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_rapport.(string)$Concentratie,$_h3);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_rapport.(string)$Werktempo,$_h4);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_rapport.(string)$Nauwkeurigheid,$_h5);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_rapport.(string)$Zelfvertrouwen,$_h6);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_rapport.(string)$Doorzetting_svermogen,$_h7);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_rapport.(string)$Zelfstandigheid,$_h8);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_rapport.(string)$Verzorging,$_h9);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum_rapport.(string)$Inzicht_Rekenen,$_h10);
              }
            }
          }
        }
      }
    }
    catch (Exception $e){
      $error = $e->getMessage();
      echo $error;
    }

  }



}
