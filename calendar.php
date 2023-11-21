<style>
#calendar {
    width: 100%;
    margin: 0 auto;
}

.response {
    height: 60px;
}

.success {
    background: #cdf3cd;
    padding: 10px 60px;
    border: #c3e6c3 1px solid;
    display: inline-block;
}
</style>
    <div class="response"></div>
    <div id='calendar'></div>
<script>
$(document).ready(function () {
    var calendar = $('#calendar').fullCalendar({
        editable: true,
        events: "fetch-event.php",
        displayEventTime: false,
        eventRender: function (event, element, view) {
            if (event.allDay === 'true') {
                event.allDay = true;
            } else {
                event.allDay = false;
            }
        },
        selectable: true,
        selectHelper: true,
        select: function (start, end, allDay) {
            // Find the nearest Sunday before the selected date
            var startOfWeek = moment(start).startOf('week').day(0);
            
            // Find the nearest Saturday after the selected date
            var endOfWeek = moment(startOfWeek).day(6);

            var startFormatted = startOfWeek.format("Y-MM-DD");
            var endFormatted = endOfWeek.format("Y-MM-DD");

            console.log('start ' + startFormatted, 'end ' + endFormatted);

            // Redirect the user to a different URL with startFormatted and endFormatted as URL parameters
            var redirectURL = 'index.php?page=time_sheet&Start_date=' + startFormatted + '&End_date=' + endFormatted;

            window.location.href = redirectURL;
        },

        editable: true,
        eventDrop: function (event, delta) {
            var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
            var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
            $.ajax({
                url: 'edit-event.php',
                data: 'title=' + event.title + '?Start_date=' + start + '&End_date=' + end + '&id=' + event.id,
                type: "POST",
                success: function (response) {
                    // displayMessage("Updated Successfully");
                    alert_toast("Updated Successfully");
                }
            });
        },
        eventClick: function (event) {
            var dayOfWeek = event.start.day(); // Get the day of the week (0 for Sunday, 6 for Saturday)

            // Check if the clicked event's day is not Sunday (0) or Saturday (6)
            if (dayOfWeek !== 0 && dayOfWeek !== 6) {
                var deleteMsg = confirm("Do you really want to delete?");
                if (deleteMsg) {
                    $.ajax({
                        type: "POST",
                        url: "delete-event.php",
                        data: "&id=" + event.id,
                        success: function (response) {
                            if (parseInt(response) > 0) {
                                $('#calendar').fullCalendar('removeEvents', event.id);
                                alert_toast("Deleted Successfully");
                            }
                        }
                    });
                }
            }
        },

        // Set business hours to disable Sundays (0) and Saturdays (6)
        businessHours: {
            dow: [1, 2, 3, 4, 5], // Monday to Friday
            start: '10:00', // Adjust as needed
            end: '19:00' // Adjust as needed
        }
    });
});

function displayMessage(message) {
    $(".response").html("<div class='success'>" + message + "</div>");
    setInterval(function () { $(".success").fadeOut(); }, 1000);
}


</script>