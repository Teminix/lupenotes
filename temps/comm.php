<div>
  <center>
    <h1>LUPES</h1>
  </center>
  <h4>Lupes are nothing more than communities you can catagorize notes by and visit:</h4>
</div>
<div class="searchFrame">
<div>
  <input type="text" placeholder="Search" style="width:100%;box-sizing:border-box" oninput="search(this,'inline-block')" id="search">
</div>
<center>
<?php
  $conn = new mysqli("localhost","root","root","project");
  $res = $conn->query("SELECT * FROM communities");
  while ($row = $res->fetch_assoc()) {
    $description = $row["description"];
    $name = $row["Name"];
    $id = $row["ID"];
    echo "<div class='circle' baseradius='100px' hoverradius='200px' bg='images/communities/$id.jpg' searchable onclick='window.location.href=\"communities.php?c=$name\"'>
      <div class='circle-wrapper'>
        <div class='title'>
        $name
        </div>
        <div class='description'>
        $description
        </div>
      </div>
    </div>";
  }
 ?>
</center>
</div>
