<?
class dbInterface {
  public $dbConn;

  public function __construct () {
    $this->setConnection();
  }


  public function setConnection () {
    $conn = new PDO(
      'mysql:host=localhost;dbname=u1559320_marketcrm',
      'u1559320_mcrm',
      'marketCRM123+',
    
      array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"),
    );

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $this -> dbConn = $conn;
    $this -> sendResponse();
  }


  public function getTable($tableName) {
    $queryString = "SELECT * FROM $tableName";
    $queryItem = $this -> dbConn -> prepare($queryString);
    $res;

    $queryItem -> execute();
    $res = $queryItem -> fetchAll(PDO::FETCH_ASSOC);

    return $res;
  }


  public function insertCellsInTable($table, $cells) {
    $insertQuery = "INSERT INTO $table VALUES (NULL, :value)";
    $queryItem = $this -> dbConn -> prepare($insertQuery);

    foreach ($cells as $cell) {
      $queryItem -> execute(['value' => $cell]);
    }

    return $this -> getTable($table);
  }

  public function rewriteTable ($tableName, $newData) {
    $truncateQuery = "TRUNCATE TABLE $tableName";
    $truncateItem = $this -> dbConn -> prepare($truncateQuery);
    $truncateRes = $truncateItem -> execute();

    $insertQuery = "INSERT INTO $tableName VALUES (NULL, :value)";
    $insertItem = $this -> dbConn -> prepare($insertQuery);
    $resTable;


    foreach ($newData as $cell) {
      $insertItem -> execute(['value' => $cell]);
    }

    $resTable = $this -> getTable($tableName);
    return $resTable;
  }

  public function showTables() {
    $showQuery = "SHOW TABLES";
    $showItem = $this -> dbConn -> prepare($showQuery);
    $res;

    $showItem -> execute();
    $res = $showItem -> fetchAll();
    
    return $res;
  }

  public function addSale($saleData) {
    $OLD_ID = $saleData -> OLD_ID;
    $ID = $saleData -> ID;
    $NAME = $saleData -> NAME;
    $PRICE = $saleData -> PRICE;
    $SUPPLIER_ID = $saleData -> SUPPLIER_ID;
    $CITY = $saleData -> CITY;
    $CREATE_DATE = $saleData -> CREATE_DATE;
    $STATUS_ID = $saleData -> STATUS_ID;
    $INCOME_TABLE = $saleData -> INCOME_TABLE;
    $COSTS_TABLE = $saleData -> COSTS_TABLE;
    $TRACK_NUMBER = $saleData -> TRACK_NUMBER;
    $ARRIVAL_DATE = $saleData -> ARRIVAL_DATE;
    $COMMENT = $saleData -> COMMENT;
    $TEST = $saleData -> TEST;

    if ($INCOME_TABLE == NULL) { $INCOME_TABLE = '{}'; }
    if ($COSTS_TABLE == NULL) { $COSTS_TABLE = '{}'; }

    $insertQuery = "INSERT INTO `SALES` (`ID`, `NAME`, `PRICE`, `SUPPLIER_ID`, `CITY`, `CREATE_DATE`, `STATUS_ID`, `INCOME_TABLE`, `COSTS_TABLE`, `TOTAL`, `TRACK_NUMBER`, `ARRIVAL_DATE`, `COMMENT`) VALUES ('$ID', '$NAME', '$PRICE', '$SUPPLIER_ID', '$CITY', '$CREATE_DATE', '$STATUS_ID', '$INCOME_TABLE', '$COSTS_TABLE', '$TOTAL', '$TRACK_NUMBER', '$ARRIVAL_DATE', '$COMMENT')";
    $insertItem = $this -> dbConn -> prepare($insertQuery);
    $res = $insertItem -> execute();
    return $res;
  }

  public function deleteSale ($saleID) {
    $deleteQuery = " DELETE FROM `SALES` WHERE `ID` = '$saleID' ";
    $deleteItem = $this -> dbConn -> prepare($deleteQuery);
    $res = $deleteItem -> execute();
    return $res;    
  }

  public function updateSale ($saleData) {
    $OLD_ID = $saleData -> OLD_ID;
    $ID = $saleData -> ID;
    $NAME = $saleData -> NAME;
    $PRICE = $saleData -> PRICE;
    $SUPPLIER_ID = $saleData -> SUPPLIER_ID;
    $CITY = $saleData -> CITY;
    $CREATE_DATE = $saleData -> CREATE_DATE;
    $STATUS_ID = $saleData -> STATUS_ID;
    $INCOME_TABLE = $saleData -> INCOME_TABLE;
    $COSTS_TABLE = $saleData -> COSTS_TABLE;
    $TOTAL = $saleData -> TOTAL;
    $TRACK_NUMBER = $saleData -> TRACK_NUMBER;
    $ARRIVAL_DATE = $saleData -> ARRIVAL_DATE;
    $COMMENT = $saleData -> COMMENT;

    $updateQuery = "UPDATE `SALES` SET `ID` = '$ID', `NAME` = '$NAME', `PRICE` = '$PRICE', `SUPPLIER_ID` = '$SUPPLIER_ID', `CITY` = '$CITY', `CREATE_DATE` = '$CREATE_DATE', `INCOME_TABLE` = '$INCOME_TABLE', `COSTS_TABLE` = '$COSTS_TABLE', `TOTAL` = '$TOTAL', `STATUS_ID` = $STATUS_ID, `TRACK_NUMBER` = '$TRACK_NUMBER', `ARRIVAL_DATE` = '$ARRIVAL_DATE', `COMMENT` = '$COMMENT' WHERE `SALES`.`ID` = $OLD_ID";
    $updateItem = $this-> dbConn -> prepare($updateQuery);
    $res = $updateItem -> execute();
    return $res;
  }

  public function sendResponse () {
    $postData = json_decode($_POST['data']);
    $action = $postData -> action;
    $res;

    
    if ($action == 'showTables') { $res = $this-> showTables(); }
    if ($action == 'getTable') {
      $tableName = $postData -> tableName;
      $res = $this -> getTable($tableName);
    }
    
    if ($action == 'insertCellsInTable') {
      $tableName = $postData -> tableName;
      $cells = $postData -> cells;
      $res = $this -> insertCellsInTable($tableName, $cells);
    }
    
    if ($action == 'rewriteTable') {
      $tableName = $postData -> tableName;
      $cells = $postData -> cells;
      $res = $this -> rewriteTable($tableName, $cells);
    }

    if ($action == 'addSale') {
      $tableData = $postData -> tableData;
      $res = $this -> addSale($tableData);
    }

    if ($action == 'deleteSale') {
      $saleID = $postData -> saleID;
      $res = $this -> deleteSale($saleID);
    }

    if ($action == 'updateSale') {
      $saleData = $postData -> saleData;
      $res = $this -> updateSale($saleData);
    }
    
    print_r(json_encode($res));
  }
}

$inst = new dbInterface();
?>