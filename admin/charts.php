<?php
include('../php/dbClass.php');
include('authentication.php');
include('includes/header.php');
?>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
<style>
    /* .chartMenu {
      width: 100vw;
      height: 40px;
      color: rgba(255, 26, 104, 1);
    } */
    .chartMenu p {
        padding: 10px;
        font-size: 20px;
    }
    .chartCard {
        height: 81vh;
        background: rgba(255, 26, 104, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .chartBox {
        padding: 20px;
        border-radius: 20px;
        border: solid 3px rgba(255, 26, 104, 1);
        background: white;
    }
</style>
<div class="chartMenu">
    <p>Tournaments Chart</p>
</div>
<div class="chartCard">
    <div id="myfirstchart" style="height: 250px;"></div>
    <div class="text-box">
        <div class="col-md-3">
            <input type="text" name="from_date" id="from_cdate" class="form-control" placeholder="From Date" />
        </div>
        <div class="col-md-3">
            <input type="text" name="to_date" id="to_cdate" class="form-control" placeholder="To Date" />
        </div>
        <div class="col-md-5">
            <input type="button" name="filter" id="chart_filter" value="Filter" class="btn btn-info" />
        </div>
    </div>
</div>

<?php
include('includes/scripts.php');
?>
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
<script src="js/modal.js"></script>

<script>

    function loadChart(from_date,to_date){
        $.ajax({
            url:"modal.php",
            type:'post',
            data:{
                'loadChart':'true',
                'fromDate':from_date,
                'toDate':to_date,
            },
            success: function (data) {
                data=JSON.parse(data);
                $("#myfirstchart").empty();
                new Morris.Line({
                    // ID of the element in which to draw the chart.
                    element: 'myfirstchart',
                    // Chart data records -- each entry in this array corresponds to a point on
                    // the chart.
                    data: data,
                    // The name of the data record attribute that contains x-values.
                    xkey: 'tournamentRegistrationDate',
                    // A list of names of data record attributes that contain y-values.
                    ykeys: ['tournaments'],
                    // Labels for the ykeys -- will be displayed when you hover over the
                    // chart.
                    labels: ['tournaments'],
                    xLabelAngle: 60
                });
            }
        });//ajax
    }
    loadChart();
    // $('#datechangechart').on('change', function() {
    //   // alert( this.value );
    //   var range = this.value;
    //   $("#myfirstchart").empty();
    //   loadChart(range);
    // });
    $(document).ready(function () {
    //function that gets all invitations according to a certain date
    $.datepicker.setDefaults({
        dateFormat: 'yy-mm-dd'
    });
    $(function () {
        $("#from_cdate").datepicker();
        $("#to_cdate").datepicker();
    });
    $('#chart_filter').click(function () {
        var from_date = $('#from_cdate').val();
        var to_date = $('#to_cdate').val();
        if (from_date != '' && to_date != '') {
            if (from_date < to_date) {
                loadChart(from_date,to_date);
            }
            else {
                alert("Starting Date cannot be greated than Ending Date");
            }
        }
        else {
            alert("Please Select Date");
        }
    });
});

</script>


