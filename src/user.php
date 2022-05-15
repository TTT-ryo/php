<?php
require_once './base.php';
require_once './validationException.php';

//処理をまとめていく
class User extends Base{
  private string $name;
  private string $tel;
  private string $address;

  //クラスUserをインスタンス化した時にDBに接続されるように
  public function __construct(){
    $this->connection();
  }

  public function index(){
    return $this->db
      ->query("SELECT * FROM users WHERE del_flg = false")
      ->fetchAll(PDO::FETCH_ASSOC);
  }

  //削除機能＆エラーハンドリング
  public function delete(string $id){
    $sql = "UPDATE users SET del_flg = true WHERE id = :id AND del_flg = false";
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_STR);
    if (!$stmt->execute()) {
      throw new Exception('削除できませんでした');
    }
  }

  // //新規作成メソッド
  public function create(string $name, string $tel, string $address){
    $this->name = $name;
    $this->tel = $tel;
    $this->address = $address;
    $this->validation();
    $sql = "INSERT INTO users (name, address, tel) VALUES (:name, :address, :tel)";
    $stmt = $this->db->prepare($sql);
    if (!$this->createOrUpdate($stmt)) {
      throw new Exception('登録できませんでした');
    }
  }

  public function show(string $id){
    $stmt = $this->db
      ->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  //更新メソッド
  public function update(string $id, string $name, string $tel, string $address){
    $this->name = $name;
    $this->tel = $tel;
    $this->address = $address;
    $this->validation();
    $sql = "UPDATE users SET name = :name, address = :address, tel = :tel WHERE id = :id AND del_flg = false";
    $stmt = $this->db->prepare($sql);
    if (!$this->createOrUpdate($stmt, $id)) {
      throw new Exception('更新できませんでした');
    }
  }

  //バリデーションメソッド
  private function validation(){
    $errorMessage = [];
    if (empty($this->name)) {
      $errorMessage[] = '名前が入力されてません';
    }

    if (empty($this->tel)) {
      $errorMessage[] = '電話番号が入力されてません';
    }

    if (empty($this->address)) {
      $errorMessage[] = '住所が入力されてません';
    }

    if (!empty($errorMessage)) {
      throw new ValidationException($errorMessage, 422);
    }
  }

  private function createOrUpdate(PDOStatement $stmt, ?string $id = null){
    $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
    $stmt->bindValue(':tel', $this->tel);
    $stmt->bindValue(':address', $this->address, PDO::PARAM_STR);
    if (!is_null($id)) {
      $stmt->bindValue(':id', $id, PDO::PARAM_STR);
    }
    return $stmt->execute();
  }
}
?>