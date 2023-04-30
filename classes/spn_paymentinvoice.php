<?php


class spn_paymentinvoice
{
  public $tablename_invoice = "invoice";
  public $tablename_payment = "payment";
  public $exceptionvalue = "";
  public $mysqlierror = "";
  public $mysqlierrornumber = "";
  public $debug = true;
  public $error = "";
  public $errormessage = "";
  public $sp_create_invoice_student = "sp_create_invoice_student";
  public $sp_create_invoice_by_class = "sp_create_invoice_by_class";
  public $sp_create_invoice_by_school = "sp_create_invoice_by_school";
  public $sp_read_school = "sp_read_school";
  public $sp_create_payment = "sp_create_payment";
  public $sp_read_listpaymentinvoice = "sp_read_listpaymentinvoice";
  public $sp_get_invoice_by_student = "sp_get_invoice_by_student";


  function createinvoice($invoicenumber,$invoiceammount,$invoicedate,$invoiceduedate,$invoicememo,$studentid,$invoicestatus,$schoolyear,$dummy)
  {

    $result = 0;
    if ($dummy)
    $result = 1;
    else
    {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $status = 1;
      $sp_create_invoice_student = "sp_create_invoice_student";
      mysqli_report(MYSQLI_REPORT_STRICT);
      try
      {
        $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

          if ($select =$mysqli->prepare("CALL " . $this->$sp_create_invoice_student . " (?,?,?,?,?,?,?,?)"))
          {
            if ($select->bind_param("sdsssiss",$invoicenumber,$invoiceammount,$invoicedate,$invoiceduedate,$invoicememo,$studentid,$invoicestatus,$schoolyear))

          {
            if($select->execute())
            {
              /* Need to check for errors on database side for primary key errors etc.*/
              $result = 1;
              $select->close();
              $mysqli->close();
              //Audit by Caribe Developers
              require_once ("spn_audit.php");
              $spn_audit = new spn_audit();
              $spn_audit->create_audit($_SESSION['UserGUID'], 'Invoice','Create Invoice by student',false);
            }
            else
            {
              $result = 0;
              $this->mysqlierror = $mysqli->error;
              $this->mysqlierrornumber = $mysqli->errno;
            }
          }
          else
          {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        }
        else
        {
          $result = 0;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
        }
      }
      catch(Exception $e)
      {
        $result = -2;
        $this->exceptionvalue = $e->getMessage();
      }
      return $result;
    }

  }

  function createinvoicebyschool($invoicenumber,$invoiceammount,$invoicedate,$invoiceduedate,$invoicememo,$schoolid,$invoicestatus,$schoolyear,$dummy)
  {
    $result = 0;
    if ($dummy)
    $result=1;
    else
    {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $status=1;
      $sp_create_invoice_by_school = "sp_create_invoice_by_school";
      mysqli_report(MYSQLI_REPORT_STRICT);
      try
      {
        $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

        if ($select =$mysqli->prepare("CALL " . $this->sp_create_invoice_by_school . " (?,?,?,?,?,?,?,?)"))
        {
          if ($select->bind_param("sdsssiss",$invoicenumber,$invoiceammount,$invoicedate,$invoiceduedate,$invoicememo,$schoolid,$invoicestatus,$schoolyear))
          {
            if($select->execute())
            {
              /* Need to check for errors on database side for primary key errors etc.*/
              $result=1;
              $select->close();
              $mysqli->close();
              //Audit by Caribe Developers
              require_once ("spn_audit.php");
              $spn_audit = new spn_audit();
              $spn_audit->create_audit($_SESSION['UserGUID'], 'Invoice','Create invoice by school',false);
            }
            else
            {
              $result=0;
              $this->mysqlierror = $mysqli->error;
              $this->mysqlierrornumber = $mysqli->errno;
            }
          }
          else
          {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        }
        else
        {
          $result =0;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
        }
      }
      catch(Exception $e)
      {
        $result = -2;
        $this->exceptionvalue = $e->getMessage();
      }
      return $result;

    }
  }

  function createinvoicebyclass($invoicenumber,$invoiceammount,$invoicedate,$invoiceduedate,$invoicememo,$class,$schoolid,$invoicestatus,$schoolyear,$dummy)
  {
    $result = 0;
    if ($dummy)
    $result =1;
    else
    {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $status =1;
      $sp_create_invoice_by_class = "sp_create_invoice_by_class";
      mysqli_report(MYSQLI_REPORT_STRICT);
      try
      {
        $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

        if ($select =$mysqli->prepare("CALL " . $this->$sp_create_invoice_by_class . " (?,?,?,?,?,?,?,?,?)"))
        {
          if ($select->bind_param("sdssssiss",$invoicenumber,$invoiceammount,$invoicedate,$invoiceduedate,$invoicememo,$class,$schoolid,$invoicestatus,$schoolyear))
          {
            if($select->execute())
            {
              /* Need to check for errors on database side for primary key errors etc.*/
              $result =1;
              $select->close();
              $mysqli->close();
              //Audit by Caribe Developers
              require_once ("spn_audit.php");
              $spn_audit = new spn_audit();
              $spn_audit->create_audit($_SESSION['UserGUID'], 'Invoice','Create invoice by class',false);
            }
            else
            {
              $result = 0;
              $this->mysqlierror = $mysqli->error;
              $this->mysqlierrornumber = $mysqli->errno;
            }
          }
          else
          {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        }
        else
        {
          $result = 0;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
        }
      }
      catch(Exception $e)
      {
        $result = -2;
        $this->exceptionvalue = $e->getMessage();
      }
      return $result;

    }
  }

  function createpayment($paymentnumber,$paymentammount,$paymentdate,$paymentmemo,$id_invoice,$paymentstatus,$schoolyear,$dummy)

  {

    $result = 0;
    if ($dummy)
    $result = 1;
    else
    {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $status = 1;
      $sp_create_payment = "sp_create_payment";
      mysqli_report(MYSQLI_REPORT_STRICT);
      try
      {
        $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

              if ($select =$mysqli->prepare("CALL " . $this->$sp_create_payment . " (?,?,?,?,?,?,?)"))
              {
              if ($select->bind_param("sdssiss",$paymentnumber,$paymentammount,$paymentdate,$paymentmemo,$id_invoice,$paymentstatus,$schoolyear))

          {
            if($select->execute())
            {
              /* Need to check for errors on database side for primary key errors etc.*/
              $result = 1;
              $select->close();
              $mysqli->close();
              //Audit by Caribe Developers
              require_once ("spn_audit.php");
              $spn_audit = new spn_audit();
              $spn_audit->create_audit($_SESSION['UserGUID'], 'Invoice','Create Payment',false);
            }
            else
            {
              $result = 0;
              $this->mysqlierror = $mysqli->error;
              $this->mysqlierrornumber = $mysqli->errno;
            }
          }
          else
          {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        }
        else
        {
          $result = 0;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
        }
      }
      catch(Exception $e)
      {
        $result = -2;
        $this->exceptionvalue = $e->getMessage();
      }
      return $result;
    }
  }

  function listpaymentinvoice($baseurl,$detailpage, $id_school_)
  {
    $returnvalue = "";
    $user_permission="";
    $sql_query = "";
    $htmlcontrol="";


    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    $status = 1;
    $sp_read_listpaymentinvoice = "sp_read_listpaymentinvoice";
    mysqli_report(MYSQLI_REPORT_STRICT);
    try
    {
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if ($select =$mysqli->prepare("CALL " . $this->sp_read_listpaymentinvoice . "(?,?)"))
      {

        if ($select->bind_param("ii",$id_payment, $id_school_))

        {
          if($select->execute())
          {
            $this->error = false;
            $result = 1;

            $select->bind_result($id, $type, $number, $ammount,$date,$due_date,$memo, $open_balance, $status,$schooljar);
            $select->store_result();
            //Audit by Caribe Developers
            require_once ("spn_audit.php");
            $spn_audit = new spn_audit();
            $spn_audit->create_audit($_SESSION['UserGUID'], 'Invoice','List Payment Invoice',false);

            if($select->num_rows > 0)
            {
              $htmlcontrol .= "<table id=\"dataRequest-invoice\" class=\"table table-bordered table-colored\" data-table=\"yes\">";

            $htmlcontrol .= "<thead><tr><th>Type</th><th>Nummer</th><th>Bedrag</th><th>Datum</th><th>Vervaldatum</th><th>Memo</th></th><th>Saldo</th><th>Status</th><th>School Year</th></tr></thead>";
            $htmlcontrol .= "<tbody>";

              while($select->fetch())
              {
                $date_format = date_format(date_create($date), 'd/m/Y');
                $due_date_format = date_format(date_create($due_date), 'd/m/Y');

              $htmlcontrol .= "<tr><td>". htmlentities($type) ."</td><td>". htmlentities($number) ."</td><td>". htmlentities($ammount) ."</td><td>". htmlentities($date_format) ."</td><td>". htmlentities($due_date_format) ."</td><td>". htmlentities($memo) ."</td><td>". htmlentities($open_balance) ."</td><td>". htmlentities($status) ."</td><td>". htmlentities($schooljar) ."</td></tr>";
            }

              $htmlcontrol .= "</tbody>";
              $htmlcontrol .= "</table>";

              $htmlcontrol .= $this->getfinancialbalance($baseurl,$detailpage);

            }
            else
            {
              $htmlcontrol .= "No results to show";
            }

          }
          else
          {
            /* error executing query */
            $this->error = true;
            $this->errormessage = $mysqli->error;
            $result=0;

            if($this->debug)
            {
              print "error executing query" . "<br />";
              print "error" . $mysqli->error;
            }
          }
        }
        else
        {
          /* error preparing query */
          $this->error = true;
          $this->errormessage = $mysqli->error;
          $result=0;

          print "error del sql: ". $this->errormessage;

          if($this->debug)
          {
            print "error preparing query";
          }
        }
        // Cierre del prepare

        $returnvalue = $htmlcontrol;
        return $returnvalue;

      }
    }
    catch(Exception $e)
    {
      $this->error = true;
      $this->errormessage = $e->getMessage();
      $result=0;

      if($this->debug)
      {
        print "exception: " . $e->getMessage();
      }
    }

    return $returnvalue;
  }

  function listpaymentinvoicebystudent($studentid,$baseurl,$detailpage)
  {
    $returnvalue = "";
    $user_permission="";
    $sql_query = "";
    $htmlcontrol="";

    mysqli_report(MYSQLI_REPORT_STRICT);

    $sql_query = "select p.id_payment as id, 'Payment', p.number, p.ammount*-1, p.date, null, p.memo, 'N/A' as 'Open Balance (awg)', p.status from payment p inner join invoice i on p.id_invoice = i.id_invoice where i.id_student = ? union select i.id_invoice as id, 'Invoce', i.number, i.ammount, i.date, i.due_date, i.memo, case when (select SUM(pay.ammount) from payment pay inner join invoice inv on pay.id_invoice = inv.id_invoice where inv.id_student = ?) IS NOT NULL THEN  (i.ammount -(select SUM(pay.ammount) from payment pay inner join invoice inv on pay.id_invoice = inv.id_invoice where inv.id_student = ?)) ELSE i.ammount END AS 'Open Balance (awg)', i.status from invoice i where i.id_student = ? order by 5;";
    try
    {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();

      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if($select=$mysqli->prepare($sql_query))
      {
        if($select->bind_param("iiii",$studentid,$studentid,$studentid,$studentid))
        {
          if($select->execute())
          {
            $this->error = false;
            $result = 1;

            $select->bind_result($id, $type, $number, $ammount,$date,$due_date,$memo, $open_balance, $status);
            $select->store_result();
            //Audit by Caribe Developers
            require_once ("spn_audit.php");
            $spn_audit = new spn_audit();
            $spn_audit->create_audit($_SESSION['UserGUID'], 'Invoice','List Payment and invoice by students',false);

            if($select->num_rows > 0)
            {
              $htmlcontrol .= "<table id=\"dataRequest-invoice\" class=\"table table-bordered table-colored\" data-table=\"yes\">";

              $htmlcontrol .= "<thead><tr><th>Type</th><th>Number</th><th>Ammount</th><th>Date</th><th>Due Date</th><th>Memo</th></th><th>Balance</th><th>Status</th></tr></thead>";
              $htmlcontrol .= "<tbody>";

              while($select->fetch())
              {
                $date_format = date_format(date_create($date), 'd/m/Y');
                $due_date_format = date_format(date_create($due_date), 'd/m/Y');

                $htmlcontrol .= "<tr><td>". htmlentities($type) ."</td><td>". htmlentities($number) ."</td><td>". htmlentities($ammount) ."</td><td>". htmlentities($date_format) ."</td><td>". htmlentities($due_date_format) ."</td><td>". htmlentities($memo) ."</td><td>". htmlentities($open_balance) ."</td><td>". htmlentities($status) ."</td></tr>";
              }

              $htmlcontrol .= "</tbody>";
              $htmlcontrol .= "</table>";

              $htmlcontrol .= $this->getbalancestudent($studentid,$baseurl,$detailpage);

            }
            else
            {
              $htmlcontrol .= "No results to show";
            }

          }
          else
          {
            /* error executing query */
            $this->error = true;
            $this->errormessage = $mysqli->error;
            $result=0;

            if($this->debug)
            {
              print "error executing query" . "<br />";
              print "error" . $mysqli->error;
            }
          }
        }
        else
        {
          $result = 0;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
        }
      }
      else
      {
        /* error preparing query */
        $this->error = true;
        $this->errormessage = $mysqli->error;
        $result=0;

        print "error del sql: ". $this->errormessage;

        if($this->debug)
        {
          print "error preparing query";
        }
      }
      // Cierre del prepare

      $returnvalue = $htmlcontrol;
      return $returnvalue;

    }
    catch(Exception $e)
    {
      $this->error = true;
      $this->errormessage = $e->getMessage();
      $result=0;

      if($this->debug)
      {
        print "exception: " . $e->getMessage();
      }
    }

    return $returnvalue;
  }

  function getfinancialbalance($baseurl,$detailpage)
  {
    $sql_query = "";
    $htmlcontrol="";
    $result = 0;

    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;

    mysqli_report(MYSQLI_REPORT_STRICT);


    $sql_query = "select sum(i.ammount) as Total from invoice i  union select (sum(p.ammount)*-1) as Total from payment p inner join invoice i on p.id_invoice = i.id_invoice";
    try
    {
      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if($select=$mysqli->prepare($sql_query))
      {
        if($select->execute())
        {
          $this->error = false;
          $result = 1;

          $select->bind_result($totalrow);
          $select->store_result();
          //Audit by Caribe Developers
          require_once ("spn_audit.php");
          $spn_audit = new spn_audit();
          $spn_audit->create_audit($_SESSION['UserGUID'], 'Invoice','Get financial Balance',false);

          if($select->num_rows > 0)
          {

            $htmlcontrol .= "<table id=\"dataRequest-invoice-balance\" class=\"table table-bordered table-colored\" data-table=\"yes\">";

            $htmlcontrol .= "<thead></thead>";
            $htmlcontrol .= "<tbody>";

            $total = 0;

            $select->fetch();
            $total = $total + $totalrow;
            $htmlcontrol .= "<tr><td>Totale inachtneming: </td><td style=\"text-align: right;\">". htmlentities(( !is_null($totalrow) ? $totalrow : 0 )) ."</td></tr>";

            $select->fetch();
            $total = $total + $totalrow;
            $htmlcontrol .= "<tr><td>Totaal betaald: </td><td style=\"text-align: right;\">". htmlentities(( !is_null($totalrow) ? $totalrow : 0 )) ."</td></tr>";

            $htmlcontrol .= "<tr><td><strong>Open saldo:</strong> </td><td style=\"text-align: right;\"><strong>". htmlentities($total) ."</strong></td></tr>";

            $htmlcontrol .= "</tbody>";
            $htmlcontrol .= "</table>";
          }
          else
          {
            $htmlcontrol .= "No results to show";
          }

        }
        else
        {
          /* error executing query */
          $this->error = true;
          $this->errormessage = $mysqli->error;
          $result=0;

          if($this->debug)
          {
            print "error executing query" . "<br />";
            print "error" . $mysqli->error;
          }
        }

      }
    }
    catch(Exception $e)
    {
      $result = -2;
      $this->exceptionvalue = $e->getMessage();
    }

    return $htmlcontrol;;
  }

  function getfinancialbalancebystudent($studentid,$baseurl,$detailpage)
  {
    $sql_query = "";
    $htmlcontrol="";
    $result = 0;

    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;

    mysqli_report(MYSQLI_REPORT_STRICT);


    $sql_query = "select sum(i.ammount) as Total from invoice i where id_student = ?  union select (sum(p.ammount)*-1) as Total from payment p inner join invoice i on p.id_invoice = i.id_invoice where i.id_student = ?";
    try
    {
      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if($select=$mysqli->prepare($sql_query))
      {
        if($select->bind_param("ii",$studentid,$studentid))
        {

          if($select->execute())
          {
            $this->error = false;
            $result = 1;

            $select->bind_result($totalrow);
            $select->store_result();
            //Audit by Caribe Developers
            require_once ("spn_audit.php");
            $spn_audit = new spn_audit();
            $spn_audit->create_audit($_SESSION['UserGUID'], 'Invoice','Get financial balance by student',false);

            if($select->num_rows > 0)
            {

              $htmlcontrol .= "<table id=\"dataRequest-invoice-balance\" class=\"table table-bordered table-colored\" data-table=\"yes\">";

              $htmlcontrol .= "<thead></thead>";
              $htmlcontrol .= "<tbody>";

              $total = 0;

              $select->fetch();
              $total = $total + $totalrow;
              $htmlcontrol .= "<tr><td>Total due: </td><td style=\"text-align: right;\">". htmlentities(( !is_null($totalrow) ? $totalrow : 0 )) ."</td></tr>";
              $select->fetch();
              $total = $total + $totalrow;
              $htmlcontrol .= "<tr><td>Total paid: </td><td style=\"text-align: right;\">". htmlentities(( !is_null($totalrow) ? $totalrow : 0 )) ."</td></tr>";


              $htmlcontrol .= "<tr><td><strong>Open balance:</strong> </td><td style=\"text-align: right;\"><strong>". htmlentities($total) ."</strong></td></tr>";

              $htmlcontrol .= "</tbody>";
              $htmlcontrol .= "</table>";
            }
            else
            {
              $htmlcontrol .= "No results to show";
            }

          }
          else
          {
            /* error executing query */
            $this->error = true;
            $this->errormessage = $mysqli->error;
            $result=0;

            if($this->debug)
            {
              print "error executing query" . "<br />";
              print "error" . $mysqli->error;
            }
          }
        } // aqui va el else
        else
        {
          $result = 0;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
        }
      }
    }
    catch(Exception $e)
    {
      $result = -2;
      $this->exceptionvalue = $e->getMessage();
    }

    return $htmlcontrol;;
  }

  function getinvoicenopaidbystudent($studentid)
  {
    $result = 0;

    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;
    $htmlcontrol="";

    mysqli_report(MYSQLI_REPORT_STRICT);

    try
    {

      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      //$sql = "select id_invoice, CONCAT(number ,'- Balance: ', cast(ammount as char)) as invoice from ( SELECT i.id_invoice, i.number, i.ammount, i.id_student, case when i.ammount > SUM(p.ammount) then sum(p.ammount) when i.ammount = SUM(p.ammount) then i.ammount when SUM(p.ammount) is null then 0 end as Pagos from invoice i left join payment p on i.id_invoice = p.id_invoice group by i.id_invoice, i.number, i.ammount, i.id_student) p where Pagos < ammount and id_student = ?";
      $sql = "select id_invoice, CONCAT(number ,'- Balance: ', cast(ammount - pagos as char)) as invoice from (SELECT i.id_invoice,i.number, i.ammount, i.id_student, case when i.ammount > SUM(p.ammount) then sum(p.ammount) when i.ammount = SUM(p.ammount) then i.ammount when SUM(p.ammount) is null then 0 end as Pagos from invoice i left join payment p on i.id_invoice = p.id_invoice group by i.id_invoice, i.number, i.ammount, i.id_student) p where Pagos < ammount and id_student = ?";
      if($select=$mysqli->prepare($sql))
      {
        if($select->bind_param("i",$studentid))
        {
          if($select->execute())
          {

            $this->error = false;
            $result = 1;

            $select->bind_result($invoiceid, $invoicenumber);
            $select->store_result();
            //Audit by Caribe Developers
            require_once ("spn_audit.php");
            $spn_audit = new spn_audit();
            $spn_audit->create_audit($_SESSION['UserGUID'], 'Invoice','Get invoice on paid by student',false);

            if($select->num_rows > 0)
            {
              $htmlcontrol .= "<select id=\"invoice_id\" name=\"invoice_id\" class=\"form-control\">";

              while($select->fetch())

              $htmlcontrol .= "<option value=". htmlentities($invoiceid) .">". htmlentities($invoicenumber) ."</option>";

              $htmlcontrol .= "</select>";

            }
            else

            $htmlcontrol .= "No results to show";


          }
          else
          {
            /* error executing query */
            $this->error = true;
            $this->errormessage = $mysqli->error;
            $result=0;

            if($this->debug)
            {
              print "error executing query" . "<br />";
              print "error" . $mysqli->error;
            }
          }
        }
        else
        {
          $result = 0;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
        }
      }
    }
    catch(Exception $e)
    {
      $result = -2;
      $this->exceptionvalue = $e->getMessage();
    }

    //return $result;
    return $htmlcontrol;
  }

  function getinvoicenopaid($baseurl,$detailpage)
  {
    $result = 0;

    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;
    $htmlcontrol="";

    mysqli_report(MYSQLI_REPORT_STRICT);

    try
    {

      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if($select=$mysqli->prepare("select id_invoice, CONCAT(number ,'- Balance: ', cast((ammount - p.Pagos) as char)) as invoice from ( SELECT i.id_invoice, i.number, i.ammount, i.id_student, case when i.ammount > SUM(p.ammount) then sum(p.ammount) when i.ammount = SUM(p.ammount) then i.ammount when SUM(p.ammount) is null then 0 end as Pagos from invoice i left join payment p on i.id_invoice = p.id_invoice group by i.id_invoice, i.number, i.ammount, i.id_student) p where Pagos < ammount"))
      {
        if($select->execute())
        {

          $this->error = false;
          $result = 1;

          $select->bind_result($invoiceid, $invoicenumber);
          $select->store_result();
          //Audit by Caribe Developers
          require_once ("spn_audit.php");
          $spn_audit = new spn_audit();
          $spn_audit->create_audit($_SESSION['UserGUID'], 'Invoice','Get invoice NO paid',false);


          if($select->num_rows > 0)
          {
            $htmlcontrol .= "<select id=\"invoicepaymentinvoiceid\" name=\"invoicepaymentinvoiceid\" class=\"form-control\">";
            $htmlcontrol .= "<option value=>None</option>";

            while($select->fetch())

            $htmlcontrol .= "<option value=". htmlentities($invoiceid) .">". htmlentities($invoicenumber) ."</option>";

            $htmlcontrol .= "</select>";

          }
          else

          $htmlcontrol .= "We dont have invoices created";

        }
        else
        {
          /* error executing query */
          $this->error = true;
          $this->errormessage = $mysqli->error;
          $result=0;

          if($this->debug)
          {
            print "error executing query" . "<br />";
            print "error" . $mysqli->error;
          }
        }
      }
    }
    catch(Exception $e)
    {
      $result = -2;
      $this->exceptionvalue = $e->getMessage();
    }

    //return $result;
    return $htmlcontrol;
  }

  // Las funciones que se listan a continuacion deberan ser de cla clase spn_student, spn_class y spn_school

  function listschool($schoolid)
  {
    $result = 0;

    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;
    $htmlcontrol="";

    mysqli_report(MYSQLI_REPORT_STRICT);

    try
    {
      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if($select=$mysqli->prepare("select s.id, s.schoolname FROM schools s where s.id = ?"))
      {
        if($select->bind_param("i",$schoolid))
        {
          if($select->execute())
          {
            $this->error = false;
            $result = 1;

            $select->bind_result($schoolid, $schoolname);
            $select->store_result();
            //Audit by Caribe Developers
            require_once ("spn_audit.php");
            $spn_audit = new spn_audit();
            $spn_audit->create_audit($_SESSION['UserGUID'], 'Invoice','Get invoice payment by school',false);
            if($select->num_rows > 0)
            {
              $htmlcontrol .= "<select id=\"invoicepaymentschool\" name=\"invoicepaymentschool\" class=\"form-control\" disabled>";
              while($select->fetch())
              $htmlcontrol .= "<option value=". htmlentities($schoolid) .">". htmlentities($schoolname) ."</option>";
              $htmlcontrol .= "</select>";
            }
            else
            $htmlcontrol .= "No results to show";
          }
          else
          {
            /* error executing query */
            $this->error = true;
            $this->errormessage = $mysqli->error;
            $result=0;
            if($this->debug)
            {
              print "error executing query" . "<br />";
              print "error" . $mysqli->error;
            }
          }
        }
      }
    }
    catch(Exception $e)
    {
      $result = -2;
      $this->exceptionvalue = $e->getMessage();
      $result = $e->getMessage();
    }

    //return $result;
    return $htmlcontrol;
  }


  function listclass()
  {


    $result = 0;

    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;
    $htmlcontrol="";

    mysqli_report(MYSQLI_REPORT_STRICT);

    try
    {
      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if($select=$mysqli->prepare("SELECT DISTINCT s.class FROM students s WHERE s.status = 1 order by class;"))
      {
        if($select->execute())
        {
          $this->error = false;
          $result = 1;

          $select->bind_result($class);
          $select->store_result();
          //Audit by Caribe Developers
          require_once ('../document_start.php');
          require_once ("spn_audit.php");
          $spn_audit = new spn_audit();
          $spn_audit->create_audit($_SESSION['UserGUID'], 'students','list class',false);

          if($select->num_rows > 0)
          {
            $htmlcontrol .= "<select id=\"invoicepaymentclass\" name=\"invoicepaymentclass\" class=\"form-control\">";
            $htmlcontrol .= "<option value=>None</option>";

            while($select->fetch())

            $htmlcontrol .= "<option value=". htmlentities($class) .">". htmlentities($class) ."</option>";

            $htmlcontrol .= "</select>";

          }
          else

          $htmlcontrol .= "No results to show";
        }
        else
        {
          /* error executing query */
          $this->error = true;
          $this->errormessage = $mysqli->error;
          $result=0;

          if($this->debug)
          {
            print "error executing query" . "<br />";
            print "error" . $mysqli->error;
          }
        }

      }
    }
    catch(Exception $e)
    {
      $result = -2;
      $this->exceptionvalue = $e->getMessage();
    }

    //return $result;
    return $htmlcontrol;
  }

  function listclassbyschool($schoolid)
  {
    $result = 0;

    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;
    $htmlcontrol="";

    mysqli_report(MYSQLI_REPORT_STRICT);

    try
    {
      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if($select=$mysqli->prepare("SELECT DISTINCT s.class FROM students s WHERE s.status = 1 AND s.schoolid = ? order by class"))
      {
        if($select->bind_param("i",$schoolid))
        {
          if($select->execute())
          {
            $this->error = false;
            $result = 1;

            $select->bind_result($class);
            $select->store_result();
            //Audit by Caribe Developers
            require_once ('../document_start.php');
            require_once ("spn_audit.php");
            $spn_audit = new spn_audit();
            $spn_audit->create_audit($_SESSION['UserGUID'], 'students','list class by school',false);

            if($select->num_rows > 0)
            {
              $htmlcontrol .= "<select id=\"invoicepaymentclass\" name=\"invoicepaymentclass\" class=\"form-control\">";
              $htmlcontrol .= "<option value=0>None</option>";

              while($select->fetch())

              $htmlcontrol .= "<option value=". htmlentities($class) .">". htmlentities($class) ."</option>";

              $htmlcontrol .= "</select>";

            }
            else

            $htmlcontrol .= "No results to show";
          }
          else
          {
            /* error executing query */
            $this->error = true;
            $this->errormessage = $mysqli->error;
            $result=0;

            if($this->debug)
            {
              print "error executing query" . "<br />";
              print "error" . $mysqli->error;
            }
          }
        }
        else
        {
          $result = 0;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
        }
      }
    }
    catch(Exception $e)
    {
      $result = -2;
      $this->exceptionvalue = $e->getMessage();
    }

    //return $result;
    return $htmlcontrol;
  }

  function liststudent()
  {
    $result = 0;

    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;
    $htmlcontrol="";

    mysqli_report(MYSQLI_REPORT_STRICT);

    try
    {
      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if($select=$mysqli->prepare("SELECT s.id, s.studentnumber FROM students s WHERE s.status = 1"))
      {
        if($select->execute())
        {
          $this->error = false;
          $result = 1;

          $select->bind_result($studentid, $studentnumber);
          $select->store_result();
          //Audit by Caribe Developers
          require_once ("spn_audit.php");
          $spn_audit = new spn_audit();
          $spn_audit->create_audit($_SESSION['UserGUID'], 'students','Get invoice payment by studentds',false);

          if($select->num_rows > 0)
          {
            $htmlcontrol .= "<select id=\"invoicepaymentstudent\" name=\"invoicepaymentstudent\" class=\"form-control\">";
            $htmlcontrol .= "<option value=0>None</option>";

            while($select->fetch())

            $htmlcontrol .= "<option value=". htmlentities($studentid) .">". htmlentities($studentnumber) ."</option>";

            $htmlcontrol .= "</select>";

          }
          else

          $htmlcontrol .= "No results to show";
        }
        else
        {
          /* error executing query */
          $this->error = true;
          $this->errormessage = $mysqli->error;
          $result=0;

          if($this->debug)
          {
            print "error executing query" . "<br />";
            print "error" . $mysqli->error;
          }
        }
      }
    }
    catch(Exception $e)
    {
      $result = -2;
      $this->exceptionvalue = $e->getMessage();
    }

    //return $result;
    return $htmlcontrol;
  }


  function liststudentbyschool($schoolid)
  {
    $result = 0;

    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;
    $htmlcontrol="";

    mysqli_report(MYSQLI_REPORT_STRICT);

    try
    {
      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if($select=$mysqli->prepare("SELECT s.id, s.studentnumber FROM students s WHERE s.status = 1 and s.schoolid = ?"))
      {
        if($select->bind_param("i",$schoolid))
        {
          if($select->execute())
          {
            $this->error = false;
            $result = 1;

            $select->bind_result($studentid, $studentnumber);
            $select->store_result();
            //Audit by Caribe Developers
            require_once ("spn_audit.php");
            $spn_audit = new spn_audit();
            $spn_audit->create_audit($_SESSION['UserGUID'], 'student','list student by school',false);

            if($select->num_rows > 0)
            {
              $htmlcontrol .= "<select id=\"invoicepaymentstudent\" name=\"invoicepaymentstudent\" class=\"form-control\">";
              $htmlcontrol .= "<option value=0>None</option>";

              while($select->fetch())

              $htmlcontrol .= "<option value=". htmlentities($studentid) .">". htmlentities($studentnumber) ."</option>";

              $htmlcontrol .= "</select>";

            }
            else

            $htmlcontrol .= "No results to show";
          }
          else
          {
            /* error executing query */
            $this->error = true;
            $this->errormessage = $mysqli->error;
            $result=0;

            if($this->debug)
            {
              print "error executing query" . "<br />";
              print "error" . $mysqli->error;
            }
          }
        }
        else
        {
          $result = 0;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
        }
      }
    }
    catch(Exception $e)
    {
      $result = -2;
      $this->exceptionvalue = $e->getMessage();
    }

    //return $result;
    return $htmlcontrol;
  }

  function liststudentbyschoolclass($schoolid, $class)
  {
    $result = 0;

    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;
    $htmlcontrol="";

    mysqli_report(MYSQLI_REPORT_STRICT);

    try
    {
      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      $sql = "SELECT s.id, s.studentnumber FROM students s WHERE s.status = 1". ($schoolid != 0 ? " and s.schoolid = ? ":"") . ($class != 0 ? " and s.class = ?":"");

      if($select=$mysqli->prepare($sql))
      {
        if ($class != 0 && $schoolid != 0)
        {
          $param_added = $select->bind_param("is",$schoolid,$class);
        }
        elseif ($class == 0 && $schoolid != 0) {
          $param_added = $select->bind_param("i",$schoolid);
        }
        else {
          $param_added = $select->bind_param("s",$class);
        }
        if  ($param_added)
        {
          if($select->execute())
          {
            $this->error = false;
            $result = 1;

            $select->bind_result($studentid, $studentnumber);
            $select->store_result();
            //Audit by Caribe Developers
            require_once ("spn_audit.php");
            $spn_audit = new spn_audit();
            $spn_audit->create_audit($_SESSION['UserGUID'], 'student','List student by class',false);

            if($select->num_rows > 0)
            {
              $htmlcontrol .= "<select id=\"invoicepaymentstudent\" name=\"invoicepaymentstudent\" class=\"form-control\">";
              $htmlcontrol .= "<option value=0>None</option>";

              while($select->fetch())

              $htmlcontrol .= "<option value=". htmlentities($studentid) .">". htmlentities($studentnumber) ."</option>";

              $htmlcontrol .= "</select>";

            }
            else

            $htmlcontrol .= "No results to show";
          }
          else
          {
            /* error executing query */
            $this->error = true;
            $this->errormessage = $mysqli->error;
            $result=0;

            if($this->debug)
            {
              print "error executing query" . "<br />";
              print "error" . $mysqli->error;
            }
          }
        }
        else
        {
          $result = 0;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
        }
      }
    }
    catch(Exception $e)
    {
      $result = -2;
      $this->exceptionvalue = $e->getMessage();
    }

    //return $result;
    return $htmlcontrol;


  }

  function list_invoice_by_student($studentid, $dummy)
	{
     $returnvalue = "";
     $sql_query = "";
     $htmlcontrol="";

     $result = 0;
     if ($dummy)
     $result = 1;
     else
     {
       mysqli_report(MYSQLI_REPORT_STRICT);
       require_once("spn_utils.php");
       $utils = new spn_utils();
       try
       {
         require_once("DBCreds.php");
         $DBCreds = new DBCreds();
         $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort,$dummy);
         $sp_get_invoice_by_student = "sp_get_invoice_by_student";

         if($stmt=$mysqli->prepare("CALL " . $this->$sp_get_invoice_by_student . "(?)"))
         {
           if ($stmt->bind_param("i",$studentid))
           {

             if($stmt->execute())
             {

               $spn_audit = new spn_audit();
               $UserGUID = $_SESSION['UserGUID'];
               $spn_audit->create_audit($UserGUID, 'Invoice','list invoice by student',appconfig::GetDummy());
               $this->error = false;
               $result=1;
               $stmt->bind_result($number, $amount, $date, $due_date, $memo, $status);
               $stmt->store_result();

               if($stmt->num_rows > 0)

               {
                 $htmlcontrol .= "<table id=\"tbl_invoice_by_idstudent\" class=\"table table-bordered table-colored\" data-table=\"no\">";
                 $htmlcontrol .= "<thead>";
                 $htmlcontrol .= "<tr class=\"text-align-center\"> <th>Nummer</th><th>Bedrag</th><th>Datum</th><th>Vervaldatum</th><th>Memo</th><th>Status</th></tr>";
                 $htmlcontrol .= "</thead>";
                 $htmlcontrol .= "<tbody>";
                 while($stmt->fetch())
                 {
                   $htmlcontrol .= "<tr>";
                   $htmlcontrol .= "<td align=\"center\">". htmlentities($number) ."</td>";
                   $htmlcontrol .= "<td>". htmlentities($amount) ."</td>";
                   $htmlcontrol .= "<td>". htmlentities($date) ."</td>";
                   $htmlcontrol .= "<td>". htmlentities($due_date) ."</td>";
                   $htmlcontrol .= "<td>". htmlentities($memo) ."</td>";
                   $htmlcontrol .= "<td>". htmlentities($status) ."</td>";

                 }
                 $htmlcontrol .= "</tbody>";
                 $htmlcontrol .= "</table>";
               }
               else
               {
                 $htmlcontrol .= "No results to show";
               }
             }

             else
             {
               $result = 0;
               $this->mysqlierror = $mysqli->error;
               $this->mysqlierrornumber = $mysqli->errno;
             }

           }
           else
           {
             $result = 0;
             $this->mysqlierror = $mysqli->error;
             $this->mysqlierrornumber = $mysqli->errno;
           }
         }
         else
         {
           $result = 0;
           $this->mysqlierror = $mysqli->error;
           $this->mysqlierrornumber = $mysqli->errno;
         }
       }

       catch(Exception $e)
       {
         $result = -2;
         $this->exceptionvalue = $e->getMessage();
         $result = $e->getMessage();
       }
       return $htmlcontrol;
     }
   }

}

?>
