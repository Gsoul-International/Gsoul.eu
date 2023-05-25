

<form action="" method="post" enctype="multipart/form-data">
<input type="file" multiple name="img[]"/>
<input type="submit">
</form>
<?php
foreach($_FILES['img'] as $fi){
  var_dump($fi);
  }
?>

