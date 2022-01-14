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
    $data = json_decode($_POST['data']);
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
    
    print_r($res);
  }
}

$inst = new dbInterface();
?>