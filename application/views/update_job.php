 <div class="container-fluid">
                <div class="row">
           <?= $this->session->flashdata('message') ?>
                   <div class="col-md-8">
                        <div class="card">
                           
                           
                
                            <div class="header">
                                <h4 class="title" style="float: left;">Update Job</h4> <a href="javascript:window.history.go(-1);" class="btn btn-info btn-fill pull-right">Back</a>
<div class="clearfix"></div>
                                
                                                             <?php if(validation_errors())
{   
echo '<div class="alert alert-danger fade in alert-dismissable" title="Error:"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>';
echo validation_errors();
echo '</div>';
}
?>
                            </div>
                            <div class="content">
                                  <?php
                                $status='';
                                   foreach( $jobs as $job ) : ?>  

                                <?php echo form_open('server/update_job',array('id'=>"jobform",'autocomplete'=>"off"));?>
                                <?php  $status= $job->status; ?>
                                    <div class="row">
                                        <input type="hidden" name="id" class="hidden_id" value="<?php echo $job->id ?>"></input>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Job Name</label>
                                                <input class="form-control" placeholder="Job Name" name="jobname" value="<?php echo $job->job_name ?>" type="text">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>First Name</label>
                                                <input class="form-control" name="firstname" value="<?php echo $job->firstname ?>" placeholder="" type="text">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Last Name</label>
                                                <input class="form-control" placeholder="Last Name" name="lastname" value="<?php echo $job->lastname ?>" type="text">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Address</label>
                                                <input class="form-control" placeholder="Address" name="address" value="<?php echo $job->address ?>" type="text">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>City</label>
                                                <input class="form-control" placeholder="City" value="<?php echo $job->city ?>" name="city" type="text">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>State</label>
                                                <input class="form-control" placeholder="Country" Name="country" value="<?php echo $job->state ?>" type="text">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Postal Code</label>
                                                <input class="form-control" placeholder="ZIP Code" value="<?php echo $job->zip ?>" name="zip" type="text">
                                            </div>
                                        </div>
                                    </div>

                                 <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Phone 1</label>
                                                <input class="form-control" placeholder="Phone 1" name="phone1" value="<?php echo $job->phone1 ?>" type="text">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Phone 2</label>
                                                <input class="form-control" placeholder="Phone 2" name="phone2" value="<?php echo $job->phone2 ?>" type="text">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input class="form-control" name="email" placeholder="Email" value="<?php echo $job->email ?>" type="email">
                                            </div>
                                        </div>
                                    </div>


                                    <button type="submit" class="btn btn-info btn-fill pull-right">Save</button>
                                    <div class="clearfix"></div>
                                <?php echo form_close(); ?>
      
                            
                       
					   <div class="footer" style="margin-bottom: 10px;">
                                    
                                    <hr>
                                    <a href="<?php echo base_url('index.php/dashboard/addphoto/'.$job->id);?>" class="btn btn-success btn-fill">Photo</a>
									
  <!-- <a href="http://developeradda.tech/project/roofing-crm/report/?id=<?php echo $job->id ?>" target="_blank" class="btn btn-danger btn-fill">Photo Report</a>-->
   <a href="<?php echo base_url();?>index.php/dashboard/alljobreport/<?php echo $job->id ?>" class="btn btn-danger btn-fill">All Report</a>
   <a href="" class="btn btn-success btn-fill">Create Estimate</a>
   <a href="<?php echo base_url('index.php/dashboard/adddoc/'.$job->id);?>" class="btn btn-danger btn-fill">Docs</a>
                                </div>
								                                   <?php endforeach; ?>
					</div>
                          
                        </div>
                    </div>

 <div class="col-md-4">
            <div class="card">
                <div class="header">
                                    <h4 class="title" style="float: left;">Job Status</h4><span class="status <?php if($status=='closed'){ echo 'closed';}else{ echo 'open';} ?>"><?php echo $status; ?></span> 
                                    <div class="clearfix"></div>
                                    <div class="content">
                                            <select class="form-control" id="leadstatus">
                                                <option value="">update</option>
                            <option value="lead">Lead</option>
                            <option value="cash">Cash job</option>
                            <option value="insurance">Insurance job</option>
                            <option value="closed">Closed</option>
                        </select>
                                    </div>                       
                                </div>
             </div>
        <div class="card">
                           
                           
                
                            <div class="header">
                                <h4 class="title" style="float: left;">Additional Party(If any)</h4> 
                                <div class="clearfix"></div>
                              
                                                           
                            </div>
                            <div class="content">
                            <?php if( !empty( $add_info ) ) : ?>
                            <?php foreach( $add_info as $info ) : ?>  
                                <?php echo form_open('server/additional_party_update',array('id'=>"jobform",'autocomplete'=>"off"));?>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <input type="hidden"  name="id" value="<?php echo $jobid; ?>"></input>
                                                <label>First Name</label>
                                                <input class="form-control" name="firstname" value="<?php echo $info->fname ?>" placeholder="First Name" type="text">
                                            </div>
                                       
                                            <div class="form-group">
                                                <label>Last Name</label>
                                                <input class="form-control" placeholder="Last Name" name="lastname" value="<?php echo $info->lname ?>" type="text">
                                            </div>
                                       
                                            <div class="form-group">
                                                <label>Phone</label>
                                                <input class="form-control" placeholder="Phone" name="phone" value="<?php echo $info->phone ?>" type="text">
                                            </div>
                                       
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input class="form-control" placeholder="Email" name="email" value="<?php echo $info->email ?>" type="text">
                                            </div>
                                             <button type="submit" class="btn btn-info btn-fill pull-right">Save</button>
                                        </div>
                                    </div>

                                 <?php echo form_close(); ?>
                                 <?php endforeach; ?>
                                <?php else : ?>
                                   <?php echo form_open('server/additional_party_add',array('id'=>"jobform",'autocomplete'=>"off"));?>

                                    <div class="row">
                                         <input type="hidden" name="id" value="<?php echo $jobid; ?>"></input>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>First Name</label>
                                                <input class="form-control" name="firstname" value="" placeholder="First Name" type="text">
                                            </div>
                                       
                                            <div class="form-group">
                                                <label>Last Name</label>
                                                <input class="form-control" placeholder="Last Name" name="lastname" value="" type="text">
                                            </div>
                                       
                                            <div class="form-group">
                                                <label>Phone</label>
                                                <input class="form-control" placeholder="Phone" name="phone" value="" type="text">
                                            </div>
                                       
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input class="form-control" placeholder="Email" name="email" value="" type="text">
                                            </div>
                                             <button type="submit" class="btn btn-info btn-fill pull-right">Save</button>
                                        </div>
                                    </div>

                                 <?php echo form_close(); ?>

                                <?php endif; ?>
                            </div>
                        </div>
</div>
					   </div>
                        </div>
  
