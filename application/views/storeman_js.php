<script>

var countDownDate = new Date("Feb 9, 2021 00:04:00").getTime();

var x = setInterval(function() {

  var now = new Date().getTime();

  var distance = countDownDate - now;

  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);

  document.getElementById("demo").innerHTML = days + "d " + hours + "h "
  + minutes + "m " + seconds + "s ";

  if (distance < 0) {
	clearInterval(x);
	document.getElementById("demo").innerHTML = "ENDED";
  }
}, 1000);


$(function() {

        $(".progress-circle").each(function() {
            var value = $(this).attr('data-value');
            var left = $(this).find('.progress-left .progress-bar');
            var right = $(this).find('.progress-right .progress-bar');

            if (value > 0) {
                if (value <= 50) {
                    right.css('transform', 'rotate(' + percentageToDegrees(value) + 'deg)');
                } else {
                    right.css('transform', 'rotate(180deg)');
                    left.css('transform', 'rotate(' + percentageToDegrees(value - 50) + 'deg)');
                }
            }
        });
        function percentageToDegrees(percentage) {
            return percentage / 100 * 360;
        }

        $('[data-toggle="tooltip"]').tooltip({
            delay: { "show":300, "hide": 1000 },
        });

        $('[data-toggle="tooltip-table"]').tooltip({
            delay: { "show":300, "hide": 1000 },
            container: '.table'
        });
    });

</script>