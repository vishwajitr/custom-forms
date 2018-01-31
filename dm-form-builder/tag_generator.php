<?php
if(isset($_GET['tagreq'])&&($_GET['tagreq']=="tag-generator-panel-text")){
?>

<div id="tag-generator-panel-text">
   <div class="tag-generator-panel">
      <div class="control-box">
         <fieldset>
            <form id="myform" method="GET">
            <input type="text" name="tagreq" value="<?php echo $_GET['tagreq']; ?>">	
            <table class="form-table">
               <tbody>
                  <tr>
                     <th scope="row">Field type</th>
                     <td>
                        <fieldset>
                           <legend class="screen-reader-text">Field type</legend>
                           <label><input type="checkbox" name="required" class="require_field"> Required field</label>
                        </fieldset>
                     </td>
                  </tr>
                  <tr>
                     <th scope="row"><label for="tag-generator-panel-text-name">Name</label></th>
                     <td><input type="text" name="name" class="tg-name oneline" id="tag-generator-panel-text-name" onkeyup="ajaxCall(this.value)"></td>
                  </tr>
                  <tr>
                     <th scope="row"><label for="tag-generator-panel-text-values">Default value</label></th>
                     <td><input type="text" name="values" class="oneline" id="tag-generator-panel-text-values" onkeyup="ajaxCall(this.value)"><br>
                        <label><input type="checkbox" name="placeholder" class="option default_value"> Use this text as the placeholder of the field</label>
                     </td>
                  </tr>
                  <tr>
                     <th scope="row"><label for="tag-generator-panel-text-id">Id attribute</label></th>
                     <td><input type="text" name="id" class="idvalue oneline option" id="tag-generator-panel-text-id" onkeyup="ajaxCall(this.value)"></td>
                  </tr>
                  <tr>
                     <th scope="row"><label for="tag-generator-panel-text-class">Class attribute</label></th>
                     <td><input type="text" name="class" class="classvalue oneline option" id="tag-generator-panel-text-class" onkeyup="ajaxCall(this.value)"></td>
                  </tr>
               </tbody>
            </table>
            </form>
         </fieldset>
          
      </div>
      <div class="insert-box">
         <span id="txtHint"></span>
         <div class="submitbox">
            <input type="button" class="button button-primary insert-tag" value="Insert Tag" onclick="insertAtCaret()">
         </div>
         <br class="clear">
      </div>
   </div>
</div>
<?php
}
?>



<?php
if(isset($_GET['tagreq'])&&($_GET['tagreq']=="tag-generator-panel-email")){
?>
<div id="tag-generator-panel-email">
   <div class="tag-generator-panel">
      <div class="control-box">
         <fieldset>
            <form id="myform" method="GET">
            <input type="text" name="tagreq" value="<?php echo $_GET['tagreq']; ?>">	
            <table class="form-table">
               <tbody>
                  <tr>
                     <th scope="row">Field type</th>
                     <td>
                        <fieldset>
                           <legend class="screen-reader-email">Field type</legend>
                           <label><input type="checkbox" name="required" class="require_field"> Required field</label>
                        </fieldset>
                     </td>
                  </tr>
                  <tr>
                     <th scope="row"><label for="tag-generator-panel-email-name">Name</label></th>
                     <td><input type="text" name="name" class="tg-name oneline" id="tag-generator-panel-email-name" onkeyup="ajaxCall(this.value)"></td>
                  </tr>
                  <tr>
                     <th scope="row"><label for="tag-generator-panel-email-values">Default value</label></th>
                     <td><input type="text" name="values" class="oneline" id="tag-generator-panel-email-values" onkeyup="ajaxCall(this.value)"><br>
                        <label><input type="checkbox" name="placeholder" class="option default_value"> Use this text as the placeholder of the field</label>
                     </td>
                  </tr>
                  <tr>
                     <th scope="row"><label for="tag-generator-panel-email-id">Id attribute</label></th>
                     <td><input type="text" name="id" class="idvalue oneline option" id="tag-generator-panel-email-id" onkeyup="ajaxCall(this.value)"></td>
                  </tr>
                  <tr>
                     <th scope="row"><label for="tag-generator-panel-text-class">Class attribute</label></th>
                     <td><input type="text" name="class" class="classvalue oneline option" id="tag-generator-panel-email-class" onkeyup="ajaxCall(this.value)"></td>
                  </tr>
               </tbody>
            </table>
            </form>
         </fieldset>
          
      </div>
      <div class="insert-box">
         <span id="txtHint"></span>
         <div class="submitbox">
            <input type="button" class="button button-primary insert-tag" value="Insert Tag" onclick="insertAtCaret()">
         </div>
         <br class="clear">
      </div>
   </div>
</div>
<?php
}
?>


