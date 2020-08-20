<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <?php
            if (!empty($this->session->flashdata('errors'))) {
                echo '<div class="alert alert-danger fade in alert-dismissable" title="Error:"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>';
                echo $this->session->flashdata('errors');
                echo '</div>';
            }
            ?>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row page-header-buttons">
        <div class="col-md-12">
            <a href="<?= base_url('leads/status/0'); ?>" class="btn btn-info btn-fill"><i class="fa fa-chevron-left" aria-hidden="true"></i>&nbsp; Back</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="header">
                    <h4 class="title">Add Leads / Clients</h4>
                </div>

                <div class="content">
                    <div class="row">
                        <div id="validation-errors" class="col-md-12">
                        </div>
                    </div>

                    <?= form_open('lead/store', array('id' => 'lead_create', 'method' => 'post', 'novalidate' => true)) ?>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>First Name<span class="red-mark">*</span></label>
                                <input class="form-control" name="firstname" placeholder="First Name" type="text">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Last Name<span class="red-mark">*</span></label>
                                <input class="form-control" placeholder="Last Name" name="lastname" type="text">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Address Line 1<span class="red-mark">*</span></label>
                                <input class="form-control" placeholder="Address Line 1" name="address" type="text">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Address Line 2</label>
                                <input class="form-control" placeholder="Address Line 2" name="address_2" type="text">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>City<span class="red-mark">*</span></label>
                                <input class="form-control" placeholder="City" name="city" type="text">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>State<span class="red-mark">*</span></label>
                                <input class="form-control" placeholder="State" Name="state" type="text">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Postal Code<span class="red-mark">*</span></label>
                                <input class="form-control" placeholder="ZIP Code" name="zip" type="text">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Cell Phone<span class="red-mark">*</span></label>
                                <input class="form-control" placeholder="Phone 1" name="phone1" type="text">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Home Phone</label>
                                <input class="form-control" placeholder="Phone 2" name="phone2" type="text">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email</label>
                                <input class="form-control" name="email" placeholder="Email" type="email">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Lead Source</label>
                                <select name="lead_source" class="form-control">
                                    <option value="" disabled selected>Select Lead Source</option>
                                    <?php foreach ($leadSources as $leadSource) {
                                        echo '<option value="' . $leadSource->id . '">' . $leadSource->name . '</option>';
                                    } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="title">Additional Party</h4>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>First Name</label>
                                <input class="form-control" name="ap_firstname" placeholder="First Name" type="text">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Last Name</label>
                                <input class="form-control" placeholder="Last Name" name="ap_lastname" type="text">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Phone</label>
                                <input class="form-control" placeholder="Phone" name="ap_phone" type="text">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email</label>
                                <input class="form-control" placeholder="Email" name="ap_email" type="text">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div>
                                <h4 class="title" style="float: left;">Contract Status<span class="red-mark">*</span></h4>
                                <div class="clearfix"></div>
                                <div>
                                    <select class="form-control" id="lead" name="status">
                                        <option value="" disabled selected>Select Contract Status</option>
                                        <optgroup label="Leads">
											<?php foreach($lead_statuses as $status_index): $status_value = LeadModel::statusToStr($status_index); ?>
												<?php $selected = $this->input->get('lead_status') == $status_index ? ' selected="selected"' : ''; ?>
												<option value="<?php echo $status_index ?>" <?php echo $selected ?>><?php echo $status_value ?></option>
											<?php endforeach; ?>
                                        </optgroup>
                                        <optgroup label="Prospects">
											<?php foreach($prospect_statuses as $status_index): $status_value = LeadModel::statusToStr($status_index); ?>
												<?php $selected = $this->input->get('lead_status') == $status_index ? ' selected="selected"' : ''; ?>
												<option value="<?php echo $status_index ?>" <?php echo $selected ?>><?php echo $status_value ?></option>
											<?php endforeach; ?>
                                        </optgroup>
                                        <optgroup label="Prospects">
											<?php foreach($prospect2_statuses as $status_index): $status_value = LeadModel::statusToStr($status_index); ?>
												<?php $selected = $this->input->get('lead_status') == $status_index ? ' selected="selected"' : ''; ?>
												<option value="<?php echo $status_index ?>" <?php echo $selected ?>><?php echo $status_value ?></option>
											<?php endforeach; ?>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div>
                                <h4 class="title" style="float: left;">Category<span class="red-mark">*</span></h4>
                                <div class="clearfix"></div>
                                <div>
                                    <select class="form-control" id="category" name="category">
                                        <option value="" disabled selected>Select Category</option>
                                        <?php foreach ($lead_category_tags as $s_id => $s_tags) : ?>
                                            <option value="<?= $s_id ?>"><?= $s_tags ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div>
                                <h4 class="title" style="float: left;">Job Type<span class="red-mark">*</span></h4>
                                <div class="clearfix"></div>
                                <div>
                                    <select class="form-control lead-status" id="job" name="type">
                                        <option value="" disabled selected>Select Job Type</option>
                                        <?php foreach ($job_type_tags as $j_id => $job) : ?>
                                            <option value="<?= $j_id ?>"><?= $job ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div>
                                <h4 class="title" style="float: left;">Classification<span class="red-mark">*</span></h4>
                                <div class="clearfix"></div>
                                <div>
                                    <select class="form-control lead-status" id="job" name="classification">
                                        <option value="" disabled selected>Select Classification</option>
                                        <?php foreach ($classification as $clsf) : ?>
                                            <option value="<?= $clsf->id ?>"><?= $clsf->name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

					<div class="row">
						<div class="col-md-12">
							<div>
								<label for="sales_rep_id"><h4 class="title" style="float: left;">Sales Rep</h4></label>
								<div class="clearfix"></div>
								<div>
									<select class="form-control lead-status" id="sales_rep_id" name="sales_rep_id">
									</select>
								</div>
								<div class="clearfix" style="padding: 10px;"></div>
							</div>
						</div>
					</div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-info btn-fill pull-right">Create</button>
                            </div>
                        </div>
                    </div>
                    <?= form_close() ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
        </div>
    </div>
</div>

<script src="<?= base_url('assets/js/leads/create.js') ?>"></script>
