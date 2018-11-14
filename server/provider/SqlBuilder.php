<?php 
  class SqlBuilder{
    private $qinsert;
    private $qselect;
    private $qupdate;
    private $qdelete;
    private $qfrom;
    private $qwhere;
    private $qorder;
    private $qlimit;
    private $qgroup;
    private $qhaving;
    private $qpaginate;
    private $qtype;

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

    public function select(string $qselect){
      $this->qselect = $qselect;
      $this->qtype = "select";
      return $this;
    }

    public function update(string $qupdate){
      $this->qupdate = $qupdate;
      $this->qtype = "update";
      return $this;
    }

    public function delete(string $qdelete){
      $this->qdelete = $qdelete;
      $this->qtype = "delete";
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

    public function paginate($id, $page, $itemPerPage){
      $start = ($page - 1) * $itemPerPage; //0-based
      $end = $start + $itemPerPage;
      $this->qpaginate = "($id > ($start) and $id <= ($end))";
      $this->qlimit = $itemPerPage;
      return $this;
    }

    public function build(){
      $sql = "";

      switch($this->qtype){
        case "select":
          $sql = "select $this->qselect from $this->qfrom";
          $sql .= isset($this->qwhere) && isset($this->qpaginate) ? " where $this->qwhere and $this->qpaginate" : "";
          $sql .= isset($this->qwhere) && is_null($this->qpaginate) ? " where $this->qwhere" : "";
          $sql .= is_null($this->qwhere) && isset($this->qpaginate) ? " and $this->qpaginate" : "";
          $sql .= isset($this->qgroup) ? " group by $this->qfrom" : "";
          $sql .= isset($this->qhaving) ? " having $this->qhaving" : "";
          $sql .= isset($this->qorder) ? " order by $this->qorder" : "";
          $sql .= isset($this->qlimit) ? " limit $this->qlimit" : "";
          break;
        case "update":
          $sql = "update $qfrom set $qupdate where $qwhere";
          break;
        case "delete":
          $sql = "delete $qfrom where $qwhere";
          break;
        case "insert":
          $sql = "insert into $qfrom values(string $qinsert)";
          break;
      }
      return $sql . ";";
    }
  }
?>