<?php
if(isset($_GET['tagreq'])&&($_GET['tagreq']=="tag-generator-panel-url")){
?>
<div id="tag-generator-panel-url">
   <div class="tag-generator-panel">
      <div class="control-box">
         <fieldset>
            <form id="myform" method="GET">
            <input type="text" name="tagreq" value="<?php echo $_GET['tagreq']; ?>">	
            <table class="form-table">
               <tbody>
                  <tr>
                     <th scope="row">Field type</th>
                     <td>
                        <fieldset>
                           <legend class="screen-reader-url">Field type</legend>
                           <label><input type="checkbox" name="required" class="require_field"> Required field</label>
                        </fieldset>
                     </td>
                  </tr>
                  <tr>
                     <th scope="row"><label for="tag-generator-panel-url-name">Name</label></th>
                     <td><input type="text" name="name" class="tg-name oneline" id="tag-generator-panel-url-name" onkeyup="ajaxCall(this.value)"></td>
                  </tr>
                  <tr>
                     <th scope="row"><label for="tag-generator-panel-url-values">Default value</label></th>
                     <td><input type="text" name="values" class="oneline" id="tag-generator-panel-url-values" onkeyup="ajaxCall(this.value)"><br>
                        <label><input type="checkbox" name="placeholder" class="option default_value"> Use this text as the placeholder of the field</label>
                     </td>
                  </tr>
                  <tr>
                     <th scope="row"><label for="tag-generator-panel-url-id">Id attribute</label></th>
                     <td><input type="text" name="id" class="idvalue oneline option" id="tag-generator-panel-url-id" onkeyup="ajaxCall(this.value)"></td>
                  </tr>
                  <tr>
                     <th scope="row"><label for="tag-generator-panel-text-class">Class attribute</label></th>
                     <td><input type="text" name="class" class="classvalue oneline option" id="tag-generator-panel-url-class" onkeyup="ajaxCall(this.value)"></td>
                  </tr>
               </tbody>
            </table>
            </form>
         </fieldset>
          
      </div>
      <div class="insert-box">
         <span id="txtHint"></span>
         <div class="submitbox">
            <input type="button" class="button button-primary insert-tag" value="Insert Tag" onclick="insertAtCaret()">
         </div>
         <br class="clear">
      </div>
   </div>
</div>
<?php
}
?>

<?php
if(isset($_GET['tagreq'])&&($_GET['tagreq']=="tag-generator-panel-tel")){
?>
<div id="tag-generator-panel-tel">
   <div class="tag-generator-panel">
      <div class="control-box">
         <fieldset>
            <form id="myform" method="GET">
            <input type="text" name="tagreq" value="<?php echo $_GET['tagreq']; ?>">	
            <table class="form-table">
               <tbody>
                  <tr>
                     <th scope="row">Field type</th>
                     <td>
                        <fieldset>
                           <legend class="screen-reader-tel">Field type</legend>
                           <label><input type="checkbox" name="required" class="require_field"> Required field</label>
                        </fieldset>
                     </td>
                  </tr>
                  <tr>
                     <th scope="row"><label for="tag-generator-panel-tel-name">Name</label></th>
                     <td><input type="text" name="name" class="tg-name oneline" id="tag-generator-panel-tel-name" onkeyup="ajaxCall(this.value)"></td>
                  </tr>
                  <tr>
                     <th scope="row"><label for="tag-generator-panel-tel-values">Default value</label></th>
                     <td><input type="text" name="values" class="oneline" id="tag-generator-panel-tel-values" onkeyup="ajaxCall(this.value)"><br>
                        <label><input type="checkbox" name="placeholder" class="option default_value"> Use this text as the placeholder of the field</label>
                     </td>
                  </tr>
                  <tr>
                     <th scope="row"><label for="tag-generator-panel-tel-id">Id attribute</label></th>
                     <td><input type="text" name="id" class="idvalue oneline option" id="tag-generator-panel-tel-id" onkeyup="ajaxCall(this.value)"></td>
                  </tr>
                  <tr>
                     <th scope="row"><label for="tag-generator-panel-text-class">Class attribute</label></th>
                     <td><input type="text" name="class" class="classvalue oneline option" id="tag-generator-panel-tel-class" onkeyup="ajaxCall(this.value)"></td>
                  </tr>
               </tbody>
            </table>
            </form>
         </fieldset>
          
      </div>
      <div class="insert-box">
         <span id="txtHint"></span>
         <div class="submitbox">
            <input type="button" class="button button-primary insert-tag" value="Insert Tag" onclick="insertAtCaret()">
         </div>
         <br class="clear">
      </div>
   </div>
</div>
<?php
}
?>



