 <div class="container-fluid">
                <div class="row">
                   <div class="col-md-12">
                        <div class="card">
                             <div class="row header">
                                 <div class="col-md-6">
                                         <input class="form-control" id="myInput"  placeholder="Search Job" type="text">
                                 </div>
                                  <div class="col-md-6">
                                    <a href="<?php echo base_url('index.php/dashboard/addteams');?>" class="btn btn-info btn-fill pull-right">Create New Team</a>
                                 </div>
                            </div>
                            <div class="header">
                                 <?= $this->session->flashdata('message') ?>
                                <h4 class="title">Team List</h4>
                                
                            </div>
                            <div class="content table-responsive">
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th>Team Name</th>
                                        <th>Remark</th>
                                       
                                        
                                       
                                      
                                        
                                    </thead>
                                    <tbody id="myTable">

                                    <?php if( !empty( $teams ) ) : ?>
              <?php foreach( $teams as $team ) : ?>  
                <tr>
              
              <td style="width: 30px"><i class="del-doc pe-7s-trash" id=""></i></td>
                <td style="width: 30px"><a href="<?php echo base_url();?>index.php/dashboard/team_detail/<?php echo $team->id ?>"><i class="pe-7s-news-paper" style="font-size: 30px" /></a></td>
                <td><span class=""><i class="del-edit pe-7s-note" style="font-size: 30px;" /></span></td>
                  <td><?php echo $team->team_name ?></td>
                  <td><?php echo $team->remark ?></td>
                
                
                 </tr>


                      <?php endforeach; ?>
            <?php else : ?>
               <p class="mb-15">  No Record Found!</p>
            <?php endif; ?>
               


                                  
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
					   </div>
                        </div>
  
