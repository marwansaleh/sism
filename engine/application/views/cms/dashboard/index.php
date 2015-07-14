<style type="text/css">
    .large-box {height: 300px; overflow: hidden; outline: none;}
    .medium-box {height: 110px; overflow: hidden; outline:none;}
    .userlist-box {height: 500px; overflow: hidden; outline:none;}
</style>
<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="ion ion-ios-people"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Users</span>
                <span class="info-box-number"><?php echo number_format($user_count); ?><small> persons</small></span>
            </div><!-- /.info-box-content -->
        </div><!-- /.info-box -->
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-red"><i class="ion ion-email"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Surat Masuk</span>
                <span class="info-box-number">
                    { <span data-toggle="tooltip" title="Total"><?php echo number_format($incoming_total_count); ?></span> | 
                    <span data-toggle="tooltip" title="Milik anda"><?php echo number_format($incoming_count); ?></span> }
                </span>
            </div><!-- /.info-box-content -->
        </div><!-- /.info-box -->
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-green"><i class="ion ion-reply"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Surat Keluar</span>
                <span class="info-box-number">
                    { <span data-toggle="tooltip" title="Total"><?php echo number_format($outgoing_total_count); ?></span> |
                    <span data-toggle="tooltip" title="Milik anda"><?php echo number_format($outgoing_count); ?></span> }
                </span>
            </div><!-- /.info-box-content -->
        </div><!-- /.info-box -->
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="ion ion-forward"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Disposisi</span>
                <span class="info-box-number">
                    { <span data-toggle="tooltip" title="Mengirim disposisi"><?php echo number_format($disposition_send_count); ?></span> |
                    <span data-toggle="tooltip" title="Menerima disposisi"><?php echo number_format($disposition_receive_count); ?></span> }
                </span>
            </div><!-- /.info-box-content -->
        </div><!-- /.info-box -->
    </div>

    <div class="col-sm-8">
        <!-- graph for mail -->
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title"><i class="fa fa-bar-chart-o"></i> Grafik Surat Tahun <?php echo date('Y'); ?></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <canvas class="graphs" id="mail-chart"><span>Loading data...</span></canvas>
            </div>
        </div>
        <!-- Latest Incoming -->
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title"><i class="fa fa-file"></i> Latest Incomings</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="medium-box">
                    <?php if (count($last_incomings)): ?>
                        <table class="table table-striped table-condensed" role="table">
                            <tr>
                                <th>Sender</th>
                                <th>Recipient</th>
                                <th>Date</th>
                                <th>Subject</th>
                            </tr>
                            <?php foreach ($last_incomings as $incoming): ?>
                                <tr>
                                    <td><?php echo $incoming->sender_name; ?></td>
                                    <td><?php echo $incoming->receiver_name ?></td>
                                    <td><?php echo date("d-m-Y", strtotime($incoming->receive_date)); ?></td>
                                    <td><?php echo $incoming->subject; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    <?php else : ?>
                        <p>You don't have any incoming mail yet</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <!-- Latest disposition -->
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title"><i class="fa fa-file"></i> Latest Dispositions</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="medium-box">
                    <?php if (count($last_dispositions)): ?>
                        <table class="table table-striped table-condensed" role="table">
                            <tr>
                                <th>Sender</th>
                                <th>Recipient</th>
                                <th>Date</th>
                                <th>Note</th>
                            </tr>
                            <?php foreach ($last_dispositions as $disposition): ?>
                                <tr>
                                    <td><?php echo $disposition->sender_name; ?></td>
                                    <td><?php echo $disposition->receiver_name ?></td>
                                    <td><?php echo date("d-m-Y", $disposition->created); ?></td>
                                    <td><?php echo $disposition->notes; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    <?php else: ?>
                        <p>You don't have any disposition mail yet</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <!-- Latest outgoing -->
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title"><i class="fa fa-file"></i> Latest Outgoings</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="medium-box">
                    <?php if (count($last_outgoings)): ?>
                        <table class="table table-striped table-condensed" role="table">
                            <tr>
                                <th>Sender</th>
                                <th>Recipient</th>
                                <th>Date</th>
                                <th>Subject</th>
                            </tr>
                            <?php foreach ($last_outgoings as $outgoing): ?>
                                <tr>
                                    <td><?php echo $outgoing->sender_name; ?></td>
                                    <td><?php echo $outgoing->receiver_name ?></td>
                                    <td><?php echo date("d-m-Y", $outgoing->created); ?></td>
                                    <td><?php echo $outgoing->subject; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    <?php else: ?>
                        <p>You don't have any outgoing mail yet</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="box box-danger">
            <div class="box-header">
                <h3 class="box-title"><i class="fa fa-users"></i> User Online</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="userlist-box">
                    <table class="table table-striped">
                        <tbody>
                            <?php foreach ($user_onlines as $ol): ?>
                                <tr>
                                    <td><a data-toggle="tooltip" data-placement="left" title="Look profile" href="<?php echo site_url('profile/index?id=' . $ol->id); ?>"><?php echo $ol->full_name; ?></a></td>
                                    <td>
                                    <td class="text-right">
                                        <?php if ($ol->is_online): ?>
                                            <i class="ion ion-ios-person" data-toggle="tooltip" title="Online"></i>
                                        <?php else: ?>
                                            <i class="ion ion-ios-person-outline" data-toggle="tooltip" title="Offline"></i>
                                        <?php endif; ?>
                                    </td>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo site_url(config_item('path_lib').'chartjs/Chart.min.js'); ?>"></script>
