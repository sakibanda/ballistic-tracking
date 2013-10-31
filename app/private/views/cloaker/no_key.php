<h1 class="grid_12"><span>Advanced Redirects</span></h1>

<div class="grid_12 minwidth">
    <p>You do not have access. Do you need a key?</p>
    <button ype="button" id="purchase">Purchase a Key</button>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('button#purchase').click(function(){
            document.location.href='/admin/purchase/';
        })
    });
</script>