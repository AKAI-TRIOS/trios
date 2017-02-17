<!-- jQuery first, then Tether, then Bootstrap JS. -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js" integrity="sha384-3ceskX3iaEnIogmQchP8opvBy3Mi7Ce34nWjpBIwVTHfGYWQS9jwHDVRnpKKHJg7" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.3.7/js/tether.min.js" integrity="sha384-XTs3FgkjiBgo8qjEjBk0tGmf3wPrWtA6coPfQDfFEY8AnYJwjalXCiosYRBIBZX8" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/js/bootstrap.min.js" integrity="sha384-BLiI7JTZm+JWlgKa0M0kGRpJbF2J8q+qreVrKBC47e3K6BW78kGLrCkeRX6I9RoK" crossorigin="anonymous"></script>
<script>
    //Make cyrrent nav section active
    $('ul.nav a').filter(function() {
        return this.href == window.location;
    }).parent().addClass('active');
    //Make magic happen
    //Function to load new trio
    $( document ).ready(function() {

        // 0 - white, check
        // 1 - red, try again
        // 2 - green, next trio
        var checkButtonState = 0;

        //AJAX magic
        //On first load fetch a random trio
        var jqxhr = $.getJSON( "/api/solve", function( trio ) {
            //Fill the page
            $("#sentence1").html(trio.sentence1.replace("$@$", "_____"));
            $("#sentence2").html(trio.sentence2.replace("$@$", "_____"));
            $("#sentence3").html(trio.sentence3.replace("$@$", "_____"));
            $("#trio-id").html(trio.id);

        })
            .fail(function() {
                alert("We're having some trouble fetching a new Trio for you. :< Please try again.");
                console.log( "error" );
            });
        //After user inputs answer and clicks check
        $("#check-button").click(function (e) {
            e.preventDefault();

            //IF the button was already green, load next trio
            if (checkButtonState == 2) {
                //Make JSON request
                var jqxhr = $.getJSON( "/api/solve", function( trio ) {
                    //Fill the page
                    $("#sentence1").html(trio.sentence1.replace("$@$", "_____"));
                    $("#sentence2").html(trio.sentence2.replace("$@$", "_____"));
                    $("#sentence3").html(trio.sentence3.replace("$@$", "_____"));
                    $("#trio-id").html(trio.id);
                });
                $("#check-button")
                    .removeClass("btn-success")
                    .removeClass("btn-danger")
                    .addClass("btn-default")
                    .html("Check");
                //Clear the text input
                $("#answer").val('');
                checkButtonState = 0;
                return;
            }

            //Get answer
            var answer = $("#answer").val();
            //Send POST check request to /api/solve/{trio}
            $.post( "/api/solve/" + $("#trio-id").text(), {
                "answer" : answer,
                _token: $('meta[name="csrf-token"]').attr('content')
            })
                .done(function ( data ) {
                    var ret = JSON.parse(data);
                    if(ret.answer.isCorrect == true) {
                        //IF answer is correct, change button to green and change text to "Next trio"
                        $("#check-button")
                            .removeClass("btn-danger")
                            .removeClass("btn-default")
                            .addClass("btn-success")
                            .html("Correct, next trio→");
                        checkButtonState = 2;
                    } else {
                        //ELSE if answer is not correct, change button to red and change text to "try again"
                        $("#check-button")
                            .removeClass("btn-default")
                            .addClass("btn-danger")
                            .html("Try again");
                        checkButtonState = 1;
                    }
            });
        });

        //ON I don't know click or green buton click, load new random trio
        $("#idk-button").click(function (e) {
            e.preventDefault();

            //Reset check button state
            if (checkButtonState == 1) {
                $("#check-button")
                    .removeClass("btn-danger")
                    .addClass("btn-default")
                    .html("Check");
                checkButtonState = 0;
            }
            //CLear the text input
            $("#answer").val('');
            //Make JSON request
            var jqxhr = $.getJSON( "/api/solve", function( trio ) {
                //Fill the page
                $("#sentence1").html(trio.sentence1.replace("$@$", "_____"));
                $("#sentence2").html(trio.sentence2.replace("$@$", "_____"));
                $("#sentence3").html(trio.sentence3.replace("$@$", "_____"));
                $("#trio-id").html(trio.id);
            });
        });
    });
</script>