<?php
defined('BASEPATH') or exit('No direct script access allowed');
?><div class="container-fluid">
	<div class="row">
		<div class="col-md-9">
			<div class="row">
				<div class="col-md-12">
					<h3>Leads</h3>
				</div>
			</div>
			<div class="dashbord-box">
				<div class="box-container">
					<div class="box gray-box">
						<a href="<?= base_url('leads/status/0') ?>">
							<span><?= $newLeads ?></span>
							<p><?= $boxNames[0]->label ?? 'New' ?></p>
						</a>
					</div>
				</div>
				<div class="box-container">
					<div class="box gray-box">
						<a href="<?= base_url('leads/status/1') ?>">
							<span><?= $appointmentScheduledLeads ?></span>
							<p><?= $boxNames[1]->label ?? 'Appointment<br>Scheduled' ?></p>
						</a>
					</div>
				</div>
				<div class="box-container">
					<div class="box gray-box">
						<a href="<?= base_url('leads/status/2') ?>">
							<span><?= $followUpLeads ?></span>
							<p><?= $boxNames[2]->label ?? 'Needs Follow Up<br>Call' ?></p>
						</a>
					</div>
				</div>
				<div class="box-container">
					<div class="box gray-box">
						<a href="<?= base_url('leads/status/3') ?>">
							<span><?= $needsSiteVisitLeads ?></span>
							<p><?= $boxNames[3]->label ?? 'Needs Site<br>Visit' ?></p>
						</a>
					</div>
				</div>
				<div class="box-container">
					<div class="box gray-box">
						<a href="<?= base_url('leads/status/4') ?>">
							<span><?= $estimateBidLeads ?></span>
							<p><?= $boxNames[4]->label ?? 'Needs<br>Estimate / Bid' ?></p>
						</a>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<h3>Prospects</h3>
				</div>
			</div>
			<div class="dashbord-box">
				<div class="box-container">
					<div class="box gray-box">
						<a href="<?= base_url('leads/status/5') ?>">
							<span><?= $estimateSentLeads ?></span>
							<p><?= $boxNames[5]->label ?? 'Estimate Sent' ?></p>
						</a>
					</div>
				</div>
				<div class="box-container">
					<div class="box gray-box">
						<a href="<?= base_url('leads/status/6') ?>">
							<span><?= $readyToSignLeads ?></span>
							<p><?= $boxNames[6]->label ?? 'Ready to<br>Sign / Verbal Go' ?></p>
						</a>
					</div>
				</div>
				<div class="box-container">
					<div class="box gray-box">
						<a href="<?= base_url('leads/status/12') ?>">
							<span><?= $coldLeads ?></span>
							<p><?= $boxNames[7]->label ?? 'Cold' ?></p>
						</a>
					</div>
				</div>
				<div class="box-container">
					<div class="box gray-box">
						<a href="<?= base_url('leads/status/13') ?>">
							<span><?= $postponedLeads ?></span>
							<p><?= $boxNames[8]->label ?? 'Postponed' ?></p>
						</a>
					</div>
				</div>
				<div class="box-container">
					<div class="box gray-box">
						<a href="<?= base_url('leads/status/14') ?>">
							<span><?= $lostLeads ?></span>
							<p><?= $boxNames[9]->label ?? 'Lost' ?></p>
						</a>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<h3>Jobs</h3>
				</div>
			</div>
			<div class="dashbord-box">
				<div class="box-container">
					<div class="box gray-box">
						<a href="<?= base_url('lead/signed-jobs') ?>">
							<span><?= $signedJobs ?></span>
							<p><?= $boxNames[10]->label ?? 'Signed' ?></p>
						</a>
					</div>
				</div>
				<div class="box-container">
					<div class="box gray-box">
						<a href="<?= base_url('lead/production-jobs') ?>">
							<span><?= $productionJobs ?></span>
							<p><?= $boxNames[11]->label ?? 'Production' ?></p>
						</a>
					</div>
				</div>
				<div class="box-container">
					<div class="box gray-box">
						<a href="<?= base_url('lead/completed-jobs') ?>">
							<span><?= $completedJobs ?></span>
							<p><?= $boxNames[12]->label ?? 'Completed' ?></p>
						</a>
					</div>
				</div>
				<div class="box-container">
					<div class="box gray-box">
						<a href="<?= base_url('lead/closed-jobs') ?>">
							<span><?= $closedJobs ?></span>
							<p><?= $boxNames[13]->label ?? 'Closed' ?></p>
						</a>
					</div>
				</div>
				<div class="box-container">
					<div class="box gray-box">
						<a href="<?= base_url('lead/archive-jobs') ?>">
							<span><?= $archiveJobs ?></span>
							<p><?= $boxNames[14]->label ?? 'Archive' ?></p>
						</a>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<h3>Repairs & Service Calls</h3>
				</div>
			</div>
			<div class="dashbord-box">
				<div class="box-container">
					<div class="box gray-box">
						<a href="#">
							<span><?= $createdTasks ?></span>
							<p><?= $boxNames[20]->label ?? 'New' ?></p>
						</a>
					</div>
				</div>
				<div class="box-container">
					<div class="box gray-box">
						<a href="#">
							<span><?= $workingTasks ?></span>
							<p><?= $boxNames[21]->label ?? 'Pending' ?></p>
						</a>
					</div>
				</div>
				<div class="box-container">
					<div class="box gray-box">
						<a href="#">
							<span><?= $stuckTasks ?></span>
							<p><?= $boxNames[22]->label ?? 'In-Process' ?></p>
						</a>
					</div>
				</div>
				<div class="box-container">
					<div class="box gray-box">
						<a href="#">
							<span><?= $holdTasks ?></span>
							<p><?= $boxNames[23]->label ?? 'Completed' ?></p>
						</a>
					</div>
				</div>
				<div class="box-container">
					<div class="box gray-box">
						<a href="#">
							<span><?= $completedTasks ?></span>
							<p><?= $boxNames[24]->label ?? 'Closed' ?></p>
						</a>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<h3>Tasks</h3>
				</div>
			</div>
			<div class="dashbord-box">
				<div class="box-container">
					<div class="box gray-box">
						<a href="<?= base_url('tasks/status/0') ?>">
							<span><?= $createdTasks ?></span>
							<p><?= $boxNames[15]->label ?? 'Created' ?></p>
						</a>
					</div>
				</div>
				<div class="box-container">
					<div class="box gray-box">
						<a href="<?= base_url('tasks/status/1') ?>">
							<span><?= $workingTasks ?></span>
							<p><?= $boxNames[16]->label ?? 'Working' ?></p>
						</a>
					</div>
				</div>
				<div class="box-container">
					<div class="box gray-box">
						<a href="<?= base_url('tasks/status/2') ?>">
							<span><?= $stuckTasks ?></span>
							<p><?= $boxNames[17]->label ?? 'Stuck' ?></p>
						</a>
					</div>
				</div>
				<div class="box-container">
					<div class="box gray-box">
						<a href="<?= base_url('tasks/status/3') ?>">
							<span><?= $holdTasks ?></span>
							<p><?= $boxNames[18]->label ?? 'Hold' ?></p>
						</a>
					</div>
				</div>
				<div class="box-container">
					<div class="box gray-box">
						<a href="<?= base_url('tasks/status/4') ?>">
							<span><?= $completedTasks ?></span>
							<p><?= $boxNames[19]->label ?? 'Completed' ?></p>
						</a>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<h3>Projects</h3>
				</div>
			</div>
			<div class="dashbord-box">
				<div class="box-container">
					<div class="box gray-box">
						<a href="#">
							<span><?= $createdTasks ?></span>
							<p><?= $boxNames[25]->label ?? 'New' ?></p>
						</a>
					</div>
				</div>
				<div class="box-container">
					<div class="box gray-box">
						<a href="#">
							<span><?= $workingTasks ?></span>
							<p><?= $boxNames[26]->label ?? 'Planning & Budgeting' ?></p>
						</a>
					</div>
				</div>
				<div class="box-container">
					<div class="box gray-box">
						<a href="#">
							<span><?= $stuckTasks ?></span>
							<p><?= $boxNames[27]->label ?? 'In-Process' ?></p>
						</a>
					</div>
				</div>
				<div class="box-container">
					<div class="box gray-box">
						<a href="#">
							<span><?= $holdTasks ?></span>
							<p><?= $boxNames[28]->label ?? 'Completed' ?></p>
						</a>
					</div>
				</div>
				<div class="box-container">
					<div class="box gray-box">
						<a href="#">
							<span><?= $completedTasks ?></span>
							<p><?= $boxNames[29]->label ?? 'Closed' ?></p>
						</a>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="row">
				<div class="col-md-12">
					<h3>Activity Log</h3>
				</div>
			</div>
			<div class="row activity-logs">
				<div class="col-md-12">
					<?php
					foreach ($aLogs as $aLog) {
						echo '<p>' . ActivityLogsModel::stringifyLog($aLog) . '</p>';
					}
					?>
				</div>
			</div>
		</div>
	</div>
</div>
