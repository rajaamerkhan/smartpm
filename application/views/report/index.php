<?php
defined('BASEPATH') or exit('No direct script access allowed');
?><div class="container-fluid">
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
    <div class="col-md-6">
      <div class="card">
        <div class="header">
          <h4 class="title" style="float: left;">Photo Report</h4>
          <a href="<?= base_url('lead/' . $jobid) ?>" class="btn btn-info btn-fill pull-right">Back</a>
          <div class="clearfix"></div>
        </div>
        <div class="content table-responsive table-full-width">
          <table class="table table-hover table-striped">
            <thead>
              <th style="width: 55px;"></th>
              <th style="width: 80px;">ID</th>
              <th>Report Link</th>
            </thead>
            <tbody class="has-del-btn">
              <?php if (!empty($allreport)) : ?>
                <?php foreach ($allreport as $jobs) : ?>
                  <tr class="tr<?= $jobs->id ?>">
                    <td><i class="del-job pe-7s-close" id="<?= $jobs->id; ?>"></i></td>
                    <td><?= $jobs->id ?></td>
                    <td>
                      <a href="<?= base_url('lead/' . $jobid . '/report/' . $jobs->id . '/pdf'); ?>" target="_blank" class="btn btn-danger btn-right btn-fill">view</a>
                  </tr>
                <?php endforeach; ?>
              <?php else : ?>
                <tr>
                  <td colspan="3">
                    <p class="mb-15"> No Record Found!</p>
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
          <div class="footer">
            <a href="<?= base_url('lead/' . $jobid . '/report/create'); ?>" target="_blank" class="btn btn-danger pull-right" style="margin:5px 10px 10px 0;">Genrate New Report</a> </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  $(document).ready(function() {
    var baseUrl = '<?= base_url(); ?>';
    $(".del-job").click(function() {
      var id = $(this).attr('id');
      $.ajax({
        url: baseUrl + 'lead/' + id + '/delete-report',
        data: {
          id: id
        },
        type: 'post',
        success: function(php_script_response) {
          $('.tr' + id).remove();
        }
      });
    });
  });
</script>