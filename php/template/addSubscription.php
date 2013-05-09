
<div data-role="popup" id="popupAddSubscription<?=$idPage?>" data-theme="e" class="ui-corner-all">
    <form>
        <div style="padding:10px 20px;">
          <h3>Add a Subscription</h3>
          <label for="user" class="ui-hidden-accessible">Subscription:</label>
          <input name="user" id="txtAddSubscription<?=$idPage?>" value="" placeholder="Subscription URL" data-theme="e" type="text">

            <div data-role="fieldcontain">
                <label for="selectCategory<?=$idPage?>" class="ui-hidden-accessible">Category:</label>
                <select class="selectCategory" id="selectCategory<?=$idPage?>" name="selectCategory">
                </select>
            </div>
        
             <a href="#" id="btnAddSubscription<?=$idPage?>" data-role="button" data-rel="back" data-theme="e">Add Subscription</a>
        </div>
    </form>
</div>
<script>
    $('#btnAddSubscription<?=$idPage?>').click(function(){
        subscription = $("#txtAddSubscription<?=$idPage?>").val();
        category = $("#selectCategory<?=$idPage?>").val();
        addSubscription(subscription, category);
    });
</script>
