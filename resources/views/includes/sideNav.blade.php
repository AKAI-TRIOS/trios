@push('afterjs')
<script type="text/javascript">
$(function() {
    /* Set the width of the side navigation to 250px and the left margin of the page content to 250px and add a black background color to body */
    $(".open-menu").on("click", function() {
        $("#mySidenav").addClass("open");
    });

    /* Set the width of the side navigation to 0 and the left margin of the page content to 0, and the background color of body to white */
    $(".close-menu").on("click", function() {
        $("#mySidenav").removeClass("open");
    });
});
</script>
@endpush

<div id="mySidenav" class="sidenav">
    <a href="javascript:void(0)" class="close-menu">&times;</a>
    <nav class="menu">
        <a href="{{ url('/login') }}">Login</a>
        <a href="https://github.com/AKAI-TRIOS/trios">Source Code</a>
        <a href="https://www.facebook.com/akai.pp/">Contact</a>
    </nav>
</div>
<div class="overlay close-menu"></div>
<a href="#" class="open-menu"><span class="glyphicon glyphicon-menu-hamburger"></span></a>
