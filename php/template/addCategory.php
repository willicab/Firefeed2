
<div data-role="popup" id="popupAddCategory<?=$idPage?>" data-theme="e" class="ui-corner-all">
    <form>
        <div style="padding:10px 20px;">
          <h3>Add a Category</h3>
          <label for="un" class="ui-hidden-accessible">Category:</label>
          <input name="user" id="txtAddCategory<?=$idPage?>" value="" placeholder="Category" data-theme="e" type="text">
             <a href="#" id="btnAddCategory<?=$idPage?>" data-role="button" data-rel="back" data-theme="e">Add Category</a>
        </div>
    </form>
</div>
<script>
    $('#btnAddCategory<?=$idPage?>').click(function(){
        addCategory($("#txtAddCategory<?=$idPage?>").val());
    });
</script>
