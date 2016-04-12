<div class="row">
    <div class="col-sm-12">
        <?php if ($this->session->flashdata('message')): ?>
        <?php echo create_alert_box($this->session->flashdata('message'),$this->session->flashdata('message_type')); ?>
        <?php endif; ?>
        
        <form role="form" method="post" action="<?php echo $submit_url; ?>">
            <input type="hidden" id="id" name="id" value="<?php echo $item->id; ?>" />
            <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?php echo $item->id?'Update Data':'Create New'; ?></h3>
            </div><!-- /.box-header -->
            
            <div class="box-body">
                <div class="form-group">
                    <label>Configuration Name</label>
                    <input type="text" name="var_name" class="form-control" placeholder="Variable name ..." value="<?php echo $item->var_name; ?>">
                </div>
                <div class="form-group">
                    <label>Configuration Value</label>
                    <?php if ($item->func_custom_value){
                        $func_array = explode(',', $item->func_custom_value);
                        $func_name =  $func_array[0]; 
                        if (function_exists($func_name)){
                            if (count($func_array)==1){
                                echo $func_name($item->var_value);
                            }else{
                                $func_array[0] = $item->var_value;
                                echo call_user_func_array($func_name, $func_array);
                            }
                        }
                    }else{ ?>
                    <input type="text" name="var_value" class="form-control" placeholder="Variable value ..." value="<?php echo $item->var_value; ?>">
                    <?php } ?>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Value Type</label>
                            <select name="var_type" class="form-control">
                                <option value="string">String</option>
                                <option value="integer">Integer</option>
                                <option value="float">Numeric</option>
                                <option value="boolean">Boolean</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Custom Function (<span class="text-warning">!</span>)</label>
                            <input type="text" name="func_custom_value" class="form-control" placeholder="Custom function ..." value="<?php echo $item->func_custom_value; ?>" data-toggle="tooltip" title="Becarefull to change this value. Ensure the function exists in custom helper.">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>
                        <input type="radio" class="form-control icheck" name="is_list" value="1" <?php echo $item->is_list==1?'checked':''; ?> /> This is list
                    </label>
                    <label>
                        <input type="radio" class="form-control icheck" name="is_list" value="0" <?php echo $item->is_list==0?'checked':''; ?> /> SIngle value
                    </label>
                </div>
            </div>
            <div class="box-footer clearfix">
                <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Submit</button>
                <button class="btn btn-warning" type="reset"><i class="fa fa-refresh"></i> Reset</button>
                <a class="btn btn-default" href="<?php echo $back_url; ?>"><i class="fa fa-backward"></i> Cancel</a>
            </div>
        </div>
        </form>
    </div>
</div>

