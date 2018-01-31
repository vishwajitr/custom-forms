<?php 
//shortcode generator ajax call
if(isset($_GET['tagreq'])&&($_GET['tagreq']=="tag-generator-panel-text")){

$name = $_GET['name'];
$values = $_GET['values'];

$id = $_GET['id'];
$class = $_GET['class'];
?>
<input type="text" <?php if(isset($name)&&!empty($name)){echo 'name="'.$name.'" '; } ?><?php if(isset($id)&&!empty($id)){echo 'id="'.$id.'" '; } ?><?php if(isset($class)&&!empty($class)){echo 'class="'.$class.'" '; } ?><?php if(isset($_GET['placeholder'])){$placeholder = $_GET['placeholder']; if(isset($placeholder)&&!empty($placeholder)){echo 'placeholder="'.$values.'" '; } } ?><?php if(isset($values)&&!empty($values)){echo 'value="'.$values.'" '; } ?><?php if(isset($_GET['required'])){$required = $_GET['required']; if(isset($required)&&!empty($required)){echo 'required=""'; } } ?> >

<?php }



if(isset($_GET['tagreq'])&&($_GET['tagreq']=="tag-generator-panel-email")){

$name = $_GET['name'];
$values = $_GET['values'];

$id = $_GET['id'];
$class = $_GET['class'];
?>
<input type="email" <?php if(isset($name)&&!empty($name)){echo 'name="'.$name.'" '; } ?><?php if(isset($id)&&!empty($id)){echo 'id="'.$id.'" '; } ?><?php if(isset($class)&&!empty($class)){echo 'class="'.$class.'" '; } ?><?php if(isset($_GET['placeholder'])){$placeholder = $_GET['placeholder']; if(isset($placeholder)&&!empty($placeholder)){echo 'placeholder="'.$values.'" '; } } ?><?php if(isset($values)&&!empty($values)){echo 'value="'.$values.'" '; } ?><?php if(isset($_GET['required'])){$required = $_GET['required']; if(isset($required)&&!empty($required)){echo 'required=""'; } } ?> >

<?php }

if(isset($_GET['tagreq'])&&($_GET['tagreq']=="tag-generator-panel-url")){

$name = $_GET['name'];
$values = $_GET['values'];

$id = $_GET['id'];
$class = $_GET['class'];
?>
<input type="url" <?php if(isset($name)&&!empty($name)){echo 'name="'.$name.'" '; } ?><?php if(isset($id)&&!empty($id)){echo 'id="'.$id.'" '; } ?><?php if(isset($class)&&!empty($class)){echo 'class="'.$class.'" '; } ?><?php if(isset($_GET['placeholder'])){$placeholder = $_GET['placeholder']; if(isset($placeholder)&&!empty($placeholder)){echo 'placeholder="'.$values.'" '; } } ?><?php if(isset($values)&&!empty($values)){echo 'value="'.$values.'" '; } ?><?php if(isset($_GET['required'])){$required = $_GET['required']; if(isset($required)&&!empty($required)){echo 'required=""'; } } ?> >

<?php } 


if(isset($_GET['tagreq'])&&($_GET['tagreq']=="tag-generator-panel-tel")){

$name = $_GET['name'];
$values = $_GET['values'];

$id = $_GET['id'];
$class = $_GET['class'];
?>
<input type="tel" <?php if(isset($name)&&!empty($name)){echo 'name="'.$name.'" '; } ?><?php if(isset($id)&&!empty($id)){echo 'id="'.$id.'" '; } ?><?php if(isset($class)&&!empty($class)){echo 'class="'.$class.'" '; } ?><?php if(isset($_GET['placeholder'])){$placeholder = $_GET['placeholder']; if(isset($placeholder)&&!empty($placeholder)){echo 'placeholder="'.$values.'" '; } } ?><?php if(isset($values)&&!empty($values)){echo 'value="'.$values.'" '; } ?><?php if(isset($_GET['required'])){$required = $_GET['required']; if(isset($required)&&!empty($required)){echo 'required=""'; } } ?> >

<?php } 

if(isset($_GET['tagreq'])&&($_GET['tagreq']=="tag-generator-panel-number")){

$name = $_GET['name'];
$values = $_GET['values'];

$id = $_GET['id'];
$class = $_GET['class'];
?>
<input type="number" <?php if(isset($name)&&!empty($name)){echo 'name="'.$name.'" '; } ?><?php if(isset($id)&&!empty($id)){echo 'id="'.$id.'" '; } ?><?php if(isset($class)&&!empty($class)){echo 'class="'.$class.'" '; } ?><?php if(isset($_GET['placeholder'])){$placeholder = $_GET['placeholder']; if(isset($placeholder)&&!empty($placeholder)){echo 'placeholder="'.$values.'" '; } } ?><?php if(isset($values)&&!empty($values)){echo 'value="'.$values.'" '; } ?><?php if(isset($_GET['required'])){$required = $_GET['required']; if(isset($required)&&!empty($required)){echo 'required=""'; } } ?> >

<?php } 