<script type="text/javascript">
    var MapManager = {
        init: function () {
            Chart.defaults.global = {
                // Boolean - Whether to animate the chart
                animation: true,
                // Number - Number of animation steps
                animationSteps: 60,
                // String - Animation easing effect
                animationEasing: "easeOutQuart",
                // Boolean - If we should show the scale at all
                showScale: true,
                // Boolean - If we want to override with a hard coded scale
                scaleOverride: false,
                // ** Required if scaleOverride is true **
                // Number - The number of steps in a hard coded scale
                scaleSteps: null,
                // Number - The value jump in the hard coded scale
                scaleStepWidth: null,
                // Number - The scale starting value
                scaleStartValue: null,
                // String - Colour of the scale line
                scaleLineColor: "rgba(0,0,0,.1)",
                // Number - Pixel width of the scale line
                scaleLineWidth: 1,
                // Boolean - Whether to show labels on the scale
                scaleShowLabels: true,
                // Interpolated JS string - can access value
                scaleLabel: "<%=value%>",
                // Boolean - Whether the scale should stick to integers, not floats even if drawing space is there
                scaleIntegersOnly: true,
                // Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
                scaleBeginAtZero: false,
                // String - Scale label font declaration for the scale label
                scaleFontFamily: "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif",
                // Number - Scale label font size in pixels
                scaleFontSize: 12,
                // String - Scale label font weight style
                scaleFontStyle: "normal",
                // String - Scale label font colour
                scaleFontColor: "#666",
                // Boolean - whether or not the chart should be responsive and resize when the browser does.
                responsive: true,
                // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
                maintainAspectRatio: false,
                // Boolean - Determines whether to draw tooltips on the canvas or not
                showTooltips: true,
                // Array - Array of string names to attach tooltip events
                tooltipEvents: ["mousemove", "touchstart", "touchmove"],
                // String - Tooltip background colour
                tooltipFillColor: "rgba(0,0,0,0.8)",
                // String - Tooltip label font declaration for the scale label
                tooltipFontFamily: "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif",
                // Number - Tooltip label font size in pixels
                tooltipFontSize: 12,
                // String - Tooltip font weight style
                tooltipFontStyle: "normal",
                // String - Tooltip label font colour
                tooltipFontColor: "#fff",
                // String - Tooltip title font declaration for the scale label
                tooltipTitleFontFamily: "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif",
                // Number - Tooltip title font size in pixels
                tooltipTitleFontSize: 14,
                // String - Tooltip title font weight style
                tooltipTitleFontStyle: "bold",
                // String - Tooltip title font colour
                tooltipTitleFontColor: "#fff",
                // Number - pixel width of padding around tooltip text
                tooltipYPadding: 6,
                // Number - pixel width of padding around tooltip text
                tooltipXPadding: 6,
                // Number - Size of the caret on the tooltip
                tooltipCaretSize: 8,
                // Number - Pixel radius of the tooltip border
                tooltipCornerRadius: 6,
                // Number - Pixel offset from point x to tooltip edge
                tooltipXOffset: 10,
                // String - Template string for single tooltips
                tooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= value %>",
                // String - Template string for single tooltips
                multiTooltipTemplate: "<%= value %>",
                // Function - Will fire on animation progression.
                onAnimationProgress: function () {
                },
                // Function - Will fire on animation completion.
                onAnimationComplete: function () {
                }
            }
        },
        loadMailMonthlyToMap: function () {
            $.post('<?php echo site_url('ajax/dashboard/loadMailMonthly'); ?>', function (result) {
                var data = {
                    labels: result.labels,
                    datasets: [
                        {
                            label: "Incoming",
                            fillColor: "rgba(151,187,205,0.5)",
                            strokeColor: "rgba(151,187,205,0.8)",
                            highlightFill: "rgba(151,187,205,0.75)",
                            highlightStroke: "rgba(151,187,205,1)",
                            data: result.dataset.incoming
                        },
                        {
                            label: "Disposition",
                            fillColor: "rgba(220,220,220,0.5)",
                            strokeColor: "rgba(220,220,220,0.8)",
                            highlightFill: "rgba(220,220,220,0.75)",
                            highlightStroke: "rgba(220,220,220,1)",
                            data: result.dataset.disposition
                        },
                        {
                            label: "Outgoing",
                            fillColor: "rgba(242,184,184,0.5)",
                            strokeColor: "rgba(242,184,184,0.8)",
                            highlightFill: "rgba(242,184,184,0.75)",
                            highlightStroke: "rgba(242,184,184,1)",
                            data: result.dataset.outgoing
                        }
                    ]
                };

                // Get the context of the canvas element we want to select
                var ctx = document.getElementById("mail-chart");
                var register_chart = new Chart(ctx.getContext("2d")).Bar(data);
                //$(ctx).parent().parent().parent().find('span.year').text(year);
            }, 'json');
        }
    };

    $(document).ready(function () {
        $('.medium-box').niceScroll({cursorcolor: "#cecece"});
        $('.userlist-box').niceScroll({cursorcolor: "#cecece"});
        
        //load map data
        MapManager.init();
        MapManager.loadMailMonthlyToMap();
    });
</script>