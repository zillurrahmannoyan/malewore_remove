 <div class="modal fade delete-modal" id="deleteModal"  tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" aria-hidden="false">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
            <h4 class="modal-title">Title goes here ...</h4>
        </div>
        <div class="modal-body">
        Loading content body ..        
        </div>

        <div class="modal-footer">
        <a class="btn btn-danger confirm" data-href="#" data-index="" data-id="" data-target="">Confirm</a>
        <button class="btn btn-default" aria-hidden="true" data-dismiss="modal" class="close" type="button">Cancel</button> 
        <span class="msg"></span>           
        </div>
       
    </div>
  </div>
</div>

<div class="modal fade embed-modal" id="deleteModal"  tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" aria-hidden="false">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
            <h4 class="modal-title">Copy and paste code below</h4>
        </div>
        <div class="modal-body">
        <textarea class="form-control" rows="3"></textarea>       
        </div>

        <div class="modal-footer">
        <button class="btn btn-default" aria-hidden="true" data-dismiss="modal" class="close" type="button">Cancel</button> 
        <span class="msg"></span>           
        </div>
       
    </div>
  </div>
</div>

<script>
$(document).on('click','.btn', function() {
    var val = $(this).data('loader');
    if(val) {
        $(this).html(val).attr('disabled', 'disabled');        
    }
});

$(document).on('click','.embed', function() {
    var $this  = $(this);
    var $embed  = $this.data('embed');

    $('.modal-body textarea').val('<a href="'+$embed+'" class="btn-download" download>Download</a>');
});

$(document).on('click','.delete', function() {
	var $this  = $(this);
	var $title = $this.data('title');
	var $body  = $this.data('body');
    var $href  = $this.data('href');
    var $target = $(this).attr('target');

    $target = ($target == '_blank') ? '_blank' : '';

    $('.modal a.confirm').attr('data-target', $target);
	$('.modal a.confirm').attr('data-href', $href);
    $('.modal .modal-title').html($title);
	$('.modal .modal-body').html($body);
});

$(document).on('click','.delete-modal .modal-footer a', function(e) {
    e.preventDefault();
    $(this).html('Processing ...').attr('disabled', 'disabled');

    var $target = $(this).attr('data-target');
    var $href = $(this).attr('data-href');

    if($target == '_blank') {
        $('.modal').modal('hide');
        $(this).html('Confirm').removeAttr('disabled');
        window.open($href);
    } else {
        location.href = $href;    
    }

});
</script>