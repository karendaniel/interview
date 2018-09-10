<?php
if(!$_SESSION['loggedIn']) {
  ?>
  <a href="<?php echo site_url().'/wp-admin';?>">Kindly Login</a>
  <?php
  exit;
}
?>
<form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="POST">
	<input type="hidden" name="action" value="addListing">

	<table>
		
	<tr>
		<td>List Name:</td>
		<td><input type="text" name="list_name"></td></tr>
	<tr>
		<td>Distance:</td>
		<td><input type="text" name="distance"></td></tr>
	<tr><td colspan="2"><input type="hidden" name="user_id" value="<?php echo get_current_user_id();?>"></td></tr>
	<tr><td colspan="2"><input type="submit" value="ADD LISTING"></td></tr>

	</table>

	
</form>