<?php
if(isset($_GET['tagreq'])&&($_GET['tagreq']=="tag-generator-panel-number")){
?>
<div id="tag-generator-panel-number">
   <div class="tag-generator-panel">
      <div class="control-box">
         <fieldset>
            <form id="myform" method="GET">
            <input type="text" name="tagreq" value="<?php echo $_GET['tagreq']; ?>">	
            <table class="form-table">
               <tbody>
                  <tr>
                     <th scope="row">Field type</th>
                     <td>
                        <fieldset>
                           <legend class="screen-reader-number">Field type</legend>
                           <label><input type="checkbox" name="required" class="require_field"> Required field</label>
                        </fieldset>
                     </td>
                  </tr>
                  <tr>
                     <th scope="row"><label for="tag-generator-panel-number-name">Name</label></th>
                     <td><input type="text" name="name" class="tg-name oneline" id="tag-generator-panel-number-name" onkeyup="ajaxCall(this.value)"></td>
                  </tr>
                  <tr>
                     <th scope="row"><label for="tag-generator-panel-number-values">Default value</label></th>
                     <td><input type="text" name="values" class="oneline" id="tag-generator-panel-number-values" onkeyup="ajaxCall(this.value)"><br>
                        <label><input type="checkbox" name="placeholder" class="option default_value"> Use this text as the placeholder of the field</label>
                     </td>
                  </tr>
                  <tr>
                     <th scope="row"><label for="tag-generator-panel-number-id">Id attribute</label></th>
                     <td><input type="text" name="id" class="idvalue oneline option" id="tag-generator-panel-number-id" onkeyup="ajaxCall(this.value)"></td>
                  </tr>
                  <tr>
                     <th scope="row"><label for="tag-generator-panel-text-class">Class attribute</label></th>
                     <td><input type="text" name="class" class="classvalue oneline option" id="tag-generator-panel-number-class" onkeyup="ajaxCall(this.value)"></td>
                  </tr>
               </tbody>
            </table>
            </form>
         </fieldset>
          
      </div>
      <div class="insert-box">
         <span id="txtHint"></span>
         <div class="submitbox">
            <input type="button" class="button button-primary insert-tag" value="Insert Tag" onclick="insertAtCaret()">
         </div>
         <br class="clear">
      </div>
   </div>
</div>
<?php
}
?>



<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script>
function ajaxCall() {
var send_data = $("form").serializeArray();
$.get('ajax_shortcode.php', send_data, function (data) {		 
$('#txtHint').text(data);
});
}
$(document).ready(function(){
$('.default_value').change(function(){
ajaxCall();
});

$('.require_field').change(function(){
ajaxCall();
});

})

function htmlEncode(value) {
return $('<div/>').text(value).html();
}
 
function htmlDecode(value) {
return $('<div/>').html(value).text();
}

function insertAtCaret(text) {
	    // parent.tb_remove(); parent.location.reload(1)
	    parent.eval('tb_remove()');
    
	    var text = document.getElementById('txtHint').innerHTML;
	    text = htmlDecode(text);
	    alert(text);
	    var txtarea = parent.document.getElementById('htmlcode');


		if (!txtarea) { return; }

		var scrollPos = txtarea.scrollTop;
		var strPos = 0;
		var br = ((txtarea.selectionStart || txtarea.selectionStart == '0') ?
			"ff" : (document.selection ? "ie" : false ) );
		if (br == "ie") {
			txtarea.focus();
			var range = document.selection.createRange();
			range.moveStart ('character', -txtarea.value.length);
			strPos = range.text.length;
		} else if (br == "ff") {
			strPos = txtarea.selectionStart;
		}

		var front = (txtarea.value).substring(0, strPos);
		var back = (txtarea.value).substring(strPos, txtarea.value.length);
		txtarea.value = front + text + back;
		strPos = strPos + text.length;
		if (br == "ie") {
			txtarea.focus();
			var ieRange = document.selection.createRange();
			ieRange.moveStart ('character', -txtarea.value.length);
			ieRange.moveStart ('character', strPos);
			ieRange.moveEnd ('character', 0);
			ieRange.select();
		} else if (br == "ff") {
			txtarea.selectionStart = strPos;
			txtarea.selectionEnd = strPos;
			txtarea.focus();
		}

		txtarea.scrollTop = scrollPos;
	}

</script>

