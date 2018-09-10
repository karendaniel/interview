<a href="<?php echo site_url().'/add-listing'?>" id="add">add</a>

<?php
if(!$_SESSION['loggedIn']) {
 ?>
 <a href="<?php echo site_url().'/wp-admin';?>">Kindly Login</a>
 <?php
 exit;
}
?>
<table>
  <tr>
    <th>id</th>
    <th>list_name</th>
    <th>name</th>
    <th>user_id</th>
    <th></th>

  </tr>
  <?php 
  foreach ($data as $d) {

   foreach ($d as $value) {
    
    $id = $value['id'];
    ?>
    <tr>
      <td><?php echo $id;?></td>
      <td><?php echo $value['list_name'];?></td>
      <td><?php echo $value['distance'];?></td>
      <td><?php echo $value['user_id'];?></td>
      <td><a href="#" id="<?php echo $id;?>" class="delete">delete</a></td>

    </tr>
    <?php
   }

  }
  ?>
</table>


