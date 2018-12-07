<?php 
  class SqlBuilder{
    public $qinsert;
    public $qselect;
    public $qupdate;
    public $qdelete;
    public $qfrom;
    public $qwhere;
    public $qorder;
    public $qlimit;
    public $qgroup;
    public $qhaving;
    public $qpaginate;
    public $qtype;

    public function __construct(string $qfrom){
      $this->qfrom = $qfrom;
    }

    static function from(string $qfrom){
      return new self($qfrom);
    }

    public function insert(string $qinsert){
      $this->qinsert = $qinsert;
      $this->qtype = "insert";
      return $this;
    }

    public function select(string $qselect = "*"){
      $this->qselect = $qselect;
      $this->qtype = "select";
      return $this;
    }

    public function update(string $qupdate){
      $this->qupdate = $qupdate;
      $this->qtype = "update";
      return $this;
    }

    public function delete(){
      $this->qtype = "delete";
      return $this;
    }

    public function where(string $qwhere){
      $this->qwhere = $qwhere;
      return $this;
    }

    public function order(string $qorder){
      $this->qorder = $qorder;
      return $this;
    }

    public function limit(string $qlimit){
      $this->qlimit = $qlimit;
      return $this;
    }

    public function group(string $qgroup){
      $this->qgroup = $qgroup;
      return $this;
    }

    public function having(string $qhaving){
      $this->qhaving = $qhaving;
      return $this;
    }

    public function paginate($page, $itemPerPage){
      $start = ($page - 1) * $itemPerPage; //0-based
      $this->qlimit = "$start, $itemPerPage";
      return $this;
    }

    public function build(){
      $sql = "";

      switch($this->qtype){
        case "select":
          $sql = "select $this->qselect from $this->qfrom";
          $sql .= isset($this->qwhere) ? " where $this->qwhere" : "";
          $sql .= isset($this->qgroup) ? " group by $this->qgroup" : "";
          $sql .= isset($this->qhaving) ? " having $this->qhaving" : "";
          $sql .= isset($this->qorder) ? " order by $this->qorder" : "";
          $sql .= isset($this->qlimit) ? " limit $this->qlimit" : "";
          break;
        case "update":
          $sql = "update $this->qfrom set $this->qupdate where $this->qwhere";
          break;
        case "delete":
          $sql = "delete from $this->qfrom where $this->qwhere";
          break;
        case "insert":
          $sql = "insert into $this->qfrom values($this->qinsert)";
          break;
      }
      //$sql = strip_tags($sql);
      return $sql . ";";
    }
  }
?>