<html>
<head>
<title>簡易掲示板</title>
</head>
<body>
  <form method="post">
    <textarea name="textarea" rows="10" cols="50" placeholder="Write Something Here"></textarea>
    <button type="submit" name="action" value="send">送信</button>
  </form>
  <hr>
  <?php
    if(isset($_POST["action"])) {
      $contents = $_POST["textarea"];
      if(isset($_GET["index"]))
        $index = $_GET["index"];
      else
        $index = 0;
      $conn = pg_pconnect("host=localhost dbname=keijiban_test");
      if(!$conn) {
        echo "データベース接続中にエラーが起きました。.\n";
        exit;
      }
      $result = pg_query($conn, "select max(id) from keijiban;");
      $row = pg_fetch_row($result);
      $row[0] += 1;
      pg_query($conn, "insert into keijiban(id,contents) values('{$row[0]}','{$contents}');");
      $result = pg_query($conn, "select * from keijiban order by id desc limit 5 offset {$index};");
      $result2 = pg_query($conn, "select count(id) from keijiban;");
      while($row = pg_fetch_row($result)) {
        print "{$row[1]}<br><hr>";
      }
      $row2 = pg_fetch_row($result2);
      print "<center>";
      for($i = 1; $i <= ceil($row2[0] / 5); $i++) {
        $index2 = ($i - 1) * 5;
        print "<a href=\"http://localhost:8000?index={$index2}\">{$i}</a>&nbsp;";
      }
      print "</center>";
      pg_close($conn);
    } else {
      if(isset($_GET["index"]))
        $index = $_GET["index"];
      else
        $index = 0;
      $conn = pg_pconnect("host=localhost dbname=keijiban_test");
      if(!$conn) {
        echo "データベース接続中にエラーが起きました。\n";
        exit;
      }
      $result = pg_query($conn, "select * from keijiban order by id desc limit 5 offset {$index};");
      $result2 = pg_query($conn, "select count(id) from keijiban;");
      while($row = pg_fetch_row($result)) {
        print "{$row[1]}<br><hr>";
      }
      $row2 = pg_fetch_row($result2);
      print "<center>";
      for($i = 1; $i <= ceil($row2[0] / 5); $i++) {
        $index2 = ($i - 1) * 5;
        print "<a href=\"http://localhost:8000?index={$index2}\">{$i}</a>&nbsp;";
      }
      print "</center>";
      pg_close($conn);
    }
   ?>
</body>
</html>
