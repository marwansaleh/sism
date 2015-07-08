<div class="row">
    <div class="col-sm-12">
        <?php if ($this->session->flashdata('message')): ?>
        <?php echo create_alert_box($this->session->flashdata('message'),$this->session->flashdata('message_type')); ?>
        <?php endif; ?>
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Report</h3>
                <div class="box-tools">
                    <form role="form" method="post" class="form-inline">
                        <input type="hidden" name="submitted" value="1">
                        <select class="form-control input-sm" id="year" name='year'>
                            <option value="0">--Select Year--</option>
                            <?php foreach ($years as $year): ?>
                            <option value="<?php echo $year; ?>" <?php echo $selected_year==$year?'selected':''; ?>><?php echo $year;?></option>
                            <?php endforeach; ?>
                        </select>
                        <select class="form-control input-sm" id="month" name='month'>
                            <option value="0">--Select Month--</option>
                            <?php for ($month=1; $month <= count($months); $month++): ?>
                            <option value="<?php echo $month; ?>" <?php echo $selected_month==$month?'selected':''; ?>><?php echo $months[$month-1];?></option>
                            <?php endfor; ?>
                        </select>
                        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                    </form>
                    
                </div>
            </div><!-- /.box-header -->
            
            <div class="box-body table-responsive no-padding">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="active"><a href="#incomings" role="tab" data-toggle="tab">Incomings</a></li>
                        <li><a href="#dispositions" role="tab" data-toggle="tab">Dispositions</a></li>
                        <li><a href="#outgoings" role="tab" data-toggle="tab">Outgoings</a></li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="incomings">
                            <?php $this->load->view('cms/report/incoming'); ?>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="dispositions">
                            <?php $this->load->view('cms/report/disposition'); ?>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="outgoings">
                            <?php $this->load->view('cms/report/outgoing'); ?>
                        </div>
                    </div>
                </div>
            </div><!-- /.box-body -->
        </div>
    </div>
</div>