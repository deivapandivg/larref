if($_POST['hs_examination_passed']!="") {

      $hsArr=array();
      $hs_examination_passed=$_POST['hs_examination_passed'];
      $hs_board=$_POST['hs_board'];
      $hs_year=$_POST['hs_year'];
      $hs_marks=$_POST['hs_marks'];
      $hs_percentage=$_POST['hs_percentage'];
      $hsArr[]=array("hs_examination_passed"=>$hs_examination_passed, "hs_board"=>$hs_board, "hs_year"=>$hs_year, "hs_marks"=>$hs_marks, "hs_percentage"=>$hs_percentage);
      $hs=json_encode($hsArr);

    }
    if($_POST['examination_passed']!="") {

      $ItiArr=array();
      $examination_passed=$_POST['examination_passed'];
      $board=$_POST['board'];
      $year=$_POST['year'];
      $marks=$_POST['marks'];
      $percentage=$_POST['percentage'];
      $ItiArr[]=array("examination_passed"=>$examination_passed, "board"=>$board, "year"=>$year, "marks"=>$marks, "percentage"=>$percentage);
      $iti=json_encode($ItiArr);

    }
    if($_POST['ug_examination_passed']!="") {

      $ugArr=array();
      $ug_examination_passed=$_POST['ug_examination_passed'];
      $ug_board=$_POST['ug_board'];
      $ug_year=$_POST['ug_year'];
      $ug_marks=$_POST['ug_marks'];
      $ug_percentage=$_POST['ug_percentage'];
      $ugArr[]=array("g_examination_passed"=>$g_examination_passed, "ug_board"=>$ug_board, "ug_year"=>$ug_year, "ug_marks"=>$ug_marks, "ug_percentage"=>$ug_percentage);
      $ug=json_encode($ugArr);

    }
    if($_POST['pg_examination_passed']!="") {

      $pgArr=array();
      $pg_examination_passed=$_POST['pg_examination_passed'];
      $pg_board=$_POST['pg_board'];
      $pg_year=$_POST['pg_year'];
      $pg_marks=$_POST['pg_marks'];
      $pg_percentage=$_POST['pg_percentage'];
      $pgArr[]=array("pg_examination_passed"=>$pg_examination_passed, "pg_board"=>$pg_board, "pg_year"=>$pg_year, "pg_marks"=>$pg_marks, "pg_percentage"=>$pg_percentage);
      $pg=json_encode($pgArr);

    }
    if($_POST['mphil_examination_passed']!="") {
      
      $mphilArr=array();
      $mphil_examination_passed=$_POST['mphil_examination_passed'];
      $mphil_board=$_POST['mphil_board'];
      $mphil_year=$_POST['mphil_year'];
      $mphil_marks=$_POST['mphil_marks'];
      $mphil_percentage=$_POST['mphil_percentage'];
      $mphilArr[]=array("mphil_examination_passed"=>$mphil_examination_passed, "mphil_board"=>$mphil_board, "mphil_year"=>$mphil_year, "mphil_marks"=>$mphil_marks, "mphil_percentage"=>$mphil_percentage);
      $mphil=json_encode($mphilArr);

    }