<?php
defined('BASEPATH') or exit('No direct script access allowed');
//echo '<pre>',print_r($this,true);exit;
?><div class="container-fluid">
	<div class="row page-header-buttons">
		<div class="col-md-12">
			<a href="<?= base_url('lead/create?lead_status=' . $this->uri->segment('3')) ?>" class="btn btn-info btn-fill">Create <?php echo isset($subtitle) ? $subtitle : $title; ?></a>
		</div>
	</div>
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
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="header">
					<h4 class="title"><?php echo isset($subtitle) ? $subtitle : $title; ?></h4>
				</div>
				<div class="content table-responsive table-full-width">
					<table class="table table-hover table-striped">
						<thead>
							<th class="text-center">View</th>
							<th>Job Number</th>
							<th>First Name</th>
							<th>Last Name</th>
							<th>Status</th>
							<th>Type</th>
						</thead>
						<tbody>
							<?php if (!empty($leads)) : ?>
								<?php foreach ($leads as $lead) : ?>
									<tr>
										<td class="text-center"><a href="<?= base_url('lead/' . $lead->id) ?>" class="text-info"><i class="fa fa-eye"></i></a></td>
										<td><?= (1600 + $lead->id); ?></td>
										<td><?= $lead->firstname ?></td>
										<td><?= $lead->lastname ?></td>
										<td><?= LeadModel::statusToStr($lead->status) ?></td>
										<td><?= LeadModel::typeToStr($lead->type) ?></td>
									</tr>
								<?php endforeach; ?>
							<?php else : ?>
								<tr>
									<td colspan="8" class="text-center">No Record Found!</td>
								</tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
